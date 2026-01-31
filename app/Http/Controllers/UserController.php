<?php

namespace App\Http\Controllers;

use App\Models\ProfileDosen;
use App\Models\ProfileMahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $baseQuery = User::query();

        if (auth()->user()->hasRole('admin')) {
            $baseQuery->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['super admin', 'admin']);
            });
        }

        $users = (clone $baseQuery)
            ->with('roles')
            ->paginate(10);

        $stats = (clone $baseQuery)
            ->with('roles')
            ->get()
            ->groupBy(fn ($user) => $user->getRoleNames()->first())
            ->map->count()
            ->toArray();

        $stats = array_merge([
            'super admin' => 0,
            'admin' => 0,
            'dosen' => 0,
            'mahasiswa' => 0,
        ], $stats);

        return view('users.index', compact('users', 'stats'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name');
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,mahasiswa,dosen'
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $user->assignRole($request['role']);

        if ($request['role'] === 'mahasiswa') {
            ProfileMahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->id_number,
                'kontak' => $request->phone
            ]);
        }

        if ($request['role'] === 'dosen') {
            ProfileDosen::create([
                'user_id' => $user->id,
                'nip' => $request->id_number,
                'kontak' => $request->phone
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $user->load(['dosenProfile', 'mahasiswaProfile', 'roles']);

        $roles = Role::pluck('name', 'name');
        $userRole = $user->roles->pluck('name')->first();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required'
        ]);

        $user->update([
            'name'=>$request->name,
            'email'=>$request->email
        ]);

         $user->syncRoles([$request->role]);

        if ($request->role === 'mahasiswa') {
            $profile = $user->mahasiswaProfile;
            if ($profile) {
                $profile->update([
                    'nim' => $request->id_number,
                    'kontak' => $request->phone
                ]);
            } else {
                ProfileMahasiswa::create([
                    'user_id' => $user->id,
                    'nim' => $request->id_number,
                    'kontak' => $request->phone
                ]);
            }
        }

        if ($request->role === 'dosen') {
            $profile = $user->dosenProfile;
            if ($profile) {
                $profile->update([
                    'nip' => $request->id_number,
                    'kontak' => $request->phone
                ]);
            } else {
                ProfileDosen::create([
                    'user_id' => $user->id,
                    'nip' => $request->id_number,
                    'kontak' => $request->phone
                ]);
            }
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}

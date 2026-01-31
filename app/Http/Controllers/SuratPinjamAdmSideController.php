<?php

namespace App\Http\Controllers;

use App\Models\SuratPinjamAdmSide;
use Illuminate\Http\Request;

class SuratPinjamAdmSideController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratPinjamAdmSide::with('user')->latest();
        
        // Search berdasarkan nama pemohon atau user
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $suratList = $query->paginate(10);
        
        return view('sp-admin', compact('suratList'));
    }
    
    public function download($id)
    {
        $surat = SuratPinjamAdmSide::findOrFail($id);
        
        $path = $surat->surat_path;
        
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            return back()->with('error', 'File tidak ditemukan: ' . $path);
        }
        
        return response()->download($fullPath);
    }
}
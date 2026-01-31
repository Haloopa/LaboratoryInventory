<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Inventory::all();
        return view('inventory.index', compact('items'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'merk' => 'required',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required',
            'lokasi' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inventory', 'public');
            $data['image'] = $imagePath;
        }

        Inventory::create($data);
        
        return redirect()->route('inventory.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $item = Inventory::findOrFail($id);
        return view('inventory.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required',
            'merk' => 'required',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required',
            'lokasi' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $item = Inventory::findOrFail($id);
        $data = $request->except('_token', '_method');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            
            $imagePath = $request->file('image')->store('inventory', 'public');
            $data['image'] = $imagePath;
        } elseif ($request->has('remove_image')) {
            // Remove image if user wants to delete it
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = null;
        }

        $item->update($data);

        return redirect()->route('inventory.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $item = Inventory::findOrFail($id);
        
        // Delete image if exists
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
        
        $item->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'nama_barang',
        'merk',
        'jumlah',
        'kondisi',
        'lokasi',
        'image',
    ];
    
    // Method untuk get URL gambar (tanpa symbolic link)
    public function getImageUrl()
    {
        if ($this->image) {
            // Ambil hanya nama file dari path
            $filename = basename($this->image);
            // Akses melalui route khusus
            return url('/inventory-images/' . $filename);
        }
        return asset('images/default-inventory.jpg');
    }
    
    // Accessor untuk kompatibilitas
    public function getImageUrlAttribute()
    {
        return $this->getImageUrl();
    }
    
    // Mutator untuk menyimpan gambar
    public function setImageAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['image'] = $value;
        }
    }
}
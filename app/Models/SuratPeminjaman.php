<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SuratPeminjaman extends Model
{
    protected $table = 'surat_peminjamans';

    protected $fillable = [
        'peminjaman_id',
        'user_id',
        'nama_pemohon',
        'no_hp',
        'keperluan',
        'surat_path',
        'status',
        'catatan_admin',
        'tanggal_mulai',
        'tanggal_selesai',
        'barang_dipinjam'
    ];

    protected $casts = [
        'barang_dipinjam' => 'array',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date'
    ];

    public function getSuratUrlAttribute()
    {
        if ($this->surat_path) {
            return Storage::url($this->surat_path);
        }
        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPinjamAdmSide extends Model
{
    use HasFactory;

    // PASTIKAN table-nya benar
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
        'barang_dipinjam',
        'signed_response_path'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'barang_dipinjam' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'profiles_mahasiswa';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

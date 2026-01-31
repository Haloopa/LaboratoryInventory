<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileDosen extends Model
{
    use HasFactory;

    protected $table = 'profiles_dosen';
    protected $fillable = ['user_id', 'nip', 'kontak'];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

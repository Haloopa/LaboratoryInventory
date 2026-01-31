<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuratAdminController extends Controller
{
    public function index(){
        $surat = DB::table('surat_peminjamans')->orderByDesc('created_at')->paginate(10);
        return view('admin.surat.index', compact('surat'));
    }

}


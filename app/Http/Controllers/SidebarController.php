<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function toggle()
    {
        session([
            'sidebar_collapsed' => !session('sidebar_collapsed', false)
        ]);

        return response()->json(['success' => true]);
    }
}

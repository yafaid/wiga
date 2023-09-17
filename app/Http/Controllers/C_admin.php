<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class C_admin extends Controller
{
    public function admin()
    {

        return view('admin.adminacc');
    }
}

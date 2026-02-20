<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Inventory extends Controller
{
    public function index()
    {
        return view('inventory');
    }
}

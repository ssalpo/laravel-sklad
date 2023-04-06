<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Select2Controller extends Controller
{
    public function index()
    {
        return inertia('Select2');
    }
}

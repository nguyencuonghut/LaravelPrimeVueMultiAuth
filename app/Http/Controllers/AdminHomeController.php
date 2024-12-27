<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminHomeController extends Controller
{
    public function home()
    {
        return Inertia::render('AdminHome');
    }
}

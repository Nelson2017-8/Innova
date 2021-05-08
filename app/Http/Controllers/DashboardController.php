<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TokenController;
use App\User;

class DashboardController extends Controller
{
    public function index()
    {
        // VISTA DE INICIO
        return view('dashboard.index');
    }

}

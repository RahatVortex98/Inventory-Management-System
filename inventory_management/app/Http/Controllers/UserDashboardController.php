<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
class UserDashboardController extends Controller
{
   public function index()
    {
        // Check if user is logged in first to avoid errors
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view('admin.admin_dashboard'); 
        }

        return view('user.dashboard'); 
    }
}

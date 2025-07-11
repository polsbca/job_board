<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the employer dashboard.
     */
    public function index()
    {
        // You can pass relevant employer data here
        return view('dashboard.employer');
    }
}

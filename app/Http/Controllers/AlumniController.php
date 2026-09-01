<?php

namespace App\Http\Controllers;

use Inertia\Inertia; 
use Inertia\Response;

class AlumniController extends Controller
{
    /** 
     * Display Alumni List
     **/
    public function index():Response
    {
        return Inertia::render('Alumni/Index');
    }
    /** * Show the form for creating a new alumnus. */
     public function create(): Response 
     { 
        return Inertia::render('Alumni/Create'); 
    }
    public function pending(): Response 
    {
        return Inertia::render('Alumni/PendingAlumni');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $file = "/../../../README.md";
        $file = __DIR__ . str_replace("/", DIRECTORY_SEPARATOR, $file);

        $read = file_get_contents($file);
        return view('home', ["read" => $read]);
    }
}

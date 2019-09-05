<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use date;
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
        $UserName = Auth::user()->name;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9090/search?name=$UserName");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        $isTrue = json_decode($temp, true);
        $date = date('Y-m-d');
        return view('home', ['mymoney' => $isTrue['money'], 'date' => $date]);
    }
}

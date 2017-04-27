<?php

namespace App\Http\Controllers;

use App\Model\Huobi;
use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function getData()
    {
        return Datatables::of(Huobi::query()->where('id', '>', 1)->orderBy('id', 'desc'))->make(true);
    }
}

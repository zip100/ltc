<?php

namespace App\Http\Controllers;

use App\Model\Huobi;
use App\Module\Huobi\Product\Btc;
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

    public function getBuy()
    {
        $account = Btc::getInstance()->getAccountInfo();

        return view('buy', ['account' => $account]);
    }

    public function postBuyBtc(Request $request)
    {
        $this->validate($request, [
            'price' => 'required'
        ]);

        $res = Btc::getInstance()->buyCoinsAuto($request->get('price'));

        if (isset($res['result']) && $res['result'] == 'success') {
            return "Buy Success";
        } else {
            return redirect()->back()->withErrors($res['message']);
        }


    }
}

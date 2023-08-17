<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class deliveryChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }
    public function index()
    {
        return view('App.deliveryChannel.index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('App.invoice.index');
    }

    public function create()
    {
        return view('App.invoice.create');
    }

    public function add(Request $request)
    {
        dd($request->all());
    }
}

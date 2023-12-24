<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceLayout;

class InvoiceController extends Controller
{
    public function index()
    {
        $layouts = InvoiceLayout::all();
        return view('App.invoice.index',compact('layouts'));
    }

    public function create()
    {
        return view('App.invoice.create');
    }

    public function add(Request $request)
    {
        $invoiceTemplateData = $this->getInvoiceTemplateData($request);
        // dd($invoiceTemplateData);
        InvoiceLayout::create($invoiceTemplateData);
        return back();
    }

    public function detail($id)
    {
        $layout = InvoiceLayout::find($id);
        $table_text = false;
        return view('App.invoice.detail',compact('layout','table_text'));
    }

    // $checkboxFields = ['customer_name', 'supplier_name', 'phone', 'address', 'purchase_status', 'date', 'invoice_number', 'number', 'expense', 'discount'];

    private function getInvoiceTemplateData($request)
    {
        return [
            'name' => $request->layoutName,
            'paper_size' => $request->paperSize,
            'header_text' => $request->header,
            'footer_text' => $request->footer,
            'data_text' => [
                'customer_name' => boolval($request->customerName ?? false),
                'supplier_name' => boolval($request->supplierName ?? false),
                'phone' => boolval($request->phone ?? false),
                'address' => boolval($request->phone ?? false),
                'purchase_status' => boolval($request->purchase_status ?? false),
                'date' => boolval($request->date ?? false),
                'invoice_number' => boolval($request->invoice_number ?? false),
            ],
            'table_text' => [
                'number' => boolval($request->number ?? false),
                'expense' => boolval($request->expense ?? false),
                'discount' => boolval($request->discount ?? false),
            ]
        ];
    }
}

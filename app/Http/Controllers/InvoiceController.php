<?php

namespace App\Http\Controllers;

use App\Models\BusinessUser;
use Illuminate\Http\Request;
use App\Models\InvoiceLayout;
use App\Models\settings\businessSettings;

class InvoiceController extends Controller
{
    public $request;
    public function index()
    {
        $layouts = InvoiceLayout::all();
        return view('App.invoice.index', compact('layouts'));
    }

    public function create()
    {

        $businessInfo = businessSettings::select('name','address','city','state','country')->first();
        return view('App.invoice.create', compact('businessInfo'));
    }

    public function add(Request $request)
    {
        $this->request = $request;
        // $this->validateInvoiceTemplateData($request);
        $invoiceTemplateData = $this->getInvoiceTemplateData($request);
        InvoiceLayout::create($invoiceTemplateData);
        return redirect()->route('invoice.index')->with('success', 'Successfully Created');
    }

    public function detail($id)
    {
        $layout = InvoiceLayout::find($id);
        $table_text = json_decode($layout->table_text);
        $data_text = json_decode($layout->data_text);
        return view('App.invoice.sample-detail', compact('layout', 'table_text', 'data_text'));
    }

    public function edit($id)
    {
        $layout = InvoiceLayout::find($id);
        $table_text = json_decode($layout->table_text);
        $data_text = json_decode($layout->data_text);
        return view('App.invoice.edit', compact('layout', 'table_text', 'data_text'));
    }

    public function update(Request $request)
    {
        $this->request = $request;
        $this->validateInvoiceTemplateData($request);
        $data = $this->getInvoiceTemplateData($request);
        InvoiceLayout::find($request->layoutId)->update($data);
        return redirect()->route('invoice.index');
    }

    // $checkboxFields = ['customer_name', 'supplier_name', 'phone', 'address', 'purchase_status', 'date', 'invoice_number', 'number', 'expense', 'discount'];

    private function getInvoiceTemplateData($request)
    {
        $table_text = $this->getTableText();
        $data_text = $this->getDataText($request);
        return [
            'name' => $request->layoutName,
            'paper_size' => $request->paperSize,
            'header_text' => $request->header,
            'footer_text' => $request->footer,
            'note' => $request->note,
            'data_text' => json_encode($data_text),
            'table_text' => json_encode($table_text),
        ];
    }

    private function validateInvoiceTemplateData($request)
    {
        $request->validate([
            'layoutName' => 'required',
            'paperSize' => 'required',
            'header' => 'required',
            'footer' => 'required'
        ]);
    }

    public function getTableText()
    {
        return [
            'number' => $this->getLableDatas('number', 'Number'),
            'description' => $this->getLableDatas('description', 'Description'),
            'quantity' => $this->getLableDatas('quantity', 'Quantity'),
            'uom_price' => $this->getLableDatas('uom_price', 'Uom Price'),
            'discount' => $this->getLableDatas('discount', 'Discount'),
            'subtotal' => $this->getLableDatas('subtotal', 'Subtotal'),
        ];
    }

    public function getDataText($request)
    {
        return [
            'customer_name' => boolval($request->customerName ?? false),
            'supplier_name' => boolval($request->supplierName ?? false),
            'phone' => boolval($request->phone ?? false),
            'address' => boolval($request->phone ?? false),
            'purchase_status' => boolval($request->purchase_status ?? false),
            'date' => boolval($request->date ?? false),
            'invoice_number' => boolval($request->invoiceNumber ?? false),
            'status' => boolval($request->status ?? false),
            'net_sale_amount' => $this->getLableDatas('net_sale_amount', 'Net Sale Amount'),
            'extra_discount_amount' => $this->getLableDatas('extra_discount_amount', 'Extra Discount Amount'),
            'total_sale_amount' => $this->getLableDatas('total_sale_amount', 'Total Amount'),
        ];
    }

    public function destory($id)
    {
        InvoiceLayout::where('id', $id)->first()->delete();
        return back()->with('success', 'Successfully Deleted');
    }
    public function getLable($column, $defatult)
    {
        return $this->request[$column]['label'] ?? $defatult;
    }
    public function checkIsShow($column)
    {
        return $this->request[$column]['is_show'] ?? false;
    }

    public function getLableDatas($column, $defatultLabel)
    {
        return [
            'label' => $this->getLable($column, $defatultLabel),
            'is_show' => $this->checkIsShow($column),
        ];
    }
}

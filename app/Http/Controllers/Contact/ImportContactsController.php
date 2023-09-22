<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware(['canView:supplier', 'canView:customer'])->only('index');
    }

    public function index(){
        return view('App.contact_management.import_contacts.index');
    }

    public function dowloadContactExcel() {
        $path = public_path('Excel/importContact.xls');
        if (!file_exists($path)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="importContact.xls"',
        ];

        return response()->download($path, 'importContact.xls', $headers);
    }
}

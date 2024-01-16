<?php

namespace App\Http\Controllers\Contact;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\contact\contactImport;
use Illuminate\Support\Facades\Validator;

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

        activity('contact')
            ->log('Contact template download has been success')
            ->event('download')
            ->status('success')
            ->save();
        return response()->download($path, 'importContact.xls', $headers);
    }
    public function import(Request $request){
        try {
            DB::beginTransaction();
            Validator::make($request->toArray(),
                [
                    'contact_file' => 'required|mimes:xlx,xls,xlsx,csv|max:2048',
                ],
                [
                    'contact_file.required' => 'Import file is required!',
                    'contact_file.mimes' => 'Format not support!',
                ]
            )->validate();

            $file = $request->file('contact_file');
            Excel::import(new contactImport(), $file);
            DB::commit();
            activity('contact')
                ->log('Contact import has been success')
                ->event('import')
                ->status('success')
                ->save();
            return back()->with(['success'=>'Successfully imported']);
        } catch (\Throwable $th) {
            DB::rollBack();
            activity('contact')
                ->log('Contact import has been fail')
                ->event('import')
                ->status('fail')
                ->save();
            $text= "Something Wrong !";
            return redirect()->back()->with(['error' => $text]);
        }
    }
}

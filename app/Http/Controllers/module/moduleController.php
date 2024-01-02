<?php

namespace App\Http\Controllers\module;

use ZipArchive;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class moduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:Module')->only(['index']);
        $this->middleware('canUpload:Module')->only(['uploadModule']);
        $this->middleware('canInstall:Module')->only(['install']);
        $this->middleware('canUninstall:Module')->only(['uninstall']);
        $this->middleware('canDelete:Module')->only('delete');
    }
    public function index(){
        $modules=Module::toCollection();
        return view('App.module.module',compact('modules'));
    }
    public function install(Request $request){
        $decryptName=decrypt($request->module_name);
        $ucModuleName=ucwords($decryptName);
         Artisan::call('module:migrate', ['module' => $ucModuleName]);
        Artisan::call('module:seed', ['module' => $ucModuleName]);
        $module = Module::find($ucModuleName);
        $module->enable();

        Artisan::call('module:publish');

        return back()->with(['success'=>'Successfully Install']);
    }
    public function uninstall(Request $request){
        $decryptName=decrypt($request->module_name);
        $moduleName=ucwords($decryptName);
        $module = Module::find($moduleName);
        $module->disable();
        return back()->with(['success'=>'Successfully Uninstall']);
    }

    public function delete(Request $request){
        $decryptName=decrypt($request->module_name);
        $moduleName=ucwords($decryptName);
        $module = Module::find($moduleName);
        // Artisan::call('module:migrate-rollback', ['module' => $moduleName]);
        $module->disable();
        $module->delete();
        return back()->with(['success'=>'Successfully Uninstall']);
    }
    public function uploadModule(Request $request)
    {
        $module = $request->file('module_zip');
        Validator::make([
            'file'=> $module
        ],
        [
            'file'=> 'required|mimetypes:application/zip'
        ])->validate();
         //check if uploaded file is valid or not and and if not redirect back
         if ($module->getMimeType() != 'application/zip') {
             return redirect()->back()->with(['warning' => 'Something Wrong on Unzipping Error']);
         }

            //check if 'Modules' folder exist or not, if not exist create
            $path = '../Modules';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            //extract the zipped file in given path
            $zip = new ZipArchive();
            if ($zip->open($module) === true) {
                $zip->extractTo($path .'/');
                $zip->close();
            }
            return back()->with(['success'=>'Successfully UnZipped']);
    }
}

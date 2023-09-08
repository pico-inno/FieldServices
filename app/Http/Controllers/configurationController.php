<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Database\Seeders\UoMSeeder;
use Database\Seeders\BrandTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\CategoryTableSeeder;

class configurationController extends Controller
{

    public function __construct()
    {
        
    }
    public function envConfigure()
    {
        return view('App.business.envConfigurationForm');
    }
    public function store(Request $request)
    {
        try {
            updenvWithoutQuote($request->toArray());
            return redirect()->route('envConfigure.migrationForm');
        } catch (\Throwable $th) {
            return back()->with(['error'=>'Something is wrong']);
        }
    }
    public function migrationForm()
    {
        try {
            Artisan::call('migrate');
            Artisan::call('db:seed --class=RolesTableSeeder');
            // Artisan::call('db:seed', ["--class=CurrenciesTableSeeder"]);
            Artisan::call('db:seed --class=UoMSeeder');
            Artisan::call('db:seed --class=ContactWalkInTableSeeder');
            return view('App.business.activationForm')->with(['success'=>'Successfully Configured']);
        } catch (\Throwable $th) {
            return back()->with(['error'=> $th->getMessage()]);
        }
    }
    public function dataSeed(Request $request){
        try {
            $field=$request->field;
           if($field){
                switch ($field) {
                    case 'uom':
                        Artisan::call('db:seed --class=UoMSeeder');
                        return response()->json(['success' => 'successfully seeding'], 200);
                        break;
                    case 'brand':
                        Artisan::call('db:seed --class=BrandTableSeeder');
                        return response()->json(['success' => 'successfully seeding'], 200);
                        break;
                    case 'category':
                        Artisan::call('db:seed --class=CategoryTableSeeder');
                        return response()->json(['success' => 'successfully seeding'], 200);
                        break;
                    case 'contact':
                        Artisan::call('db:seed --class=ContactsTableSeeder');
                        return response()->json(['success' => 'successfully seeding'], 200);
                        break;

                    default:
                        break;
                }
           }
           return response()->json(['warning'=>'Something Missing'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 200);
        }
    }
}

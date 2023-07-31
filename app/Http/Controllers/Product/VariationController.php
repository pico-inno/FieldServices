<?php

namespace App\Http\Controllers\Product;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Variation\VariationCreateRequest;
use App\Http\Requests\Product\Variation\VariationUpdateRequest;
use App\Models\Product\ProductVariation;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Product\VariationTemplates;
use App\Models\Product\VariationTemplateValues;

class VariationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:variation')->only('index');
        $this->middleware('canCreate:variation')->only(['add', 'create']);
        $this->middleware('canUpdate:variation')->only(['edit', 'update']);
        $this->middleware('canDelete:variation')->only('delete');
    }

    public function variationDatas()
    {
        $variations = VariationTemplates::with('variationTemplateValues')->get();

        return DataTables::of($variations)
        ->addColumn('action', function($vari){
            $filter_variation = null;
            $raw = ProductVariation::with('variationTemplateValue', 'product')->get();
            $productVariationId = [];
            foreach($raw as $value){
                if(!$value->variationTemplateValue){
                    continue;
                }
                $productVariationId[] = $value->variationTemplateValue->variation_template_id;
            }
            if(in_array($vari->id, $productVariationId)){
                $filter_variation = true;
            }else{
                $filter_variation = false;
            }
            return ['action' => $vari->id, 'filter_variation' => $filter_variation];
        })
        ->addColumn('value', function($vari){
            $values = [];
            foreach($vari->variationTemplateValues as $v){
                $values[] = $v->name;
            }
            return implode(", ", $values);
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    // from product add blade file
    public function value($id)
    {
        $variationValues = VariationTemplateValues::with('variationTemplate')->where('variation_template_id', $id)->get();

        return response()->json($variationValues);
    }

    public function index()
    {
        return view('App.product.variation.variationList');
    }

    public function add()
    {
        return view('App.product.variation.variationAdd');
    }

    public function create(VariationCreateRequest $request)
    {
        DB::beginTransaction();
        try{
            $nextVariationId = VariationTemplates::create([
                'name' => $request->variation_name,
                'created_by' => Auth::user()->id
            ])->id;

            // For Variation Template Value
            $values = $request->variation_value;
            foreach($values as $val){
                DB::table('variation_template_values')->insert([
                    'name' => $val['value'],
                    'variation_template_id' => $nextVariationId,
                    'created_by' => Auth::user()->id,
                    'created_at' => now()
                ]);
            }

            DB::commit();
            return redirect('/variation')->with('message' ,'Created sucessfully Variation');
        } catch(Exception $e){
            DB::rollBack();
            dd($e);
            return back()->with('message', $e->getMessage());
        }
    }

    public function edit(VariationTemplates $variation)
    {
        $var_tem_values = VariationTemplateValues::with('variationTemplate')->where('variation_template_id', $variation->id)->get();

        return view('App.product.variation.variationEdit', compact('variation', 'var_tem_values'));
    }

    public function update(VariationTemplates $variation, VariationUpdateRequest $request)
    {
        DB::beginTransaction();
        try{
            // == > for Variation Template Name < ==
            $variation->name = $request->variation_name;
            $variation->updated_by = Auth::user()->id;
            $variation->save();

            // ==> for Variation Template Value Name < ==
            $values = $request->variation_values;

            // if request values and original values are the same
            $not_null_values = array_filter($values, function($n) {
                return $n['id'] !== null;
            });
            if(count($not_null_values) !== 0){
                foreach($not_null_values as $value){
                    $valueToUpdate = VariationTemplateValues::find($value['id']);
                    $valueToUpdate->name = $value['variation_values'];
                    $valueToUpdate->updated_by = Auth::user()->id;
                    $valueToUpdate->save();
                }
            }

            // if request values are less than original values
            $db_values = VariationTemplateValues::where('variation_template_id', $variation->id)->get()->pluck('id');
            $request_ids = [];
            foreach($values as $v){
                $request_ids[] = $v['id'];
            }
            if(count($request->variation_values) < count($db_values)){
                $to_delete_ids = array_diff($db_values->toArray(), $request_ids);
                VariationTemplateValues::whereIn('id', $to_delete_ids)->update(['deleted_by' => Auth::user()->id]);
                VariationTemplateValues::whereIn('id', $to_delete_ids)->update(['updated_by' => Auth::user()->id]);
                VariationTemplateValues::destroy($to_delete_ids);
            }

            // if request values are grater than original values
            $null_values = array_filter($values, function($n) {
                return $n['id'] === null;
            });
            if(count($null_values) !== 0){
                foreach($null_values as $val){
                    DB::table('variation_template_values')->insert([
                        'name' => $val['variation_values'],
                        'updated_by' => Auth::user()->id,
                        'variation_template_id' => $request->variation_template_id,
                        'created_at' => now()
                    ]);
                }
            }

            DB::commit();
            return redirect('/variation');
        } catch(Exception $e){
            DB::rollBack();
            return back()->with('message', $e->getMessage());
        }
    }

    public function delete(VariationTemplates $variation)
    {
        DB::beginTransaction();
        try{
            // Variation templates
            $variation->deleted_by = Auth::user()->id;
            $variation->save();
            $variation->delete();

            // Variation template values
            $ids = VariationTemplateValues::where('variation_template_id', $variation->id)->get()->pluck('id');
            VariationTemplateValues::whereIn('id', $ids)->update(['deleted_by' => Auth::user()->id]);
            VariationTemplateValues::destroy($ids);

            DB::commit();
            return response()->json(['message' => 'Deleted sucessfully variation']);
        } catch(Exception $e){
            DB::rollBack();
            return back()->with('message', $e->getMessage());
        }
    }
}

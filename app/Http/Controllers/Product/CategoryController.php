<?php

namespace App\Http\Controllers\Product;

use App\Models\Product\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Product\Category\CategoryCreateRequest;
use App\Http\Requests\Product\Category\CategoryUpdateRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:category')->only(['index', 'unitDatas', 'uomDatas']);
        $this->middleware('canCreate:category')->only(['add', 'create']);
        $this->middleware('canUpdate:category')->only(['edit', 'update']);
        $this->middleware('canDelete:category')->only('delete');
    }
    public function datas()
    {
        $categories = Category::with('parentCategory','childCategory')->get();

        return DataTables::of($categories)
        ->addColumn('action', function($category){
            return $category->id;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function index()
    {
        // $categories = Category::with('parentCategory','childCategory')->get();
        return view('App.product.category.categoryList');
    }

    public function add()
    {
        $categories = Category::where('parent_id', null)->with('parentCategory', 'childCategory')->get();
        // dd($categories);
        return view('App.product.category.categoryAdd', compact('categories'));
    }

    public function subCategory($id)
    {
        $subCategories = Category::with('parentCategory', 'childCategory')->where('parent_id', $id)->get();

        return response()->json($subCategories);
    }

    public function create(CategoryCreateRequest $request)
    {
        $category = new Category();
        $category->name = $request->category_name;
        $category->short_code = $request->category_code;
        $category->description = $request->category_desc;

        if($request->parent_id !== "Select"){
            $category->parent_id = $request->parent_id;
        }
        $category->created_by = Auth::user()->id;

        $category->save();

        return redirect('/category')->with('message', 'Created sucessfully category');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('parent_id', null)->with('parentCategory', 'childCategory')->get();

        return view('App.product.category.categoryEdit', compact('category', 'categories'));
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->name = $request->category_name;
        $category->short_code = $request->category_code;
        $category->description = $request->category_desc;

        if($request->parent_id !== "Select"){
            $category->parent_id = $request->parent_id;
        }
        $category->updated_by = Auth::user()->id;

        $category->save();

        return redirect('/category')->with('message', 'Updated sucessfully category');
    }

    public function delete(Category $category)
    {
        $category->deleted_by = Auth::user()->id;
        $category->save();
        $category->delete();

        return response()->json(['message' => 'Deleted sucessfully category']);
    }
}

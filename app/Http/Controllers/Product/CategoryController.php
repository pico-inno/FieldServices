<?php

namespace App\Http\Controllers\Product;

use App\Actions\product\CategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Category\CategoryCreateRequest;
use App\Http\Requests\Product\Category\CategoryUpdateRequest;
use App\Models\Product\Category;
use App\Repositories\Product\CategoryRepository;
use Illuminate\Support\Facades\DB;
use Modules\Service\Actions\ServiceCategoryAction;
use Modules\Service\Repositories\ServiceCategoryRepository;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    protected $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:category')->only(['index', 'unitDatas', 'uomDatas']);
        $this->middleware('canCreate:category')->only(['add', 'create']);
        $this->middleware('canUpdate:category')->only(['edit', 'update']);
        $this->middleware('canDelete:category')->only('delete');

        $this->categoryRepository = $categoryRepository;
    }
    public function datas()
    {
        $categories = $this->categoryRepository->getWithRelationships(['parentCategory','childCategory']);
        return DataTables::of($categories)
            ->addColumn('action', function($category){
                return $category->id;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function index()
    {
        return view('App.product.category.index');
    }

    public function add()
    {
        $categories = $this->categoryRepository->getByParentIdWithRelationships(null, ['parentCategory', 'childCategory']);

        return view('App.product.category.categoryAdd', [
            'categories' => $categories
        ]);
    }

    public function create(CategoryCreateRequest $request, CategoryAction $categoryAction)
    {
        try {
            DB::beginTransaction();
            $categoryAction->create($request);
            DB::commit();
            activity('category')
                ->log('New category creation has been success')
                ->event('create')
                ->status('success')
                ->save();
            if($request->form_type === "from_product"){
                return response()->json([
                    'message' => 'Category created successfully',
                    'categories' => $this->categoryRepository->getAll(),
                ]);
            }else{
                return redirect()->route('categories')->with('message', 'Category created successfully');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            activity('category')
                ->log('New category creation has been fail')
                ->event('create')
                ->status('fail')
                ->save();
            return redirect()->route('categories')->with('message', 'Category creation failed');
        }
    }

//    public function subCategory($id)
//    {
//        $subCategories = $this->categoryRepository->getByParentIdWithRelationships($id, ['parentCategory', 'childCategory']);
//        return response()->json($subCategories);
//    }



    public function edit(Category $category)
    {
        $categories = $this->categoryRepository->getByParentIdWithRelationships(null, ['parentCategory', 'childCategory']);
        $service_category = null;
        if(hasModule('Service') && isEnableModule('Service')){
            $serviceCategoryRepository = new ServiceCategoryRepository();
            $service_category = $serviceCategoryRepository->query()->where('category_id', $category->id)->first();
        }

        return view('App.product.category.categoryEdit', [
            'category' => $category,
            'categories' => $categories,
            'service_category' => $service_category,
        ]);
    }

    public function update(CategoryUpdateRequest $request, Category $category, CategoryAction $categoryAction)
    {
        try {
            DB::beginTransaction();
            $categoryAction->update($category->id, $request);
            DB::commit();
            activity('category')
                ->log('Category update has been success')
                ->event('update')
                ->status('success')
                ->save();
            return redirect()->route('categories')->with('message', 'Category updated successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            activity('category')
                ->log('Category update has been fail')
                ->event('update')
                ->status('fail')
                ->save();
            return redirect()->route('categories')->with('message', 'Category update failed');
        }
    }

    public function delete(Category $category, CategoryAction $categoryAction)
    {
        try {
            DB::beginTransaction();
            $categoryAction->delete($category->id);
            DB::commit();
            activity('category')
                ->log('Category deletion has been success')
                ->event('delete')
                ->status('success')
                ->save();
            return response()->json(['message' => 'Category deleted successfully']);
        }catch (\Exception $exception){
            DB::rollBack();
            activity('category')
                ->log('Category deletion has been fail')
                ->event('delete')
                ->status('fail')
                ->save();
            return response()->json(['message' => 'Category deletion failed']);
        }
    }
}

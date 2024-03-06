<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Datatables\datatable;
use App\Models\Product\Brand;
use App\Models\Product\Product;
use App\Repositories\Product\UOMRepository;
use App\Repositories\Product\BrandRepository;
use App\Repositories\Product\GenericRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\VariationRepository;
use App\Repositories\Product\ManufacturerRepository;
use App\Repositories\Product\UnitCategoryRepository;
use App\Repositories\UserManagement\BusinessUserRepository;
use App\Repositories\interfaces\LocationRepositoryInterface;

class ProductList extends Component
{
    use WithPagination,datatable;
    public $categories;
    public $brands;
    public $generics;
    public $manufactures;
    public $product_types;
    public $locations;

    // Keywords
    public $productTypeFilter='all';
    public $categoryFilterId = 'all';
    public $brandId = 'all';
    public $genericId = 'all';
    public $manufactureId = 'all';
    public $locationId = 'all';

    public function mount(
        LocationRepositoryInterface $locationRepository,
        ManufacturerRepository $manufacturerRepository,
        CategoryRepository $categoryRepository,
        BrandRepository $brandRepository,
        ProductRepository $productRepository,
        GenericRepository $genericRepository){
        $this->categories = $categoryRepository->query()->select('name','id')->get();
        $this->brands = $brandRepository->query()->select('name','id')->get();
        $this->generics = $genericRepository->query()->select('name','id')->get();
        $this->manufactures = $manufacturerRepository->query()->select('name','id')->get();
        $this->product_types = $productRepository->query()->select('product_type')->distinct()->pluck('product_type')->toArray();
        $this->locations=$locationRepository->locationWithAccessControlQuery()->select('id','name')->get();

    }
    #[On('product-deleted')]
    public function render()
    {
        $keyword =$this->search;
        $productTypeFilter=$this->productTypeFilter;
        $categoryFilterId=$this->categoryFilterId;
        $brandId=$this->brandId;
        $genericId=$this->genericId;
        $manufactureId=$this->manufactureId;


        $products=Product::query()
                ->select(
                    'products.*',
                    'brands.name as brandName',
                    'generics.name as genericName',
                    'manufacturers.name as manufacturerName',
                    'categories.name as categoryName',
                    'subCategory.name as subCategoryName'
                )
                ->leftJoin('brands','brand_id','=','brands.id')
                ->leftJoin('generics','generic_id','=','generics.id')
                ->leftJoin('manufacturers','manufacturer_id','=','manufacturers.id')
                ->leftJoin('categories','category_id','=','categories.id')
                ->leftJoin('categories as subCategory','sub_category_id','=','subCategory.id')
                ->with('productVariations')
                ->when(
                    $keyword !='' || $productTypeFilter !='all' || $categoryFilterId !='all' || $brandId!='all' || $genericId!='all' || $manufactureId !='all',
                    function($q) use($keyword,$productTypeFilter,$categoryFilterId,$brandId,$genericId,$manufactureId)
                    {
                        $q->when($keyword,function($query) use ($keyword){
                            $query->where('products.name','like','%'.$keyword.'%')
                            ->orWhere('products.sku','like','%'.$keyword.'%');
                        })->when($productTypeFilter != 'all',function($query) use ($productTypeFilter){
                            $query->where('products.product_type','=',$productTypeFilter);
                        })->when($categoryFilterId != 'all',function($query) use ($categoryFilterId){
                            $query->where('categories.id','=',$categoryFilterId);
                        })->when($brandId != 'all',function($query) use ($brandId){
                            $query->where('brands.id','=',$brandId);
                        })->when($genericId != 'all',function($query) use ($genericId){
                            $query->where('generics.id','=',$genericId);
                        })->when($manufactureId != 'all',function($query) use ($manufactureId){
                            $query->where('manufacturers.id','=',$manufactureId);
                        })
                        ;
                    }
                )

                ->paginate(20);
        // dd($products->toArray());
        return view('livewire.product-list',compact('products'));
    }
}

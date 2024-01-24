<?php

namespace Modules\Ecommerce\Http\Controllers;

use App\Models\Contact\Contact;
use App\Models\CurrentStockBalance;
use App\Models\Product\Brand;
use App\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Ecommerce\Entities\CustomerUser;
use Modules\Ecommerce\Entities\ProductCart;
use Modules\Ecommerce\Http\Requests\StoreCustomerUserRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\FieldService\Entities\FsCampaign;

class EcommerceController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        $keyword = $request->input('query');
        $priceFilter = $request->input('price_filter');
        $brandFilter = $request->input('brand_filter');

        $data = $request->data ?? [];
        $variation_id = $data['variation_id'] ?? null;

        $relations = [
            'uom:id,name,short_name,unit_category_id,unit_type,value,rounded_amount',
            'uom.unit_category:id,name',
            'uom.unit_category.uomByCategory:id,name,short_name,unit_type,unit_category_id,value,rounded_amount',
            'product_variations.packaging.uom',
            'product_variations.additionalProduct.productVariation.product',
            'product_variations.additionalProduct.uom',
            'product_variations.additionalProduct.productVariation.variationTemplateValue',
        ];
        $products = Product::select(
            'products.name as name',
            'products.id as id',
            'products.product_code',
            'products.sku',
            'products.product_type',
            'products.has_variation',
            'products.lot_count',
            'products.uom_id',
            'products.purchase_uom_id',
            'products.can_sale',
            'products.is_recurring',
            'products.receipe_of_material_id',
            'products.image',
            'products.brand_id',


            'product_variations.product_id',
            'product_variations.variation_sku',
            'product_variations.variation_template_value_id',
            'product_variations.default_selling_price',
            'product_variations.id as variation_id',

            'variation_template_values.variation_template_id',
            'variation_template_values.name as variation_name',
            'variation_template_values.id as variation_template_values_id'
        )->whereNull('products.deleted_at')
            ->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
            ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('can_sale', 1)
                        ->where('products.name', 'like', '%' . $keyword . '%');
                });
            })
            ->when($variation_id, function ($query) use ($variation_id) {
                $query->where('product_variations.id', $variation_id);
            })
            ->when($priceFilter, function ($query, $priceFilter) {
                if ($priceFilter == 'price_low_to_high') {
                    $query->orderBy('default_selling_price', 'asc');
                } elseif ($priceFilter == 'price_high_to_low') {
                    $query->orderBy('default_selling_price', 'desc');
                }
            })
            ->when($brandFilter, function ($query, $brandFilter) {
                    $query->whereIn('products.brand_id', $brandFilter);
            })
            ->with($relations)
            ->withSum(['stock'], 'current_quantity')
            ->orderBy('id','ASC')->paginate(12);
//return $products;

        $productsCount = Product::count();
        $brands =  Brand::select(['id', 'name'])->get();


        if ($request->ajax()){
            $view = view('ecommerce::product_card', compact('products'))->render();

            return response()->json(['html' => $view,]);
        }

        return view('ecommerce::index',compact('products', 'productsCount', 'brands'));
    }

    public function home()
    {
        return 'work';
    }

    public function productDetail(Request $request, $id)
    {
        $relations = [
            'uom:id,name,short_name,unit_category_id,unit_type,value,rounded_amount',
            'productVariations.variationTemplateValue',
        ];
        $product = Product::select(
            'products.name as name',
            'products.id as id',
            'products.product_code',
            'products.sku',
            'products.product_type',
            'products.has_variation',
            'products.lot_count',
            'products.uom_id',
            'products.purchase_uom_id',
            'products.can_sale',
            'products.is_recurring',
            'products.receipe_of_material_id',
            'products.image',
            'products.brand_id',
            'products.product_description',
            'products.category_id',

            'product_variations.product_id',
            'product_variations.variation_sku',
            'product_variations.variation_template_value_id',
            'product_variations.default_selling_price',
            'product_variations.id as variation_id',
            'variation_templates.name as variation_template_name',

            'variation_template_values.variation_template_id',
            'variation_template_values.name as variation_name',
            'variation_template_values.id as variation_template_values_id'
        )->whereNull('products.deleted_at')
            ->when($id, function ($query) use ($id) {
                $query->where('products.id', $id);
            })
            ->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
            ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
            ->leftJoin('variation_templates', 'variation_templates.id' , '=', 'variation_template_values.variation_template_id')
            ->with($relations)
            ->withSum(['stock'], 'current_quantity')
            ->first();






            $relations = [
                'uom:id,name,short_name,unit_category_id,unit_type,value,rounded_amount',
                'uom.unit_category:id,name',
                'uom.unit_category.uomByCategory:id,name,short_name,unit_type,unit_category_id,value,rounded_amount',
                'product_variations.packaging.uom',
                'product_variations.additionalProduct.productVariation.product',
                'product_variations.additionalProduct.uom',
                'product_variations.additionalProduct.productVariation.variationTemplateValue',
            ];
            $products = Product::select(
                'products.name as name',
                'products.id as id',
                'products.product_code',
                'products.sku',
                'products.product_type',
                'products.has_variation',
                'products.lot_count',
                'products.uom_id',
                'products.purchase_uom_id',
                'products.can_sale',
                'products.is_recurring',
                'products.receipe_of_material_id',
                'products.image',
                'products.brand_id',


                'product_variations.product_id',
                'product_variations.variation_sku',
                'product_variations.variation_template_value_id',
                'product_variations.default_selling_price',
                'product_variations.id as variation_id',

                'variation_template_values.variation_template_id',
                'variation_template_values.name as variation_name',
                'variation_template_values.id as variation_template_values_id'
            )->whereNull('products.deleted_at')
                ->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
                ->leftJoin('variation_template_values', 'product_variations.variation_template_value_id', '=', 'variation_template_values.id')
                ->when($product->brand_id || $product->category_id, function ($query) use ($product) {
                    $query->where(function ($query) use ($product) {
                        $query->orWhere('products.brand_id', $product->brand_id)
                            ->orWhere('products.category_id', $product->category_id);
                    });
                })
                ->with($relations)
                ->withSum(['stock'], 'current_quantity')
                ->orderBy(DB::raw('RAND()'))->inRandomOrder()->paginate(6);


//        return $products;
        return view('ecommerce::product-details-view', compact('product', 'products'));
    }

    public function pushCart(Request $request)
    {
        $productCart = ProductCart::create([
            'customer_user_id' => Auth::guard('customer')->user()->id,
            'order_id' => 0,
            'product_id' => $request->id,
            'variation_id' => $request->variation_id,
            'price' => $request->default_selling_price,
            'quantity' => $request->purchase_qty,
        ]);

        if ($productCart){
            return response()->json(['message'=> 'success']);
        }else{
            return response()->json(['message'=> 'fail']);
        }

    }


    public function getShoppingCart()
    {
        $product_cart  = ProductCart::where('customer_user_id', Auth::guard('customer')->user()->id)
        ->select(
            'product_carts.id as id',
            'product_carts.product_id as product_id',
            'product_carts.variation_id as variation_id',
            'product_carts.price as price',
            'product_carts.quantity as quantity',

            'products.name as product_name',
        )
        ->leftJoin('products', 'products.id', '=', 'product_carts.product_id')->get();

        return response()->json(['data' => $product_cart]);
    }

    public function getTotalItemInCart()
    {
        $totalItem = ProductCart::where('customer_user_id', Auth::guard('customer')->user()->id)
        ->count();
        return response()->json(['data' => $totalItem]);
    }

    public function removeCartItem($cart_id)
    {
        if (ProductCart::where('id',$cart_id)->delete()){
            return response()->json(['message' => 'success']);
        }else{
            return response()->json(['message' => 'error']);
        }

    }

    public function create()
    {
        return view('ecommerce::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('ecommerce::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('ecommerce::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}

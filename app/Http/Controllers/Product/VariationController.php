<?php

namespace App\Http\Controllers\Product;

use App\Actions\product\VariationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Variation\VariationCreateRequest;
use App\Http\Requests\Product\Variation\VariationUpdateRequest;
use App\Models\Product\Product;
use App\Models\Product\VariationTemplates;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\VariationRepository;
use App\Services\product\VariationService;
use Yajra\DataTables\Facades\DataTables;


class VariationController extends Controller
{
    protected $variationRepository;
    protected $productRepository;

    public function __construct(VariationRepository $variationRepository,
    ProductRepository $productRepository,
    )
    {

        $this->middleware(['auth', 'isActive']);
        $this->middleware('canView:variation')->only('index');
        $this->middleware('canCreate:variation')->only(['add', 'create']);
        $this->middleware('canUpdate:variation')->only(['edit', 'update']);
        $this->middleware('canDelete:variation')->only('delete');

        $this->variationRepository = $variationRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        return view('App.product.variation.variationList');
    }

    public function add()
    {
        return view('App.product.variation.variationAdd');
    }

    public function create(VariationCreateRequest $request, VariationAction $variationAction)
    {

        $variationAction->create($request, $request->variation_value);

        return redirect()->route('variations')->with('message', 'Variation created successfully');

    }

    public function edit(VariationTemplates $variation)
    {
        return view('App.product.variation.variationEdit', [
            'variation' => $variation,
            'var_tem_values' => $this->variationRepository->getTemplateValuesByIdWithRelationships($variation->id, ['variationTemplate']),
        ]);
    }

    public function update(VariationTemplates $variation, VariationUpdateRequest $request, VariationAction $variationAction)
    {

        $variationAction->update($variation->id, $request, $request->variation_values);

        return redirect()->route('variations')->with('message', 'Variation updated successfully');

    }

    public function delete(VariationTemplates $variation, VariationAction $variationAction)
    {
        $variation_value_ids = $this->variationRepository->queryTemplateValues()->where('variation_template_id', $variation->id)->pluck('id');
        $products = $this->productRepository->queryVariation()->whereIn('variation_template_value_id', $variation_value_ids)->get();

        if (!$products->isEmpty()){
            return response()->json(['error' => 'This variation is associated with one or more product. Delete these products or associate them with a different variation.']);
        }

        $variationAction->delete($variation->id);

        return response()->json(['message' => "Variation deleted successfully"]);

    }

    public function variationDataForDatatable(VariationService $variationService)
    {
        $variations = $this->variationRepository->getTemplateWithRelationships(['variationTemplateValues']);

        return DataTables::of($variations)
            ->addColumn('action', function ($variation) use ($variationService) {

                $filter_variation = $variationService->isVariationAssociatedWithProducts($variation->id);

                return ['action' => $variation->id, 'filter_variation' => $filter_variation];
            })
            ->addColumn('value', function ($variation) {
                $values = $variation->variationTemplateValues->pluck('name')->implode(', ');
                return $values;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function value($id)
    {
        $variationValues = $this->variationRepository->getTemplateValuesByIdWithRelationships($id, ['variationTemplate']);

        return response()->json($variationValues);
    }
}

<?php

namespace App\Services\UOM;

use App\Models\Product\UnitCategory;
use App\Models\Product\UOM;
use Yajra\DataTables\Facades\DataTables;

class UnitService
{
    public function getUnitCategoryDataForDataTable()
    {
        $unitCategories = UnitCategory::all();

        $dataTablesData = DataTables::of($unitCategories)
            ->addColumn('uom', function ($unitCategory) {
                $uoms = $unitCategory->uomByCategory;
                $uomLists = '';

                if ($uoms) {
                    foreach ($uoms as $uom) {
                        if ($uom->unit_type === "reference") {
                            $uomLists .= '<span class="badge badge-light-success">' . $uom->short_name . '</span>&nbsp;';
                        } else {
                            $uomLists .= '<span class="badge badge-light-dark">' . $uom->short_name . '</span>&nbsp;';
                        }
                    }
                }
                return $uomLists;
            })
            ->addColumn('action', function ($unitCategory) {
                return $unitCategory->id;
            })
            ->rawColumns(['uom', 'action'])
            ->make(true);

        return $dataTablesData;
    }

    public function getUomDataForDatatbale()
    {
        $uoms = UOM::all();

        $dataTablesData = DataTables::of($uoms)
            ->addColumn('unit_category', function ($uom) {
                return $uom->unit_category->name;
            })
            ->addColumn('action', function ($uom) {
                return $uom->id;
            })
            ->rawColumns(['unit_category', 'action'])
            ->make(true);

        return $dataTablesData;
    }
}

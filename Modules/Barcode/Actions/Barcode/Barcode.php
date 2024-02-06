<?php

namespace Modules\Barcode\Actions\Barcode;

use Illuminate\Support\Facades\DB;
use Modules\Barcode\Entities\BarcodeTemplate;

class Barcode
{

    public function create($request){
        // dd($data->toArray());
        try {
            DB::beginTransaction();
            $data=$this->data($request);
            BarcodeTemplate::create($data);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }
    public function update($id,$request)
    {
        // dd($data->toArray());
        try {
            DB::beginTransaction();
            $data = $this->data($request);
            BarcodeTemplate::where('id',$id)->update($data);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function  data($data) : array {
        $templateData = json_encode([
            'paper_type'=>arr($data, 'paper_type'),
            'paperMarginTop' => arr($data, 'paperMarginTop'),
            'paperMarginLeft' => arr($data, 'paperMarginLeft'),
            'paperWidth' => arr($data, 'paperWidth'),
            'paperHeight' => arr($data, 'paperHeight'),
            'stickerWidth' => arr($data, 'stickerWidth'),
            'stickerHeight' => arr($data, 'stickerHeight'),
            'rowCount' => arr($data, 'rowCount') ?? 1,
            'columnCount' => arr($data, 'columnCount'),
            'rowGap' => arr($data, 'rowGap'),
            'columnGap' => arr($data, 'columnGap'),
            'barcodeHeight' => arr($data, 'barcodeHeight'),
            'barcodeInnerPadding' => arr($data, 'barcodeInnerPadding'),
        ]);
        return [
            'name' => arr($data, 'name'),
            'description' => arr($data, 'description'),
            'template_data' => $templateData,
        ];
    }
}

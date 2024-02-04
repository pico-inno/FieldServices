





<table>
    <thead>
        <tr>
            <th colspan="5" rowspan="2" style="text-align: center;font-weight: bold; font-size: 19px">
                Daily Summary Report
            </th>
        </tr>
        <tr>
            <th  colspan="5" >
                &nbsp;
            </th>
        </tr>
        <tr>

            <th  colspan="5" style="text-align: center;font-weight: bold; font-size: 10px">
              Report  Date : {{fdate($dateFilter)}}
            </th>
        </tr>
        <tr>
            <th></th>
        </tr>

        <tr>
            <th></th>
        </tr>


        <tr>
            <th></th>
        </tr>


        <tr>
            <th></th>
        </tr>
    </thead>
</table>
@foreach ($PBIds as $i=>$id)
@php
    $userInfo= App\Models\BusinessUser::where('id',$id)
                    ->select('id','username','role_id','personal_info_id')
                    ->with('personal_info:id,initname,first_name,last_name','role:id,name')
                    ->first()
                    ->toArray();
    $attendanceRecord=Modules\FieldService\Entities\attendanceRecords::where('campaign_id',$campaignId)
                                                    ->where('employee_id',$id)
                                                    ->orderBy('id','DESC')
                                                    ->whereDate('checkin_datetime',$dateFilter)->first();
    $txs=productSummary($campaignId,$id,$dateFilter);
    // dd($txs);
    $pbName=$userInfo['personal_info']['initname'].' '.$userInfo['personal_info']['first_name'].' '.$userInfo['personal_info']['last_name'];
@endphp
    <table>
        <thead>

            <tr>
                <th style="font-weight: bold; text-align: center;background-color: burlywood;" colspan="5" > {{$pbName}} </th>
            </tr>
            <tr style=" font-size: 10px">
                <th style="font-weight: bold">
                    BP Name
                </th>
                <th >
                    {{$pbName}}
                </th>
            </tr>


            <tr style=" font-size: 10px">
                <th style="font-weight: bold">
                    Role
                </th>
                <th >
                    {{$userInfo['role']['name']}}
                </th>
            </tr>
            <tr style=" font-size: 10px">
                <th style="font-weight: bold">
                    Check In Date
                </th>

                @if($attendanceRecord)
                <th >
                    {{$attendanceRecord['checkin_datetime'] ? fdate($attendanceRecord['checkin_datetime']) : ''}}
                </th>
                @else
                <th></th>
                @endif

            </tr>


            <tr style=" font-size: 10px">
                <th style="font-weight: bold">
                    Check Out Date
                </th>

                @if($attendanceRecord)

            <th >
                {{$attendanceRecord['checkin_datetime'] ? fdate($attendanceRecord['checkout_datetime']) : ''}}
            </th>
                @else
                <th></th>
                @endif
            </tr>
        </thead>

    </table>

    <table>
        <thead>
            <tr>
                <th style="font-weight: bold; text-align: center;" colspan="4" > Transactions </th>
            </tr>

            <tr>
                <th style="font-weight: bold; text-align: center;" colspan="4" >  </th>
            </tr>
            <tr >
                <th style="font-weight: bold;">Product Name
                </th>
                <th  style="font-weight: bold;">Category Name</th>
                <th  style="font-weight: bold; text-align: end;"> Qty</th>
                <th  style="font-weight: bold;">UOM</th>
                <th  style="font-weight: bold; text-align: end;"> Price</th>
            </tr>
        </thead>
        <tbody >
           @if(count($txs)>0)
                @foreach ($txs as $datas)
                    @foreach ($datas as $data)
                        <tr class="">
                            <th>
                                {{$data['name']}}
                            </th>
                            <th>
                                {{$data['category_name']}}
                            </th>
                            <th class="text-end">
                                {{formatNumberv2($data['totalQty'])}}
                            </th>
                            <th>
                                &nbsp;{{ $data['uom'] }}&nbsp;
                            </th>
                            <th class="text-end">
                                {{formatNumberv2($data['totalPrice'])}}
                            </th>
                        </tr>
                    @endforeach
                @endforeach
            @endif

        </tbody>
        <tfoot>
            <tr></tr>
            <tr class="">
                <th colspan="2" style="font-weight: bold; font-size: 13px;">
                    Total :
                </th>
                <th class="text-start">
                    {{$txs->sum('totalQty')}}
                </th>
                <th></th>
                <th class="text-end">
                    {{$txs->sum('totalPrice')}}
                </th>
            </tr>
        </tfoot>
    </table>
    <table>
        <tbody>
            <tr>
                <td></td>
            </tr>

            <tr>
                <td></td>
            </tr>

            <tr>
                <td></td>
            </tr>
        </tbody>
    </table>
@endforeach

{{-- <table>
    <thead>
        <tr style="text-align: center;font-weight: bold;">
            <th style="font-weight: bold;">Product Name</th>
            <th style="font-weight: bold;">Variation Name</th>
            <th  style="font-weight: bold;">Product Variation SKU</th>
            <th  style="font-weight: bold;">Expired date</th>
            <th  style="font-weight: bold;">Unit (uom name)</th>
            <th  style="font-weight: bold;">Quantity</th>
            <th  style="font-weight: bold;">Price</th>
            <th  style="font-weight: bold;">remark</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $p)
        @php
            // if($p->productVariations[0]->variation_template_value_id){
            //     dd($p->productVariations[0]->variation_template_value_id);
            // };
        @endphp
            @if ($p->has_variation=='variable')
                @foreach ($p->productVariations as $v)
                <tr>
                    <td>{{$p->name}}</td>
                    <td>{{$v->variationTemplateValue->name}}</td>
                    <td>{{$v->variation_sku}}</td>
                    <td></td>
                    <td>{{$p->purchaseUOM ? $p->purchaseUOM->name :''}}</td>
                    <td>0</td>
                    <td>0</td>
                    <td></td>
                </tr>
                @endforeach

            @else
            <tr>
                <td>{{$p->name}}</td>
                <td></td>
                <td>{{$p->productVariations[0]->variation_sku}}</td>
                <td></td>
                <td>{{$p->purchaseUOM ? $p->purchaseUOM->name :''}}</td>
                <td>0</td>
                <td>0</td>
                <td></td>
            </tr>
            @endif

        @endforeach
    </tbody>
</table> --}}

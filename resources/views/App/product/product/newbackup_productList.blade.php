@extends('App.main.navBar')

@section('styles')
 {{-- css file for this page --}}
@endsection
@section('products_icon', 'active')
@section('products_show', 'active show')
@section('products_menu_link', 'active')
@livewireStyles
@section('title')
<!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-4">Product List</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Product</li>
        <li class="breadcrumb-item text-dark">Product List</li>
    </ul>
<!--end::Breadcrumb-->
@endsection

@section('styles')
<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
           <livewire:product.product-table />
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
    <div class="modal fade" tabindex="-1" id="locationSelect">
        <div class="modal-dialog w-md-600px modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Select Location</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="col-12">
                        <form action="" id="locationAsssignForm">
                            <select class="form-select form-select-solid" data-control="select2" id="locationSelect2" data-close-on-select="false"
                                data-placeholder="Select an option" data-allow-clear="true" multiple="multiple">
                                <option></option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light">Close</button>
                    <button type="button" class="btn btn-primary" id="locationAssignChanges">Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="locationRemove">
            <div class="modal-dialog w-md-600px modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Select Location To Remove Product</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="col-12">
                            <form action="" id="locationRemoveAsssignForm">
                                <select class="form-select form-select-solid" data-control="select2" id="locationRemoveSelect2"
                                    data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true"
                                    multiple="multiple">
                                    <option></option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light">Close</button>
                        <button type="button" class="btn btn-danger" id="locationRemoveChanges">Save</button>
                    </div>
                </div>
            </div>
        </div>
@endsection
@livewireScripts
@push('scripts')
    <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>
    <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!-- <script src="{{ asset('customJs/product/productListExport.js') }}"></script> -->
    <script>
        const modal = new bootstrap.Modal($('#locationSelect'));
        const removeModal = new bootstrap.Modal($('#locationRemove'));
        // assign product to location
        let locations=@json($locations ?? []);
        const transformedObject = Object.fromEntries(locations.map(({ id, name }) => [id, name]));
        var options=locations.map((l)=>{
            return {'id':l.id,'text':l.name}
        })

        $(document).on('click','#selectAll',function() {
            let checkBoxs=document.querySelectorAll('[data-checked="assign"]');
            checkBoxs.forEach(c => {
                $(c).prop('checked', $(this).prop('checked'))
            });
        });
        $('#assignBtn').click(()=>{
            let checkBoxs=document.querySelectorAll('[data-checked="assign"]');
            let checkCount=0;
            let productIds=[];
            checkBoxs.forEach(c => {
                if (c.checked) {
                    checkCount++;
                    let productId=$(c).val();
                    productIds=[...productIds,productId];
                }
            });
            if(checkCount <1){
                Swal.fire({
                    title: 'Please Select The Products',
                    // text: "You want to delete it!",
                    icon: "warning",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                })
            }else{
                $('#locationSelect2').select2({data:options});
                modal.show();
                $(document).off().on('click','#locationAssignChanges',async function(){
                    let locationIds=$('#locationSelect2').val();
                    console.log(locationIds);
                    if(locationIds.length > 0){
                        $('#locationAssignChanges').prop('disabled', true).text('loading.....');
                        await assign(locationIds,productIds);
                        $('#locationAssignChanges').prop('disabled', false).text('Save');
                        $('#locationSelect2').val('');
                        modal.hide();
                    }

                })
            }
        })
        function assign(locationIds,productIds){
            return new Promise((resolve, reject)=>{
                $.ajax({
                    url:'/location-product/store',
                    type: 'GET',
                    data:{
                        locationIds,
                        productIds,
                    },
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    success: function(s) {
                        Swal.fire({
                            title: 'Successfully Assigned',
                            icon: "success",
                            confirmButton:true,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        })
                        $('.checkAssign').prop('checked',false);
                        resolve();
                    },
                    error:function(){
                        resolve();
                    }
                })
            })
        }
        function remove(locationIds,productIds){
            return new Promise((resolve, reject)=>{
                $.ajax({
                    url:'/location-product/remove',
                    type: 'GET',
                    data:{
                        locationIds,
                        productIds,
                    },
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    success: function(s) {
                        Swal.fire({
                            title: 'Successfully Removed',
                            icon: "success",
                            confirmButton:true,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        })
                        resolve();
                        $('.checkAssign').prop('checked',false);
                    },
                    error:function(){
                        resolve();
                    }
                })
            })
        }
        $('#removeAssignBtn').click(()=>{
            let checkBoxs=document.querySelectorAll('[data-checked="assign"]');
            let checkCount=0;
            let productIds=[];
            checkBoxs.forEach(c => {
                if (c.checked) {
                    checkCount++;
                    let productId=$(c).val();
                    productIds=[...productIds,productId];
                }
            });
            if(checkCount <1){
                Swal.fire({
                    title: 'Please Select The Products',
                    // text: "You want to delete it!",
                    icon: "warning",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                })
            }else{
                $('#locationRemoveSelect2').select2({data:options});
                removeModal.show();
                $(document).off('click').on('click','#locationRemoveChanges',async function(){
                    let locationIds=$('#locationRemoveSelect2').val();
                    if(locationIds.length > 0){
                        $('#locationRemoveChanges').prop('disabled', true).text('loading.....');
                        await remove(locationIds,productIds);
                        $('#locationRemoveChanges').prop('disabled', false).text('Save');
                        $('#locationRemoveSelect2').val('');
                        removeModal.hide();
                    }

                })

            }
        })

        var table;
        {{--$(document).ready(function () {--}}

        {{--    function disablePagination() {--}}
        {{--        // Store the current pagination state--}}
        {{--        var currentPage = table.page();--}}

        {{--        // Disable pagination--}}
        {{--        table.page('all').draw('page');--}}

        {{--        // Revert to the original page after the export is complete--}}
        {{--        table.one('draw.dt', function () {--}}
        {{--            table.page(currentPage).draw('page');--}}
        {{--        });--}}
        {{--    }--}}

        {{--    var initDatatable = function (){--}}
        {{--        table = $('.Datatable-tb').DataTable({--}}
        {{--        processing: true,--}}
        {{--        paging:true,--}}
        {{--        serverSide: true,--}}
        {{--        'columnDefs': [--}}
        {{--        // Disable ordering on column 0 (checkbox)--}}
        {{--            { orderable: false, targets: 0 },--}}
        {{--            { orderable: false, targets: 1 }--}}
        {{--        ],--}}
        {{--        ajax: {--}}
        {{--            url: '/product-datas',--}}
        {{--            data: function (d) {--}}
        {{--                d.length = $('.Datatable-tb').DataTable().page.len();--}}
        {{--            },--}}
        {{--        },--}}

        {{--        columns: [--}}
        {{--            {--}}
        {{--            data: 'check_box',--}}
        {{--            name: 'check_box',--}}
        {{--            render: function(data, type, full, meta){--}}

        {{--                return `--}}
        {{--                    <div class="form-check form-check-sm form-check-custom ">--}}
        {{--                        <input class="form-check-input checkAssign" type="checkbox" data-checked="assign" value="${data}" />--}}
        {{--                    </div>--}}
        {{--                `;--}}
        {{--                }--}}
        {{--            },--}}
        {{--            {--}}
        {{--                "className":      'details-control',--}}
        {{--                "orderable":      false,--}}
        {{--                "data":           null,--}}
        {{--                "defaultContent": `--}}
        {{--                    <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px">--}}
        {{--                        <i id="toggle" class="fas fa-plus"></i>--}}
        {{--                    </button>--}}
        {{--                `--}}
        {{--            },--}}

        {{--            {--}}
        {{--                data: 'action',--}}
        {{--                name: 'action',--}}
        {{--                render: function(data, type, full, meta){--}}

        {{--                    let updatePermission = <?php echo hasUpdate('product') ? 'true' : 'false'; ?>;--}}
        {{--                    let deletePermission = <?php echo hasDelete('product') ? 'true' : 'false'; ?>;--}}

        {{--                    return `--}}
        {{--                        <div class="dropdown">--}}
        {{--                         <button class="btn btn-sm btn-light btn-active-light-primary fw-semibold fs-7  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">--}}
        {{--                             Actions--}}
        {{--                         </button>--}}
        {{--                         <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">--}}
        {{--                                  ${updatePermission ? `<li><a href="/product/edit/${data}" class="dropdown-item p-2 edit-brand px-3" >--}}
        {{--                                <i class="fas fa-pen-to-square me-3"></i>{{ __('product/product.edit') }}</a>--}}
        {{--                            </li>` : ''}--}}
        {{--                                    ${deletePermission ? ` <li><div class="dropdown-item p-2 product-delete-confirm cursor-pointer px-3" data-id="${data}" >--}}
        {{--                                <i class="fas fa-trash me-3"></i>{{ __('product/product.delete') }}</div>--}}
        {{--                            </li>` : ''}--}}
        {{--                        </ul>--}}
        {{--                        </div>--}}
        {{--                        `;--}}
        {{--                }--}}

        {{--            },--}}
        {{--            {--}}
        {{--                data: 'product',--}}
        {{--                name: 'product.name',--}}
        {{--                render: function(data, type, full, meta) {--}}

        {{--                    return `--}}
        {{--                    <div class="d-flex align-items-center">--}}
        {{--                        <div class="symbol symbol-50px">--}}
        {{--                            ${data.image ? `<span class="symbol-label" style="background-image:url(/storage/product-image/${data.image});"></span>` : `<span class="symbol-label"></span>` }--}}
        {{--                        </div>--}}
        {{--                        <div class="ms-3 ${data.deleted_variation == 'deleted' ? 'text-danger' : ''}">${data.name}</div>--}}
        {{--                    </div>--}}
        {{--                    `;--}}
        {{--                }--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'sku',--}}
        {{--                name: 'sku',--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'assign_location',--}}
        {{--                name: 'assign_location',--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'purchase_price',--}}
        {{--                name: 'purchase_price',--}}
        {{--                render: function(data) {--}}
        {{--                    if(data.has_variation === "single"){--}}
        {{--                        return data.purchase_prices;--}}
        {{--                    }--}}
        {{--                    if(data.has_variation === "variable"){--}}
        {{--                        return '';--}}
        {{--                    }--}}
        {{--                }--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'selling_price',--}}
        {{--                name: 'selling_price',--}}
        {{--                render: function(data) {--}}
        {{--                    if(data.has_variation === "single"){--}}
        {{--                        return data.selling_prices;--}}
        {{--                    }--}}
        {{--                    if(data.has_variation === "variable"){--}}
        {{--                        return '';--}}
        {{--                    }--}}
        {{--                }--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'product_type',--}}
        {{--                name: 'product_type'--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'category',--}}
        {{--                name: 'category',--}}

        {{--            },--}}

        {{--            {--}}
        {{--                data: 'brand',--}}
        {{--                name: 'brand'--}}
        {{--            },--}}

        {{--            {--}}
        {{--                data: 'generic',--}}
        {{--                name: 'generic'--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'manufacturer',--}}
        {{--                name: 'manufacturer'--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'product_custom_field1',--}}
        {{--                name: 'product_custom_field1'--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'product_custom_field2',--}}
        {{--                name: 'product_custom_field2'--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'product_custom_field3',--}}
        {{--                name: 'product_custom_field3'--}}
        {{--            },--}}
        {{--            {--}}
        {{--                data: 'product_custom_field4',--}}
        {{--                name: 'product_custom_field4'--}}
        {{--            }--}}

        {{--        ]--}}
        {{--    });--}}

        {{--    disablePagination();--}}
        {{--    };--}}

        {{--    initDatatable();--}}
        {{--    // Search--}}
        {{--    $('#search').on('keyup', function() {--}}
        {{--        table.search(this.value).draw();--}}
        {{--    });--}}

        {{--    $('#location_filter').on('change', function() {--}}
        {{--        let value = this.value;--}}
        {{--        if (value === 'all') {--}}
        {{--            value = '';--}}
        {{--        }--}}
        {{--        table.column(5).search(value).draw();--}}
        {{--    });--}}

        {{--    $('#product_type_filter').on('change', function() {--}}
        {{--        let value = this.value;--}}
        {{--        if (value === 'all') {--}}
        {{--            value = '';--}}
        {{--        }--}}
        {{--        table.column(8).search(value).draw();--}}
        {{--    });--}}

        {{--    $('#category_filter').on('change', function () {--}}
        {{--        let value = this.value;--}}
        {{--        if (value === 'all') {--}}
        {{--            value = '';--}}
        {{--        }--}}

        {{--        table.column('category:name').search(value).draw();--}}
        {{--    });--}}




        {{--    $('#brand_filter').on('change', function() {--}}
        {{--        let value = this.value;--}}
        {{--        if (value === 'all') {--}}
        {{--            value = '';--}}
        {{--        }--}}
        {{--        table.column(10).search(value).draw();--}}
        {{--    });--}}

        {{--    $('#generic_filter').on('change', function() {--}}
        {{--        let value = this.value;--}}
        {{--        if (value === 'all') {--}}
        {{--            value = '';--}}
        {{--        }--}}
        {{--        table.column(11).search(value).draw();--}}
        {{--    });--}}

        {{--    $('#manufacture_filter').on('change', function() {--}}
        {{--        let value = this.value;--}}
        {{--        if (value === 'all') {--}}
        {{--            value = '';--}}
        {{--        }--}}
        {{--        table.column(12).search(value).draw();--}}
        {{--    });--}}


        {{--    // Product - DELETE--}}
        {{--    $(document).on('click', '.product-delete-confirm', function(e) {--}}
        {{--        e.preventDefault();--}}
        {{--        let id = $(this).data('id');--}}
        {{--        Swal.fire({--}}
        {{--            title: 'Are you sure?',--}}
        {{--            text: "You want to delete it!",--}}
        {{--            type: 'warning',--}}
        {{--            showCancelButton: true,--}}
        {{--            confirmButtonColor: '#3085d6',--}}
        {{--            cancelButtonColor: '#d33',--}}
        {{--            confirmButtonText: 'Yes, delete it!'--}}
        {{--        }).then((result) => {--}}
        {{--            if (result.value) {--}}
        {{--                $.ajax({--}}
        {{--                    url: '/product/delete/' + id,--}}
        {{--                    type: 'POST',--}}
        {{--                    headers: {--}}
        {{--                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--                    },--}}
        {{--                    data: {--}}
        {{--                        _method: 'DELETE',--}}
        {{--                    },--}}
        {{--                    success: function(response) {--}}
        {{--                        if(response.message){--}}
        {{--                            success(response.message);--}}
        {{--                        }--}}

        {{--                        if(response.error){--}}
        {{--                            Swal.fire({--}}
        {{--                                text: response.error,--}}
        {{--                                icon: "error",--}}
        {{--                                buttonsStyling: false,--}}
        {{--                                showCancelButton: false,--}}
        {{--                                confirmButtonText: "Ok",--}}
        {{--                                cancelButtonText: "Delete",--}}
        {{--                                customClass: {--}}
        {{--                                    confirmButton: "btn fw-bold btn-primary",--}}
        {{--                                }--}}
        {{--                            });--}}
        {{--                        }--}}
        {{--                        table.ajax.reload();--}}
        {{--                    }--}}
        {{--                })--}}
        {{--            }--}}
        {{--        });--}}

        {{--    });--}}

        {{--    // Variation - DELETE--}}
        {{--    $(document).on('click', '.variation-delete-confirm', function(e) {--}}
        {{--        e.preventDefault();--}}
        {{--        let id = $(this).data('id');--}}
        {{--        Swal.fire({--}}
        {{--            title: 'Are you sure?',--}}
        {{--            text: "You want to delete it!",--}}
        {{--            type: 'warning',--}}
        {{--            showCancelButton: true,--}}
        {{--            confirmButtonColor: '#3085d6',--}}
        {{--            cancelButtonColor: '#d33',--}}
        {{--            confirmButtonText: 'Yes, delete it!'--}}
        {{--        }).then((result) => {--}}
        {{--            if (result.value) {--}}
        {{--                $.ajax({--}}
        {{--                    url: '/product-variation/delete/' + id,--}}
        {{--                    type: 'POST',--}}
        {{--                    headers: {--}}
        {{--                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--                    },--}}
        {{--                    data: {--}}
        {{--                        _method: 'DELETE',--}}
        {{--                    },--}}
        {{--                    success: function(response) {--}}
        {{--                        if (response.message) {--}}
        {{--                            success(response.message);--}}
        {{--                        }--}}

        {{--                        table.ajax.reload();--}}
        {{--                    }--}}

        {{--                })--}}
        {{--            }--}}
        {{--        });--}}

        {{--    });--}}

        {{--    // Sub Table for Variation--}}
        {{--    $('.Datatable-tb tbody').on('click', 'td.details-control', function () {--}}
        {{--        var tr = $(this).closest('tr');--}}
        {{--        var row = table.row( tr );--}}

        {{--        // for single product type--}}
        {{--        if(row.data().has_variation === "single"){--}}
        {{--            return;--}}
        {{--        }--}}

        {{--        // for '+' and '-' click button--}}
        {{--        let toggle = tr.find('#toggle');--}}

        {{--        if ( row.child.isShown() ) {--}}
        {{--            toggle.removeClass('fa-minus').addClass('fa-plus');--}}

        {{--            // This row is already open - close it--}}
        {{--            row.child.hide();--}}
        {{--            tr.removeClass('shown');--}}

        {{--        }else {--}}
        {{--            toggle.removeClass('fa-plus').addClass('fa-minus');--}}
        {{--            // Open this row--}}
        {{--            let purchasePrices = row.data().purchase_price.purchase_prices; // outpout is array [10, 20, 30]--}}

        {{--            let childRows = '';--}}

        {{--            $.each(purchasePrices, function(index, item){--}}
        {{--                childRows += format(row.data(), index, item);--}}
        {{--            })--}}

        {{--            row.child(childRows).show();--}}
        {{--            tr.addClass('shown');--}}
        {{--        }--}}
        {{--    });--}}

        {{--     /* Formatting function for row details - modify as you need */--}}
        {{--    function format ( data,index, purchase_price ) {--}}
        {{--        // begin: for category conditions--}}
        {{--            let category;--}}
        {{--            if(data.category.parentCategory && data.category.subCategory){--}}
        {{--                category = data.category.parentCategory + " ," + data.category.subCategory;--}}
        {{--            }--}}
        {{--            if(data.category.parentCategory && !data.category.subCategory){--}}
        {{--                category = data.category.parentCategory;--}}
        {{--            }--}}
        {{--            if(!data.category.parentCategory && data.category.subCategory){--}}
        {{--                category = data.category.subCategory;--}}
        {{--            }--}}
        {{--            if(category === undefined){--}}
        {{--                category = '';--}}
        {{--            }--}}
        {{--        // end: for category conditions--}}
        {{--        let variation_name = data.purchase_price.variation_name[index] != 'deleted' ? data.purchase_price.variation_name[index] : data.purchase_price.deleted_variation_name[index];--}}
        {{--        let selling_price = data.selling_price.selling_prices[index];--}}
        {{--        let variation_id = data.product_variations[index].id;--}}
        {{--        let cssClass = data.purchase_price.variation_name[index] == 'deleted' ? 'text-danger' : 'text-gray-500';--}}
        {{--        return `--}}
        {{--            <table class="table" >--}}
        {{--                <tr>--}}
        {{--                    <td class="w-50px"></td>--}}
        {{--                    <td class="text-start w-100px text-gray-500">--}}
        {{--                        <div class="dropdown">--}}
        {{--                            <button class="btn btn-sm btn-light btn-active-light-primary fw-semibold fs-7  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">--}}
        {{--                                Actions--}}
        {{--                            </button>--}}
        {{--                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">--}}
        {{--                                <li><div class="dropdown-item p-2 variation-delete-confirm cursor-pointer" data-id="${variation_id}" >Delete</div></li>--}}
        {{--                            </ul>--}}
        {{--                        </div>--}}
        {{--                    </td>--}}
        {{--                    <td class="w-150px ${cssClass}">${data.name} | ${variation_name}</td>--}}
        {{--                    <td class="text-start w-150px text-gray-500">Ks ${purchase_price}</td>--}}
        {{--                    <td class="text-start w-100px text-gray-500">Ks ${selling_price}</td>--}}
        {{--                    <td class="text-start w-100px text-gray-500">${data.has_variation}</td>--}}
        {{--                    <td class="text-start w-150px text-gray-500">${category}</td>--}}
        {{--                    <td class="text-start w-100px text-gray-500">${data.brand !== null ? data.brand : ''}</td>--}}
        {{--                    <td class="text-start w-100px text-gray-500"></td>--}}
        {{--                    <td class="text-start w-100px text-gray-500"></td>--}}
        {{--                    <td class="text-start w-150px text-gray-500"></td>--}}
        {{--                    <td class="text-start w-150px text-gray-500"></td>--}}
        {{--                    <td class="text-start w-150px text-gray-500"></td>--}}
        {{--                    <td class="text-start w-150px text-gray-500"></td>--}}
        {{--                </tr>--}}
        {{--            </table>--}}
        {{--            `;--}}
        {{--    }--}}


        {{--    var exportButtons = () => {--}}
        {{--                const documentTitle = 'Product List';--}}



        {{--                var buttons = new $.fn.dataTable.Buttons(table, {--}}
        {{--                    buttons: [--}}
        {{--                        {--}}
        {{--                            extend: 'copyHtml5',--}}
        {{--                            title: documentTitle,--}}
        {{--                            exportOptions: {--}}
        {{--                                page: 'all', // Export all pages--}}
        {{--                                search: 'none' // Exclude search filter from export--}}
        {{--                            },--}}


        {{--                        },--}}
        {{--                        {--}}
        {{--                            extend: 'excelHtml5',--}}
        {{--                            title: documentTitle,--}}
        {{--                            exportOptions: {--}}
        {{--                                page: 'all', // Export all pages--}}
        {{--                                search: 'none' // Exclude search filter from export--}}
        {{--                            },--}}

        {{--                        },--}}
        {{--                        {--}}
        {{--                            extend: 'csvHtml5',--}}
        {{--                            title: documentTitle,--}}
        {{--                            exportOptions: {--}}
        {{--                        modifier: {--}}
        {{--                            page: 'all', // Export all pages--}}
        {{--                            search: 'none' // Exclude search filter from export--}}
        {{--                        }--}}
        {{--                    }--}}
        {{--                        },--}}
        {{--                        {--}}
        {{--                            extend: 'pdfHtml5',--}}
        {{--                            title: documentTitle,--}}
        {{--                            exportOptions: {--}}
        {{--                        modifier: {--}}
        {{--                            page: 'all', // Export all pages--}}
        {{--                            search: 'none' // Exclude search filter from export--}}
        {{--                        }--}}
        {{--                    }--}}
        {{--                        },--}}

        {{--                    ]--}}
        {{--                }).container().appendTo($('#kt_datatable_example_buttons'));--}}

        {{--                const exportButtons = document.querySelectorAll('#kt_datatable_example_export_menu [data-kt-export]');--}}
        {{--                // exportButtons.forEach(exportButton => {--}}
        {{--                //     exportButton.addEventListener('click', e => {--}}
        {{--                //         console.log('work');--}}
        {{--                //         e.preventDefault();--}}

        {{--                //         const exportValue = e.target.getAttribute('data-kt-export');--}}
        {{--                //         const target = document.querySelector('.dt-buttons .buttons-' + exportValue);--}}
        {{--                //         target.click();--}}
        {{--                //     });--}}
        {{--                // });--}}
        {{--     }--}}
        {{--     exportButtons();--}}
        {{--});--}}



        @if(session('message'))

        success("{{session('message')}}");

        @endif
    </script>
@endpush

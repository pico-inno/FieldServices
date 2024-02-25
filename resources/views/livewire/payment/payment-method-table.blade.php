<div class="">
    <!--begin::Card-->
    <div class="card">

        <div class="card-header py-5 gap-2 gap-md-5 d-flex flex-column">
            <div class="card-toolbar d-flex justify-content-between ">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                        </svg>
                    </span>
                    <input type="text" data-kt-filter="search" class="form-control form-control-sm form-control-solid w-250px ps-14" placeholder="Search....." />
                </div>
                <!--end::Search-->

                <livewire:Payment.PaymentMethodCreateAction wire:ignore  />
            </div>
        </div>
        <div class="card-body pt-0 saleTableCard table-responsive position-relative" id="">
            <div class="position-absolute w-fit  top-10 bg-white p-3 rounded-1 border border-1 border-gray-500 "
                wire:loading style="top: 40px;left:50%;">
                <h2 class="text-primary">Loading....</h2>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-7 gy-3 table-max-high" id="kt_Item_table"
                    data-sticky-header="true">
                    <thead>
                        <tr class="text-end text-gray-600 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom  me-3">
                                    <input class="form-check-input checkForDelete" data-checked="selectAll" id="selectAll"
                                        type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_Item_table .form-check-input" value="" />
                                </div>
                            </th>
                            <th class="min-w-100px text-start">
                                {{__('table/label.actions')}}
                            </th>
                            <th class="min-w-100px text-start cursor-pointer" wire:click="sortBy('sales_voucher_no')">
                                Name
                                <x-datatable.sort-icon field="sales_voucher_no" :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </th>
                            <th class="min-w-100px text-start cursor-pointer" wire:click="sortBy('contacts.first_name')">
                                <div class="">
                                    <span>Payment Account</span>
                                    <x-datatable.sort-icon field="contacts.first_name" :sortField="$sortField"
                                        :sortAsc="$sortAsc" />
                                </div>
                            </th>
                            <th class="min-w-100px text-end">note</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="fw-semibold text-gray-600 fs-6 fw-semibold" id="allSaleTable">
                        @foreach ($paymentMethods as $paymentMethod)
                            {{-- {{Livewire::dev()}} --}}
                            <tr wire:ignore.self  wire:key="pml-{{ $paymentMethod->id }}" >
                                <td class="actionRow">
                                    <div class="form-check form-check-sm form-check-custom ">
                                        <input class="form-check-input checkForDelete" type="checkbox" data-checked="delete"
                                            value='{{$paymentMethod->id}}' />
                                    </div>
                                </td>
                                <td class="actionRow" >
                                    <div class="dropdown text-start actionRow"  >
                                        <livewire:payment.method.Actions :id="$paymentMethod->id" wire:key="pm-{{ $paymentMethod->id }}" >
                                    </div>
                                </td>
                                <td class="text-start"> {{$paymentMethod->name}}</td>
                                {{-- @php
                                    dd($paymentMethod->toArray(),$paymentMethod->toArray()['payment_account']);
                                @endphp --}}
                                <td> {{$paymentMethod->toArray()['payment_account']['name'] ?? ''}}</td>
                                <td class="text-end">{{substr($paymentMethod->note,0,30)}} {{strlen($paymentMethod->note) >30 ? '...' :''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="row justify-content-center  justify-content-md-between">
                <div class="col-md-6 col-12 mb-3 ">
                    <div class="w-auto">
                        <select name="" id="" wire:model.change="perPage" class="form-select form-select-sm w-auto m-auto m-md-0">
                            @foreach ($aviablePerPages as $page)
                            <option value="{{$page}}">{{$page}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    {{$paymentMethods->links()}}
                </div>
            </div>
        </div>

    </div>

</div>

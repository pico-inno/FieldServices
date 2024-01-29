<div>
        <div class="row mb-4">
            <div class="d-flex align-items-center position-relative my-1 me-2 col-12 col-md-6 col-lg-4">
                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                            transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
                <input type="search" wire:model.live.debounce.50ms="search" data-kt-saleItem-table-filter="search"
                    class="form-control form-control-sm  ps-15" placeholder="Search" />
            </div>
        </div>
        @if ($AllCampaigns && count($AllCampaigns) >0 )
        <div class="row align-items-stretch">
            @foreach ($AllCampaigns as $AllCampaign)
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="card h-100">
                        <div class="card-header min-h-unset ">
                            <div class="card-title col-12">
                                <div class="d-flex justify-content-between align-items-center col-12">
                                    <div class="">
                                        {{-- <i class="fa-solid fa-circle text-success fs-9  animation-blink end-100"></i> --}}
                                        <span class="fs-8 fw-semibold text-primary">Campaign</span>
                                    </div>
                                    @if ($hasUpdate || $hasDelete)
                                    <div class="fs-8 fw-bold">
                                        <div class="dropdown ">
                                            <button class="btn btn-light bg-transparent py-1 text-center btn-sm fs-8  " type="button" id="purchaseDropDown" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis p-0 fs-2"></i>
                                            </button>
                                            <div class="z-3">
                                                <ul class="dropdown-menu z-10 p-2" aria-labelledby="purchaseDropDown" role="menu">
                                                    @if ($hasUpdate)
                                                    <a href="{{route('campaign.edit',$AllCampaign->id)}}" class="dropdown-item fw-semibold">Edit</a>
                                                    @endif
                                                    @if ($hasDelete)
                                                        <form action="{{route('campaign.destroy',$AllCampaign->id)}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"  class="dropdown-item fw-semibold text-danger">Delete </button>
                                                        </form>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="card-body mt-0 pt-0 pb-4">
                            @php
                                $route=route('campaign.showOar',$AllCampaign->id);
                                if(!$hasCampaignReport){
                                    $route=route('campaign.showGallery',$AllCampaign->id);
                                }
                            @endphp
                            <a href="{{$route}}">
                                <div class="row min-h-80px">
                                    <div class="col-12 col-sm-6 mt-5">
                                        <div class="fs-7 fw-semibold text-gray-600">{{__('fieldservice::campaign.outlet')}}</div>
                                        <div class="fs-7 fw-semibold mt-2 text-dark">
                                            {{$AllCampaign->bln}}
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 mt-5">
                                        <div class="fs-7 fw-semibold text-gray-600">{{__('fieldservice::campaign.campaign_name')}}</div>
                                        <div class="fs-7 fw-semibold mt-2  text-dark">
                                            {{$AllCampaign->name}}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-6 mt-5">
                                        <div class="fs-7 fw-semibold text-gray-600">{{__('fieldservice::campaign.date')}}</div>
                                        <div class="fs-7 fw-semibold mt-2  text-dark">
                                            {{fdate($AllCampaign->campaign_start_date,false,false)}} to {{fdate($AllCampaign->campaign_end_date,false,false)}}
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 mt-5">
                                        <div class="fs-7 fw-semibold text-gray-600">{{__('fieldservice::campaign.campaign_leader')}}</div>
                                        <div class="fs-7 fw-semibold mt-2  text-dark">
                                            {{optional(App\Models\BusinessUser::where('id',$AllCampaign->campaign_leader)->first())->username}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="separator my-3"></div>
                            <div class="mt-3  justify-content-start">
                                @if ($AllCampaign->status!='start' && $AllCampaign->campaign_leader==Auth::user()->id)
                                    <button href="#" class="btn btn-sm btn-success fs-8 py-2 start_campaign" data-id="{{$AllCampaign->id}}">
                                        {{__('fieldservice::actions.start')}}
                                    </button>

                                @elseif ($AllCampaign->status!='start' && $AllCampaign->campaign_leader!=Auth::user()->id)
                                    <button class="bg-dark text-white btn btn-sm" disabled> Campaign Not Start</button>
                                @else
                                    @if (getStatus($AllCampaign->id,Auth::user()->id)=='checkIn')
                                        <a href="{{route('campaign.ptx',$AllCampaign->id)}}" class="btn btn-sm  btn-outline btn-primary fs-8 py-2">
                                            {{__('fieldservice::actions.activity')}}
                                        </a>
                                    {{-- @elseif(isCheckOut($AllCampaign->id,Auth::user()->id))
                                        <button class="btn btn-sm btn-secondary fs-8 py-2 " disabled>
                                            You Already Checkout
                                        </button> --}}
                                    @endif
                                    @if (getStatus($AllCampaign->id,Auth::user()->id)=='checkOut' || getStatus($AllCampaign->id,Auth::user()->id)==null)
                                        <a href="{{route('campaign.checkInForm',$AllCampaign->id)}}"  class="btn btn-sm btn-outline btn-outline-primary fs-8 py-2">
                                            {{__('fieldservice::actions.check_in')}}
                                        </a>
                                        @if ($AllCampaign->status=='start')
                                            <button type="button" class="btn btn-sm  btn-outline btn-danger fs-8 py-2 close_campaign" data-id="{{$AllCampaign->id}}">
                                                Close
                                            </button>
                                        @endif
                                    @elseif(getStatus($AllCampaign->id,Auth::user()->id)=='checkIn')
                                        <a href="{{route('campaign.checkOutForm',$AllCampaign->id)}}" class="btn btn-sm  btn-outline btn-outline-danger fs-8 py-2">
                                            {{__('fieldservice::actions.check_out')}}
                                        </a>

                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @elseif(count($AllCampaigns ?? []) <=0 )
        <div class="row">
            <div class="col-12 h-100px text-center border-dotted border-gray-300 d-flex justify-content-center align-items-center rounded">
                No Campaign Found!
            </div>
        </div>
        @endif
        <div class="">
            {{$AllCampaigns->links()}}
        </div>
</div>

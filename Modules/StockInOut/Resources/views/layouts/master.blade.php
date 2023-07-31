@if ($navbarType === 'main_link')
@if(hasAll('stockin'))
    <!--begin:Menu item-->
    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('stockin_here_show')">
        <!--begin:Menu link-->
        <span class="menu-link">
            <span class="menu-icon">
                <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-03-24-172858/core/html/src/media/icons/duotune/general/gen035.svg-->
                    <span
                        class="svg-icon svg-icon-gray-500 svg-icon-2"><svg
                            width="24" height="24"
                            viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                    <rect opacity="0.3" x="2" y="2" width="20"
                          height="20" rx="5" fill="currentColor"/>
                    <rect x="10.8891" y="17.8033" width="12"
                          height="2" rx="1"
                          transform="rotate(-90 10.8891 17.8033)"
                          fill="currentColor"/>
                    <rect x="6.01041" y="10.9247" width="12"
                          height="2" rx="1" fill="currentColor"/>
                    </svg>
                    </span>
                <!--end::Svg Icon-->
            </span>
            <span
                class="menu-title">{{__('stockinout::stockin.stockin')}}</span>
            <span class="menu-arrow"></span>
        </span>
        <!--end:Menu link-->
        <!--begin:Menu sub-->
        <!--begin:Menu sub-->
        <div class="menu-sub menu-sub-accordion">
            @if(hasView('stockin') && hasCreate('stockin'))
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link @yield('upcoming_stockin_active_show')"
                       href="{{route('stock-in.upcoming.index')}}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                        <span class="menu-title">{{__('stockinout::stockin.upcoming_list')}}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            @endif
            <!--begin:Menu item-->
            @if(hasView('stockin'))
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link @yield('stockin_active_show')" href="{{route('stock-in.index')}}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                        <span class="menu-title">{{__('stockinout::stockin.stockin_list')}}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            @endif
            @if(hasCreate('stockin'))
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link  @yield('stockin_add_active_show')" href="{{route('stock-in.create')}}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                        <span class="menu-title">{{__('stockinout::stockin.create')}}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            @endif
        </div>
        <!--end:Menu sub-->
        <!--end:Menu sub-->
    </div>
    <!--end:Menu item-->
@endif
@if(hasAll('stockout'))
    <!--begin:Menu item-->
    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('stockout_here_show')">
        <!--begin:Menu link-->
        <span class="menu-link">
                                                                <span class="menu-icon">
                                                                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-03-24-172858/core/html/src/media/icons/duotune/general/gen036.svg-->
                                                                    <span class="svg-icon svg-icon-gray-500 svg-icon-2"><svg
                                                                            width="24" height="24" viewBox="0 0 24 24"
                                                                            fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                    <rect opacity="0.3" x="2" y="2" width="20"
                                                                          height="20" rx="5" fill="currentColor"/>
                                                                    <rect x="6.0104" y="10.9247" width="12" height="2"
                                                                          rx="1" fill="currentColor"/>
                                                                    </svg>
                                                                    </span>
                                                                    <!--end::Svg Icon-->
                                                                </span>
                                                                <span
                                                                    class="menu-title">{{__('stockinout::stockout.stockout')}}</span>
                                                                <span class="menu-arrow"></span>
                                                            </span>
        <!--end:Menu link-->
        <!--begin:Menu sub-->
        <div class="menu-sub menu-sub-accordion">
            @if(hasView('stockout') && hasCreate('stockout'))
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link @yield('outgoing_stockout_active_show')"
                       href="{{route('stock-out.outgoing.index')}}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                        <span class="menu-title">{{__('stockinout::stockout.outgoing_list')}}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            @endif
            @if(hasView('stockout'))
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link @yield('stockout_active_show')" href="{{route('stock-out.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                        <span class="menu-title">{{__('stockinout::stockout.stockout_list')}}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            @endif
            @if(hasCreate('stockout'))
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link  @yield('stockout_add_active_show')" href="{{route('stock-out.create')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                        <span class="menu-title">{{__('stockinout::stockout.create')}}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            @endif
        </div>
        <!--end:Menu sub-->
        <!--end:Menu sub-->
    </div>
    <!--end:Menu item-->
@endif
@elseif($navbarType === 'report_link')
    <!--begin:Menu item-->
    @if((hasView('stockin') && hasExport('stockin')) || (hasView('stockout') && hasExport('stockin')))
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link @yield('stock_summary_active_show')" href="{{route('report.stock.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                <span class="menu-title">{{__('stockinout::stockinoutreport.stockinout_summary')}}</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link  @yield('stock_details_reports_active_show')" href="{{route('report.stock.details.index')}}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                <span class="menu-title">{{__('stockinout::stockinoutreport.stockinout_details')}}</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
    @endif
@endif

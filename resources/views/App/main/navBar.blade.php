<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="../"/>
		<title>ERPPOS</title>
		<meta charset="utf-8" />
		<meta name="description" content="သင့်လုပ်ငန်းနဲ့ အသင့်တော်ဆုံး PICO SBS ကိုသုံး" />
		<meta name="keywords" content="pico, picosbs, sbs, erp, pos, erppos, picoerp, picosbs" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="ERPPOS Business Software" />
		<meta property="og:site_name" content="ERPPOS" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="shortcut icon" href="{{asset('assets/media/logos/favicon.ico')}}" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href={{asset("assets/plugins/custom/datatables/datatables.bundle.css")}} rel="stylesheet" type="text/css" />
		<link href={{asset("assets/plugins/custom/vis-timeline/vis-timeline.bundle.css")}} rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css" />
		<link href={{asset("assets/css/style.bundle.css")}} rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href={{asset("customCss/scrollbar.css")}}>
		<!--end::Global Stylesheets Bundle-->
        @yield('styles')
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" style="background-image:" class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-secondary-enabled" data-kt-aside-minimize="on">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ){ if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Aside-->
				<div id="kt_aside" class="aside aside-extended" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
					<!--begin::Primary-->
					<div class="aside-primary d-flex flex-column align-items-lg-center flex-row-auto">
						<!--begin::Logo-->
						<div class="aside-logo d-none d-lg-flex flex-column align-items-center flex-column-auto py-10" id="kt_aside_logo">
							<a href="../../demo7/dist/index.html">
								<img alt="Logo" src={{asset("assets/media/logos/demo7.svg")}} class="h-24px w-35px" />
							</a>
						</div>
						<!--end::Logo-->
						<!--begin::Nav-->
						<div class="aside-nav d-flex flex-column align-items-center flex-column-fluid w-100 pt-5 pt-lg-0" id="kt_aside_nav">
							<!--begin::Wrapper-->
							<div class="hover-scroll-overlay-y mb-5 px-5" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_aside_nav" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-offset="0px">
								<!--begin::Nav-->
								<ul class="nav flex-column w-100" id="kt_aside_nav_tabs">
                                    <!--begin::Nav item-->
                                    <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Home">
                                        <!--begin::Nav link-->
                                        <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-600 btn-active-light @yield('dashboard_active')" data-bs-toggle="tab" href="#kt_aside_nav_tab_dashboard">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                            <i class="fa-solid fa-house fs-6"></i>
                                        </a>
                                        <!--end::Nav link-->
                                    </li>
                                    <!--end::Nav item-->

                                    @if(hasAll('user')||hasAll('role'))
									<!--begin::Nav item-->
									<li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Users">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-600 btn-active-light @yield('user_active')" data-bs-toggle="tab" href="#kt_aside_nav_tab_users">
											<!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                            <i class="fa-solid fa-users fs-6"></i>
										</a>
										<!--end::Nav link-->
									</li>
									<!--end::Nav item-->
                                    @endif
									@if(hasAll('customer')||hasAll('supplier'))
                                    <!--begin::Nav item-->
									<li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Contacts">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light  @yield('contact_active')" data-bs-toggle="tab" href="#kt_aside_nav_tab_contact">
											<!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                            <i class="fa-solid fa-address-book fs-6"></i>
										</a>
										<!--end::Nav link-->
									</li>
									<!--end::Nav item-->
                                    @endif
                                    @if(hasAll('product') || hasAll('variation') || hasAll('selling price groups') || hasAll('unit') || hasAll('uom') || hasAll('category') || hasAll('brand') || hasAll('warranty') || hasAll('manufacture') || hasAll('generic'))
									<!--begin::Nav item-->
									<li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Products">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('products_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_products">
											<!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->

                                            <span class="svg-icon svg-icon-gray-600 svg-icon-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.3" d="M3 13H10C10.6 13 11 13.4 11 14V21C11 21.6 10.6 22 10 22H3C2.4 22 2 21.6 2 21V14C2 13.4 2.4 13 3 13Z" fill="currentColor"/>
                                                    <path d="M7 16H6C5.4 16 5 15.6 5 15V13H8V15C8 15.6 7.6 16 7 16Z" fill="currentColor"/>
                                                    <path opacity="0.3" d="M14 13H21C21.6 13 22 13.4 22 14V21C22 21.6 21.6 22 21 22H14C13.4 22 13 21.6 13 21V14C13 13.4 13.4 13 14 13Z" fill="currentColor"/>
                                                    <path d="M18 16H17C16.4 16 16 15.6 16 15V13H19V15C19 15.6 18.6 16 18 16Z" fill="currentColor"/>
                                                    <path opacity="0.3" d="M3 2H10C10.6 2 11 2.4 11 3V10C11 10.6 10.6 11 10 11H3C2.4 11 2 10.6 2 10V3C2 2.4 2.4 2 3 2Z" fill="currentColor"/>
                                                    <path d="M7 5H6C5.4 5 5 4.6 5 4V2H8V4C8 4.6 7.6 5 7 5Z" fill="currentColor"/>
                                                    <path opacity="0.3" d="M14 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H14C13.4 11 13 10.6 13 10V3C13 2.4 13.4 2 14 2Z" fill="currentColor"/>
                                                    <path d="M18 5H17C16.4 5 16 4.6 16 4V2H19V4C19 4.6 18.6 5 18 5Z" fill="currentColor"/>
                                                </svg>
                                            </span>
										</a>
										<!--end::Nav link-->
									</li>
									<!--end::Nav item-->
                                        @endif
                                    @if(hasAll('purchase'))
                                    <!--begin::Nav item-->
									<li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Purchases">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('purchases_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_stocks_purchase">
											<!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
											<i class="fa-solid fa-cart-shopping fs-6"></i>
											<!--end::Svg Icon-->
										</a>
										<!--end::Nav link-->
									</li>
                                    @endif
                                    @if(hasAll('sell'))
                                    <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Sell">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('sell_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_stocks_sell">
											<!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
											<span class="svg-icon svg-icon-gray-500 svg-icon-3"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M18 10V20C18 20.6 18.4 21 19 21C19.6 21 20 20.6 20 20V10H18Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M11 10V17H6V10H4V20C4 20.6 4.4 21 5 21H12C12.6 21 13 20.6 13 20V10H11Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M10 10C10 11.1 9.1 12 8 12C6.9 12 6 11.1 6 10H10Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M18 10C18 11.1 17.1 12 16 12C14.9 12 14 11.1 14 10H18Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M14 4H10V10H14V4Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M17 4H20L22 10H18L17 4Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M7 4H4L2 10H6L7 4Z" fill="currentColor"/>
                                                <path d="M6 10C6 11.1 5.1 12 4 12C2.9 12 2 11.1 2 10H6ZM10 10C10 11.1 10.9 12 12 12C13.1 12 14 11.1 14 10H10ZM18 10C18 11.1 18.9 12 20 12C21.1 12 22 11.1 22 10H18ZM19 2H5C4.4 2 4 2.4 4 3V4H20V3C20 2.4 19.6 2 19 2ZM12 17C12 16.4 11.6 16 11 16H6C5.4 16 5 16.4 5 17C5 17.6 5.4 18 6 18H11C11.6 18 12 17.6 12 17Z" fill="currentColor"/>
                                                </svg>
                                            </span>
											<!--end::Svg Icon-->
										</a>
										<!--end::Nav link-->
									</li>
									<!--end::Nav item-->
                                     @endif
                                       <!--begin::Nav item-->
                                    {{-- <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="POS">
                                        <!-- begin::Nav link -->
                                        <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('pos_icon')" href="{{ route('pos.selectPos')}}">
                                            <!-- begin::Fontawesome Icon -->
                                            <i class="fa-solid fa-cash-register fs-6"></i>
                                            <!-- end::Fontawesome Icon -->
                                        </a>
                                        <!-- end::Nav link -->
                                    </li> --}}
                                    <!--end::Nav item-->
                                    {{-- <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Inventory">
                                        <!-- begin::Nav link -->
                                        <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('inventory_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_inventory">
                                            <!-- begin::Fontawesome Icon -->
                                            <i class="fa-solid fa-warehouse fs-7"></i>
                                            <!-- end::Fontawesome Icon -->
                                        </a>
                                        <!-- end::Nav link -->
                                    </li> --}}
                                    @if(hasAll('pos'))
                                        <!--begin::Nav item-->
                                        <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="POS">
                                            <!-- begin::Nav link -->
                                            <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('pos_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_pos">
                                                <!-- begin::Fontawesome Icon -->
                                                <i class="fa-solid fa-cash-register fs-6"></i>
                                                <!-- end::Fontawesome Icon -->
                                            </a>
                                            <!-- end::Nav link -->
                                        </li>
                                        <!--end::Nav item-->
                                    @endif
                                    @if(multiHasAll(['opening stock', 'stockin', 'stockout', 'stock transfer', 'stock adjustment']))
                                        <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Inventory">
                                            <!-- begin::Nav link -->
                                            <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('inventory_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_inventory">
                                                <!-- begin::Fontawesome Icon -->
                                                <i class="fa-solid fa-warehouse fs-7"></i>
                                                <!-- end::Fontawesome Icon -->
                                            </a>
                                            <!-- end::Nav link -->
                                        </li>
                                    @endif
                                    {{-- @if(hasAll('stockin') || hasAll('stockout'))
                                    <!--begin::Nav item-->
                                    <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Stocks In / Out">
                                        <!--begin::Nav link-->
                                        <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('stock_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_stocks">
                                            <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-03-24-172858/core/html/src/media/icons/duotune/abstract/abs027.svg-->
                                            <span class="svg-icon svg-icon-gray-500 svg-icon-3 fs-4">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="currentColor"/>
                                                <path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </a>
                                        <!--end::Nav link-->
                                    </li>
                                    <!--end::Nav item-->
                                    @endif --}}
                                    @if(hasAll('opening stock'))
                                    <!--begin::Nav item-->
									{{-- <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Import Opening Stock">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('opening_stock_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_opening_stocks">
											<!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
											<span class="menu-icon">
                                                <i class="fa-solid fa-download fs-6"></i>
                                            </span>
											<!--end::Svg Icon-->
										</a>
										<!--end::Nav link-->
									</li> --}}
									<!--end::Nav item-->
                                    @endif
                                    @if(hasAll('stock transfer'))
									<!--begin::Nav item-->
									{{-- <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Stocks Transfer">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('stock_transfer_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_stocks_transfer">
											<!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
											<i class="fa-solid fa-truck fs-6"></i>
											<!--end::Svg Icon-->
										</a>
										<!--end::Nav link-->
									</li> --}}
									<!--end::Nav item-->
                                    @endif
                                        <!--begin::Nav item-->
                                        <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Reports">
                                            <!--begin::Nav link-->
                                            <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-400 btn-active-light @yield('reports_active')" data-bs-toggle="tab" href="#kt_aside_nav_tab_reports">
                                                <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-03-24-172858/core/html/src/media/icons/duotune/graphs/gra001.svg-->
                                                <span class="svg-icon svg-icon-gray-100 svg-icon-4">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M14 3V21H10V3C10 2.4 10.4 2 11 2H13C13.6 2 14 2.4 14 3ZM7 14H5C4.4 14 4 14.4 4 15V21H8V15C8 14.4 7.6 14 7 14Z" fill="currentColor"/>
                                                <path d="M21 20H20V8C20 7.4 19.6 7 19 7H17C16.4 7 16 7.4 16 8V20H3C2.4 20 2 20.4 2 21C2 21.6 2.4 22 3 22H21C21.6 22 22 21.6 22 21C22 20.4 21.6 20 21 20Z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                                <!--end::Svg Icon-->
                                            </a>
                                            <!--end::Nav link-->
                                        </li>
                                        <!--end::Nav item-->
                                        <!--begin::Nav item-->
									<li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Cash & Payment">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('fa_active')" data-bs-toggle="tab" href="#kt_aside_nav_tab_fa">
											<!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
											<i class="fa-solid fa-money-check fs-6"></i>
											<!--end::Svg Icon-->
										</a>
										<!--end::Nav link-->
									</li>
                                    <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="{{__('expense.expense')}}">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('expense_active')" data-bs-toggle="tab" href="#kt_aside_nav_tab_expense">
											<!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
											<i class="fa-solid fa-hand-holding-dollar fs-6"></i>
											<!--end::Svg Icon-->
										</a>
										<!--end::Nav link-->
									</li>
									<!--end::Nav item-->
                                    <!--begin::Nav item-->
									<li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="SMS">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('sms_active')" data-bs-toggle="tab" href="#kt_aside_nav_tab_sms">
											<!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
											<i class="fa-solid fa-sms fs-6"></i>
											<!--end::Svg Icon-->
										</a>
										<!--end::Nav link-->
									</li>
									<!--end::Nav item-->
									<!--begin::Nav item-->
									<li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Settings">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('setting_active')" data-bs-toggle="tab" href="#kt_aside_nav_tab_settings">
											<!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
											<i class="fa-solid fa-gears fs-6"></i>
											<!--end::Svg Icon-->
										</a>
										<!--end::Nav link-->
									</li>
									<!--end::Nav item-->
                                    <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Modules">
										<!--begin::Nav link-->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('module_active')" data-bs-toggle="tab" href="#kt_aside_nav_tab_modules">
											<!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
                                            <i class="ki-solid ki-data fs-2"></i>
											<!--end::Svg Icon-->
										</a>
										<!--end::Nav link-->
									</li>
									<!--end:-->

                                    <!--begin::Tab pane-->

                                    @if(hasModule('Service') && isEnableModule('Service'))
                                        <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Service">
                                            <!-- begin::Nav link -->
                                            <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('service_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_service">
                                                <!-- begin::Fontawesome Icon -->
                                                <i class="fa-solid fa-hand-holding-hand fs-6"></i>
                                                <!-- end::Fontawesome Icon -->
                                            </a>
                                            <!-- end::Nav link -->
                                        </li>
                                    @endif
                                    @if(hasModule('RoomManagement') && isEnableModule('RoomManagement'))
                                        <!-- begin::Nav item -->
                                        <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Room Management">
                                            <!-- begin::Nav link -->
                                            <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('room_management_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_room_management">
                                                <!-- begin::Fontawesome Icon -->
                                                <i class="fa-solid fa-door-closed fs-6"></i>
                                                <!-- end::Fontawesome Icon -->
                                            </a>
                                            <!-- end::Nav link -->
                                        </li>
                                    @endif
									<!-- end::Nav item -->
                                    <!-- begin::Nav item -->
                                    @if (hasModule('restaurant') && isEnableModule('restaurant'))
                                        <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Restaurant Management">
                                            <!-- begin::Nav link -->
                                            <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('res_icon')" data-bs-toggle="tab" href="#res_management">
                                                <!-- begin::Fontawesome Icon -->
                                                <i class="fa-solid fa-utensils fs-6"></i>
                                                <!-- end::Fontawesome Icon -->
                                            </a>
                                            <!-- end::Nav link -->
                                        </li>
                                    @endif

									<!-- end::Nav item -->

                                    @if(hasModule('Reservation') && isEnableModule('Reservation'))
                                        <!-- begin::Nav item -->
                                        <li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Reservation">
                                            <!-- begin::Nav link -->
                                            <a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('reservation_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_reservation">
                                                <!-- begin::Fontawesome Icon -->
                                                <i class="fa-solid fa-calendar-check fs-6"></i>
                                                <!-- end::Fontawesome Icon -->
                                            </a>
                                            <!-- end::Nav link -->
                                        </li>
                                    @endif
									<!-- end::Nav item -->
                                    <!-- begin::Nav item -->

                                    @if (hasModule('HospitalManagement') && isEnableModule('HospitalManagement'))
									<li class="nav-item mb-2" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="right" data-bs-dismiss="click" title="Hospital Registration">
										<!-- begin::Nav link -->
										<a class="nav-link btn btn-icon btn-active-color-primary btn-color-gray-500 btn-active-light @yield('registration_icon')" data-bs-toggle="tab" href="#kt_aside_nav_tab_hospital_registration">
											<!-- begin::Fontawesome Icon -->
											<i class="fa-solid fa-hospital-user fs-6"></i>
											<!-- end::Fontawesome Icon -->
										</a>
										<!-- end::Nav link -->
									</li>
                                    @endif
									<!-- end::Nav item -->
								</ul>
								<!--end::Tabs-->
							</div>
							<!--end::Nav-->
						</div>
						<!--end::Nav-->
                        <!--begin::Footer-->

                        {{--new profile  --}}
                        {{-- <div class="aside-footer d-flex flex-column align-items-center flex-column-auto" id="kt_aside_footer">
							<!--begin::User-->
                            @php
                                $personalInfo = \App\Models\PersonalInfo::find(\Illuminate\Support\Facades\Auth::user()->personal_info_id);
                                $roleName = \App\Models\Role::find(\Illuminate\Support\Facades\Auth::user()->role_id)->name;
                            @endphp
							<div class="d-flex align-items-center mb-10" id="kt_header_user_menu_toggle">
								<!--begin::Menu wrapper-->
								<div class="cursor-pointer symbol symbol-40px" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="top-start" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-dismiss="click" title="User profile">
                                    @if($personalInfo)
                                        @if($personalInfo->profile_photo == null)
                                            <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                {{ substr($personalInfo->first_name, 0, 1) }}
                                            </div>
                                        @else
                                            <img alt="Profile Picture" src="{{ $personalInfo->profile_photo }}" />
                                        @endif
                                    @endif
								</div>
								<!--begin::User account menu-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content d-flex align-items-center px-3">
											<!--begin::Avatar-->
											<div class="symbol symbol-50px me-5">
                                                    @if($personalInfo)
                                                        @if($personalInfo->profile_photo == null)
                                                            <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                                {{ substr($personalInfo->first_name, 0, 1) }}
                                                            </div>
                                                        @else
                                                            <img alt="Profile Picture" src="{{ $personalInfo->profile_photo }}" />
                                                        @endif
                                                    @endif
											</div>
											<!--end::Avatar-->
											<!--begin::Username-->
											<div class="d-flex flex-column">
												<div class="fw-bold d-flex align-items-center fs-5">
                                                    @if($personalInfo)
                                                        {{ $personalInfo->initname }}
                                                        {{ $personalInfo->first_name }}
                                                        {{ $personalInfo->last_name }}
                                                    @endif
                                                </div>
												<a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{$roleName}}</a>
											</div>
											<!--end::Username-->
										</div>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator my-2"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-5">
										<a href="{{route('profile.index')}}" class="menu-link px-5">My Profile</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator my-2"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-5">
										<a class="menu-link px-5" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Sign Out</a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::User account menu-->
								<!--end::Menu wrapper-->
							</div>
							<!--end::User-->
						</div> --}}


                        <!--end::Footer-->
					</div>
					<!--end::Primary-->
					<!--begin::Secondary-->
					<div class="aside-secondary d-flex flex-row-fluid">
						<!--begin::Workspace-->
						<div class="aside-workspace my-2 py-1" id="aside-secondary ">
							<div class="d-flex h-100 flex-column">
								<!--begin::Wrapper-->
								<div class="flex-column-fluid hover-scroll-y" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_aside_wordspace" data-kt-scroll-dependencies="#kt_aside_secondary_footer" data-kt-scroll-offset="0px">
									<!--begin::Tab content-->
									<div class="tab-content">

                                        <!--begin::Tab pane-->
                                        <div class="tab-pane fade @yield('dashboard_show')" id="kt_aside_nav_tab_dashboard" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
                                                <div id="kt_aside_menu_wrapper" class="menu-fit">
                                                    <div class="menu-item pt-2">
                                                        <!--begin:Menu content-->
                                                        <div class="menu-content">
                                                            <span class="menu-heading fw-bold text-uppercase fs-7">Home </span>
                                                        </div>
                                                        <!--end:Menu content-->
                                                    </div>
                                                    <!--begin:Menu item-->
                                                    <div data-kt-menu-trigger="click" class="menu-item ">
                                                        <div class="menu-item">
                                                            <a class="menu-link @yield('dashboard_active_show') px-0" href="{{route('home')}}">
                                                                <span class="menu-icon">
                                                                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-06-05-142852/core/html/src/media/icons/duotune/general/gen008.svg-->
                                                                    <span class="svg-icon svg-icon-muted svg-icon-3"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M3 2H10C10.6 2 11 2.4 11 3V10C11 10.6 10.6 11 10 11H3C2.4 11 2 10.6 2 10V3C2 2.4 2.4 2 3 2Z" fill="currentColor"/>
                                                                    <path opacity="0.3" d="M14 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H14C13.4 11 13 10.6 13 10V3C13 2.4 13.4 2 14 2Z" fill="currentColor"/>
                                                                    <path opacity="0.3" d="M3 13H10C10.6 13 11 13.4 11 14V21C11 21.6 10.6 22 10 22H3C2.4 22 2 21.6 2 21V14C2 13.4 2.4 13 3 13Z" fill="currentColor"/>
                                                                    <path opacity="0.3" d="M14 13H21C21.6 13 22 13.4 22 14V21C22 21.6 21.6 22 21 22H14C13.4 22 13 21.6 13 21V14C13 13.4 13.4 13 14 13Z" fill="currentColor"/>
                                                                    </svg>
                                                                    </span>
                                                                    <!--end::Svg Icon-->
                                                                </span>
                                                                <span class="menu-title fs-6">{{__('dashboard.dashboard')}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <!--end:Menu item-->
                                                </div>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Tab pane-->



										<!--begin::Tab pane-->
										<div class="tab-pane fade @yield('user_active_show')" id="kt_aside_nav_tab_users" role="tabpanel">
											<!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">{{__('user.user_managements')}}</span>
														</div>
														<!--end:Menu content-->
													</div>
                                                    @if(hasAll('user'))
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('user_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<span class="svg-icon svg-icon-2">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z" fill="currentColor" />
																		<rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="currentColor" />
																	</svg>
																</span>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title fw-semibold">{{__('user.users')}}</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
														<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
                                                            @if(hasView('user'))
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('user_list_active_show')" href="{{route('users.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fw-semibold">{{__('user.users_list')}}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
                                                            @endif
                                                            @if(hasCreate('user'))
                                                                <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link  @yield('user_add_active_show')" href="{{route('users.create')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title">{{__('user.create_user')}}</span>
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
                                                    @if(hasAll('role'))
                                                    <!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('role_active_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<i class="bi bi-bar-chart-steps"></i>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title">Roles </span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
															<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
                                                            @if(hasView('role'))
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('role_list_active_show')" href="{{route('roles.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title">Roles List</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
                                                            @endif
                                                            @if(hasCreate('role'))
                                                                <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link  @yield('role_add_active_show')" href="{{route('roles.create')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title">Add Role</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
															<!--end:Menu item-->
                                                            @endif
														</div>
														<!--end:Menu sub-->
														<!--end:Menu sub-->
													</div>
													<!--end:Menu item-->
                                                    @endif
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab pane-->

										@if(hasAll('supplier') || hasAll('customer'))
                                        <div class="tab-pane fade  @yield('contact_active_show')" id="kt_aside_nav_tab_contact" role="tabpanel">
											<!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 px-4 pe-8 my-2 my-lg-0 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Contacts</span>
														</div>
														<!--end:Menu content-->
													</div>

                                                    @if(hasAll('supplier'))
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('supplier_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<span class="svg-icon svg-icon-4">
                                                                    <i class="fa-solid fa-people-carry-box"></i>
																</span>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title fs-6">Suppliers</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
														<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
															@if(hasView('supplier'))
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('supplier_list_active_show')" href="{{route('suppliers.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Suppliers List</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
																@endif
																@if(hasCreate('supplier'))
                                                                <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link  @yield('supplier_add_active_show')"  href="{{route('suppliers.create')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Add Supplier</span>
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
                                                    @if(hasAll('customer'))
                                                    <!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('customer_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<i class="fa-solid fa-user-group"></i>
															</span>
															<span class="menu-title fs-6">Customers</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
															<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
                                                            @if(hasView('customer'))
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('customer_list_active_show')" href="{{route('customers.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Customers List</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
															@endif
                                                            @if(hasCreate('customer'))
                                                                <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link  @yield('customer_add_active_show')" href="{{route('customers.create')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Add Customer</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
															@endif
															<!--end:Menu item-->
														</div>
														<!--end:Menu sub-->
														<!--end:Menu sub-->
													</div>
													<!--end:Menu item-->
													@endif
                                                    @if(hasView('customer'))
                                                    <!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('customer_group_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<i class="fa-solid fa-users"></i>
															</span>
															<span class="menu-title fs-6">Customer Groups</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
															<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('customer_group_list_active_show')" href="{{route('customer-group.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Customer groups List</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
															<!--end:Menu item-->

														</div>
														<!--end:Menu sub-->
														<!--end:Menu sub-->
													</div>
													<!--end:Menu item-->
													@endif
                                                    <div class="menu-item d-none">
                                                        <a class="menu-link @yield('shipments_active_show') px-0" href="{{route('deliveryChannel.list')}}">
                                                            <span class="menu-icon">
                                                                <i class="fa-solid fa-truck-fast"></i>
                                                            </span>
                                                            <span class="menu-title">Delivery Chanels</span>
                                                        </a>
                                                    </div>
                                                    @if(hasImport('supplier') && hasImport('customer'))
                                                    <div class="menu-item ">
														<!--begin:Menu link-->
														<a class="menu-link @yield('import_contacts_active')" href="{{route('import-contacts.index')}}">
															<span class="menu-icon">
                                                                <i class="fa-solid fa-download"></i>
															</span>
															<span class="menu-title fs-6">Import Contacts</span>
														</a>
													</div>
                                                        @endif
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
                                        @endif
                                        @if(multiHasAll(['product', 'variation', 'selling price groups', 'unit', 'uom', 'category', 'brand', 'warranty', 'manufacture', 'generic', 'opening stock']))
										<!--begin::Tab pane-->
										<div class="tab-pane fade @yield('products_show')" id="kt_aside_nav_tab_products" role="tabpanel">
											<!--begin::Menu-->
											<!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-1">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Products</span>
														</div>
														<!--end:Menu content-->
													</div>
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion here show">
														<!--begin:Menu link-->
														{{-- <span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<i class="fa-solid fa-cubes-stacked fs-2"></i>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title">Products</span>
															<span class="menu-arrow"></span>
														</span> --}}
														<!--end:Menu link-->
														<!--begin:Menu sub-->
															<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
                                                            @if(hasView('product'))
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('products_menu_link')" href="{{ route('products') }}">

																		<span class="menu-icon">
																			<i class="fa-solid fa-list"></i>
																		</span>
                                                                        <span class="menu-title fs-6">{{ __('product/product.list_products') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
                                                            @endif
                                                            @if(hasCreate('product'))
                                                                <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('add_product_menu_link')" href="{{ route('product.add') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-circle-plus"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/product.add_product') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasPrint('product'))
                                                                 <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('print_labels_menu_link')" href="{{ url('/printLabel') }}">
                                                                        <span class="menu-icon">
																			<i class="fa-solid fa-barcode"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/product.print_label') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasView('variation'))
                                                                 <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('variations_menu_link')" href="{{ url('/variation') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-circle"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/variation.variation') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasImport('product'))
                                                                <!--end:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                   <a class="menu-link @yield('import_products_menu_link')" href="{{ route('import-product') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-download"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/import-product.import_product') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasImport('opening stock'))
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('import_opening_stock_menu_link')" href="{{ url('/import-opening-stock') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-download"></i>
                                                                        </span>
                                                                        <span class="menu-title  fs-6">{{ __('product/product.import_opening_stock') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            {{-- @if(hasView('selling price groups') || hasImport('selling price groups') || hasExport('selling price groups'))
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('selling_price_group_menu_link')" href="{{ route('price-group') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-circle"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Selling Price Group</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif --}}
                                                            @if(hasView('unit') || hasView('uom'))
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('units_menu_link')" href="{{ route('unit-category') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-balance-scale"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/unit-and-uom.unit') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasView('category'))
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('categories_menu_link')" href="{{ route('categories') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-tags"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/category.category') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasView('brand'))
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('brands_menu_link')" href="{{ route('brands') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-gem"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/product.brand') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasView('warranty'))
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('warranties_menu_link')" href="{{ url('/warranites') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-shield-alt"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/warranties.warranties') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasView('manufacture'))
																<div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('manufacturer_menu_link')" href="{{ route('manufacturer') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-wrench"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/manufacturer.manufacturer') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasView('generic'))
																<div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('generic_menu_link')" href="{{ url('/generic') }}">
                                                                        <span class="menu-icon">
                                                                            <i class="fa-solid fa-tags"></i>
                                                                        </span>
                                                                        <span class="menu-title fs-6">{{ __('product/generic.generic') }}</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif

															<div class="menu-item">
																<!--begin:Menu link-->
																<a class="menu-link @yield('price_list_detail_menu_link')" href="{{ route('price-list-detail') }}">
																	<span class="menu-icon">
																		<i class="fa-solid fa-circle"></i>
																	</span>
																	<span class="menu-title fs-6">{{ __('product/pricelist.pricelist') }}</span>
																</a>
																<!--end:Menu link-->
															</div>
														</div>
														<!--end:Menu sub-->
														<!--end:Menu sub-->
													</div>
													<!--end:Menu item-->
												</div>
											</div>
											<!--end::Wrapper-->
											<!--end::Menu-->
										</div>
										<!--end::Tab pane-->
                                        @endif
                                        @if(hasAll('purchase'))
                                        <!--begin::Tab pane-->
										<div class="tab-pane fade @yield('pruchases_show')" id="kt_aside_nav_tab_stocks_purchase" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Purchases</span>
														</div>
														<!--end:Menu content-->
													</div>
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item ">
                                                        {{-- <div class="menu-item">
                                                            <a class="menu-link @yield('purchases_order_active_show') px-0" href="{{ route('purchase_order') }}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-list"></i>
                                                                </span>
                                                                <span class="menu-title">Purchase Order</span>
                                                            </a>
                                                        </div> --}}
                                                        @if(hasView('purchase'))
                                                        <div class="menu-item">
                                                            <a class="menu-link @yield('purchases_list_active_show') px-0" href="{{route('purchase_list')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-list-ul"></i>
                                                                </span>
                                                                <span class="menu-title fs-6">List Purchase</span>
                                                            </a>
                                                        </div>
                                                        @endif
                                                        @if(hasCreate('purchase'))
                                                        <div class="menu-item">
                                                            <a class="menu-link @yield('purchases_add_active_show') px-0" href="{{route('purchase_add')}}">
                                                                <span class="menu-icon">
                                                                     <i class="fa-solid fa-cart-plus"></i>
                                                                </span>
                                                                <span class="menu-title px-0">Add Purchase</span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item d-none">
                                                            <a class="menu-link @yield('purchases_new_add_active_show') px-0" href="{{route('purchase_new_add')}}">
                                                                <span class="menu-icon">
                                                                     <i class="fa-solid fa-cart-plus"></i>
                                                                </span>
                                                                <span class="menu-title px-0">Add New Purchase</span>
                                                            </a>
                                                        </div>
                                                        @endif
                                                        @yield('edit_purchase')
                                                        {{-- <div class="menu-item">
                                                            <a class="menu-link @yield('purchases_list_return_active_show') px-0" href="{{route('purchase_list_return')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-rotate-left"></i>
                                                                </span>
                                                                <span class="menu-title">List Purchase Return</span>
                                                            </a>
                                                        </div> --}}
													</div>
													<!--end:Menu item-->
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab pane-->
                                        @endif
                                        @if(hasAll('sell'))
                                         <!--begin::Tab pane-->
										<div class="tab-pane fade @yield('sell_show')" id="kt_aside_nav_tab_stocks_sell" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2 ">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Sales Order</span>
														</div>
														<!--end:Menu content-->
													</div>
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item  ">
                                                        <div class="menu-item  d-none">
                                                            <a class="menu-link @yield('sell_order_active_show') px-0" href="{{ route('sale_order') }}">
                                                                <span class="menu-icon">
                                                                    <span class="svg-icon svg-icon-gray-500 svg-icon-1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path opacity="0.3" d="M11 11H13C13.6 11 14 11.4 14 12V21H10V12C10 11.4 10.4 11 11 11ZM16 3V21H20V3C20 2.4 19.6 2 19 2H17C16.4 2 16 2.4 16 3Z" fill="currentColor"/>
                                                                        <path d="M21 20H8V16C8 15.4 7.6 15 7 15H5C4.4 15 4 15.4 4 16V20H3C2.4 20 2 20.4 2 21C2 21.6 2.4 22 3 22H21C21.6 22 22 21.6 22 21C22 20.4 21.6 20 21 20Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </span>
                                                                <span class="menu-title">Sales Order</span>
                                                            </a>
                                                        </div>

                                                        <div class="menu-item d-none ">
                                                            <a class="menu-link @yield('add_sales_order_active_show') px-0" href="{{route('add_sale_order')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-square-plus fs-3"></i>
                                                                </span>
                                                                <span class="menu-title">Add Sale Order</span>
                                                            </a>
                                                        </div>

                                                        @if(hasCreate('sell'))
                                                        <div class="menu-item">
                                                            <a class="menu-link @yield('add_sales_active_show') px-0 fs-6" href="{{route('add_sale')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-square-plus fs-3"></i>
                                                                </span>
                                                                <span class="menu-title">Add Sale</span>
                                                            </a>
                                                        </div>
                                                        @endif
                                                        @if(hasView('sell'))
                                                            <div class="menu-item">
                                                                <a class="menu-link @yield('allSales_active_show') px-0 fs-6" href="{{route('all_sales','allSales')}}">
                                                                    <span class="menu-icon">
                                                                        <i class="fa-solid fa-list-ul"></i>
                                                                    </span>
                                                                    <span class="menu-title">All Sale</span>
                                                                </a>
                                                            </div>
                                                            <div class="menu-item">
                                                                <a class="menu-link @yield('sales_active_show') px-0 fs-6" href="{{route('all_sales','sales')}}">
                                                                    <span class="menu-icon">
                                                                        <i class="fa-solid fa-list-ul"></i>
                                                                    </span>
                                                                    <span class="menu-title">Sale List</span>
                                                                </a>
                                                            </div>
                                                            <div class="menu-item">
                                                                <a class="menu-link @yield('posSales_active_show') px-0 fs-6" href="{{route('all_sales','posSales')}}">
                                                                    <span class="menu-icon">
                                                                        <i class="fa-solid fa-list-ul"></i>
                                                                    </span>
                                                                    <span class="menu-title">POS Sale List</span>
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div class="menu-item d-none">
                                                            <a class="menu-link @yield('list_pos_active_show') px-0" href="{{route('list_pos')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-list-ul"></i>
                                                                </span>
                                                                <span class="menu-title">List POS</span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item d-none">
                                                            <a class="menu-link @yield('list_drafts_active_show') px-0" href="{{route('list_drafts')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-list-ul"></i>
                                                                </span>
                                                                <span class="menu-title">List Drafts</span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item d-none">
                                                            <a class="menu-link @yield('add_drafts_active_show') px-0" href="{{route('add_draft')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-square-plus fs-3"></i>
                                                                </span>
                                                                <span class="menu-title">Add Drafts</span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item d-none">
                                                            <a class="menu-link @yield('list_quotations_active_show') px-0" href="{{route('list_quotations')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-list-ul"></i>
                                                                </span>
                                                                <span class="menu-title">List Quotations</span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item d-none">
                                                            <a class="menu-link @yield('add_quotations_active_show') px-0" href="{{route('add_quotations')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-square-plus fs-3"></i>
                                                                </span>
                                                                <span class="menu-title">Add Quotations</span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item d-none">
                                                            <a class="menu-link @yield('shipments_active_show') px-0" href="{{route('shipments')}}">
                                                                <span class="menu-icon">
                                                                   <i class="fa-solid fa-truck-fast"></i>
                                                                </span>
                                                                <span class="menu-title">Shipments</span>
                                                            </a>
                                                        </div>
													</div>
													<!--end:Menu item-->
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab pane-->
										@endif

                                        <div class="tab-pane fade  @yield('pos_bar_show')" id="kt_aside_nav_tab_pos" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
                                                <div id="kt_aside_menu_wrapper" class="menu-fit">
                                                    <div class="menu-item pt-2">
                                                        <!--begin:Menu content-->
                                                        <div class="menu-content">
                                                            <span class="menu-heading fw-bold text-uppercase fs-8">Point Of Sale</span>
                                                        </div>
                                                        <!--end:Menu content-->
                                                    </div>
                                                </div>
                                                <!--begin:Menu item-->
                                                <div  class="menu-item menu-accordion ">
                                                    <!--begin:Menu link-->
                                                    <!--begin:Menu link-->
                                                    <a class="menu-link @yield('pos_active_show')" href="{{route('pos.selectPos')}}">
                                                        <span class=" menu-icon">
                                                            <i class="fa-solid fa-cash-register fs-6"></i>
                                                        </span>
                                                        <span class="menu-title ">POS</span>
                                                    </a>
                                                </div>
                                                <div  class="menu-item menu-accordion ">
                                                        <a class="menu-link @yield('pos_register_list_active_show')" href="{{route('posList')}}">
                                                            <span class="menu-icon">
                                                                <i class="fa-solid fa-list fs-6"></i>
                                                            </span>
                                                            <span class="menu-title">POS Register List</span>
                                                        </a>
                                                </div>

                                                @if (hasModule('OrderDisplay') && isEnableModule('OrderDisplay'))
                                                    {{-- <div  class="menu-item menu-accordion ">
                                                        <a class="menu-link @yield('')" href="{{route('pos.selectPos')}}">
                                                            <span class=" menu-icon">
                                                                <i class="fa-solid fa-display fs-6"></i>
                                                            </span>
                                                            <span class="menu-title ">Order Display</span>
                                                        </a>
                                                    </div> --}}
                                                    @if (Route::has('odList'))
                                                        <div  class="menu-item menu-accordion ">
                                                            <a class="menu-link @yield('order_display_list_active_show')" href="{{route('odList')}}">
                                                                <span class="menu-icon">
                                                                    <i class="fa-solid fa-list fs-6"></i>
                                                                </span>
                                                                <span class="menu-title">Order Display List</span>
                                                            </a>
                                                    </div>
                                                    @endif
                                                @endif
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        @if(multiHasAll(['opening stock', 'stockin', 'stockout', 'stock transfer', 'stock adjustment']))
                                        <!--begin::Tab pane-->
                                        <div class="tab-pane fade @yield('inventory_show')" id="kt_aside_nav_tab_inventory" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
                                                <div id="kt_aside_menu_wrapper" class="menu-fit">
                                                    <div class="menu-item pt-2">
                                                        <!--begin:Menu content-->
                                                        <div class="menu-content">
                                                            <span class="menu-heading fw-bold text-uppercase fs-7">Inventory</span>
                                                        </div>
                                                        <!--end:Menu content-->
                                                    </div>
                                                    @if(hasAll('opening stock'))
                                                    <!--begin:Menu item-->
                                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('opening_stock_here_show')">
                                                        <!--begin:Menu link-->
                                                        <span class="menu-link">
                                                            <span class="menu-icon">
                                                                <i class="fa-solid fa-download fs-6"></i>
                                                            </span>
                                                            <span class="menu-title"> Opening Stock</span>
                                                            <span class="menu-arrow"></span>
                                                        </span>
                                                        <!--end:Menu link-->
                                                        <!--begin:Menu sub-->
                                                        <!--begin:Menu sub-->
                                                        <div class="menu-sub menu-sub-accordion">
                                                            @if(hasView('opening stock'))
                                                            <!--begin:Menu link-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('opening_stock_active_show')" href="{{ route('opening_stock_list') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                        <span class="menu-title">List Opening Stocks</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasCreate('opening stock'))
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('add_opening_stock_active_show')" href="{{ route('add_opening_stock') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                        <span class="menu-title">Add Opening Stock</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            @if(hasImport('opening stock'))
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('import_opening_stock_menu_link') " href="{{ route('import_opening_stock') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                        <span class="menu-title">Import Opening Stock</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                            @endif
                                                            <!--end:Menu sub-->
                                                        </div>
                                                        <!--end:Menu sub-->
                                                        <!--end:Menu sub-->
                                                    </div>
                                                    <!--end:Menu item-->
                                                    @endif
                                                    @if(hasModule('StockInOut') && isEnableModule('StockInOut'))
                                                        @include('stockinout::layouts.master', ['navbarType' => 'main_link'])
                                                    @endif
                                                    @if(hasAll('stock transfer'))
                                                        <!--begin:Menu item-->
                                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('stock_transfer_here_show')">
                                                            <!--begin:Menu link-->
                                                            <span class="menu-link">
                                                                <span class="menu-icon">
                                                                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-07-10-101904/core/html/src/media/icons/duotune/ecommerce/ecm006.svg-->
                                                                    <span class="svg-icon svg-icon-muted svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z" fill="currentColor"/>
                                                                        <path opacity="0.3" d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z" fill="currentColor"/>
                                                                        </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                </span>
                                                                <span class="menu-title">{{__('transfer.transfer')}}</span>
                                                                <span class="menu-arrow"></span>
                                                            </span>
                                                            <!--end:Menu link-->
                                                            <!--begin:Menu sub-->
                                                            <div class="menu-sub menu-sub-accordion">
                                                                @if(hasView('stock transfer'))
                                                                <!--begin:Menu link-->
                                                                    <div class="menu-item">
                                                                        <!--begin:Menu link-->
                                                                        <a class="menu-link @yield('stock_transfer_active_show')" href="{{ route('stock-transfer.index') }}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                                                                            <span class="menu-title">{{__('transfer.transfer_list')}}</span>
                                                                        </a>
                                                                        <!--end:Menu link-->
                                                                    </div>
                                                                <!--end:Menu link-->
                                                                @endif
                                                                @if(hasCreate('stock transfer'))
                                                                <!--begin:Menu link-->
                                                                    <div class="menu-item">
                                                                        <!--begin:Menu link-->
                                                                        <a class="menu-link @yield('stock_transfer_add_active_show')" href="{{ route('stock-transfer.create') }}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                                                                            <span class="menu-title">{{__('transfer.create')}}</span>
                                                                        </a>
                                                                        <!--end:Menu link-->
                                                                    </div>
                                                                <!--end:Menu sub-->
                                                                @endif
                                                            </div>
                                                            <!--end:Menu sub-->
                                                            <!--end:Menu sub-->
                                                        </div>
                                                        <!--end:Menu item-->
                                                        @endif
                                                        @if(hasAll('stock adjustment'))
                                                        <!--begin:Menu item-->
                                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('stock_adjustment_here_show')">
                                                            <!--begin:Menu link-->
                                                            <span class="menu-link">
                                                                <span class="menu-icon">
                                                                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-07-10-101904/core/html/src/media/icons/duotune/arrows/arr031.svg-->
                                                                <span class="svg-icon svg-icon-muted svg-icon-2"><svg width="23" height="24" viewBox="0 0 23 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M21 13V13.5C21 16 19 18 16.5 18H5.6V16H16.5C17.9 16 19 14.9 19 13.5V13C19 12.4 19.4 12 20 12C20.6 12 21 12.4 21 13ZM18.4 6H7.5C5 6 3 8 3 10.5V11C3 11.6 3.4 12 4 12C4.6 12 5 11.6 5 11V10.5C5 9.1 6.1 8 7.5 8H18.4V6Z" fill="currentColor"/>
                                                                    <path opacity="0.3" d="M21.7 6.29999C22.1 6.69999 22.1 7.30001 21.7 7.70001L18.4 11V3L21.7 6.29999ZM2.3 16.3C1.9 16.7 1.9 17.3 2.3 17.7L5.6 21V13L2.3 16.3Z" fill="currentColor"/>
                                                                    </svg>
                                                                    </span>
                                                                    <!--end::Svg Icon-->
                                                                </span>
                                                                <span class="menu-title">Stock Adjustment</span>
                                                                <span class="menu-arrow"></span>
                                                            </span>
                                                            <!--end:Menu link-->
                                                            <!--begin:Menu sub-->
                                                            <!--begin:Menu sub-->
                                                            <div class="menu-sub menu-sub-accordion">
                                                                @if(hasView('stock adjustment'))
                                                                <!--begin:Menu link-->
                                                                    <div class="menu-item">
                                                                        <!--begin:Menu link-->
                                                                        <a class="menu-link @yield('stock_adjustment_active_show')" href="{{ route('stock-adjustment.index') }}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                                                                            <span class="menu-title">Adjustment List</span>
                                                                        </a>
                                                                        <!--end:Menu link-->
                                                                    </div>
                                                                <!--end:Menu link-->
                                                                @endif
                                                                @if(hasCreate('stock adjustment'))
                                                                <!--begin:Menu link-->
                                                                    <div class="menu-item">
                                                                        <!--begin:Menu link-->
                                                                        <a class="menu-link @yield('stock_adjustment_add_active_show')" href="{{ route('stock-adjustment.create') }}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                                                                            <span class="menu-title">Add Adjustment</span>
                                                                        </a>
                                                                        <!--end:Menu link-->
                                                                    </div>
                                                                <!--end:Menu sub-->
                                                                @endif
                                                            </div>
                                                            <!--end:Menu sub-->
                                                            <!--end:Menu sub-->
                                                        </div>
                                                        <!--end:Menu item-->
                                                    @endif
                                                </div>
                                                <!--begin:Menu item-->
                                               @if(multiHasAll(['stockin', 'stockout', 'stock transfer', 'stock adjustment', 'opening stock']))
                                                    <div  class="menu-item menu-accordion ">
                                                        <!--begin:Menu link-->
                                                        <!--begin:Menu link-->
                                                        <a class="menu-link @yield('current_stock_reports_active_show')" href="{{route('report.currentstockbalance.index')}}">
                                                            <span class=" menu-icon">
                                                                <i class="fa-solid fa-arrow-trend-up fs-6"></i>
                                                            </span>
                                                            <span class="menu-title ">Current Stocks</span>
                                                        </a>
                                                    </div>
                                                    <div  class="menu-item menu-accordion ">
                                                        <!--begin:Menu link-->
                                                        <!--begin:Menu link-->
                                                        <a class="menu-link @yield('stock_history_active_show')" href="{{route('stockHistory_list')}}">
                                                            <span class=" menu-icon">
                                                                <i class="fa-solid fa-clock-rotate-left fs-6"></i>
                                                            </span>
                                                            <span class="menu-title ">Stocks History</span>
                                                        </a>
                                                    </div>
                                               @endif
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Tab pane-->
                                        @endif
                                        {{-- @if(hasAll('stock transfer'))
										<!--begin::Tab pane-->
										<div class="tab-pane fade @yield('stock_transfer_show')" id="kt_aside_nav_tab_stocks_transfer" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Stocks Transfer</span>
														</div>
														<!--end:Menu content-->
													</div>
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion here show">
                                                        @if(hasView('stock transfer'))
														<!--begin:Menu link-->
														    <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('stock_transfer_active_show')" href="{{ route('stock-transfer.index') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">List Stock Transfer</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                        <!--end:Menu link-->
                                                        @endif
                                                        @if(hasCreate('stock transfer'))
                                                        <!--begin:Menu link-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('stock_transfer_add_active_show')" href="{{ route('stock-transfer.create') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">Add Stock Transfer</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
														<!--end:Menu sub-->
                                                        @endif
													</div>
													<!--end:Menu item-->
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab pane-->
                                        @endif --}}
                                        <!--begin::Tab pane-->
                                        <div class="tab-pane fade @yield('reports_active_show')" id="kt_aside_nav_tab_reports" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
                                                <div id="kt_aside_menu_wrapper" class="menu-fit">
                                                    <div class="menu-item pt-2">
                                                        <!--begin:Menu content-->
                                                        <div class="menu-content">
                                                            <span class="menu-heading fw-bold text-uppercase fs-7">Reports</span>
                                                        </div>
                                                        <!--end:Menu content-->
                                                    </div>
                                                    @if(hasAll('sell'))
                                                    <!--begin:Menu item-->
                                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('sales_reports_here_show')">
                                                        <!--begin:Menu link-->
                                                        <span class="menu-link">
                                                            <span class="menu-title">Sales Reports</span>
                                                            <span class="menu-arrow"></span>
                                                        </span>
                                                        <!--end:Menu link-->
                                                        <!--begin:Menu sub-->
                                                        <!--begin:Menu sub-->
                                                        <div class="menu-sub menu-sub-accordion">
                                                            <!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('sales_summary_active_show')" href="{{route('report.sale.index')}}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">Sales Summary</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link  @yield('sales_details_active_show')" href="{{route('report.sale.details.index')}}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">Sales Details</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                            <!--begin:Menu item-->
{{--                                                            <div class="menu-item">--}}
{{--                                                                <!--begin:Menu link-->--}}
{{--                                                                <a class="menu-link  @yield('sales_by_category_active_show')" href="#">--}}
{{--                                                                    <span class="menu-bullet">--}}
{{--                                                                        <span class="bullet bullet-dot"></span>--}}
{{--                                                                    </span>--}}
{{--                                                                    <span class="menu-title">Sales by Category</span>--}}
{{--                                                                </a>--}}
{{--                                                                <!--end:Menu link-->--}}
{{--                                                            </div>--}}
{{--                                                            <!--end:Menu item-->--}}
{{--                                                            <!--begin:Menu item-->--}}
{{--                                                            <div class="menu-item">--}}
{{--                                                                <!--begin:Menu link-->--}}
{{--                                                                <a class="menu-link  @yield('sales_by_customer_active_show')" href="#">--}}
{{--                                                                    <span class="menu-bullet">--}}
{{--                                                                        <span class="bullet bullet-dot"></span>--}}
{{--                                                                    </span>--}}
{{--                                                                    <span class="menu-title">Sales by Customer</span>--}}
{{--                                                                </a>--}}
{{--                                                                <!--end:Menu link-->--}}
{{--                                                            </div>--}}
{{--                                                            <!--end:Menu item-->--}}
{{--                                                            <!--begin:Menu item-->--}}
{{--                                                            <div class="menu-item">--}}
{{--                                                                <!--begin:Menu link-->--}}
{{--                                                                <a class="menu-link  @yield('sales_by_employee_active_show')" href="#">--}}
{{--                                                                    <span class="menu-bullet">--}}
{{--                                                                        <span class="bullet bullet-dot"></span>--}}
{{--                                                                    </span>--}}
{{--                                                                    <span class="menu-title">Sales by Employee</span>--}}
{{--                                                                </a>--}}
{{--                                                                <!--end:Menu link-->--}}
{{--                                                            </div>--}}
{{--                                                            <!--end:Menu item-->--}}
{{--                                                            <!--begin:Menu item-->--}}
{{--                                                            <div class="menu-item">--}}
{{--                                                                <!--begin:Menu link-->--}}
{{--                                                                <a class="menu-link  @yield('top_selling_products_active_show')" href="#">--}}
{{--                                                                    <span class="menu-bullet">--}}
{{--                                                                        <span class="bullet bullet-dot"></span>--}}
{{--                                                                    </span>--}}
{{--                                                                    <span class="menu-title">Top Selling Prouducts</span>--}}
{{--                                                                </a>--}}
{{--                                                                <!--end:Menu link-->--}}
{{--                                                            </div>--}}
{{--                                                            <!--end:Menu item-->--}}


                                                        </div>
                                                        <!--end:Menu sub-->
                                                        <!--end:Menu sub-->
                                                    </div>
                                                    <!--end:Menu item-->
                                                    @endif
                                                    @if(hasAll('purchase'))
                                                    <!--begin:Menu item-->
                                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('purchase_reports_here_show')">

                                                        <!--begin:Menu link-->
                                                        <span class="menu-link">
                                                        <span class="menu-title">Purchase Reports</span>
                                                        <span class="menu-arrow"></span>
                                                    </span>
                                                        <!--end:Menu link-->
                                                        <!--begin:Menu sub-->
                                                        <!--begin:Menu sub-->
                                                        <div class="menu-sub menu-sub-accordion">
                                                            <!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('purchase_summary_active_show')" href="{{route('report.purchase.index')}}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">Purchase Summary</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link  @yield('purchased_details_active_show')" href="{{route('report.purchase.details.index')}}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">Purchase Details</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                        </div>
                                                        <!--end:Menu sub-->
                                                    </div>
                                                    <!--end:Menu item-->
                                                    @endif
                                                    @if(multiHasAll(['stockin', 'stockout','stock transfer']))
                                                    <!--begin:Menu item-->
                                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('inventory_reports_here_show')">
                                                        <!--begin:Menu link-->
                                                        <span class="menu-link">
															<span class="menu-title">Inventory Reports</span>
															<span class="menu-arrow"></span>
														</span>
                                                        <!--end:Menu link-->
                                                        <!--begin:Menu sub-->
                                                        <!--begin:Menu sub-->
                                                        <div class="menu-sub menu-sub-accordion">



                                                            @if (hasModule('StockInOut') && isEnableModule('StockInOut'))
                                                                @include('stockinout::layouts.master', ['navbarType' => 'report_link'])
                                                            @endif
                                                                @if(hasView('stock transfer') && hasExport('stock transfer'))
                                                                    <!--begin:Menu item-->
                                                                    <div class="menu-item">
                                                                        <!--begin:Menu link-->
                                                                        <a class="menu-link  @yield('stock_transfer_summary_active_show')" href="{{route('report.stocktransfer.index')}}">
                                                                                <span class="menu-bullet">
                                                                                    <span class="bullet bullet-dot"></span>
                                                                                </span>
                                                                            <span class="menu-title">Stock Transfer Summary</span>
                                                                        </a>
                                                                        <!--end:Menu link-->
                                                                    </div>
                                                                    <!--end:Menu item-->
                                                                    <!--begin:Menu item-->
                                                                    <div class="menu-item">
                                                                        <!--begin:Menu link-->
                                                                        <a class="menu-link  @yield('stock_by_products_active_show')" href="{{route('report.transfer.details.index')}}">
                                                                                <span class="menu-bullet">
                                                                                    <span class="bullet bullet-dot"></span>
                                                                                </span>
                                                                            <span class="menu-title">Stock Transfer Details</span>
                                                                        </a>
                                                                        <!--end:Menu link-->
                                                                    </div>
                                                                    <!--end:Menu item-->
                                                                @endif
                                                                @if(multiHasAll(['stockin', 'stockout', 'stock transfer', 'stock adjustment', 'opening stock']))
                                                                    <!--begin:Menu item-->
                                                                    <div class="menu-item">
                                                                        <!--begin:Menu link-->
                                                                        <a class="menu-link  @yield('stock_by_lot_active_show')" href="{{route('report.currentstockbalance.index')}}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                                                                            <span class="menu-title">Current Stock Balance</span>
                                                                        </a>
                                                                        <!--end:Menu link-->
                                                                    </div>
                                                                    <!--end:Menu item-->
                                                                @endif
                                                                <!--begin:Menu item-->
{{--                                                                <div class="menu-item">--}}
{{--                                                                    <!--begin:Menu link-->--}}
{{--                                                                    <a class="menu-link  @yield('stock_by_category_active_show')" href="#">--}}
{{--                                                                            <span class="menu-bullet">--}}
{{--                                                                                <span class="bullet bullet-dot"></span>--}}
{{--                                                                            </span>--}}
{{--                                                                        <span class="menu-title">Stock by Category</span>--}}
{{--                                                                    </a>--}}
{{--                                                                    <!--end:Menu link-->--}}
{{--                                                                </div>--}}
                                                                <!--end:Menu item-->
                                                                <!--begin:Menu item-->
{{--                                               d--}}
                                                                <!--end:Menu item-->
                                                                @if(multiHasAll(['stockin', 'stockout', 'stock transfer', 'stock adjustment', 'opening stock']))
                                                                <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link  @yield('stock_history_active_show')" href="{{route('stockHistory_list')}}">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                                                                        <span class="menu-title">Stock History</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
                                                                @endif
                                                            </div>

                                                        <!--end:Menu sub-->
                                                    </div>
                                                    <!--end:Menu item-->
                                                    @endif
                                                    <!--begin:Menu item-->
                                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('stock_alert_reports_here_show')">

                                                        <!--begin:Menu link-->
                                                        <span class="menu-link">
                                                        <span class="menu-title">Stock Alerts</span>
                                                        <span class="menu-arrow"></span>
                                                    </span>
                                                        <!--end:Menu link-->
                                                        <!--begin:Menu sub-->
                                                        <!--begin:Menu sub-->
                                                        <div class="menu-sub menu-sub-accordion">
                                                            <!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('quantity_alerts_active_show')" href="{{route('report.stockAlert.quantity')}}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">Quantity Alerts</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link  @yield('expire_alerts_active_show')" href="{{route('report.stockAlert.expire')}}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">Expire Alerts</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                        </div>
                                                        <!--end:Menu sub-->
                                                    </div>
                                                    <!--end:Menu item-->

                                                </div>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Tab pane-->

                                        <div class="tab-pane fade  @yield('module_show')" id="kt_aside_nav_tab_modules" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
                                                <div id="kt_aside_menu_wrapper" class="menu-fit">
                                                    <div class="menu-item pt-2">
                                                        <!--begin:Menu content-->
                                                        <div class="menu-content">
                                                            <span class="menu-heading fw-bold text-uppercase fs-8">Module</span>
                                                        </div>
                                                        <!--end:Menu content-->
                                                    </div>
                                                </div>

                                                <div  class="menu-item menu-accordion ">
                                                        <a class="menu-link @yield('modules_list_active')" href="{{route('module.index')}}">
                                                            <span class="menu-icon">
                                                                <i class="fa-solid fa-list fs-6"></i>
                                                            </span>
                                                            <span class="menu-title">Module</span>
                                                        </a>
                                                </div>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div><!--begin::Tab pane-->
                                        <div class="tab-pane fade @yield('sms_active_show')" id="kt_aside_nav_tab_sms" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0"
                                                id="kt_aside_menu" data-kt-menu="true">
                                                <div id="kt_aside_menu_wrapper" class="menu-fit">
                                                    <div class="menu-item pt-2">
                                                        <!--begin:Menu content-->
                                                        <div class="menu-content">
                                                            <span class="menu-heading fw-bold text-uppercase fs-7">SMS </span>
                                                        </div>
                                                        <!--end:Menu content-->
                                                    </div>
                                                    <!--begin:Menu item-->
                                                    <!-- <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('business_setting_here_show')">

                                                        {{-- <span class="menu-link">
                                                            <span class="menu-icon">
                                                                <i class="bi bi-gear fs-2"></i>
                                                            </span>
                                                            <span class="menu-title">SMS Configuration</span>
                                                            <span class="menu-arrow"></span>
                                                        </span> --}}
                                                        {{-- <div class="menu-sub menu-sub-accordion">
                                                            <!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('business_settings_nav')" href="{{route('business_settings')}}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title ">Business settings</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                        </div> --}}
                                                    </div> -->
                                                    {{-- <div data-kt-menu-trigger="click" class="menu-item menu-accordion  @yield('location_here_show')">
                                                        <!--begin:Menu link-->
                                                        <span class="menu-link">
                                                            <span class="menu-icon">
                                                                <!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
                                                                <i class="bi bi-geo-alt-fill fs-2"></i>
                                                                <!--end::Svg Icon-->
                                                            </span>
                                                            <span class="menu-title ">Business Location</span>
                                                            <span class="menu-arrow"></span>
                                                        </span>
                                                        <!--end:Menu link-->
                                                        <!--begin:Menu sub-->
                                                        <!--begin:Menu sub-->
                                                        <div class="menu-sub menu-sub-accordion">
                                                            <!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('location_list_nav')" href="{{route('business_location')}}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">Location List</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('location_add_nav')" href="{{route('location_add_form')}}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title">Add Location</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                            <!--end:Menu item-->

                                                        </div>
                                                    </div> --}}

                                                    <div class="menu-item menu-accordion ">
                                                        <!--begin:Menu link-->
                                                        <!--begin:Menu link-->
                                                        <a class="menu-link @yield('sms_active_show')" href="{{route('sms.create')}}">
                                                            <span class="menu-icon">
                                                                <i class="fa-regular fa-comment fs-6"></i>
                                                            </span>
                                                            <span class="menu-title">Send SMS</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item menu-accordion d-none">
                                                        <!--begin:Menu link-->
                                                        <!--begin:Menu link-->
                                                        <a class="menu-link @yield('printers_list_active_show')" href="{{route('printerList')}}">
                                                            <span class="menu-icon">
                                                                <i class="la la-gears fs-2"></i>
                                                            </span>
                                                            <span class="menu-title">SMS Configuration</span>
                                                        </a>
                                                    </div>
                                                    <!--end:Menu item-->
                                                    {{-- <div class="menu-item menu-accordion">
                                                        <!--begin:Menu link-->
                                                        <a class="menu-link @yield('building_active_show')" href="{{ route('building.index') }}">
                                                            <span class=" menu-icon">
                                                                <i class="fa-solid fa-hotel fs-6"></i>
                                                            </span>
                                                            <span class="menu-title">Building</span>
                                                        </a>
                                                        <!--end::Menu link-->
                                                    </div>
                                                    <div class="menu-item menu-accordion">
                                                        <!--begin:Menu link-->
                                                        <a class="menu-link @yield('floor_active_show')" href="{{route('floor.index')}}">
                                                            <span class=" menu-icon">
                                                                <i class="fa-solid fa-building fs-6"></i>
                                                            </span>
                                                            <span class="menu-title">Floor</span>
                                                        </a>
                                                        <!--end::Menu link-->
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
										<!--begin::Tab pane-->
										<div class="tab-pane fade @yield('setting_active_show')" id="kt_aside_nav_tab_settings" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Settings</span>
														</div>
														<!--end:Menu content-->
													</div>
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('business_setting_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<i class="bi bi-building-fill-gear fs-2"></i>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title">Business settings</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
														<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('business_settings_nav')" href="{{route('business_settings')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title ">Business settings</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
														</div>
														<!--end:Menu sub-->
														<!--end:Menu sub-->
													</div>
													<!--end:Menu item-->
                                                    			<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion  @yield('location_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<i class="bi bi-geo-alt-fill fs-2"></i>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title ">Business Location</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
															<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('location_list_nav')" href="{{route('business_location')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title">Location List</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
                                                                <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('location_add_nav')" href="{{route('location_add_form')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title">Add Location</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
															<!--end:Menu item-->

														</div>
													</div>

                                                    <div  class="menu-item menu-accordion ">
                                                        <!--begin:Menu link-->
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link @yield('printers_list_active_show')" href="{{route('printerList')}}">
                                                                <span class="menu-icon">
                                                                    <i class="las la-print fs-2"></i>
                                                                </span>
                                                                <span class="menu-title">Printer List</span>
                                                            </a>
                                                    </div>
													<!--end:Menu item-->
													<div class="menu-item menu-accordion">
                                                        <!--begin:Menu link-->
                                                        <a class="menu-link @yield('building_active_show')" href="{{ route('building.index') }}">
                                                            <span class=" menu-icon">
																<i class="fa-solid fa-hotel fs-6"></i>
                                                            </span>
                                                            <span class="menu-title">Building</span>
                                                        </a>
														<!--end::Menu link-->
                                                    </div>
													<div class="menu-item menu-accordion">
                                                        <!--begin:Menu link-->
                                                        <a class="menu-link @yield('floor_active_show')" href="{{route('floor.index')}}">
                                                            <span class=" menu-icon">
																<i class="fa-solid fa-building fs-6"></i>
                                                            </span>
                                                            <span class="menu-title">Floor</span>
                                                        </a>
														<!--end::Menu link-->
                                                    </div>
												</div>
											</div>
											<!--end::Wrapper-->
										</div>

										<div class="tab-pane fade @yield('fa_active_show')" id="kt_aside_nav_tab_fa" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Cash & Payment</span>
														</div>
														<!--end:Menu content-->
													</div>

                                                    @if (hasModule('ExchangeRate') && isEnableModule('ExchangeRate'))
                                                        <div  class="menu-item menu-accordion ">
                                                            <!--begin:Menu link-->
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('ex_rate_active_show')" href="{{route('exchangeRate.list')}}">
                                                                    <span class=" menu-icon">
                                                                        <i class="fa-solid fa-dollar-sign fs-6"></i>
                                                                    </span>
                                                                    <span class="menu-title">Exchange Rate</span>
                                                                </a>
                                                        </div>
                                                    @endif
                                                    @if (getSettingsValue('use_paymentAccount')==1)
                                                        <div  class="menu-item menu-accordion ">
                                                            <!--begin:Menu link-->
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('payment_account_active')" href="{{route('paymentAcc.list')}}">
                                                                    <span class=" menu-icon">
                                                                        <i class="fa-solid fa-money-check-dollar"></i>
                                                                    </span>
                                                                    <span class="menu-title">Payment Accounts</span>
                                                                </a>
                                                        </div>

                                                    @endif
													<!--begin:Menu item-->
													{{-- <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('business_setting_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<i class="bi bi-building-fill-gear fs-2"></i>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title">Business settings</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
														<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('business_settings_nav')" href="{{route('business_settings')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title ">Business settings</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
														</div>
														<!--end:Menu sub-->
														<!--end:Menu sub-->
													</div>
													<!--end:Menu item-->
                                                    			<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion  @yield('location_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<i class="bi bi-geo-alt-fill fs-2"></i>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title ">Business Location</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
															<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('location_list_nav')" href="{{route('business_location')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title">Location List</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
                                                                <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('location_add_nav')" href="{{route('location_add_form')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title">Add Location</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
															<!--end:Menu item-->

														</div>
														<!--end:Menu sub-->
														<!--end:Menu sub-->
													</div> --}}
													<!--end:Menu item-->
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
										<div class="tab-pane fade @yield('expense_active_show')" id="kt_aside_nav_tab_expense" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">{{__('expense.expense')}}</span>
														</div>
														<!--end:Menu content-->
													</div>
                                                    <div  class="menu-item menu-accordion ">
                                                        <!--begin:Menu link-->
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link @yield('expense_report_active')" href="{{route('expenseReport.list')}}">
                                                                <span class=" menu-icon">
                                                                    <i class="fa-solid fa-chart-pie fs-6"></i>
                                                                </span>
                                                                <span class="menu-title">{{__('expense.expense_report_list')}}</span>
                                                            </a>
                                                    </div>
                                                    {{-- <div  class="menu-item menu-accordion ">
                                                        <!--begin:Menu link-->
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link @yield('create_expense_active')" href="{{route('expense.create')}}">
                                                                <span class=" menu-icon">
                                                                    <i class="fa-solid fa-circle-plus fs-6"></i>
                                                                </span>
                                                                <span class="menu-title">{{__('expense.create_expense')}}</span>
                                                            </a>
                                                    </div> --}}
                                                    <div  class="menu-item menu-accordion ">
                                                        <!--begin:Menu link-->
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link @yield('list_expense_active')" href="{{route('expense.list')}}">
                                                                <span class=" menu-icon">
                                                                    <i class="fa-regular fa-rectangle-list fs-6"></i>
                                                                </span>
                                                                <span class="menu-title">{{__('expense.expense_list')}}</span>
                                                            </a>
                                                    </div>
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Tab pane-->
										<!-- begin::Tab pane -->

                                        @if(hasModule('Service') && isEnableModule('Service'))
										<div class="tab-pane fade @yield('service_show')" id="kt_aside_nav_tab_service" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Service</span>
														</div>
														<!--end:Menu content-->
													</div>
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion here show">
														<!--begin:Menu link-->
														    <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link @yield('service_type_active_show')" href="{{ route('service-type') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title fs-7">{{ __('service.service_type') }}</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
														<!--end:Menu sub-->
														<!--begin:Menu link-->
														<div class="menu-item">
															<!--begin:Menu link-->
															<a class="menu-link @yield('service_active_show')" href="{{ route('service') }}">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title fs-7">{{ __('service.service') }}</span>
															</a>
															<!--end:Menu link-->
														</div>
														<!--end:Menu sub-->
														<!--begin:Menu link-->
														<div class="menu-item">
															<!--begin:Menu link-->
															<a class="menu-link @yield('service_sales_active_show')" href="{{ route('service-sale') }}">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title fs-7">{{ __('service.service_sale') }}</span>
															</a>
															<!--end:Menu link-->
														</div>
														<!--end:Menu sub-->
													</div>
													<!--end:Menu item-->
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
                                        @endif
										<!-- end::Tab pane -->
										<!-- begin::Tab pane -->
                                        @if (hasModule('RoomManagement') && isEnableModule('RoomManagement'))
                                            <div class="tab-pane fade @yield('room_management_show')" id="kt_aside_nav_tab_room_management" role="tabpanel">
                                                <!--begin::Wrapper-->
                                                <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
                                                    <div id="kt_aside_menu_wrapper" class="menu-fit">
                                                        <div class="menu-item pt-2">
                                                            <!--begin:Menu content-->
                                                            <div class="menu-content">
                                                                <span class="menu-heading fw-bold text-uppercase fs-7">Room Management</span>
                                                            </div>
                                                            <!--end:Menu content-->
                                                        </div>
                                                        <!--begin:Menu item-->
                                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion here show">
                                                            <!--begin:Menu link-->
                                                                {{-- <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('building_active_show')" href="{{ route('building.index') }}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Building</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div> --}}
                                                                {{-- <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('floor_active_show')" href="{{route('floor.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Floor</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div> --}}
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('room_type_active_show')" href="{{route('room-type.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Room Type</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('bed_type_active_show')" href="{{route('bed-type.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Bed Type</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('bed_active_show')" href="{{route('bed.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Bed</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('room_active_show')" href="{{route('room.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Room</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('room_rate_active_show')" href="{{route('room-rate.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Room Rate</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('room_sale_active_show')" href="{{route('room-sale.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Room Sale</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('facility_active_show')" href="{{route('facility.index')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title fs-6">Facility</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>
                                                            <!--end:Menu sub-->
                                                        </div>
                                                        <!--end:Menu item-->
                                                    </div>
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                        @endif
										<!-- end::Tab pane -->
                                        @if (hasModule('restaurant') && isEnableModule('restaurant'))
										<div class="tab-pane fade @yield('res_management_show')" id="res_management" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Restaurant Management</span>
														</div>
														<!--end:Menu content-->
													</div>
													<!--begin:Menu item-->
                                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('table_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<span class="svg-icon svg-icon-2">
                                                                    <i class="fa-solid fa-table-cells fs-6"></i>
																</span>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title">Tables</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->
														<!--begin:Menu sub-->
														<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
															@if(hasView('supplier'))
                                                            <!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('table_list_active_show')" href="{{route('restaurant.tableList')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title">Table List</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
																@endif
														</div>
														<!--end:Menu sub-->
													</div>
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion @yield('pos_register_here_show')">
														<!--begin:Menu link-->
														<span class="menu-link">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
																<span class="svg-icon svg-icon-2">
                                                                    <i class="fa-solid fa-cash-register fs-7"></i>
																</span>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title">POS</span>
															<span class="menu-arrow"></span>
														</span>
														<!--end:Menu link-->

														<!--begin:Menu sub-->
														<div class="menu-sub menu-sub-accordion">
															<!--begin:Menu item-->
                                                                <div class="menu-item">
                                                                    <!--begin:Menu link-->
                                                                    <a class="menu-link @yield('pos_register_list_active_show')" href="{{route('posList')}}">
                                                                        <span class="menu-bullet">
                                                                            <span class="bullet bullet-dot"></span>
                                                                        </span>
                                                                        <span class="menu-title">POS Register List</span>
                                                                    </a>
                                                                    <!--end:Menu link-->
                                                                </div>
                                                                <!--end:Menu item-->
														</div>
														<!--end:Menu sub-->

													</div>
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
                                        @endif
										<!-- end::Tab pane -->
										<!-- begin::Tab pane -->

                                        @if(hasModule('Reservation') && isEnableModule('Reservation'))
										<div class="tab-pane fade @yield('reservation_show')" id="kt_aside_nav_tab_reservation" role="tabpanel">
                                            <!--begin::Wrapper-->
                                            <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
												<div id="kt_aside_menu_wrapper" class="menu-fit">
													<div class="menu-item pt-2">
														<!--begin:Menu content-->
														<div class="menu-content">
															<span class="menu-heading fw-bold text-uppercase fs-7">Reservation</span>
														</div>
														<!--end:Menu content-->
													</div>
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion here show">
														<!--begin:Menu link-->
															<div class="menu-item">
																<!-- begin::Menu link -->
																<a class="menu-link @yield('reservation_list_active_show')" href="{{route('reservation.index')}}">
																	<span class="menu-bullet">
																		<span class="bullet bullet-dot"></span>
																	</span>
																	<span class="menu-title fs-6">Reservation List</span>
																</a>
																<!-- end::Menu link -->
															</div>
													</div>
													<!--end:Menu item-->

													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion here show">
														<!--begin:Menu link-->
															<div class="menu-item">
																<!-- begin::Menu link -->
																<a class="menu-link @yield('reservation_add_active_show')" href="{{route('reservation.create')}}">
																	<span class="menu-bullet">
																		<span class="bullet bullet-dot"></span>
																	</span>
																	<span class="menu-title fs-6">Add Reservation</span>
																</a>
																<!-- end::Menu link -->
															</div>
														<!--end:Menu sub-->
													</div>
													<!--end:Menu item-->
													<!--begin:Menu item-->
													<div data-kt-menu-trigger="click" class="menu-item menu-accordion here show">
														<!--begin:Menu link-->
															<div class="menu-item">
																<!-- begin::Menu link -->
																<a class="menu-link @yield('room_dashboard_active_show')" href="{{url('room-dashboard')}}">
																	<span class="menu-bullet">
																		<span class="bullet bullet-dot"></span>
																	</span>
																	<span class="menu-title fs-6">Room Dashboard</span>
																</a>
																<!-- end::Menu link -->
															</div>
														<!--end:Menu sub-->
													</div>
													<!--end:Menu item-->
												</div>
											</div>
											<!--end::Wrapper-->
										</div>
                                        @endif
										<!-- end::Tab pane -->
                                        <!-- begin::Tab pane -->

                                        @if (hasModule('HospitalManagement') && isEnableModule('HospitalManagement'))
                                            <div class="tab-pane fade @yield('hospital_registration_show')" id="kt_aside_nav_tab_hospital_registration" role="tabpanel">
                                                <!--begin::Wrapper-->
                                                <div class="menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-5 ps-6 pe-8 my-2 my-lg-0" id="kt_aside_menu" data-kt-menu="true">
                                                    <div id="kt_aside_menu_wrapper" class="menu-fit">
                                                        <div class="menu-item pt-2">
                                                            <!--begin:Menu content-->
                                                            <div class="menu-content">
                                                                <span class="menu-heading fw-bold text-uppercase fs-8">Hostpital Registration</span>
                                                            </div>
                                                            <!--end:Menu content-->
                                                        </div>
                                                        <!--begin:Menu item-->
                                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion here show">
                                                            <!--begin:Menu link-->
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('registration_timeline_active_show')" href="{{route('registration_timeLine')}}">
                                                                        <span class="menu-title fs-6">Romm Registration Timeline</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('registration_active_show')" href="{{route('registration_list')}}">
                                                                        <span class="menu-title fs-6">Registration</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>
                                                                <div class="menu-item">
                                                                    <!-- begin::Menu link -->
                                                                    <a class="menu-link @yield('create_registration_active_show')" href="{{route('registration_create')}}">
                                                                        <span class="menu-title fs-6">Create Registration</span>
                                                                    </a>
                                                                    <!-- end::Menu link -->
                                                                </div>

                                                            <!--end:Menu sub-->
                                                        </div>
                                                        <!--end:Menu item-->
                                                    </div>
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                        @endif
										<!-- end::Tab pane -->
									</div>
									<!--end::Tab content-->
								</div>
								<!--end::Wrapper-->
								<!--begin::Footer-->
								{{-- <div class="flex-column-auto pt-10 px-5" id="kt_aside_secondary_footer">
									<a href="https://preview.keenthemes.com/html/metronic/docs" class="btn btn-bg-light btn-color-gray-600 btn-flex btn-active-color-primary flex-center w-100" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-trigger="hover" data-bs-offset="0,5" data-bs-dismiss-="click" title="200+ in-house components and 3rd-party plugins">
										<span class="btn-label">Docs & Components</span>
										<!--begin::Svg Icon | path: icons/duotune/general/gen005.svg-->
										<span class="svg-icon btn-icon svg-icon-4 ms-2">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM12.5 18C12.5 17.4 12.6 17.5 12 17.5H8.5C7.9 17.5 8 17.4 8 18C8 18.6 7.9 18.5 8.5 18.5L12 18C12.6 18 12.5 18.6 12.5 18ZM16.5 13C16.5 12.4 16.6 12.5 16 12.5H8.5C7.9 12.5 8 12.4 8 13C8 13.6 7.9 13.5 8.5 13.5H15.5C16.1 13.5 16.5 13.6 16.5 13ZM12.5 8C12.5 7.4 12.6 7.5 12 7.5H8C7.4 7.5 7.5 7.4 7.5 8C7.5 8.6 7.4 8.5 8 8.5H12C12.6 8.5 12.5 8.6 12.5 8Z" fill="currentColor" />
												<rect x="7" y="17" width="6" height="2" rx="1" fill="currentColor" />
												<rect x="7" y="12" width="10" height="2" rx="1" fill="currentColor" />
												<rect x="7" y="7" width="6" height="2" rx="1" fill="currentColor" />
												<path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</a>
								</div> --}}
								<!--end::Footer-->
							</div>
						</div>
						<!--end::Workspace-->
					</div>
					<!--end::Secondary-->
					<!--begin::Aside Toggle-->
					<button id="kt_aside_toggle" class="aside-toggle btn btn-sm btn-icon bg-body btn-color-gray-700 btn-active-primary position-absolute translate-middle start-100 end-0 bottom-0 shadow-sm d-none d-lg-flex mb-5" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
						<!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
						<span class="svg-icon svg-icon-2 rotate-180">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="currentColor" />
								<path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="currentColor" />
							</svg>
						</span>
						<!--end::Svg Icon-->
					</button>
					<!--end::Aside Toggle-->
				</div>
				<!--end::Aside-->
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
						<!--begin::Container-->
						<div class="container-xxl d-flex align-items-center justify-content-between" id="kt_header_container">
							<!--begin::Page title-->
							<div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap mt-n3 mt-lg-0 me-lg-2 pb-2 pb-lg-0" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
								<!--begin::Heading-->
								@yield("title")
								<!--end::Heading-->
							</div>
							<!--end::Page title=-->
							<!--begin::Wrapper-->
							<div class="d-flex d-lg-none align-items-center ms-n2 me-2">
								<!--begin::Aside mobile toggle-->
								<div class="btn btn-icon btn-active-icon-primary" id="kt_aside_mobile_toggle">
									<!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
									<span class="svg-icon svg-icon-1">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor" />
											<path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor" />
										</svg>
									</span>
									<!--end::Svg Icon-->
								</div>
								<!--end::Aside mobile toggle-->
								<!--begin::Logo-->
								<a href="../../demo7/dist/index.html" class="d-flex align-items-center">
									<img alt="Logo" src={{asset("assets/media/logos/demo7.svg")}} class="h-30px" />
								</a>
								<!--end::Logo-->
							</div>
							<!--end::Wrapper-->
							<!--begin::Toolbar wrapper-->
							<div class="d-flex flex-shrink-0">
								<!--begin::Invite user-->
								<div class="d-flex ms-3 d-none">
									<a href="#" class="btn btn-flex flex-center bg-body btn-color-gray-700 btn-active-color-primary w-40px w-md-auto h-40px px-0 px-md-6" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
										<!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
										<span class="svg-icon svg-icon-2 svg-icon-primary me-0 me-md-2">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
												<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
										<span class="d-none d-md-inline">New Member</span>
									</a>
								</div>
								<!--end::Invite user-->
                                <div class="d-flex align-items-center ms-3">
                                    <a  href="{{ route('pos.selectPos')}}"  class="btn btn-sm btn-icon flex-center bg-body btn-color-gray-600 h-40px"  title="POS Screen">
										<!--begin::Svg Icon | path: icons/duotune/general/gen060.svg-->
										<span class="svg-icon svg-icon-5" >
											<i class="fa-solid fa-cash-register fs-7"></i>
										</span>
										<!--end::Svg Icon-->
									</a>
                                </div>
								<!--end::Create app-->
								<!--begin::Theme mode-->
                                <div class="d-flex align-items-center ms-3">
                                    <a href="#" id="alertSound"  class="btn btn-sm btn-icon flex-center bg-body btn-color-gray-600 h-40px" >
										<!--begin::Svg Icon | path: icons/duotune/general/gen060.svg-->
										<span class="svg-icon svg-icon-5" id='alertIcon'>
											<i class="fa-solid fa-volume-low text-primary fs-5"></i>
										</span>
										<!--end::Svg Icon-->
									</a>
                                </div>


								<div class="d-flex align-items-center ms-3">
									<!--begin::Menu toggle-->
									<a href="#" class="btn btn-icon flex-center btn-sm bg-body btn-color-gray-600 btn-active-color-primary h-40px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<!--begin::Svg Icon | path: icons/duotune/general/gen060.svg-->
										<span class="svg-icon theme-light-show svg-icon-5">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M11.9905 5.62598C10.7293 5.62574 9.49646 5.9995 8.44775 6.69997C7.39903 7.40045 6.58159 8.39619 6.09881 9.56126C5.61603 10.7263 5.48958 12.0084 5.73547 13.2453C5.98135 14.4823 6.58852 15.6185 7.48019 16.5104C8.37186 17.4022 9.50798 18.0096 10.7449 18.2557C11.9818 18.5019 13.2639 18.3757 14.429 17.8931C15.5942 17.4106 16.5901 16.5933 17.2908 15.5448C17.9915 14.4962 18.3655 13.2634 18.3655 12.0023C18.3637 10.3119 17.6916 8.69129 16.4964 7.49593C15.3013 6.30056 13.6808 5.62806 11.9905 5.62598Z" fill="currentColor" />
												<path d="M22.1258 10.8771H20.627C20.3286 10.8771 20.0424 10.9956 19.8314 11.2066C19.6204 11.4176 19.5018 11.7038 19.5018 12.0023C19.5018 12.3007 19.6204 12.5869 19.8314 12.7979C20.0424 13.0089 20.3286 13.1274 20.627 13.1274H22.1258C22.4242 13.1274 22.7104 13.0089 22.9214 12.7979C23.1324 12.5869 23.2509 12.3007 23.2509 12.0023C23.2509 11.7038 23.1324 11.4176 22.9214 11.2066C22.7104 10.9956 22.4242 10.8771 22.1258 10.8771Z" fill="currentColor" />
												<path d="M11.9905 19.4995C11.6923 19.5 11.4064 19.6187 11.1956 19.8296C10.9848 20.0405 10.8663 20.3265 10.866 20.6247V22.1249C10.866 22.4231 10.9845 22.7091 11.1953 22.9199C11.4062 23.1308 11.6922 23.2492 11.9904 23.2492C12.2886 23.2492 12.5746 23.1308 12.7854 22.9199C12.9963 22.7091 13.1147 22.4231 13.1147 22.1249V20.6247C13.1145 20.3265 12.996 20.0406 12.7853 19.8296C12.5745 19.6187 12.2887 19.5 11.9905 19.4995Z" fill="currentColor" />
												<path d="M4.49743 12.0023C4.49718 11.704 4.37865 11.4181 4.16785 11.2072C3.95705 10.9962 3.67119 10.8775 3.37298 10.8771H1.87445C1.57603 10.8771 1.28984 10.9956 1.07883 11.2066C0.867812 11.4176 0.749266 11.7038 0.749266 12.0023C0.749266 12.3007 0.867812 12.5869 1.07883 12.7979C1.28984 13.0089 1.57603 13.1274 1.87445 13.1274H3.37299C3.6712 13.127 3.95706 13.0083 4.16785 12.7973C4.37865 12.5864 4.49718 12.3005 4.49743 12.0023Z" fill="currentColor" />
												<path d="M11.9905 4.50058C12.2887 4.50012 12.5745 4.38141 12.7853 4.17048C12.9961 3.95954 13.1147 3.67361 13.1149 3.3754V1.87521C13.1149 1.57701 12.9965 1.29103 12.7856 1.08017C12.5748 0.869313 12.2888 0.750854 11.9906 0.750854C11.6924 0.750854 11.4064 0.869313 11.1955 1.08017C10.9847 1.29103 10.8662 1.57701 10.8662 1.87521V3.3754C10.8664 3.67359 10.9849 3.95952 11.1957 4.17046C11.4065 4.3814 11.6923 4.50012 11.9905 4.50058Z" fill="currentColor" />
												<path d="M18.8857 6.6972L19.9465 5.63642C20.0512 5.53209 20.1343 5.40813 20.1911 5.27163C20.2479 5.13513 20.2772 4.98877 20.2774 4.84093C20.2775 4.69309 20.2485 4.54667 20.192 4.41006C20.1355 4.27344 20.0526 4.14932 19.948 4.04478C19.8435 3.94024 19.7194 3.85734 19.5828 3.80083C19.4462 3.74432 19.2997 3.71531 19.1519 3.71545C19.0041 3.7156 18.8577 3.7449 18.7212 3.80167C18.5847 3.85845 18.4607 3.94159 18.3564 4.04633L17.2956 5.10714C17.1909 5.21147 17.1077 5.33543 17.0509 5.47194C16.9942 5.60844 16.9649 5.7548 16.9647 5.90264C16.9646 6.05048 16.9936 6.19689 17.0501 6.33351C17.1066 6.47012 17.1895 6.59425 17.294 6.69878C17.3986 6.80332 17.5227 6.88621 17.6593 6.94272C17.7959 6.99923 17.9424 7.02824 18.0902 7.02809C18.238 7.02795 18.3844 6.99865 18.5209 6.94187C18.6574 6.88509 18.7814 6.80195 18.8857 6.6972Z" fill="currentColor" />
												<path d="M18.8855 17.3073C18.7812 17.2026 18.6572 17.1195 18.5207 17.0627C18.3843 17.006 18.2379 16.9767 18.0901 16.9766C17.9423 16.9764 17.7959 17.0055 17.6593 17.062C17.5227 17.1185 17.3986 17.2014 17.2941 17.3059C17.1895 17.4104 17.1067 17.5345 17.0501 17.6711C16.9936 17.8077 16.9646 17.9541 16.9648 18.1019C16.9649 18.2497 16.9942 18.3961 17.0509 18.5326C17.1077 18.6691 17.1908 18.793 17.2955 18.8974L18.3563 19.9582C18.4606 20.0629 18.5846 20.146 18.721 20.2027C18.8575 20.2595 19.0039 20.2887 19.1517 20.2889C19.2995 20.289 19.4459 20.26 19.5825 20.2035C19.7191 20.147 19.8432 20.0641 19.9477 19.9595C20.0523 19.855 20.1351 19.7309 20.1916 19.5943C20.2482 19.4577 20.2772 19.3113 20.277 19.1635C20.2769 19.0157 20.2476 18.8694 20.1909 18.7329C20.1341 18.5964 20.051 18.4724 19.9463 18.3681L18.8855 17.3073Z" fill="currentColor" />
												<path d="M5.09528 17.3072L4.0345 18.368C3.92972 18.4723 3.84655 18.5963 3.78974 18.7328C3.73294 18.8693 3.70362 19.0156 3.70346 19.1635C3.7033 19.3114 3.7323 19.4578 3.78881 19.5944C3.84532 19.7311 3.92822 19.8552 4.03277 19.9598C4.13732 20.0643 4.26147 20.1472 4.3981 20.2037C4.53473 20.2602 4.68117 20.2892 4.82902 20.2891C4.97688 20.2889 5.12325 20.2596 5.25976 20.2028C5.39627 20.146 5.52024 20.0628 5.62456 19.958L6.68536 18.8973C6.79007 18.7929 6.87318 18.6689 6.92993 18.5325C6.98667 18.396 7.01595 18.2496 7.01608 18.1018C7.01621 17.954 6.98719 17.8076 6.93068 17.671C6.87417 17.5344 6.79129 17.4103 6.68676 17.3058C6.58224 17.2012 6.45813 17.1183 6.32153 17.0618C6.18494 17.0053 6.03855 16.9763 5.89073 16.9764C5.74291 16.9766 5.59657 17.0058 5.46007 17.0626C5.32358 17.1193 5.19962 17.2024 5.09528 17.3072Z" fill="currentColor" />
												<path d="M5.09541 6.69715C5.19979 6.8017 5.32374 6.88466 5.4602 6.94128C5.59665 6.9979 5.74292 7.02708 5.89065 7.02714C6.03839 7.0272 6.18469 6.99815 6.32119 6.94164C6.45769 6.88514 6.58171 6.80228 6.68618 6.69782C6.79064 6.59336 6.87349 6.46933 6.93 6.33283C6.9865 6.19633 7.01556 6.05003 7.01549 5.9023C7.01543 5.75457 6.98625 5.60829 6.92963 5.47184C6.87301 5.33539 6.79005 5.21143 6.6855 5.10706L5.6247 4.04626C5.5204 3.94137 5.39643 3.8581 5.25989 3.80121C5.12335 3.74432 4.97692 3.71493 4.82901 3.71472C4.68109 3.71452 4.53458 3.7435 4.39789 3.80001C4.26119 3.85652 4.13699 3.93945 4.03239 4.04404C3.9278 4.14864 3.84487 4.27284 3.78836 4.40954C3.73185 4.54624 3.70287 4.69274 3.70308 4.84066C3.70329 4.98858 3.73268 5.135 3.78957 5.27154C3.84646 5.40808 3.92974 5.53205 4.03462 5.63635L5.09541 6.69715Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
										<!--begin::Svg Icon | path: icons/duotune/general/gen061.svg-->
										<span class="svg-icon theme-dark-show svg-icon-2">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M19.0647 5.43757C19.3421 5.43757 19.567 5.21271 19.567 4.93534C19.567 4.65796 19.3421 4.43311 19.0647 4.43311C18.7874 4.43311 18.5625 4.65796 18.5625 4.93534C18.5625 5.21271 18.7874 5.43757 19.0647 5.43757Z" fill="currentColor" />
												<path d="M20.0692 9.48884C20.3466 9.48884 20.5714 9.26398 20.5714 8.98661C20.5714 8.70923 20.3466 8.48438 20.0692 8.48438C19.7918 8.48438 19.567 8.70923 19.567 8.98661C19.567 9.26398 19.7918 9.48884 20.0692 9.48884Z" fill="currentColor" />
												<path d="M12.0335 20.5714C15.6943 20.5714 18.9426 18.2053 20.1168 14.7338C20.1884 14.5225 20.1114 14.289 19.9284 14.161C19.746 14.034 19.5003 14.0418 19.3257 14.1821C18.2432 15.0546 16.9371 15.5156 15.5491 15.5156C12.2257 15.5156 9.48884 12.8122 9.48884 9.48886C9.48884 7.41079 10.5773 5.47137 12.3449 4.35752C12.5342 4.23832 12.6 4.00733 12.5377 3.79251C12.4759 3.57768 12.2571 3.42859 12.0335 3.42859C7.32556 3.42859 3.42857 7.29209 3.42857 12C3.42857 16.7079 7.32556 20.5714 12.0335 20.5714Z" fill="currentColor" />
												<path d="M13.0379 7.47998C13.8688 7.47998 14.5446 8.15585 14.5446 8.98668C14.5446 9.26428 14.7693 9.48891 15.0469 9.48891C15.3245 9.48891 15.5491 9.26428 15.5491 8.98668C15.5491 8.15585 16.225 7.47998 17.0558 7.47998C17.3334 7.47998 17.558 7.25535 17.558 6.97775C17.558 6.70015 17.3334 6.47552 17.0558 6.47552C16.225 6.47552 15.5491 5.76616 15.5491 4.93534C15.5491 4.65774 15.3245 4.43311 15.0469 4.43311C14.7693 4.43311 14.5446 4.65774 14.5446 4.93534C14.5446 5.76616 13.8688 6.47552 13.0379 6.47552C12.7603 6.47552 12.5357 6.70015 12.5357 6.97775C12.5357 7.25535 12.7603 7.47998 13.0379 7.47998Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</a>
									<!--begin::Menu toggle-->
									<!--begin::Menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-muted menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
												<span class="menu-icon" data-kt-element="icon">
													<!--begin::Svg Icon | path: icons/duotune/general/gen060.svg-->
													<span class="svg-icon svg-icon-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M11.9905 5.62598C10.7293 5.62574 9.49646 5.9995 8.44775 6.69997C7.39903 7.40045 6.58159 8.39619 6.09881 9.56126C5.61603 10.7263 5.48958 12.0084 5.73547 13.2453C5.98135 14.4823 6.58852 15.6185 7.48019 16.5104C8.37186 17.4022 9.50798 18.0096 10.7449 18.2557C11.9818 18.5019 13.2639 18.3757 14.429 17.8931C15.5942 17.4106 16.5901 16.5933 17.2908 15.5448C17.9915 14.4962 18.3655 13.2634 18.3655 12.0023C18.3637 10.3119 17.6916 8.69129 16.4964 7.49593C15.3013 6.30056 13.6808 5.62806 11.9905 5.62598Z" fill="currentColor" />
															<path d="M22.1258 10.8771H20.627C20.3286 10.8771 20.0424 10.9956 19.8314 11.2066C19.6204 11.4176 19.5018 11.7038 19.5018 12.0023C19.5018 12.3007 19.6204 12.5869 19.8314 12.7979C20.0424 13.0089 20.3286 13.1274 20.627 13.1274H22.1258C22.4242 13.1274 22.7104 13.0089 22.9214 12.7979C23.1324 12.5869 23.2509 12.3007 23.2509 12.0023C23.2509 11.7038 23.1324 11.4176 22.9214 11.2066C22.7104 10.9956 22.4242 10.8771 22.1258 10.8771Z" fill="currentColor" />
															<path d="M11.9905 19.4995C11.6923 19.5 11.4064 19.6187 11.1956 19.8296C10.9848 20.0405 10.8663 20.3265 10.866 20.6247V22.1249C10.866 22.4231 10.9845 22.7091 11.1953 22.9199C11.4062 23.1308 11.6922 23.2492 11.9904 23.2492C12.2886 23.2492 12.5746 23.1308 12.7854 22.9199C12.9963 22.7091 13.1147 22.4231 13.1147 22.1249V20.6247C13.1145 20.3265 12.996 20.0406 12.7853 19.8296C12.5745 19.6187 12.2887 19.5 11.9905 19.4995Z" fill="currentColor" />
															<path d="M4.49743 12.0023C4.49718 11.704 4.37865 11.4181 4.16785 11.2072C3.95705 10.9962 3.67119 10.8775 3.37298 10.8771H1.87445C1.57603 10.8771 1.28984 10.9956 1.07883 11.2066C0.867812 11.4176 0.749266 11.7038 0.749266 12.0023C0.749266 12.3007 0.867812 12.5869 1.07883 12.7979C1.28984 13.0089 1.57603 13.1274 1.87445 13.1274H3.37299C3.6712 13.127 3.95706 13.0083 4.16785 12.7973C4.37865 12.5864 4.49718 12.3005 4.49743 12.0023Z" fill="currentColor" />
															<path d="M11.9905 4.50058C12.2887 4.50012 12.5745 4.38141 12.7853 4.17048C12.9961 3.95954 13.1147 3.67361 13.1149 3.3754V1.87521C13.1149 1.57701 12.9965 1.29103 12.7856 1.08017C12.5748 0.869313 12.2888 0.750854 11.9906 0.750854C11.6924 0.750854 11.4064 0.869313 11.1955 1.08017C10.9847 1.29103 10.8662 1.57701 10.8662 1.87521V3.3754C10.8664 3.67359 10.9849 3.95952 11.1957 4.17046C11.4065 4.3814 11.6923 4.50012 11.9905 4.50058Z" fill="currentColor" />
															<path d="M18.8857 6.6972L19.9465 5.63642C20.0512 5.53209 20.1343 5.40813 20.1911 5.27163C20.2479 5.13513 20.2772 4.98877 20.2774 4.84093C20.2775 4.69309 20.2485 4.54667 20.192 4.41006C20.1355 4.27344 20.0526 4.14932 19.948 4.04478C19.8435 3.94024 19.7194 3.85734 19.5828 3.80083C19.4462 3.74432 19.2997 3.71531 19.1519 3.71545C19.0041 3.7156 18.8577 3.7449 18.7212 3.80167C18.5847 3.85845 18.4607 3.94159 18.3564 4.04633L17.2956 5.10714C17.1909 5.21147 17.1077 5.33543 17.0509 5.47194C16.9942 5.60844 16.9649 5.7548 16.9647 5.90264C16.9646 6.05048 16.9936 6.19689 17.0501 6.33351C17.1066 6.47012 17.1895 6.59425 17.294 6.69878C17.3986 6.80332 17.5227 6.88621 17.6593 6.94272C17.7959 6.99923 17.9424 7.02824 18.0902 7.02809C18.238 7.02795 18.3844 6.99865 18.5209 6.94187C18.6574 6.88509 18.7814 6.80195 18.8857 6.6972Z" fill="currentColor" />
															<path d="M18.8855 17.3073C18.7812 17.2026 18.6572 17.1195 18.5207 17.0627C18.3843 17.006 18.2379 16.9767 18.0901 16.9766C17.9423 16.9764 17.7959 17.0055 17.6593 17.062C17.5227 17.1185 17.3986 17.2014 17.2941 17.3059C17.1895 17.4104 17.1067 17.5345 17.0501 17.6711C16.9936 17.8077 16.9646 17.9541 16.9648 18.1019C16.9649 18.2497 16.9942 18.3961 17.0509 18.5326C17.1077 18.6691 17.1908 18.793 17.2955 18.8974L18.3563 19.9582C18.4606 20.0629 18.5846 20.146 18.721 20.2027C18.8575 20.2595 19.0039 20.2887 19.1517 20.2889C19.2995 20.289 19.4459 20.26 19.5825 20.2035C19.7191 20.147 19.8432 20.0641 19.9477 19.9595C20.0523 19.855 20.1351 19.7309 20.1916 19.5943C20.2482 19.4577 20.2772 19.3113 20.277 19.1635C20.2769 19.0157 20.2476 18.8694 20.1909 18.7329C20.1341 18.5964 20.051 18.4724 19.9463 18.3681L18.8855 17.3073Z" fill="currentColor" />
															<path d="M5.09528 17.3072L4.0345 18.368C3.92972 18.4723 3.84655 18.5963 3.78974 18.7328C3.73294 18.8693 3.70362 19.0156 3.70346 19.1635C3.7033 19.3114 3.7323 19.4578 3.78881 19.5944C3.84532 19.7311 3.92822 19.8552 4.03277 19.9598C4.13732 20.0643 4.26147 20.1472 4.3981 20.2037C4.53473 20.2602 4.68117 20.2892 4.82902 20.2891C4.97688 20.2889 5.12325 20.2596 5.25976 20.2028C5.39627 20.146 5.52024 20.0628 5.62456 19.958L6.68536 18.8973C6.79007 18.7929 6.87318 18.6689 6.92993 18.5325C6.98667 18.396 7.01595 18.2496 7.01608 18.1018C7.01621 17.954 6.98719 17.8076 6.93068 17.671C6.87417 17.5344 6.79129 17.4103 6.68676 17.3058C6.58224 17.2012 6.45813 17.1183 6.32153 17.0618C6.18494 17.0053 6.03855 16.9763 5.89073 16.9764C5.74291 16.9766 5.59657 17.0058 5.46007 17.0626C5.32358 17.1193 5.19962 17.2024 5.09528 17.3072Z" fill="currentColor" />
															<path d="M5.09541 6.69715C5.19979 6.8017 5.32374 6.88466 5.4602 6.94128C5.59665 6.9979 5.74292 7.02708 5.89065 7.02714C6.03839 7.0272 6.18469 6.99815 6.32119 6.94164C6.45769 6.88514 6.58171 6.80228 6.68618 6.69782C6.79064 6.59336 6.87349 6.46933 6.93 6.33283C6.9865 6.19633 7.01556 6.05003 7.01549 5.9023C7.01543 5.75457 6.98625 5.60829 6.92963 5.47184C6.87301 5.33539 6.79005 5.21143 6.6855 5.10706L5.6247 4.04626C5.5204 3.94137 5.39643 3.8581 5.25989 3.80121C5.12335 3.74432 4.97692 3.71493 4.82901 3.71472C4.68109 3.71452 4.53458 3.7435 4.39789 3.80001C4.26119 3.85652 4.13699 3.93945 4.03239 4.04404C3.9278 4.14864 3.84487 4.27284 3.78836 4.40954C3.73185 4.54624 3.70287 4.69274 3.70308 4.84066C3.70329 4.98858 3.73268 5.135 3.78957 5.27154C3.84646 5.40808 3.92974 5.53205 4.03462 5.63635L5.09541 6.69715Z" fill="currentColor" />
														</svg>
													</span>
													<!--end::Svg Icon-->
												</span>
												<span class="menu-title">Light</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
												<span class="menu-icon" data-kt-element="icon">
													<!--begin::Svg Icon | path: icons/duotune/general/gen061.svg-->
													<span class="svg-icon svg-icon-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M19.0647 5.43757C19.3421 5.43757 19.567 5.21271 19.567 4.93534C19.567 4.65796 19.3421 4.43311 19.0647 4.43311C18.7874 4.43311 18.5625 4.65796 18.5625 4.93534C18.5625 5.21271 18.7874 5.43757 19.0647 5.43757Z" fill="currentColor" />
															<path d="M20.0692 9.48884C20.3466 9.48884 20.5714 9.26398 20.5714 8.98661C20.5714 8.70923 20.3466 8.48438 20.0692 8.48438C19.7918 8.48438 19.567 8.70923 19.567 8.98661C19.567 9.26398 19.7918 9.48884 20.0692 9.48884Z" fill="currentColor" />
															<path d="M12.0335 20.5714C15.6943 20.5714 18.9426 18.2053 20.1168 14.7338C20.1884 14.5225 20.1114 14.289 19.9284 14.161C19.746 14.034 19.5003 14.0418 19.3257 14.1821C18.2432 15.0546 16.9371 15.5156 15.5491 15.5156C12.2257 15.5156 9.48884 12.8122 9.48884 9.48886C9.48884 7.41079 10.5773 5.47137 12.3449 4.35752C12.5342 4.23832 12.6 4.00733 12.5377 3.79251C12.4759 3.57768 12.2571 3.42859 12.0335 3.42859C7.32556 3.42859 3.42857 7.29209 3.42857 12C3.42857 16.7079 7.32556 20.5714 12.0335 20.5714Z" fill="currentColor" />
															<path d="M13.0379 7.47998C13.8688 7.47998 14.5446 8.15585 14.5446 8.98668C14.5446 9.26428 14.7693 9.48891 15.0469 9.48891C15.3245 9.48891 15.5491 9.26428 15.5491 8.98668C15.5491 8.15585 16.225 7.47998 17.0558 7.47998C17.3334 7.47998 17.558 7.25535 17.558 6.97775C17.558 6.70015 17.3334 6.47552 17.0558 6.47552C16.225 6.47552 15.5491 5.76616 15.5491 4.93534C15.5491 4.65774 15.3245 4.43311 15.0469 4.43311C14.7693 4.43311 14.5446 4.65774 14.5446 4.93534C14.5446 5.76616 13.8688 6.47552 13.0379 6.47552C12.7603 6.47552 12.5357 6.70015 12.5357 6.97775C12.5357 7.25535 12.7603 7.47998 13.0379 7.47998Z" fill="currentColor" />
														</svg>
													</span>
													<!--end::Svg Icon-->
												</span>
												<span class="menu-title">Dark</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
												<span class="menu-icon" data-kt-element="icon">
													<!--begin::Svg Icon | path: icons/duotune/general/gen062.svg-->
													<span class="svg-icon svg-icon-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path fill-rule="evenodd" clip-rule="evenodd" d="M1.34375 3.9463V15.2178C1.34375 16.119 2.08105 16.8563 2.98219 16.8563H8.65093V19.4594H6.15702C5.38853 19.4594 4.75981 19.9617 4.75981 20.5757V21.6921H19.2403V20.5757C19.2403 19.9617 18.6116 19.4594 17.8431 19.4594H15.3492V16.8563H21.0179C21.919 16.8563 22.6562 16.119 22.6562 15.2178V3.9463C22.6562 3.04516 21.9189 2.30786 21.0179 2.30786H2.98219C2.08105 2.30786 1.34375 3.04516 1.34375 3.9463ZM12.9034 9.9016C13.241 9.98792 13.5597 10.1216 13.852 10.2949L15.0393 9.4353L15.9893 10.3853L15.1297 11.5727C15.303 11.865 15.4366 12.1837 15.523 12.5212L16.97 12.7528V13.4089H13.9851C13.9766 12.3198 13.0912 11.4394 12 11.4394C10.9089 11.4394 10.0235 12.3198 10.015 13.4089H7.03006V12.7528L8.47712 12.5211C8.56345 12.1836 8.69703 11.8649 8.87037 11.5727L8.0107 10.3853L8.96078 9.4353L10.148 10.2949C10.4404 10.1215 10.759 9.98788 11.0966 9.9016L11.3282 8.45467H12.6718L12.9034 9.9016ZM16.1353 7.93758C15.6779 7.93758 15.3071 7.56681 15.3071 7.1094C15.3071 6.652 15.6779 6.28122 16.1353 6.28122C16.5926 6.28122 16.9634 6.652 16.9634 7.1094C16.9634 7.56681 16.5926 7.93758 16.1353 7.93758ZM2.71385 14.0964V3.90518C2.71385 3.78023 2.81612 3.67796 2.94107 3.67796H21.0589C21.1839 3.67796 21.2861 3.78023 21.2861 3.90518V14.0964C15.0954 14.0964 8.90462 14.0964 2.71385 14.0964Z" fill="currentColor" />
														</svg>
													</span>
													<!--end::Svg Icon-->
												</span>
												<span class="menu-title">System</span>
											</a>
										</div>

										<!--end::Menu item-->
									</div>
									<!--end::Menu-->
								</div>
                                <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
                                    <!--begin::Menu wrapper-->
                                    @php
                                        $personalInfo = \App\Models\PersonalInfo::find(\Illuminate\Support\Facades\Auth::user()->personal_info_id);
                                        $roleName = \App\Models\Role::find(\Illuminate\Support\Facades\Auth::user()->role_id)->name;
                                    @endphp
                                    <div class="cursor-pointer symbol symbol-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                        @if($personalInfo)
                                            @if($personalInfo->profile_photo == null)
                                                <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                    {{ substr($personalInfo->first_name, 0, 1) }}
                                                </div>
                                            @else
                                                <img alt="Profile Picture" width="auto" src="{{ $personalInfo->profile_photo }}" />
                                            @endif
                                        @endif
                                    </div>

                                    <!--begin::User account menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true" style="">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content d-flex align-items-center px-3">
                                                <!--begin::Avatar-->
                                                <div class="symbol symbol-50px me-5">
                                                    @if($personalInfo)
                                                        @if($personalInfo->profile_photo == null)
                                                            <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                                {{ substr($personalInfo->first_name, 0, 1) }}
                                                            </div>
                                                        @else
                                                            <img alt="Profile Picture" src="{{ $personalInfo->profile_photo }}" />
                                                        @endif
                                                    @endif
                                                </div>
                                                <!--end::Avatar-->
                                                <!--begin::Username-->
                                                <div class="d-flex flex-column">
                                                    <div class="fw-bold d-flex align-items-center fs-5">
                                                        @if($personalInfo)
                                                            {{ $personalInfo->initname }}
                                                            {{ $personalInfo->first_name }}
                                                            {{ $personalInfo->last_name }}
                                                        @endif
                                                    </div>
                                                    <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{$roleName}}</a>
                                                </div>
                                                <!--end::Username-->
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-5">
                                            <a href="{{route('profile.index')}}" class="menu-link px-5">My Profile</a>
                                        </div>
                                        <!--begin::Menu separator-->
                                        <div class="separator my-2"></div>

                                        <div class="menu-item px-5">
                                            <a class="menu-link px-5" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">Sign Out</a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                       <!--begin::User-->

                                   <!--end::User-->
                                    <!--end::Menu wrapper-->
                                </div>
								<!--end::Theme mode-->
								<!--begin::Chat-->
								<div class="d-flex align-items-center ms-3 d-none">
									<!--begin::Menu wrapper-->
									<div class="btn btn-icon btn-primary w-40px h-40px pulse pulse-white" id="kt_drawer_chat_toggle">
										<!--begin::Svg Icon | path: icons/duotune/communication/com012.svg-->
										<span class="svg-icon svg-icon-2">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path opacity="0.3" d="M20 3H4C2.89543 3 2 3.89543 2 5V16C2 17.1046 2.89543 18 4 18H4.5C5.05228 18 5.5 18.4477 5.5 19V21.5052C5.5 22.1441 6.21212 22.5253 6.74376 22.1708L11.4885 19.0077C12.4741 18.3506 13.6321 18 14.8167 18H20C21.1046 18 22 17.1046 22 16V5C22 3.89543 21.1046 3 20 3Z" fill="currentColor" />
												<rect x="6" y="12" width="7" height="2" rx="1" fill="currentColor" />
												<rect x="6" y="7" width="12" height="2" rx="1" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
										<span class="pulse-ring"></span>
									</div>
									<!--end::Menu wrapper-->
								</div>
								<!--end::Chat-->
							</div>
							<!--end::Toolbar wrapper-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->
					<!--begin::Content-->
                    <div class="">
                        <div id="spinnerWrapper" class="spinner-wrapper">
                            <div class="spinner">
                            </div>
                        </div>
                    </div>

                    @yield('content')
					<!--end::Content-->
					<!--begin::Footer-->
					<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
						<!--begin::Container-->
						<div class="container-xxl d-flex flex-column flex-md-row flex-stack">
							<!--begin::Copyright-->
							<div class="text-dark order-2 order-md-1">
								<span class="text-gray-400 fw-semibold me-1">Created by</span>
								<a href="https://picosbs.com/" target="_blank" class="text-muted text-hover-primary fw-semibold me-2 fs-6">Pico SBS</a>
							</div>
							<!--end::Copyright-->
							<!--begin::Menu-->
							<ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
								<li class="menu-item">
									<a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
								</li>
								<li class="menu-item">
									<a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
								</li>
								<li class="menu-item">
									<a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
								</li>
							</ul>
							<!--end::Menu-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->
		<!--end::Main-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
			<span class="svg-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->
		<!--begin::Modals-->
		<!--end::Modals-->
		<!--begin::Javascript-->
		<script>var hostUrl ="{{asset("assets/")}}";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src={{asset("assets/plugins/global/plugins.bundle.js")}}></script>
		<script src={{asset("assets/js/scripts.bundle.js")}}></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src={{asset("assets/plugins/custom/datatables/datatables.bundle.js")}}></script>
		<script src={{asset("assets/plugins/custom/vis-timeline/vis-timeline.bundle.js")}}></script>


		<script src={{asset('customJs/general.js')}}></script>

        <script src={{asset('customJs/loading/miniLoading.js')}}></script>
        @stack('scripts')
	</body>
	<!--end::Body-->
</html>

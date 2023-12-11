@extends('App.main.navBar')

@section('setting_active','active')
@section('setting_active_show','active show')
@section('business_setting_here_show','here show')
@section('business_settings_nav','active')
@section('styles')
 	<link href={{asset("assets/plugins/custom/datatables/datatables.bundle.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("assets/plugins/global/plugins.bundle.css")}} rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href={{asset("customCss/businessSetting.css")}}>
    <link rel="stylesheet" href={{asset("customCss/customFileInput.css")}}>
@endsection


@section('title')
    <!--begin::Heading-->
    <h1 class="text-dark fw-bold my-0 fs-2">{{__('business_settings.business_settings')}}</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">{{__('business_settings.settings')}}</li>
        <li class="breadcrumb-item text-dark">{{__('business_settings.business_settings')}}</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <!--begin::Container-->
    <div class="container-fluid " id="kt_content_container">
        <!--begin::Inbox App - Compose -->
        <div class="card mb-9">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                    <!--begin::Image-->
                    <div class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-75px h-lg-75px me-7 mb-4">
                        @if ($settingData['logo'])
                            <img alt="Logo" src="{{asset('storage/logo/'.$settingData['logo'])}}" class="mw-25px mw-lg-35px" />
                        @else
                            <img class="mw-25px mw-lg-35px" src="{{asset('default/pico.png')}}" alt="image" />
                        @endif
                    </div>
                    <!--end::Image-->
                    <!--begin::Wrapper-->
                    <div class="flex-grow-1">
                        <!--begin::Head-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::Details-->
                            <div class="d-flex flex-column">
                                <!--begin::Status-->
                                <div class="d-flex align-items-center mb-1">
                                    <a href="#" class="text-gray-800 text-hover-primary fs-3 fw-bold me-3">{{$settingData['name']}}</a>
                                    {{-- <span class="badge badge-light-success me-auto">In Progress</span> --}}
                                </div>
                                <!--end::Status-->
                                <!--begin::Description-->
                                <div class="d-flex flex-wrap fw-semibold mb-4 fs-7 text-gray-400">
                                    <span>Started Date </span> : {{fDate($settingData['start_date'],false,false)}}
                                </div>
                                <!--end::Description-->
                            </div>
                            <!--end::Details-->
                        </div>
                        <!--end::Head-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Details-->
                <div class="separator"></div>
                <!--begin::Nav-->
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold cursor-pointer">
                    <!--begin::Nav item-->
                    <li class="nav-item   d-block " data-title="{{__('business_settings.business')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link active "  data-bs-toggle="tab" href="#business_setting_business" data-bs-target="#business_setting_business" >
                            <span class="menu-title fw-bold">{{__('business_settings.business')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    {{-- <div class="nav-item  d-block">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_tax"  data-bs-target="#business_setting_tax" >
                            <span class="menu-title fw-bold">{{__('business_settings.tax')}}</span>
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Registered Tax Number For Your Business."></i>
                        </span>
                        <!--end::Marked-->
                    </div> --}}
                    <li class="nav-item   d-block" data-title="{{__('business_settings.product')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_product"  data-bs-target="#business_setting_product" >
                            <span class="menu-title fw-bold">{{__('business_settings.product')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    <li class="nav-item   d-block" data-title="{{__('business_settings.contact')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_contact"  data-bs-target="#business_setting_contact" >
                            <span class="menu-title fw-bold">{{__('business_settings.contact')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    <li class="nav-item   d-block" data-title="{{__('business_settings.sale')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_sale"  data-bs-target="#business_setting_sale" >
                            <span class="menu-title fw-bold">{{__('business_settings.sale')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    <li class="nav-item  d-block" data-title="{{__('business_settings.purchases')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_purchases"  data-bs-target="#business_setting_purchases" >
                            <span class="menu-title fw-bold">{{__('business_settings.purchases')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    <li class="nav-item   d-block" data-title="{{__('business_settings.pos')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_pos"  data-bs-target="#business_setting_pos" >
                            <span class="menu-title fw-bold">{{__('business_settings.pos')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    <li class="nav-item   d-block" data-title="{{__('business_settings.payment')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_payment"  data-bs-target="#business_setting_payment" >
                            <span class="menu-title fw-bold">{{__('business_settings.payment')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    {{-- <div class="nav-item   d-block">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_dashboard"  data-bs-target="#business_setting_dashboard" >
                            <span class="menu-title fw-bold">{{__('business_settings.dashboard')}}</span>
                        </span>
                        <!--end::Marked-->
                    </div> --}}
                    <li class="nav-item   d-block" data-title="{{__('business_settings.system')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_system"  data-bs-target="#business_setting_system" >
                            <span class="menu-title fw-bold">{{__('business_settings.system')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    <li class="nav-item   d-block" data-title="{{__('business_settings.prefix')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_prefix"  data-bs-target="#business_setting_prefix" >
                            <span class="menu-title fw-bold">{{__('business_settings.prefix')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    {{-- <div class="nav-item   d-block">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_emailSetting"  data-bs-target="#business_setting_emailSetting" >
                            <span class="menu-title fw-bold">{{__('business_settings.email_setting')}}</span>
                        </span>
                        <!--end::Marked-->
                    </div> --}}
                    {{-- <div class="nav-item   d-block">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_sms"  data-bs-target="#business_setting_sms" >
                            <span class="menu-title fw-bold">{{__('business_settings.sms_setting')}}</span>
                        </span>
                        <!--end::Marked-->
                    </div> --}}
                    <li class="nav-item   d-block" data-title="{{__('business_settings.reward_point_setting')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_reward_point_setting"  data-bs-target="#business_setting_reward_point_setting" >
                            <span class="menu-title fw-bold">{{__('business_settings.reward_point_setting')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    <li class="nav-item   d-block" data-title="{{__('business_settings.modules')}}">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_reward_point_modules"  data-bs-target="#business_setting_reward_point_modules" >
                            <span class="menu-title fw-bold">{{__('business_settings.modules')}}</span>
                        </span>
                        <!--end::Marked-->
                    </li>
                    {{-- <div class="nav-item  mb-3 d-block">
                        <!--begin::Marked-->
                        <span class="nav-link setting-link "  data-bs-toggle="tab" href="#business_setting_custom_labels"  data-bs-target="#business_setting_custom_labels" >
                            <span class="menu-title fw-bold">{{__('business_settings.customs_label')}}</span>
                        </span>
                        <!--end::Marked-->
                    </div> --}}

                </ul>
                <!--end::Nav-->
            </div>
        </div>
        <div class="d-flex flex-row flex-lg-row mt-5">
            <!--begin::Content-->
            <div class="flex-row-fluid " id="kt_content" >
                <!--begin::Container-->

                <div class="flex-row-fluid  ">
                    <!--begin::Card-->
                    <form action="{{route('business_settings_update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card " style="min-height: 100vh;">
                            <!--begin::Card body-->
                            <div class="card-body py-8 px-4 px-sm-7 px-md-10">
                                <!--begin:::Tab content-->
                                <div class="row mb-7 justify-content-between">
                                    <div class="col-4 p-3">
                                        <h2 class="" id="tab-title">{{__('business_settings.business')}}</h2>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </span>
                                            <div class="overflow-hidden flex-grow-1">
                                                <select class="form-select form-select-sm rounded-start-0" name="search-bar" id="search-bar"
                                                    data-control="select2" data-allow-clear="true" data-placeholder='Serach'>
                                                    <option></option>
                                                    {{-- business --}}
                                                    <option value="business_name">{{__('business_settings.business_name')}}</option>
                                                    <option value="owner">{{__('business_settings.owner_name')}}</option>
                                                    <option value="kt_datepicker_1">{{__('business_settings.start_date')}}</option>
                                                    <option value="default_profit_percent">{{__('business_settings.default_profit_percent')}}</option>
                                                    <option value="default_currency">{{__('business_settings.default_currency')}}</option>
                                                    <option value="use_payment_account">{{__('business_settings.use_payment_account')}}</option>
                                                    <option value="currency_symbol_placement">{{__('business_settings.currency_symbol_placement')}}</option>
                                                    <option value="timezone">{{__('business_settings.time_zone')}}</option>
                                                    <option value="update_logo">{{__('business_settings.update_logo')}}</option>
                                                    <option value="finanical_year_start_month">{{__('business_settings.financial_year_start_month')}}
                                                    </option>
                                                    <option value="stock_accounting_method">{{__('business_settings.stock_accounting_method')}}</option>
                                                    <option value="transaction_edit_days">{{__('business_settings.transaction_edit_days')}}</option>
                                                    <option value="date_format">{{__('business_settings.date_format')}}</option>
                                                    <option value="time_format">{{__('business_settings.time_format')}}</option>
                                                    <option value="currency_position">{{__('business_settings.currency_position')}}</option>
                                                    <option value="quantity_precision">{{__('business_settings.quantity_precision')}}</option>
                                                    <option value="currency_rounded_method">{{__('business_settings.currency_rounded_method')}}</option>
                                                    <option value="quantity_rounded_method">{{__('business_settings.quantity_rounded_method')}}</option>
                                                    <option value=""></option>

                                                    {{-- tax --}}
                                                    <option value="tax_1_name">{{__('business_settings.tax_1_name')}}</option>
                                                    <option value="tax_1_no">{{__('business_settings.tax_1_no')}}</option>
                                                    <option value="tax_2_name">{{__('business_settings.tax_2_name')}}</option>
                                                    <option value="tax_2_no">{{__('business_settings.tax_2_no')}}</option>
                                                    <option value="enable_inline_tax_in_purchase_and_sell">
                                                        {{__('business_settings.enable_inline_tax_in_purchase_and_sell')}}</option>

                                                    {{-- Product --}}

                                                    <option value="sku_prefix">{{__('business_settings.sku_prefix')}}</option>
                                                    <option value=""></option>
                                                    <option value="enable_product_expiry_check">{{__('business_settings.enable_product_expiry')}}</option>
                                                    <option value="enable_brands">{{__('business_settings.enable_brands')}}</option>
                                                    <option value="enable_categories">{{__('business_settings.enable_categories')}}</option>
                                                    <option value="enable_price_and_tax_info">{{__('business_settings.enable_categories')}}</option>
                                                    <option value="default_units">{{__('business_settings.default_units')}}</option>
                                                    <option value="enable_sub_unit">{{__('business_settings.enable_sub_unit')}}</option>
                                                    <option value="enable_racks">{{__('business_settings.enable_racks')}}</option>
                                                    <option value="enable_position">{{__('business_settings.enable_position')}}</option>
                                                    <option value="enable_warranty">{{__('business_settings.enable_warranty')}}</option>
                                                    <option value="enable_secondary_unit">{{__('business_settings.enable_secondary_unit')}}</option>

                                                    {{-- CONTACT --}}
                                                    <option value="default_credit_limit">{{__('business_settings.default_credit_limit')}}</option>

                                                    {{-- sale --}}
                                                    <option value="enable_line_discount_for_sale">{{__('business_settings.enable_line_discount_for_sale')}}
                                                    </option>
                                                    <option value="default_sale_discount">{{__('business_settings.default_sale_discount')}}</option>
                                                    <option value="default_sale_tax">{{__('business_settings.default_sale_tax')}}</option>
                                                    <option value="sales_item_addition_method">{{__('business_settings.sales_item_addition_method')}}
                                                    </option>
                                                    <option value="amount_rounding_method">{{__('business_settings.amount_rounding_method')}}</option>
                                                    <option value="minimun_selling_price">{{__('business_settings.sales_price_is_minimum_selling_price')}}
                                                    </option>
                                                    <option value="allow_overselling">{{__('business_settings.allow_overselling')}}</option>
                                                    <option value="enable_sale_order">{{__('business_settings.enable_sales_order')}}</option>
                                                    <option value="pay_required">{{__('business_settings.is_pay_term_required')}}</option>
                                                    <option value="sales_commission_agent">{{__('business_settings.sales_commission_agent')}}</option>
                                                    <option value="commission_calc_type">{{__('business_settings.commission_calculation_type')}}</option>
                                                    <option value="commission_agent_required">{{__('business_settings.is_commission_agent_required')}}
                                                    </option>
                                                    <option value="enable_payment_link">{{__('business_settings.enable_payment_link')}}</option>
                                                    <option value="razorpay_key_id">{{__('business_settings.razorpay_key_id')}}</option>
                                                    <option value="razorpay_key_secret">{{__('business_settings.razorapay_key_secret')}}</option>
                                                    <option value="stripe_public_key">{{__('business_settings.stripe_public_key')}}</option>
                                                    <option value="stripe_secret_key">{{__('business_settings.stripe_secret_key')}}</option>


                                                    {{-- pos --}}
                                                    <option value="express_checkout">{{__('business_settings.express_checkout')}}</option>
                                                    <option value="pay_checkout">{{__('business_settings.pay_and_checkout')}}</option>
                                                    <option value="draft">{{__('business_settings.pos_draft')}}</option>
                                                    <option value="cancle">{{__('business_settings.cancel')}}</option>
                                                    <option value="go_product_qt">{{__('business_settings.go_to_product_quantity')}}</option>
                                                    <option value="weighing_scale">{{__('business_settings.weighing_scale')}}</option>
                                                    <option value="edit_order_tax">{{__('business_settings.edit_order_tax')}}</option>
                                                    <option value="add_payment_row">{{__('business_settings.add_payment_row')}}</option>
                                                    <option value="finalize_payment">{{__('business_settings.finalize_payment')}}</option>
                                                    <option value="add_new_product">{{__('business_settings.add_new_product')}}</option>
                                                    <option value="disable_mulitple_pay">{{__('business_settings.disable_multiple_pay')}}</option>
                                                    <option value="disable_draft">{{__('business_settings.display_draft')}}</option>
                                                    <option value="disable_express_checkout">{{__('business_settings.display_express_checkout')}}</option>
                                                    <option value="show_product_suggestion">{{__('business_settings.dont_show_product_suggestion')}}
                                                    </option>
                                                    <option value="recent_transactions">{{__('business_settings.dont_show_recent_transactions')}}</option>
                                                    <option value="disable_discount">{{__('business_settings.disable_discount')}}</option>
                                                    <option value="disable_order_tax">{{__('business_settings.disable_order_tax')}}</option>
                                                    <option value="subtotal_editable">{{__('business_settings.subtotal_editable')}}</option>
                                                    <option value="disable_suspend_sale">{{__('business_settings.disable_suspend_sale')}}</option>
                                                    <option value="enable_transaction_date_on_pos_screen">
                                                        {{__('business_settings.enable_transaction_date_on_pos_screen')}}</option>
                                                    <option value="enable_serviece_staff_in_product_line">
                                                        {{__('business_settings.enable_service_staff_in_product_line')}}</option>
                                                    <option value="service_staff_required">{{__('business_settings.is_service_staff_required')}}</option>
                                                    <option value="disable_credit_sale_btn">{{__('business_settings.disable_credit_sale_button')}}</option>
                                                    <option value="enable_weighting_scale">{{__('business_settings.enable_weighing_scale')}}</option>
                                                    <option value="show_invoice_scheme">{{__('business_settings.show_invoice_scheme')}}</option>
                                                    <option value="show_invoice_layout_dropdown">{{__('business_settings.show_invoice_layout_dropdown')}}
                                                    </option>
                                                    <option value="print_invoice_on_suspend">{{__('business_settings.print_invoice_on_suspend')}}</option>
                                                    <option value="procing_on_product_suggestion_tooltip">
                                                        {{__('business_settings.show_pricing_on_product_suggestion_tooltip')}}</option>
                                                    <option value="barcode_perfix">{{__('business_settings.barcode_prefix')}}</option>
                                                    <option value="product_sku_length">{{__('business_settings.barcode_product_sku_length')}}</option>
                                                    <option value="quantity_interger_part_length">{{__('business_settings.quantity_integer_part_length')}}
                                                    </option>
                                                    <option value="quantity_fractional_part_length">
                                                        {{__('business_settings.quantity_fractional_part_length')}}</option>
                                                    <option value="enable_line_discount_for_purchase">{{__('business_settings.enable_line_discount')}}
                                                    </option>
                                                    <option value="enable_editing_product_price_from_purchase_screen">
                                                        {{__('business_settings.enable_editing_product_price_from_purchase_screen')}}</option>
                                                    <option value="enable_pruchase_status">{{__('business_settings.enable_purchase_status')}}</option>
                                                    <option value="enable_lot_number">{{__('business_settings.enable_lot_number')}}</option>
                                                    <option value="enable_purchase_order">{{__('business_settings.enable_purchase_order')}}</option>


                                                    {{-- payment Tab --}}
                                                    <option value="cash_denominations">{{__('business_settings.enable_cash_denomination_on')}}</option>
                                                    <option value="enable_cash_denomination_for_payment_methods">
                                                        {{__('business_settings.enable_cash_denomination_for_payment_methods')}}</option>
                                                    <option value="strict_check">{{__('business_settings.strict_check')}}</option>

                                                    {{-- dahsboard --}}
                                                    <option value="view_storck_expiry_alert_for">{{__('business_settings.view_stock_expiry_alert_for')}}
                                                    </option>
                                                    {{-- system --}}
                                                    <option value="theme_color">{{__('business_settings.theme_color')}}</option>
                                                    <option value="default_page_entries">{{__('business_settings.default_datatable_page_entries')}}</option>
                                                    <option value="show_help_text">{{__('business_settings.show_help_text')}}</option>


                                                    {{-- prefix --}}
                                                    <option value="purchase_return">{{__('business_settings.purchase_return')}}</option>
                                                    <option value="purchase_order">{{__('business_settings.purchase_order')}}</option>
                                                    <option value="stock_transfer">{{__('business_settings.stock_transfer')}}</option>
                                                    <option value="stock_adjustment">{{__('business_settings.stock_adjustment')}}</option>
                                                    <option value="sell_return">{{__('business_settings.sell_return')}}</option>
                                                    <option value="expenses">{{__('business_settings.expenses')}}</option>
                                                    <option value="contacts">{{__('business_settings.contacts')}}</option>
                                                    <option value="purchase_payment">{{__('business_settings.purchase_payment')}}</option>
                                                    <option value="sell_payment">{{__('business_settings.sell_payment')}}</option>
                                                    <option value="expense_payment">{{__('business_settings.expense_payment')}}</option>
                                                    <option value="business_location">{{__('business_settings.business_location')}}</option>
                                                    <option value="perfixes_username">{{__('business_settings.prefix_username')}}</option>
                                                    <option value="subscription_no">{{__('business_settings.subscription_no')}}</option>
                                                    <option value="perfixes_draft">{{__('business_settings.prefix_draft')}}</option>
                                                    <option value="sales_order">{{__('business_settings.sales_order')}}</option>


                                                    {{-- email setting --}}

                                                    <option value="mail_driver">{{__('business_settings.mail_driver')}}</option>
                                                    <option value="mail_host">{{__('business_settings.mail_host')}}</option>
                                                    <option value="mail_port">{{__('business_settings.mail_port')}}</option>
                                                    <option value="mail_username">{{__('business_settings.mail_username')}}</option>
                                                    <option value="mail_password">{{__('business_settings.mail_password')}}</option>
                                                    <option value="mail_encryption">{{__('business_settings.mail_encryption')}}</option>
                                                    <option value="mail_form_address">{{__('business_settings.mail_address_from')}}</option>
                                                    <option value="mail_from_name">{{__('business_settings.mail_name_from')}}</option>


                                                    {{-- sms --}}

                                                    <option value="sms_service">{{__('business_settings.sms_service')}}</option>
                                                    <option value="sms_url">{{__('business_settings.sms_url')}}</option>
                                                    <option value="parameter_name">{{__('business_settings.send_to_parameter_name')}}</option>
                                                    <option value="message_para_name">{{__('business_settings.message_parameter_name')}}</option>
                                                    <option value="request_method">{{__('business_settings.request_method_for_sms')}}</option>
                                                    <option value="header_1_key">{{__('business_settings.header_1_key')}}</option>
                                                    <option value="header_1_value">{{__('business_settings.header_1_value')}}</option>
                                                    <option value="header_2_key">{{__('business_settings.header_2_key')}}</option>
                                                    <option value="header_2_value">{{__('business_settings.header_2_value')}}</option>
                                                    <option value="header_3_key">{{__('business_settings.header_3_key')}}</option>
                                                    <option value="header_3_value">{{__('business_settings.header_3_value')}}</option>

                                                    <option value="parameter_1_value">{{__('business_settings.parameter_1_value')}}</option>
                                                    <option value="parameter_1_key">{{__('business_settings.parameter_1_key')}}</option>

                                                    <option value="parameter_2_value">{{__('business_settings.parameter_2_value')}}</option>
                                                    <option value="parameter_2_key">{{__('business_settings.parameter_2_key')}}</option>

                                                    <option value="parameter_3_value">{{__('business_settings.parameter_3_value')}}</option>
                                                    <option value="parameter_3_key">{{__('business_settings.parameter_3_key')}}</option>

                                                    <option value="parameter_4_value">{{__('business_settings.parameter_4_value')}}</option>
                                                    <option value="parameter_4_key">{{__('business_settings.parameter_4_key')}}</option>

                                                    <option value="parameter_5_value">{{__('business_settings.parameter_5_value')}}</option>
                                                    <option value="parameter_5_key">{{__('business_settings.parameter_5_key')}}</option>

                                                    <option value="parameter_6_value">{{__('business_settings.parameter_6_value')}}</option>
                                                    <option value="parameter_6_key">{{__('business_settings.parameter_6_key')}}</option>

                                                    <option value="parameter_7_value">{{__('business_settings.parameter_7_value')}}</option>
                                                    <option value="parameter_7_key">{{__('business_settings.parameter_7_key')}}</option>

                                                    <option value="parameter_8_value">{{__('business_settings.parameter_8_value')}}</option>
                                                    <option value="parameter_8_key">{{__('business_settings.parameter_8_key')}}</option>

                                                    <option value="parameter_9_value">{{__('business_settings.parameter_9_value')}}</option>
                                                    <option value="parameter_9_key">{{__('business_settings.parameter_9_key')}}</option>

                                                    <option value="parameter_10_value">{{__('business_settings.parameter_10_value')}}</option>
                                                    <option value="parameter_10_key">{{__('business_settings.parameter_10_key')}}</option>

                                                    <option value="nexmo_key">{{__('business_settings.nexmo_key')}}</option>
                                                    <option value="nexmo_secret">{{__('business_settings.nexmo_secret')}}</option>
                                                    <option value="nexmo_from">{{__('business_settings.from_nexmo')}}</option>

                                                    <option value="twilio_key">{{__('business_settings.twilio_key')}}</option>
                                                    <option value="twilio_secret">{{__('business_settings.twilio_secret')}}</option>
                                                    <option value="twilio_from">{{__('business_settings.from_twilio')}}</option>


                                                    {{-- reward Point --}}

                                                    <option value="enable_reward_point">{{__('business_settings.enable_reward_point')}}</option>
                                                    <option value="reward_point_display_name">{{__('business_settings.reward_point_display_name')}}</option>
                                                    <option value="amount_spend_for_unit_point">{{__('business_settings.amount_spend_for_unit_point')}}
                                                    </option>
                                                    <option value="minimun_order_total_to_earn_reward">
                                                        {{__('business_settings.minimum_order_total_to_earn_reward')}}</option>
                                                    <option value="maximum_points_per_order">{{__('business_settings.maximum_points_per_order')}}</option>

                                                    <option value="redeem_amount_per_unit_point">{{__('business_settings.redeem_amount_per_unit_point')}}
                                                    </option>
                                                    <option value="minimun_order_total_to_redeem_reward">
                                                        {{__('business_settings.minimum_order_total_to_redeem_points')}}</option>
                                                    <option value="minium_redeem_point">{{__('business_settings.minimum_redeem_point')}}</option>
                                                    <option value="maximum_redeem_point_per_order">
                                                        {{__('business_settings.maximum_redeem_point_per_order')}}</option>
                                                    <option value="reward_point_expiry_period">{{__('business_settings.reward_point_expiry_period')}}
                                                    </option>

                                                    <option value="module_purchases">{{__('business_settings.purchases')}}</option>
                                                    <option value="module_add_sale">{{__('business_settings.add_sale')}}</option>
                                                    <option value="module_pos">{{__('business_settings.pos')}}</option>
                                                    <option value="module_stock_transfers">{{__('business_settings.stock_transfers')}}</option>
                                                    <option value="modulse_stock_adjustment">{{__('business_settings.stock_adjustment')}}</option>
                                                    <option value="module_expenses">{{__('business_settings.expenses_module')}}</option>
                                                    <option value="module_account">{{__('business_settings.account')}}</option>
                                                    <option value="module_table">{{__('business_settings.table')}}</option>
                                                    <option value="module_modifiers">{{__('business_settings.modifiers')}}</option>
                                                    <option value="module_service_staff">{{__('business_settings.service_staff')}}</option>
                                                    <option value="modulse_enable_bookings">{{__('business_settings.enable_bookings')}}</option>
                                                    <option value="module_order_display_restaurants">{{__('business_settings.order_for_restaurants')}}
                                                    </option>
                                                    <option value="module_enable_subscription">{{__('business_settings.enable_subscription')}}</option>
                                                    <option value="module_type_of_service">{{__('business_settings.types_of_service')}}</option>

                                                    {{-- custom label --}}
                                                    <option value="custom_payment_1">{{__('business_settings.custom_payment_1')}}</option>
                                                    <option value="custom_payment_2">{{__('business_settings.custom_payment_2')}}</option>
                                                    <option value="custom_payment_3">{{__('business_settings.custom_payment_3')}}</option>
                                                    <option value="custom_payment_4">{{__('business_settings.custom_payment_4')}}</option>
                                                    <option value="custom_payment_5">{{__('business_settings.custom_payment_5')}}</option>
                                                    <option value="custom_payment_6">{{__('business_settings.custom_payment_6')}}</option>
                                                    <option value="custom_payment_7">{{__('business_settings.custom_payment_7')}}</option>

                                                    <option value="contact_custom_field_1">{{__('business_settings.contact_custom_field_1')}}</option>
                                                    <option value="contact_custom_field_2">{{__('business_settings.contact_custom_field_2')}}</option>
                                                    <option value="contact_custom_field_3">{{__('business_settings.contact_custom_field_3')}}</option>
                                                    <option value="contact_custom_field_4">{{__('business_settings.contact_custom_field_4')}}</option>
                                                    <option value="contact_custom_field_5">{{__('business_settings.contact_custom_field_5')}}</option>
                                                    <option value="contact_custom_field_6">{{__('business_settings.contact_custom_field_6')}}</option>
                                                    <option value="contact_custom_field_7">{{__('business_settings.contact_custom_field_7')}}</option>
                                                    <option value="contact_custom_field_8">{{__('business_settings.contact_custom_field_8')}}</option>
                                                    <option value="contact_custom_field_9">{{__('business_settings.contact_custom_field_9')}}</option>
                                                    <option value="contact_custom_field_10">{{__('business_settings.contact_custom_field_10')}}</option>

                                                    <option value="product_custom_field_1">{{__('business_settings.product_custom_field_1')}}</option>
                                                    <option value="product_custom_field_2">{{__('business_settings.product_custom_field_2')}}</option>
                                                    <option value="product_custom_field_3">{{__('business_settings.product_custom_field_3')}}</option>
                                                    <option value="product_custom_field_4">{{__('business_settings.product_custom_field_4')}}</option>


                                                    <option value="location_custom_field_1">{{__('business_settings.location_custom_field_1')}}</option>
                                                    <option value="location_custom_field_2">{{__('business_settings.location_custom_field_2')}}</option>
                                                    <option value="location_custom_field_3">{{__('business_settings.location_custom_field_3')}}</option>
                                                    <option value="location_custom_field_4">{{__('business_settings.location_custom_field_4')}}</option>

                                                    <option value="purchase_shipping_customer_field_1">
                                                        {{__('business_settings.purchase_shipping_custom_field_1')}}</option>
                                                    <option value="purchase_shipping_customer_field_2">
                                                        {{__('business_settings.purchase_shipping_custom_field_2')}}</option>
                                                    <option value="purchase_shipping_customer_field_3">
                                                        {{__('business_settings.purchase_shipping_custom_field_3')}}</option>
                                                    <option value="purchase_shipping_customer_field_4">
                                                        {{__('business_settings.purchase_shipping_custom_field_4')}}</option>
                                                    <option value="purchase_shipping_customer_field_5">
                                                        {{__('business_settings.purchase_shipping_custom_field_5')}}</option>

                                                    <option value="sell_custom_field_1">{{__('business_settings.sell_custom_field_1')}}</option>
                                                    <option value="sell_custom_field_2">{{__('business_settings.sell_custom_field_2')}}</option>
                                                    <option value="sell_custom_field_3">{{__('business_settings.sell_custom_field_3')}}</option>
                                                    <option value="sell_custom_field_4">{{__('business_settings.sell_custom_field_4')}}</option>

                                                    <option value="sale_shipping_custom_field_1">{{__('business_settings.sale_shipping_custom_field_1')}}
                                                    </option>
                                                    <option value="sale_shipping_custom_field_2">{{__('business_settings.sale_shipping_custom_field_2')}}
                                                    </option>
                                                    <option value="sale_shipping_custom_field_3">{{__('business_settings.sale_shipping_custom_field_3')}}
                                                    </option>
                                                    <option value="sale_shipping_custom_field_4">{{__('business_settings.sale_shipping_custom_field_4')}}
                                                    </option>
                                                    <option value="sale_shipping_custom_field_5">{{__('business_settings.sale_shipping_custom_field_5')}}
                                                    </option>

                                                    <option value="types_of_service_custom_1">{{__('business_settings.service_custom_payment_1')}}</option>
                                                    <option value="types_of_service_custom_2">{{__('business_settings.service_custom_payment_2')}}</option>
                                                    <option value="types_of_service_custom_3">{{__('business_settings.service_custom_payment_3')}}</option>
                                                    <option value="types_of_service_custom_4">{{__('business_settings.service_custom_payment_4')}}</option>
                                                    <option value="types_of_service_custom_5">{{__('business_settings.service_custom_payment_5')}}</option>
                                                    <option value="types_of_service_custom_6">{{__('business_settings.service_custom_payment_6')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content " id="myTabContent" >
                                    {{-- business-tab --}}
                                    <div class="tab-pane setting-tab fade active show" id="business_setting_business" role="tabpanel">

                                            <x-setting.setting-row
                                                :firstLabel="__('business_settings.business_name')" firstFor="business_name"
                                                :secLabel="__('business_settings.owner_name')" secFor="owner_name"
                                            >
                                                <x-slot:firstInput>
                                                    <x-forms.input :value="$settingData['name']" id="business_name" name="name"></x-forms.input>
                                                </x-slot>
                                                <x-slot:secInput>
                                                    <x-forms.input :value="$settingData->owner? $settingData->owner->username :''"  id="owner_id" name="owner_id" placeholder=""></x-forms.input>
                                                </x-slot>
                                            </x-setting.setting-row>

                                            <x-setting.setting-row
                                                :firstLabel="__('business_settings.upload_logo')" firstFor="update_logo"
                                                :secLabel="__('business_settings.start_date')" secFor="kt_datepicker_1"
                                            >
                                                <x-slot:firstInput>
                                                    <div class="input-group browseLogo input-group-sm">
                                                        <input type="file" class="form-control form-control form-control form-control-sm" id="update_logo" name="logo"
                                                            value="">
                                                        <button type="button" class="btn btn-sm btn-danger d-none" id="removeFileBtn"><i
                                                                class="fa-solid fa-trash"></i></button>
                                                        <label class="input-group-text btn btn-primary rounded-end" for="update_logo">
                                                            {{__('business_settings.browses')}}
                                                            <i class="fa-regular fa-folder-open"></i>
                                                        </label>
                                                    </div>
                                                </x-slot>
                                                <x-slot:secInput>
                                                    <x-forms.input :value="date('d-m-Y')"  id="kt_datepicker_1" name="start_date" placeholder="Pick a date" ></x-forms.input>
                                                </x-slot>
                                            </x-setting.setting-row>

                                            <x-setting.setting-row
                                                :firstLabel="__('business_settings.default_profit_percent')" firstFor="default_profit_percent"
                                                :secLabel="__('business_settings.default_currency')" secFor="default_currency"
                                            >
                                                <x-slot:firstInput>
                                                    <x-forms.input :value="$settingData['default_profit_percent']" id="default_profit_percent" name="default_profit_percent"></x-forms.input>
                                                </x-slot>
                                                <x-slot:secInput>
                                                    <div class="input-group flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <x-forms.nob-select name="currency_id" id="currency" placeholder="Select Currency">
                                                                <option></option>
                                                                @foreach ($currencies as $c)
                                                                    <option value="{{$c->id}}" @selected($settingData['currency_id']==$c->id)>{{$c->name}} ({{$c->symbol}})
                                                                    </option>
                                                                @endforeach
                                                            </x-forms.nob-select>
                                                        </div>
                                                    </div>
                                                </x-slot>
                                            </x-setting.setting-row>

                                            <x-setting.setting-row
                                                :firstLabel="__('business_settings.currency_symbol_placement')" firstFor="currency_symbol_placement"
                                                :secLabel="__('business_settings.time_zone')" secFor="time_zone"
                                            >
                                                <x-slot:firstInput>
                                                    <x-forms.nob-select name="currency_symbol_placement" id="currency_symbol_placement" placeholder="Currency Symbol Placement">
                                                        <option value="before" @selected($settingData['currency_symbol_placement']=='before')>Before Amount</option>
                                                        <option value="after" @selected($settingData['currency_symbol_placement']=='after')>After Amount</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                                <x-slot:secInput>
                                                    <x-forms.nob-select name="time_zone" id="time_zone" placeholder="Time Zone">
                                                       <option value="1">Asia/Yangon</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                            </x-setting.setting-row>

                                            <x-setting.setting-row
                                                :firstLabel="__('business_settings.financial_year_start_month')" firstFor="finanical_year_start_month"
                                                :secLabel="__('business_settings.stock_accounting_method')" secFor="stock_accounting_method"
                                            >
                                                <x-slot:firstInput>
                                                    <x-forms.nob-select name="finanical_year_start_month" id="finanical_year_start_month" placeholder="__('business_settings.financial_year_start_month')">
                                                                <option value="january" @selected($settingData['finanical_year_start_month']==='january' )>January</option>
                                                                <option value="february" @selected($settingData['finanical_year_start_month']==='february' )>February</option>
                                                                <option value="march" @selected($settingData['finanical_year_start_month']==='march' )>March</option>
                                                                <option value="april" @selected($settingData['finanical_year_start_month']==='april' )>April</option>
                                                                <option value="may" @selected($settingData['finanical_year_start_month']==='may' )>May</option>
                                                                <option value="june" @selected($settingData['finanical_year_start_month']==='june' )>June</option>
                                                                <option value="july" @selected($settingData['finanical_year_start_month']==='july' )>July</option>
                                                                <option value="august" @selected($settingData['finanical_year_start_month']==='august' )>August</option>
                                                                <option value="september" @selected($settingData['finanical_year_start_month']==='september' )>September</option>
                                                                <option value="october" @selected($settingData['finanical_year_start_month']==='october' )>October</option>
                                                                <option value="november" @selected($settingData['finanical_year_start_month']==='november' )>November</option>
                                                                <option value="december" @selected($settingData['finanical_year_start_month']==='december' )>December</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                                <x-slot:secInput>
                                                    <x-forms.nob-select name="stock_accounting_method" id="stock_accounting_method" :placeholder="__('business_settings.stock_accounting_method')" attr='data-hide-search="true"'>
                                                        <option value="fifo" @selected($settingData->accounting_method=='fifo')>FIFO (First In First Out)</option>
                                                        <option value="lifo" @selected($settingData->accounting_method=='lifo')>LIFO (Last In First Out)</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                            </x-setting.setting-row>

                                            <x-setting.setting-row
                                                :firstLabel="__('business_settings.transaction_edit_days')" firstFor="transaction_edit_days"
                                                :secLabel="__('business_settings.date_format')" secFor="date_format"
                                            >
                                                <x-slot:firstInput>
                                                    <x-forms.input :value="$settingData['transaction_edit_days']" id="transaction_edit_days"
                                                        name="transaction_edit_days" :placeholder="__('business_settings.transaction_edit_days')">
                                                    </x-forms.input>
                                                </x-slot>
                                                <x-slot:secInput>
                                                    <x-forms.nob-select name="date_format" id="date_format" :placeholder="__('business_settings.date_format')" >
                                                        <option value="d-m-y">dd-mm-yyyy</option>
                                                        <option value="m-d-y">mm-dd-yyyy</option>
                                                        <option value="d/m/y">dd/mm/yyyy</option>
                                                        <option value="m/d/y">mm/dd/yyyy</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                            </x-setting.setting-row>

                                            <x-setting.setting-row
                                                :firstLabel="__('business_settings.time_format')" firstFor="time_format"
                                                :secLabel="__('business_settings.currency_decimal_places')" secFor="currency_decimal_places"
                                            >
                                                <x-slot:firstInput>
                                                    <x-forms.nob-select name="time_format" id="time_format" :placeholder="__('business_settings.time_format')" >
                                                        <option value="24">24 hours</option>
                                                        <option value="12">12 hours</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                                <x-slot:secInput>
                                                    <x-forms.nob-select name="currency_decimal_places" id="currency_decimal_places" :placeholder="__('business_settings.currency_decimal_places')" >
                                                        <option value="0" @selected($settingData['currency_decimal_places'] == 0)>0</option>
                                                        <option value="1" @selected($settingData['currency_decimal_places'] == 1)>1</option>
                                                        <option value="2" @selected($settingData['currency_decimal_places'] == 2)>2</option>
                                                        <option value="3" @selected($settingData['currency_decimal_places'] == 3)>3</option>
                                                        <option value="4" @selected($settingData['currency_decimal_places'] == 4)>4</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                            </x-setting.setting-row>

                                            <x-setting.setting-row
                                                :firstLabel="__('business_settings.quantity_decimal_places')" firstFor="quantity_decimal_places"
                                                :secLabel="__('business_settings.currency_rounded_method')" secFor="currency_rounded_method"
                                            >
                                                <x-slot:firstInput>
                                                    <x-forms.nob-select name="quantity_decimal_places" id="quantity_decimal_places" :placeholder="__('business_settings.quantity_decimal_places')" >
                                                        <option value="0" @selected($settingData['quantity_decimal_places'] == 0)>0</option>
                                                        <option value="1" @selected($settingData['quantity_decimal_places'] == 1)>1</option>
                                                        <option value="2" @selected($settingData['quantity_decimal_places'] == 2)>2</option>
                                                        <option value="3" @selected($settingData['quantity_decimal_places'] == 3)>3</option>
                                                        <option value="4" @selected($settingData['quantity_decimal_places'] == 4)>4</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                                <x-slot:secInput>
                                                    <x-forms.nob-select name="currency_rounded_method" id="currency_rounded_method" :placeholder="__('business_settings.quantity_decimal_places')" >
                                                        <option value="0" @selected($settingData['currency_rounded_method']==0)>RoundUp</option>
                                                        <option value="1" @selected($settingData['currency_rounded_method']==1)>RoundDown</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                            </x-setting.setting-row>


                                            <x-setting.setting-row
                                                :firstLabel="__('business_settings.quantity_rounded_method')" firstFor="quantity_rounded_method"
                                            >
                                                <x-slot:firstInput>
                                                    <x-forms.nob-select name="quantity_rounded_method" id="quantity_rounded_method" :placeholder="__('business_settings.quantity_rounded_method')" >
                                                        <option value="0" @selected($settingData['quantity_decimal_places']==0)>RoundUp</option>
                                                        <option value="1" @selected($settingData['quantity_decimal_places']==1)>RoundDown</option>
                                                    </x-forms.nob-select>
                                                </x-slot>
                                            </x-setting.setting-row>
                                        <!--end::Form-->
                                    </div>

                                    {{-- tax-tab --}}
                                    {{-- <div class="tab-pane setting-tab fade" id="business_setting_tax" role="tabpanel">
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="tax_1_name">
                                                        <span class="required">{{__('business_settings.tax_1_name')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control form-control form-control-sm" name="tax_1_name" id="tax_1_name" value="" placeholder="GST/VAT/OTHER" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="tax_1_no">
                                                            <span class="required">{{__('business_settings.tax_1_no')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="number" class="form-control form-control form-control form-control-sm" id="tax_1_no" name="tax_1_no" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="tax_2_name">
                                                        <span class="required">{{__('business_settings.tax_2_name')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control form-control form-control-sm" id="tax_2_name" name="tax_2_name" value="" placeholder="GST/VAT/OTHER" />
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols ">
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="tax_2_no">
                                                            <span class="required">{{__('business_settings.tax_2_no')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="number" class="form-control form-control form-control form-control-sm" id="tax_2_no" name="tax_2_no" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 col-lg-4 d-flex align-items-center">
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="" id="enable_inline_tax_in_purchase_and_sell">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_inline_tax_in_purchase_and_sell">
                                                            <span >{{__('business_settings.enable_inline_tax_in_purchase_and_sell')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                    </div> --}}

                                    {{-- product-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_product" role="tabpanel">


                                            <div class="row fv-row row-cols flex-wrap">
                                                <x-setting.setting-row
                                                    :firstLabel="__('business_settings.keep_selling_on_expired')" firstFor="flexSwitchCheckDefault"
                                                    :secLabel="__('business_settings.stop_selling_n_day_before')" secFor="kt_datepicker_1"
                                                >
                                                    <x-slot:firstInput>
                                                        <div class="form-check form-switch ">
                                                            <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                        </div>
                                                    </x-slot>
                                                    <x-slot:secInput>
                                                            <x-forms.input :placeholder="__('business_settings.stop_selling_n_day_before')"> </x-forms.input>
                                                    </x-slot>
                                                </x-setting.setting-row>

                                                <x-setting.setting-row
                                                    :firstLabel="__('business_settings.default_purchase_uom')" firstFor="default_purchase_uom"
                                                    :secLabel="__('business_settings.default_sale_uom')" secFor="default_sale_uom"
                                                >
                                                    <x-slot:firstInput>
                                                        <x-forms.nob-select name="default_purchase_uom" id="default_purchase_uom"
                                                            placeholder="__('business_settings.default_purchase_uom')">
                                                           <option value=" ">{{__('business_settings.please_select')}}</option>
                                                            <option value="2">Kg</option>
                                                        </x-forms.nob-select>
                                                    </x-slot>
                                                    <x-slot:secInput>
                                                        <x-forms.nob-select name="default_sale_uom" id="default_sale_uom"
                                                            placeholder="__('business_settings.default_sale_uom')">
                                                           <option value=" ">{{__('business_settings.please_select')}}</option>
                                                            <option value="2">Kg</option>
                                                        </x-forms.nob-select>
                                                    </x-slot>
                                                </x-setting.setting-row>
                                            </div>
                                            {{-- <div class="row row-cols">
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_racks" id="enable_racks">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_racks">
                                                            <span >{{__('business_settings.enable_racks')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_row" id="enable_row">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_row">
                                                            <span >{{__('business_settings.enable_row')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_position" id="enable_position">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_position">
                                                            <span >{{__('business_settings.enable_position')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 border-gray-400 me-3" name="enable_warranty" id="enable_warranty">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_warranty">
                                                            <span >{{__('business_settings.enable_warranty')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_secondary_unit" id="enable_secondary_unit">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_secondary_unit">
                                                            <span >{{__('business_settings.enable_secondary_unit')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> --}}

                                    </div>

                                    {{-- contact-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_contact" role="tabpanel">
                                         <!--begin::Heading-->

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="default_credit_limit">
                                                        <span class="required" >{{__('business_settings.default_credit_limit')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                      <input type="text" class="form-control form-control-sm" id="default_credit_limit" placeholder="Default credit limit">
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="default_credit_limit">
                                                        <span class="required" >{{__('business_settings.default_pay_term')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <div class="input-group" tabindex="-1">
                                                        <input type="text" class="form-control form-control-sm">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select class="form-select form-select-sm rounded-start-0" data-control="select2" data-placeholder="type">
                                                                <option></option>
                                                                <option value="1">Day</option>
                                                                <option value="1">Month</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                    </div>

                                    {{-- sale-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_sale" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <div class="form-check form-switch ">
                                                        <input class="form-check-input cursor-pointer" type="checkbox" name="enable_row" value="1" role="switch" id="enable_row" @checked($settingData['enable_row']) >
                                                        <label class="form-check-label text-gray-800" for="enable_row">{{__('business_settings.enable_sale_item_in_new_row')}}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <div class="form-check form-switch ">
                                                        <input class="form-check-input cursor-pointer" type="checkbox" name="allow_overselling" role="switch" id="flexSwitchCheckDefault" @checked($settingData['allow_overselling'])>
                                                        <label class="form-check-label text-gray-800" for="flexSwitchCheckDefault">{{__('business_settings.allow_overselling')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input @checked($settingData['enable_line_discount_for_sale']=='1')  type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_line_discount_for_sale" id="enable_line_discount_for_sale" value="1">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_line_discount_for_sale">
                                                        <span>{{__('business_settings.enable_line_discount')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="pay_required" id="pay_required">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="pay_required">
                                                            <span >{{__('business_settings.is_pay_term_required')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>

                                    {{-- pos-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_pos" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row row-cols">
                                                <div class="col-12">
                                                    <h3 class="fw-lighter fs-2 text-primary">
                                                        {{__('business_settings.add_keyboard_shortcuts')}}
                                                    </h3>
                                                    <h2 class="text-gray-600 mt-5 text-center">
                                                        Comming Soon......
                                                    </h2>
                                                    <p class="text-gray-600 py-2 fs-6 d-none">
                                                        {{__('business_settings.shortcut_should_be_the_names_of_the_keys_separated_by')}} <b>'+'</b>; {{__('business_settings.example')}}: <b>ctrl+shift+b, ctrl+h</b>
                                                    </p>
                                                    <div class="help-class d-none">
                                                        <h4 class=" text-gray-600 d-block fs-3">{{__('business_settings.available_key_names_are')}}:</h4>
                                                        <p class="fs-6 text-gray-600">
                                                            shift, ctrl, alt, backspace, tab, enter, return, capslock, esc, escape, space, pageup, pagedown, end, home,
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row  flex-wrap mt-4 mb-7 d-none">
                                                {{-- left setting --}}
                                                <div class="col-12 col-lg-6 px-5">
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                                <span class="fw-bold text-primary">{{__('business_settings.operations')}}</span>
                                                            </label>
                                                        </div>
                                                         <div class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" >
                                                                <span class="fw-blod text-primary">{{__('business_settings.keyboard_shortcut')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="express_checkout">
                                                                <span class="">{{__('business_settings.express_checkout')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="express_checkout" value="shift+e" id="express_checkout" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="pay_checkout">
                                                                <span class="">{{__('business_settings.pay_and_checkout')}}</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="pay_checkout" value="shift+p" id="pay_checkout" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="draft">
                                                                <span class="">{{__('business_settings.draft')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="draft" value="shift+d" id="draft" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="cancle">
                                                                <span class="">{{__('business_settings.cancel')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="cancle" value="shift+c" id="cancle" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="go_product_qt">
                                                                <span class="">{{__('business_settings.go_to_product_quantity')}}:	</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="go_product_qt" value="f2" id="go_product_qt" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="weighing_scale">
                                                                <span class="">{{__('business_settings.weighing_scale')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="weighing_scale_setting" value="" id="weighing_scale" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- right setting --}}
                                                <div class="col-12 col-lg-6 px-5">
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                                <span class="fw-bold text-primary">{{__('business_settings.operations')}}Operations</span>
                                                            </label>
                                                        </div>
                                                         <div class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" >
                                                                <span class="fw-blod text-primary">{{__('business_settings.keyboard_shortcut')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="edit_discount">
                                                                <span class="">{{__('business_settings.edit_discount')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="edit_discount" value="shift+i" id="edit_discount" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="edit_order_tax">
                                                                <span class="">{{__('business_settings.edit_order_tax')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="edit_order_tax" value="shift+t" id="edit_order_tax" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="add_payment_row">
                                                                <span class="">{{__('business_settings.add_payment_row')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="add_payment_row" value="shift+r" id="add_payment_row" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="finalize_payment">
                                                                <span class="">{{__('business_settings.finalize_payment')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="finalize_payment" value="shift+f" id="finalize_payment" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="add_new_product">
                                                                <span class="">{{__('business_settings.add_new_product')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="add_new_product" value="f4" id="add_new_product" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                             <!--begin::Heading-->
                                            <div class="row mb-7 d-none">
                                                <div class="col-md-9 p-3">
                                                    <h2  class="text-primary">{{__('business_settings.pos_settings')}}:</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap d-none">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_mulitple_pay" id="disable_mulitple_pay">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_mulitple_pay">
                                                            <span >{{__('business_settings.disable_multiple_pay')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_draft" id="disable_draft">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_draft">
                                                            <span >{{__('business_settings.disable_draft')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_express_checkout" id="disable_express_checkout">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_express_checkout">
                                                            <span >{{__('business_settings.disable_express_checkout')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap d-none">
                                               <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="show_product_suggestion" id="show_product_suggestion">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="show_product_suggestion">
                                                            <span >{{__('business_settings.')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="recent_transactions" id="recent_transactions">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="recent_transactions">
                                                            <span>{{__('business_settings.dont_show_product_suggestion')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_discount" id="disable_discount">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_discount">
                                                            <span >{{__('business_settings.disable_discount')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap  d-none">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_order_tax" id="disable_order_tax">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_order_tax">
                                                            <span >{{__('business_settings.disable_order_tax')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="subtotal_editable" id="subtotal_editable">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="subtotal_editable">
                                                            <span>{{__('business_settings.subtotal_editable')}}<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Check this to make Subtotal field editable for each product in POS screen"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_suspend_sale" id="disable_suspend_sale">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_suspend_sale">
                                                            <span >{{__('business_settings.disable_suspend_sale')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap  d-none">
                                               <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_transaction_date_on_pos_screen" id="enable_transaction_date_on_pos_screen">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_transaction_date_on_pos_screen">
                                                            <span >{{__('business_settings.enable_transaction_date_on_pos_screen')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_serviece_staff_in_product_line" id="enable_serviece_staff_in_product_line">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_serviece_staff_in_product_line">
                                                            <span >{{__('business_settings.enable_service_staff_in_product_line')}}<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="If enabled different service staffs can be assigned for different products for an order/sale"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap  d-none">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="service_staff_required" id="service_staff_required">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="service_staff_required">
                                                            <span>{{__('business_settings.is_service_staff_required')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_credit_sale_btn" id="disable_credit_sale_btn">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_credit_sale_btn">
                                                            <span>{{__('business_settings.disable_credit_sale_button')}}<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="If enabled credit sale button will be shown in place of Card button on pos screen"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_weighting_scale" id="enable_weighting_scale">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_weighting_scale">
                                                            <span >{{__('business_settings.enable_weighing_scale')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap  d-none">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="show_invoice_scheme" id="show_invoice_scheme">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="show_invoice_scheme">
                                                            <span>{{__('business_settings.show_invoice_scheme')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="show_invoice_layout_dropdown" id="show_invoice_layout_dropdown">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="show_invoice_layout_dropdown">
                                                            <span>{{__('business_settings.show_invoice_layout_dropdown')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="print_invoice_on_suspend" id="print_invoice_on_suspend">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="print_invoice_on_suspend">
                                                            <span>{{__('business_settings.print_invoice_on_suspend')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-7 row-cols flex-wrap  d-none">
                                                <div class="col-12 d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="procing_on_product_suggestion_tooltip" id="procing_on_product_suggestion_tooltip">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="procing_on_product_suggestion_tooltip">
                                                            <span>{{__('business_settings.show_pricing_on_product_suggestion_tooltip')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="separator border-gray-600 my-7 d-none"></div>

                                            <div class="row row-cols d-none">
                                                <div class="col-12">
                                                    <h3 class="fw-lighter fs-2 text-primary">
                                                        {{__('business_settings.weighing_sale_barcode_setting')}}:
                                                    </h3>
                                                    <p class="text-gray-600 py-2 fs-6">
                                                        {{__('business_settings.configure_barcode_as_per_your_weighing_scale')}}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row row-cols d-none">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="barcode_perfix">
                                                        <span class="required">{{__('business_settings.prefix')}}:</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control" id="barcode_perfix">
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="product_sku_length">
                                                        <span class="">{{__('business_settings.product_sku_length')}}:</span>
                                                    </label>
                                                    <select name="product_sku_length" id="product_sku_length" class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">1</option>
                                                        <option value="2" >2</option>
                                                        <option value="3" >3</option>
                                                        <option value="4" >4</option>
                                                        <option value="5" >5</option>
                                                        <option value="6" >6</option>
                                                        <option value="7" >7</option>
                                                        <option value="8" >8</option>
                                                        <option value="9" >9</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                      <label class="fs-6 fw-semibold form-label mt-3" for="quantity_interger_part_length">
                                                            <span class="">{{__('business_settings.quantity_integer_part_length')}}:</span>
                                                        </label>
                                                        <select name="quantity_interger_part_length" class="form-select" data-control="select2" id="quantity_interger_part_length" data-placeholder="">
                                                            <option value="1">1</option>
                                                            <option value="2" >2</option>
                                                            <option value="3" >3</option>
                                                            <option value="4" >4</option>
                                                            <option value="5" >5</option>
                                                        </select>
                                                </div>
                                            </div>
                                            <div class="row row-cols d-none">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="quantity_fractional_part_length">
                                                        <span class="">{{__('business_settings.quantity_fractional_part_length')}}:</span>
                                                    </label>
                                                    <select name="quantity_fractional_part_length" id="quantity_fractional_part_length" class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">1</option>
                                                        <option value="2" >2</option>
                                                        <option value="3" >3</option>
                                                        <option value="4" >4</option>
                                                    </select>
                                                </div>
                                            </div>
                                    </div>

                                    {{-- purchasess-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_purchases" role="tabpanel">
                                         <!--begin::Heading-->

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="lot_control" id="lot_control"
                                                            @checked($settingData['lot_control']=='on' )>
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="lot_control">
                                                            <span>{{__('business_settings.lot_control')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input @checked($settingData['enable_line_discount_for_purchase']=='1')  type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_line_discount_for_purchase" id="enable_line_discount_for_purchase" value="1">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_line_discount_for_purchase">
                                                        <span>{{__('business_settings.enable_line_discount')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_pruchase_status" id="enable_pruchase_status">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_pruchase_status">
                                                            <span>{{__('business_settings.enable_purchase_status')}} <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="On disable all purchases will be marked as item received"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                               <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_lot_number" id="enable_lot_number">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_lot_number">
                                                        <span> {{__('business_settings.enable_lot_number')}}
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="This will enable you to enter Lot number for each purcahse line in purchase screen"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_purchase_order" id="enable_purchase_order">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_purchase_order">
                                                            <span>{{__('business_settings.enable_purchase_order')}}
                                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="
                                                                    A purchase order is a commercial document and first offical offer issued by a buyer
                                                                    to a seller indicating types, quantities, and agreed prices for products of services.
                                                                    It is used to control the purchasing of products and services from external
                                                                    suppliers.Purchase orders can be an essential part of enterprise resource planning
                                                                    system orders.
                                                                "></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                     {{-- payment-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_payment" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="col-md-12 mb-7 col-lg-4">
                                                <div class="form-check form-check-custom">
                                                    <input type="checkbox" class="form-check-input border-gray-400 me-3" name="use_paymentAccount" id="use_payment_account" @checked($settingData['use_paymentAccount']==1)  >
                                                    <label class="fs-6 fw-semibold form-label mt-3 cursor-pointer" for="use_payment_account">
                                                        <span >{{__('business_settings.use_payment_account')}}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            {{-- <div class="row fv-row row-cols flex-wrap">
                                               <div class="col-12">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="cash_denominations">
                                                        <span class="">{{__('business_settings.cash_denominations')}}:</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control" id="cash_denominations">
                                                    <span class="text-gray-700 py-2 d-block">{{__('business_settings.comma_separated_values_example')}}: 100,200,500,2000</span>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12  mb-7  col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="enable_cash_denomination">
                                                        <span class="">{{__('business_settings.enable_cash_denomination_on')}}</span>
                                                    </label>
                                                    <select name="enable_cash_denomination" id="enable_cash_denomination" data-hide-search="true" class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">{{__('business_settings.pos_screen')}}</option>
                                                        <option value="2" >{{__('business_settings.all_screen')}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="enable_cash_denomination_for_payment_methods">
                                                        <span class="">{{__('business_settings.enable_cash_denomination_for_payment_methods')}}:</span>
                                                    </label>
                                                    <select name="enable_cash_denomination_for_payment_methods[]" class="form-select form-select " id="enable_cash_denomination_for_payment_methods" data-control="select2" data-close-on-select="false" data-placeholder="" data-allow-clear="true" multiple="multiple">
                                                        <option></option>
                                                        <option value="cash">{{__('business_settings.cash')}}</option>
                                                        <option value="card" >{{__('business_settings.card')}}</option>
                                                        <option value="cheque" >{{__('business_settings.cheque')}}</option>
                                                        <option value="bank_transfer" >{{__('business_settings.bank_transfer')}}</option>
                                                        <option value="other" >{{__('business_settings.other')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" id="strict_check" name="strict_check">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="strict_check">
                                                            <span>{{__('business_settings.strict_check')}}
                                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="
                                                                    If enabled payment amount must be equal to sum of cash
                                                                    denominations
                                                                "></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> --}}
                                    </div>
                                    {{-- dashobard-tab --}}
                                    <div class="tab-pane setting-tab fade d-none" id="business_setting_dashboard" role="tabpanel">
                                         <!--begin::Heading-->

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="view_storck_expiry_alert_for">
                                                        <span class="required">{{__('business_settings.view_stock_expiry_alert_for')}}:</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                        <input class="form-control form-control" name="view_storck_expiry_alert_for" placeholder="" id="view_storck_expiry_alert_for" value="" />
                                                        <span class="input-group-text">
                                                            {{__('business_settings.day')}}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                    {{-- system-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_system" role="tabpanel">
                                         <!--begin::Heading-->

                                            <div class="row row-cols">
                                                {{-- <div class="col-md-12  mb-7  col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="theme_color">
                                                        <span class="">{{__('business_settings.theme_color')}}</span>
                                                    </label>
                                                    <select name="theme_color" id="theme_color"  class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">Blue</option>
                                                        <option value="2" ></option>
                                                    </select>
                                                </div> --}}
                                                <div class="col-md-12  mb-7  col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="default_page_entries">
                                                        <span class="">{{__('business_settings.default_datatable_page_entries')}}</span>
                                                    </label>
                                                    <select name="default_page_entries" id="default_page_entries"  class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">25</option>
                                                        <option value="2" >50</option>
                                                        <option value="2" >100</option>
                                                        <option value="2" >125</option>

                                                    </select>
                                                </div>

                                            </div>
                                            {{-- <div class="row row-cols">
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" id="show_help_text" name="show_help_text">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="show_help_text">
                                                            <span>{{__('business_settings.show_help_text')}}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> --}}
                                    </div>

                                    {{-- prefix-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_prefix" role="tabpanel">
                                        <div class="row">
                                                <div class="row mb-lg-4 gap-1 gap-lg-5 justify-content-between">
                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between ">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.purchases')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="purchase_prefix" value="{{$settingData['purchase_prefix']}}" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12  col-lg-5 mb-5 d-flex justify-content-between">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.sale')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="sale_prefix" value="{{$settingData['sale_prefix']}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between d-none">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.purchase_return')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="purchase" value="PO" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between d-none">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.purchase_order')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="purchase" value="PO" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between ">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.stock_transfer')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="stock_transfer_prefix" value="{{$settingData['stock_transfer_prefix']}}" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="stock_adjustment_prefix">
                                                                <span class="">{{__('business_settings.stock_adjustment')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="stock_adjustment_prefix" name="stock_adjustment_prefix" value="{{$settingData['stock_adjustment_prefix']}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between d-none">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.sell_return')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="purchase" value="PO" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="expense_prefix">
                                                                <span class="">{{__('business_settings.expenses')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="expense_prefix" name="expense_prefix" value="{{$settingData['expense_prefix']}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="expense_report_prefix">
                                                                <span class="">{{__('business_settings.expense_report')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="expense_report_prefix" name="expense_report_prefix" value="{{$settingData['expense_report_prefix']}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between d-none">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.contacts')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="purchase" value="PO" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase_payment_prefix">
                                                                <span class="">{{__('business_settings.purchase_payment')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase_payment_prefix" name="purchase_payment_prefix" value="{{$settingData['purchase_payment_prefix']}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between ">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="sale_payment_prefix">
                                                                <span class="">{{__('business_settings.sell_payment')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="sale_payment_prefix" name="sale_payment_prefix" value="{{$settingData['sale_payment_prefix']}}" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="expense_payment_prefix">
                                                                <span class="">{{__('business_settings.expense_payment')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="expense_payment_prefix" name="expense_payment_prefix" value="{{$settingData['expense_payment_prefix']}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between ">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="business_location_prefix">
                                                                <span class="">{{__('business_settings.business_location')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="business_location_prefix" value="{{$settingData['business_location_prefix']}}" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between d-none">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.username')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="purchase" value="PO" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between d-none">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.subscription_no')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="purchase" value="PO" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between d-none">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.draft')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="purchase" value="PO" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-lg-5 d-flex mb-5 justify-content-between d-none">
                                                        <div class="label">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                                <span class="">{{__('business_settings.sales_order')}}:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" class="form-control form-control-sm border-left-0 border-top-0 border-right-0 rounded-0"
                                                                id="purchase" name="purchase" value="PO" />
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>

                                    {{-- emailSetting-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_emailSetting" role="tabpanel">

                                        <div class="row fv-row row-cols flex-wrap">
                                            <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_driver">
                                                        <span class="">{{__('business_settings.mail_driver')}}</span>
                                                    </label>
                                                    <select name="mail_driver" data-hide-search="true" id="mail_driver"  class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">{{__('business_settings.smtp')}}</option>
                                                    </select>
                                                </div>
                                            <div class="col-md-12  mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_host">
                                                        <span class="">{{__('business_settings.host')}}:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="mail_host" id="mail_host" value="" placeholder="" />
                                            </div>
                                            <div class="col-md-12 mb-7 col-lg-4">
                                                <!--begin::Label-->
                                                <label class="fs-6 fw-semibold form-label mt-3" for="mail_port">
                                                    <span class="">{{__('business_settings.port')}}:</span>
                                                </label>
                                                <!--end::Label-->
                                                <input type="text" class="form-control form-control" name="mail_port" id="mail_port" value=""  />
                                            </div>
                                        </div>


                                        <div class="row fv-row row-cols flex-wrap">
                                            <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_driver">
                                                        <span class="">{{__('business_settings.username')}}:</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control" id="mail_username">
                                                </div>
                                            <div class="col-md-12  mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_password">
                                                        <span class="">{{__('business_settings.password')}}:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="password" class="form-control form-control" name="mail_password" id="mail_password" value="" placeholder="" />
                                            </div>
                                            <div class="col-md-12 mb-7 col-lg-4">
                                                <!--begin::Label-->
                                                <label class="fs-6 fw-semibold form-label mt-3" for="mail_encryption">
                                                    <span class="">{{__('business_settings.encryption')}}:</span>
                                                </label>
                                                <!--end::Label-->
                                                <input type="text" class="form-control form-control" name="mail_encryption" id="mail_encryption" value="" placeholder="tls/ ssl" />
                                            </div>
                                        </div>


                                        <div class="row fv-row row-cols flex-wrap">
                                            <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_form_address">
                                                        <span class="">{{__('business_settings.from_address')}}:</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control" id="mail_form_address">
                                                </div>
                                            <div class="col-md-12  mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_from_name">
                                                        <span class="">{{__('business_settings.from_name')}}:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="password" class="form-control form-control" name="mail_from_name" id="mail_from_name" value="" placeholder="" />
                                            </div>
                                        </div>
                                        <button class="btn btn-success btn-sm float-end">{{__('business_settings.send_test_mail')}}</button>

                                    </div>

                                    {{-- sms-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_sms" role="tabpanel">
                                         <!--begin::Heading-->

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sms_service">
                                                        <span class="">{{__('business_settings.sms_service')}}</span>
                                                    </label>
                                                    <select name="sms_service" data-hide-search="true" id="sms_service"  class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="smsPOH" selected>SMS POH</option>
                                                        <option value="nexmo">Nexmo</option>
                                                        <option value="twilio" >Twilio</option>
                                                        <option value="other"  >other</option>
                                                    </select>
                                                </div>
                                                <div id="other-tab" class=" d-none">
                                                    <div class="row fv-row row-cols flex-wrap">
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="sms_url">
                                                                <span class="">{{__('business_settings.url')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" id="sms_url" name="sms_url" value="" placeholder="url"/>
                                                        </div>
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_name">
                                                                    <span class="">{{__('business_settings.send_to_parameter_name')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_name" id="parameter_name" value="" placeholder="Send to parameter name" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="message_para_name">
                                                                <span class="">{{__('business_settings.message_parameter_name')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="message_para_name" id="message_para_name" value="" placeholder="Message parameter name" />
                                                        </div>

                                                    </div>
                                                    <div class="row row-cols flex-wrap">
                                                        <div class="col-md-12  mb-7  col-lg-4">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="request_method">
                                                                <span class="">{{__('business_settings.request_method')}}</span>
                                                            </label>
                                                            <select name="request_method" data-hide-search="true" id="request_method"  class="form-select" data-control="select2" data-placeholder="">
                                                                <option value="get">{{__('business_settings.get')}}</option>
                                                                <option value="post" >{{__('business_settings.post')}}</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="separator border-gray-600 my-4"></div>

                                                    <div class="row row-cols flex-wrap">
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="header_1_key">
                                                                    <span class="">{{__('business_settings.header_1_key')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="header_1_key" id="header_1_key" value="" placeholder="Send to parameter name" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="header_1_value">
                                                                <span class="">{{__('business_settings.header_1_value')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="header_1_value" id="header_1_value" value="" placeholder="Message parameter name" />
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols flex-wrap">
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="header_2_key">
                                                                    <span class="">{{__('business_settings.header_2_key')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="header_2_key" id="header_2_key" value="" placeholder="Send to parameter name" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="header_2_value">
                                                                <span class="">{{__('business_settings.header_2_value')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="header_2_value" id="header_2_value" value="" placeholder="Message parameter name" />
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols flex-wrap">
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="header_3_key">
                                                                    <span class="">{{__('business_settings.header_3_key')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="header_3_key" id="header_3_key" value="" placeholder="Send to parameter name" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="header_3_value">
                                                                <span class="">{{__('business_settings.header_3_value')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="header_3_value" id="header_3_value" value="" placeholder="Message parameter name" />
                                                        </div>
                                                    </div>
                                                    <div class="separator border-gray-600 my-4"></div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_1_key">
                                                                        <span class="">{{__('business_settings.parameter_1_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_1_key" id="parameter_1_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_1_value">
                                                                    <span class="">{{__('business_settings.parameter_1_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_1_value" id="parameter_1_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_2_key">
                                                                        <span class="">{{__('business_settings.parameter_2_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_2_key" id="parameter_2_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_2_value">
                                                                    <span class="">{{__('business_settings.parameter_2_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_2_value" id="parameter_2_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_3_key">
                                                                        <span class="">{{__('business_settings.parameter_3_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_3_key" id="parameter_3_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_3_value">
                                                                    <span class="">{{__('business_settings.parameter_3_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_3_value" id="parameter_3_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>

                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_4_key">
                                                                        <span class="">{{__('business_settings.parameter_3_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_4_key" id="parameter_4_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_4_value">
                                                                    <span class="">{{__('business_settings.parameter_4_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_4_value" id="parameter_4_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_5_key">
                                                                        <span class="">{{__('business_settings.parameter_5_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_5_key" id="parameter_5_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_5_value">
                                                                    <span class="">{{__('business_settings.parameter_5_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_5_value" id="parameter_5_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_6_key">
                                                                        <span class="">{{__('business_settings.parameter_6_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_6_key" id="parameter_6_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_6_value">
                                                                    <span class="">{{__('business_settings.parameter_6_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_6_value" id="parameter_6_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>

                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_7_key">
                                                                        <span class="">{{__('business_settings.parameter_7_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_7_key" id="parameter_7_key" value="" placeholder="Parameter 7 key" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_7_value">
                                                                    <span class="">{{__('business_settings.parameter_7_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_7_value" id="parameter_7_value" value="" placeholder="Parameter 7 value" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_8_key">
                                                                        <span class="">{{__('business_settings.parameter_8_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_8_key" id="parameter_8_key" value="" placeholder="Parameter 8 key" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_8_value">
                                                                    <span class="">{{__('business_settings.parameter_8_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_8_value" id="parameter_8_value" value="" placeholder="Parameter 8 value" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_9_key">
                                                                        <span class="">{{__('business_settings.parameter_9_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_9_key" id="parameter_9_key" value="" placeholder="Parameter 9 key" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_9_value">
                                                                    <span class="">{{__('business_settings.parameter_9_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_9_value" id="parameter_9_value" value="" placeholder="Parameter9 value" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_10_key">
                                                                        <span class="">{{__('business_settings.parameter_10_key')}}:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_10_key" id="parameter_10_key" value="" placeholder="Parameter 10 key" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_10_value">
                                                                    <span class="">{{__('business_settings.parameter_10_value')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_10_value" id="parameter_10_value" value="" placeholder="Parameter 10 value" />
                                                            </div>
                                                        </div>
                                                    <div class="separator border-gray-600 my-4"></div>
                                                    <div class="row fv-row row-cols flex-wrap">
                                                        <div class="col-md-12  mb-7 col-lg-8">
                                                            {{-- <form action="/ldf" method="POST">
                                                                @csrf
                                                            <div class="input-group">
                                                                <input class="form-control form-control" name="" placeholder="Test Number" id="" value="" />
                                                                <button type="submit" class="btn btn-sm btn-success">
                                                                    Send Test SMS
                                                                </button>
                                                            </div>
                                                        </form> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="smsPOH-tab" >

                                                        <div class="row fv-row row-cols flex-wrap">
                                                            <div class="col-md-12 mb-7 col-lg-12">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="auth_token">
                                                                    <span class="">SMS Poh Auth Key:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" id="sms_poh_auth_key" name="SMSPOH_AUTH_TOKEN" value=""
                                                                    placeholder="Auth key" />
                                                            </div>
                                                            <div class="col-md-12  mb-7 col-lg-12">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="smsPohSender">
                                                                    <span class="">Sender:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="SMSPOH_SENDER" id="smsPohSender" value=""
                                                                    placeholder="Nexmo Secret:" />
                                                            </div>
                                                        {{-- <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="nexmo_from">
                                                                <span class="">{{__('business_settings.from')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="nexmo_from" id="nexmo_from" value=""
                                                                placeholder="from" />
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                <div id="nexmo-tab" class=" d-none">
                                                        <div class="row fv-row row-cols flex-wrap">
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="nexmo_key">
                                                                <span class="">{{__('business_settings.nexmo_key')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" id="nexmo_key" name="nexmo_key" value="" placeholder="Nexmo key"/>
                                                        </div>
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="nexmo_secret">
                                                                    <span class="">{{__('business_settings.nexmo_secret')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="nexmo_secret" id="nexmo_secret" value="" placeholder="Nexmo Secret:" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="nexmo_from">
                                                                <span class="">{{__('business_settings.from')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="nexmo_from" id="nexmo_from" value="" placeholder="from" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="twilio-tab" class=" d-none">
                                                    <div class="row fv-row row-cols flex-wrap">
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="twilio_key">
                                                                <span class="">{{__('business_settings.twilio_key')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" id="twilio_key" name="twilio_key" value="" placeholder="Nexmo key"/>
                                                        </div>
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="twilio_secret">
                                                                    <span class="">{{__('business_settings.twilio_secret')}}:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="twilio_secret" id="twilio_secret" value="" placeholder="Nexmo Secret:" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="twilio_from">
                                                                <span class="">{{__('business_settings.from')}}:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="twilio_from" id="twilio_from" value="" placeholder="from" />
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                    </div>

                                    {{-- reward-point-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_reward_point_setting" role="tabpanel">
                                         <!--begin::Heading-->

                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" id="enable_reward_point" name="enable_reward_point">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_reward_point">
                                                            <span>{{__('business_settings.enable_reward_point')}}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="reward_point_display_name">
                                                        <span class="">{{__('business_settings.reward_point_display_name')}}:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="reward_point_display_name" name="reward_point_display_name" value="" placeholder="Reward Point Display Name"/>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h2 class="d-inline text-primary">{{__('business_settings.earning_points_settings')}}:</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">

                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="amount_spend_for_unit_point">
                                                        <span class="">{{__('business_settings.amount_spend_for_unit_point')}};
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="amount_spend_for_unit_point" name="amount_spend_for_unit_point" value="" placeholder="Amount spend for unit point"/>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="minimun_order_total_to_earn_reward">
                                                        <span class="">{{__('business_settings.minimum_order_total_to_earn_reward')}}:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="minimun_order_total_to_earn_reward" name="minimun_order_total_to_earn_reward" value="" placeholder="Minimum order total to earn reward:"/>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="maximum_points_per_order">
                                                        <span class="">{{__('business_settings.maximum_points_per_order')}}:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="maximum_points_per_order" name="maximum_points_per_order" value="" placeholder="maximum_points_per_order"/>
                                                </div>

                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h2 class="d-inline text-primary">{{__('business_settings.redeem_points_settings')}}:</h2>
                                                </div>
                                            </div>
                                            <div class="separator border-gray-600 my-4"></div>

                                            <div class="row fv-row row-cols flex-wrap">

                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="redeem_amount_per_unit_point">
                                                        <span class="">{{__('business_settings.redeem_amount_per_unit_point')}}:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="redeem_amount_per_unit_point" name="redeem_amount_per_unit_point" value="" placeholder="Redeem amount per unit point"/>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-5">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="minimun_order_total_to_redeem_reward">
                                                        <span class="">{{__('business_settings.minimum_order_total_to_redeem_points')}}:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="minimun_order_total_to_redeem_reward" name="minimun_order_total_to_redeem_reward" value="" placeholder="Minimum order total to earn reward:"/>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-3">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="minium_redeem_point">
                                                        <span class="">{{__('business_settings.minimum_redeem_point')}}:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="minium_redeem_point" name="minium_redeem_point" value="" placeholder="Minimum redeem point"/>
                                                </div>
                                            </div>

                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="maximum_redeem_point_per_order">
                                                        <span class="">{{__('business_settings.maximum_redeem_point_per_order')}}:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="maximum_redeem_point_per_order" name="maximum_redeem_point_per_order" value="" placeholder="Maximum redeem point per order"/>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-7">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="reward_point_expiry_period">
                                                            <span class="required">{{__('business_settings.reward_point_expiry_period')}}: </span>
                                                        </label>
                                                        <div class="input-group flex-nowrap">
                                                            <input class="form-control form-control" name="reward_point_expiry_period" placeholder="Reward Point expiry period" id="reward_point_expiry_period" value="" />
                                                            <span class="input-group-text">
                                                                -
                                                            </span>
                                                            <div class="overflow-hidden flex-grow-2">
                                                                <select name="reward_point_expiry_period_time_format" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                                    <option value="1">Year</option>
                                                                    <option value="2" >Month</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>

                                    </div>

                                    {{-- modules-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_reward_point_modules" role="tabpanel">
                                         <!--begin::Heading-->
                                             <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-md-9 p-3">
                                                    <h2  class="text-primary">{{__('business_settings.enable_disable_modules')}}</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_purchases" id="module_purchases">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_purchases">
                                                            <span >{{__('business_settings.purchases')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_add_sale" id="module_add_sale">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_add_sale">
                                                            <span >{{__('business_settings.add_sale')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_pos" id="module_pos">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_pos">
                                                            <span >{{__('business_settings.pos')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_stock_transfers" id="module_stock_transfers">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_stock_transfers">
                                                            <span>{{__('business_settings.stock_transfer')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="modulse_stock_adjustment" id="modulse_stock_adjustment">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="modulse_stock_adjustment">
                                                            <span>{{__('business_settings.stock_adjustment')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_expenses" id="module_expenses">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_expenses">
                                                            <span >{{__('business_settings.express')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_account" id="module_account">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_account">
                                                            <span> {{__('business_settings.account')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_table" id="module_table">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_table">
                                                            <span>{{__('business_settings.table')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_modifiers" id="module_modifiers">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_modifiers">
                                                            <span >{{__('business_settings.modifiers')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_service_staff" id="module_service_staff">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_service_staff">
                                                            <span>{{__('business_settings.service_staff')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="modulse_enable_bookings" id="modulse_enable_bookings">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="modulse_enable_bookings">
                                                            <span>{{__('business_settings.enable_bookings')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_order_display_restaurants" id="module_order_display_restaurants">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_order_display_restaurants">
                                                            <span >{{__('business_settings.order_for_restaurants')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_enable_subscription" id="module_enable_subscription">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_enable_subscription">
                                                            <span>{{__('business_settings.enable_subscription')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_type_of_service" id="module_type_of_service">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_type_of_service">
                                                            <span>{{__('business_settings.types_of_service')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                    {{-- business_setting_custom_labels --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_custom_labels" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">{{__('business_settings.labels_for_custom_payments')}}</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_1">
                                                        <span class="">{{__('business_settings.custom_field_1')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="custom_payment_1" name="custom_payment_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_2">
                                                            <span class="">{{__('business_settings.custom_field_2')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="custom_payment_2" id="custom_payment_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_3">
                                                        <span class="">{{__('business_settings.custom_field_3')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="custom_payment_3" id="custom_payment_3" value=""  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_4">
                                                        <span class="">{{__('business_settings.custom_field_4')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="custom_payment_4" name="custom_payment_4" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_5">
                                                            <span class="">{{__('business_settings.custom_field_5')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="custom_payment_5" id="custom_payment_5" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_6">
                                                        <span class="">{{__('business_settings.custom_field_6')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="custom_payment_6" id="custom_payment_6" value=""  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_7">
                                                        <span class="">{{__('business_settings.custom_field_7')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="custom_payment_7" name="custom_payment_7" value="" />
                                                </div>
                                            </div>

                                            <div class="separator border-gray-600 my-4"></div>

                                            {{-- contact customer field --}}
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">{{__('business_settings.labels_for_contact_custom_fields')}}</h3>
                                                </div>
                                            </div>

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_1">
                                                        <span class="">{{__('business_settings.custom_field_1')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_1" name="contact_custom_field_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_2">
                                                            <span class="">{{__('business_settings.custom_field_2')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="contact_custom_field_2" id="contact_custom_field_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_3">
                                                        <span class="">{{__('business_settings.custom_field_3')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="contact_custom_field_3" id="contact_custom_field_3" value=""  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_4">
                                                        <span class="">{{__('business_settings.custom_field_4')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_4" name="contact_custom_field_4" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_5">
                                                            <span class="">{{__('business_settings.custom_field_5')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="contact_custom_field_5" id="contact_custom_field_5" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_6">
                                                        <span class="">{{__('business_settings.custom_field_6')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="contact_custom_field_6" id="contact_custom_field_6" value=""  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_7">
                                                        <span class="">{{__('business_settings.custom_field_7')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_7" name="contact_custom_field_7" value="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_8">
                                                        <span class="">{{__('business_settings.custom_field_8')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_8" name="contact_custom_field_8" value="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_9">
                                                        <span class="">{{__('business_settings.custom_field_9')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_9" name="contact_custom_field_9" value="" />
                                                </div>
                                            </div>

                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_10">
                                                        <span class="">{{__('business_settings.custom_field_10')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_10" name="contact_custom_field_10" value="" />
                                                </div>
                                            </div>


                                            {{-- product custom --}}
                                            <div class="separator border-gray-600 my-4"></div>
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">{{__('business_settings.labels_for_product_custom_fields')}}:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="product_custom_field_1">
                                                        <span class="">{{__('business_settings.custom_field_1')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="product_custom_field_1" name="product_custom_field_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="product_custom_field_2">
                                                            <span class="">{{__('business_settings.custom_field_2')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="product_custom_field_2" id="product_custom_field_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="product_custom_field_3">
                                                        <span class="">{{__('business_settings.custom_field_3')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="product_custom_field_3" id="product_custom_field_3" value=""  />
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="product_custom_field_4">
                                                        <span class="">{{__('business_settings.custom_field_4')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="product_custom_field_4" id="product_custom_field_4" value=""  />
                                                </div>
                                            </div>
                                            {{-- location custom --}}
                                            <div class="separator border-gray-600 my-4"></div>
                                                <div class="row mb-2">
                                                    <div class="col-md-9 p-3">
                                                        <h3 class="text-primary">{{__('business_settings.labels_for_location_custom_fields')}}:</h3>
                                                    </div>
                                                </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="location_custom_field_1">
                                                        <span class="">{{__('business_settings.custom_field_1')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="location_custom_field_1" name="location_custom_field_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="location_custom_field_2">
                                                            <span class="">{{__('business_settings.custom_field_2')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="location_custom_field_2" id="location_custom_field_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="location_custom_field_3">
                                                        <span class="">{{__('business_settings.custom_field_3')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="location_custom_field_3" id="location_custom_field_3" value=""  />
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="location_custom_field_4">
                                                        <span class="">{{__('business_settings.custom_field_4')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="location_custom_field_4" id="location_custom_field_4" value=""  />
                                                </div>
                                            </div>
                                            {{-- user custom --}}
                                            <div class="separator border-gray-600 my-4"></div>
                                             <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">{{__('business_settings.labels_for_user_custom_fields')}}:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="user_custom_field_1">
                                                        <span class="">{{__('business_settings.custom_field_1')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="user_custom_field_1" name="user_custom_field_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="user_custom_field_2">
                                                            <span class="">{{__('business_settings.custom_field_2')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="user_custom_field_2" id="user_custom_field_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="user_custom_field_3">
                                                        <span class="">{{__('business_settings.custom_field_3')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="user_custom_field_3" id="user_custom_field_3" value=""  />
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="user_custom_field_4">
                                                        <span class="">{{__('business_settings.custom_field_4')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="user_custom_field_4" id="user_custom_field_4" value=""  />
                                                </div>
                                            </div>
                                            {{-- shipping custom fields --}}
                                            <div class="separator border-gray-600 my-4"></div>

                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">{{__('business_settings.labels_for_purchase_shipping_custom_fields')}}</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_1">
                                                        <span >{{__('business_settings.custom_field_1')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_1" placeholder="" id="purchase_shipping_customer_field_1" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_1_required" id="purchase_shipping_customer_field_1_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_1_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_2">
                                                        <span >{{__('business_settings.custom_field_2')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_2" placeholder="" id="purchase_shipping_customer_field_2" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_2_required" id="purchase_shipping_customer_field_2_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_2_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_3">
                                                        <span >{{__('business_settings.custom_field_3')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_3" placeholder="" id="purchase_shipping_customer_field_3" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_3_required" id="purchase_shipping_customer_field_3_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_3_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_4">
                                                        <span >{{__('business_settings.custom_field_4')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_4" placeholder="" id="purchase_shipping_customer_field_4" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_4_required" id="purchase_shipping_customer_field_4_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_4_required">
                                                                <span >{{__('business_settings.is_required')}}{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_5">
                                                        <span >{{__('business_settings.custom_field_5')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_5" placeholder="" id="purchase_shipping_customer_field_5" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_5_required" id="purchase_shipping_customer_field_5_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_5_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="separator border-gray-600 my-4"></div>
                                            {{-- sell custom --}}
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">{{__('business_settings.labels_for_sell_custom_fields')}}:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_custom_field_1">
                                                        <span >{{__('business_settings.custom_field_1')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="sell_custom_field_1" placeholder="" id="sell_custom_field_1" value="sell_custom_field_1" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sell_custom_field_1_required" id="sell_custom_field_1_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sell_custom_field_1_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_custom_field_2">
                                                        <span >{{__('business_settings.custom_field_2')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="sell_custom_field_2" placeholder="" id="sell_custom_field_2" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sell_custom_field_2_required" id="sell_custom_field_2_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sell_custom_field_2_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_custom_field_3">
                                                        <span >{{__('business_settings.custom_field_3')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="sell_custom_field_3" placeholder="" id="sell_custom_field_3" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sell_custom_field_3_required" id="sell_custom_field_3_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sell_custom_field_3_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_custom_field_4">
                                                        <span >{{__('business_settings.custom_field_4')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="sell_custom_field_4" placeholder="" id="sell_custom_field_4" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sell_custom_field_4_required" id="sell_custom_field_4_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sell_custom_field_4_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator border-gray-600 my-4"></div>
                                                <div class="row mb-2">
                                                    <div class="col-md-9 p-3">
                                                        <h3 class="text-primary">{{__('business_settings.labels_for_sale_shipping_custom_fields')}}:</h3>
                                                    </div>
                                                </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_1">
                                                        <span >{{__('business_settings.custom_field_1')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_1" placeholder="" id="sale_shipping_custom_field_1" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_1_required" id="sale_shipping_custom_field_1_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_1_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_1_default_contact" id="sale_shipping_custom_field_1_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_1_default_contact">
                                                                <span >{{__('business_settings.is_default_for_contact')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_2">
                                                        <span >{{__('business_settings.custom_field_2')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_2" placeholder="" id="sale_shipping_custom_field_2" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_2_required" id="sale_shipping_custom_field_2_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_2_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_2_default_contact" id="sale_shipping_custom_field_2_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_2_default_contact">
                                                                <span >{{__('business_settings.is_default_for_contact')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_3">
                                                        <span >{{__('business_settings.custom_field_3')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_3" placeholder="" id="sale_shipping_custom_field_3" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_3_required" id="sale_shipping_custom_field_3_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_3_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_3_default_contact" id="sale_shipping_custom_field_3_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_3_default_contact">
                                                                <span >{{__('business_settings.is_default_for_contact')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_4">
                                                        <span >{{__('business_settings.custom_field_4')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_4" placeholder="" id="sale_shipping_custom_field_4" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_4_required" id="sale_shipping_custom_field_4_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_4_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_4_default_contact" id="sale_shipping_custom_field_4_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_4_default_contact">
                                                                <span >{{__('business_settings.is_default_for_contact')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_5">
                                                        <span >{{__('business_settings.custom_field_5')}}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_5" placeholder="" id="sale_shipping_custom_field_5" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_5_required" id="sale_shipping_custom_field_5_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_5_required">
                                                                <span >{{__('business_settings.is_required')}}</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_5_default_contact" id="sale_shipping_custom_field_5_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_5_default_contact">
                                                                <span >{{__('business_settings.is_default_for_contact')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator border-gray-600 my-4"></div>
                                            {{-- label for type fo service --}}
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">{{__('business_settings.labels_for_types_of_service_custom_fields')}}:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_1">
                                                        <span class="">{{__('business_settings.custom_payment_1')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="types_of_service_custom_1" name="types_of_service_custom_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_2">
                                                            <span class="">{{__('business_settings.custom_payment_2')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="types_of_service_custom_2" id="types_of_service_custom_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_3">
                                                        <span class="">{{__('business_settings.custom_payment_3')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="types_of_service_custom_3" id="types_of_service_custom_3" value=""  />
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_4">
                                                        <span class="">{{__('business_settings.custom_payment_4')}}</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="types_of_service_custom_4" name="types_of_service_custom_4" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_5">
                                                            <span class="">{{__('business_settings.custom_payment_5')}}</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="types_of_service_custom_5" id="types_of_service_custom_5" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_6">
                                                        <span class="">{{__('business_settings.custom_payment_6')}}Custom Payment 6</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="types_of_service_custom_6" id="types_of_service_custom_6" value=""  />
                                                </div>
                                            </div>
                                    </div>


                                </div>
                                <!--end:::Tab content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <div class="row py-5">
                            <div class=" ">
                                <div class="d-flex align-items-center justify-content-center">
                                    <!--begin::Button-->
                                    <button type="reset" data-kt-ecommerce-settings-type="cancel" class="btn btn-light me-3">{{__('business_settings.cancel')}}</button>

                                    <button class="btn btn-primary" type="submit">{{__('business_settings.save')}}</button>
                                    <!--end::Button-->
                                    <!--begin::Button-->
                                    <!--end::Button-->
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Card-->
                </div>
                <!--end::Container-->
            </div>
        </div>
        <!--end::Inbox App - Compose -->
    </div>
    <!--end::Container-->



@endsection

@push('scripts')
 		<!--begin::Custom Javascript(used for this page only)-->
		{{-- <script src={{asset("assets/js/custom/apps/inbox/compose.js")}}></script> --}}
		{{-- <script src={{asset("assets/js/widgets.bundle.js")}}></script>
        <script src={{asset("assets/plugins/global/plugins.bundle.js")}}></script> --}}
		<!--end::Custom Javascript-->
        <script src={{asset('customJs/businessSettingProduct.js')}}></script>
        <script src={{asset('customJs/smsSetting.js')}}></script>
        <script src={{asset('customJs/customFileInput.js')}}></script>
        <script src={{asset('customJs/businessSettingFormSearch.js')}}></script>
        <script>
            $(document).ready(function(){
                $('.nav-link').click(function(){
                    let title=$(this).closest('.nav-item').data('title');
                    $('#tab-title').text(title);
                })
            })
        </script>
@endpush

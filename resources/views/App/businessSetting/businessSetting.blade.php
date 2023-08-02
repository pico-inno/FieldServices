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
    <h1 class="text-dark fw-bold my-0 fs-2">Business Setting</h1>
    <!--end::Heading-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb fw-semibold fs-base my-1">
        <li class="breadcrumb-item text-muted">Setting</li>
        <li class="breadcrumb-item text-dark">Business setting</li>
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')

    <!--begin::Container-->
    <div class="container-fluid " id="kt_content_container">
        <!--begin::Inbox App - Compose -->
        <div class="col-md-10 offset-md-2 my-5 col-12">
            <div class="input-group flex-nowrap">
                <span class="input-group-text" >
                   <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <div class="overflow-hidden flex-grow-1">
                    <select class="form-select rounded-start-0" name="search-bar" id="search-bar" data-control="select2" data-allow-clear="true" data-placeholder='Serach' >
                        <option></option>
                        {{-- business --}}
                        <option value="business_name">Business Name</option>
                        <option value="kt_datepicker_1">Start Date</option>
                        <option value="default_profit_percent">Default profit percent</option>
                        <option value="currency">Currency</option>
                        <option value="currency_symbol_placement">Currency Symbol Placement</option>
                        <option value="timezone">Time Zone</option>
                        <option value="update_logo">Update Logo</option>
                        <option value="finanical_year_start_month">Finanical year start month</option>
                        <option value="stock_accounting_method">Stock Accounting Method</option>
                        <option value="transaction_edit_days">Transaction Edit Days</option>
                        <option value="date_format">Date Format</option>
                        <option value="time_format">Time Format</option>
                        <option value="currency_position">Currency Position</option>
                        <option value="quantity_precision">Quantity precision</option>
                        <option value=""></option>

                        {{-- tax --}}
                        <option value="tax_1_name">Tax 1 Name</option>
                        <option value="tax_1_no">Tax 1 No</option>
                        <option value="tax_2_name">Tax 2 Name</option>
                        <option value="tax_2_no">Tax 2 No</option>
                        <option value="enable_inline_tax_in_purchase_and_sell">Enable inline tax in purchase and sell</option>

                        {{-- Product --}}

                        <option value="sku_prefix">SKU prefix</option>
                        <option value=""></option>
                        <option value="enable_product_expiry_check">Enable Product Expiry</option>
                        <option value="enable_brands">Enable Brands</option>
                        <option value="enable_categories">Enable Categories</option>
                        <option value="enable_price_and_tax_info"> Enable Price & Tax info</option>
                        <option value="default_units">Default Units</option>
                        <option value="enable_sub_unit">Enable Sub Unit</option>
                        <option value="enable_racks">Enable Racks</option>
                        <option value="enable_position">Enable Position</option>
                        <option value="enable_warranty">Enable Warranty</option>
                        <option value="enable_secondary_unit"> Enable Secondary Unit</option>

                        {{-- CONTACT --}}
                        <option value="default_credit_limit">Default credit limit</option>

                        {{-- sale --}}
                        <option value="enable_line_discount_for_sale">Enable Line Discount For sale</option>
                        <option value="default_sale_discount">Default Sale Discount</option>
                        <option value="default_sale_tax">Default Sale Tax</option>
                        <option value="sales_item_addition_method">Sales Item Addition Method</option>
                        <option value="amount_rounding_method">Amount rounding method</option>
                        <option value="minimun_selling_price">Sales price is minimum selling price</option>
                        <option value="allow_overselling">Allow Overselling</option>
                        <option value="enable_sale_order">Enable Sales Order</option>
                        <option value="pay_required"> Is pay term required?</option>
                        <option value="sales_commission_agent">Sales Commission Agent</option>
                        <option value="commission_calc_type">Commission Calculation Type</option>
                        <option value="commission_agent_required">Is commission agent required?</option>
                        <option value="enable_payment_link">Enable Payment Link</option>
                        <option value="razorpay_key_id">Razorpay Key ID</option>
                        <option value="razorpay_key_secret">Razorapay Key Secret:</option>
                        <option value="stripe_public_key">Stripe public key</option>
                        <option value="stripe_secret_key">Stripe secret key</option>


                        {{-- pos --}}
                        <option value="express_checkout">Express Checkout</option>
                        <option value="pay_checkout">Pay & Checkout</option>
                        <option value="draft">POS Draft</option>
                        <option value="cancle">Cancle</option>
                        <option value="go_product_qt">Go to product quantity</option>
                        <option value="weighing_scale">Weighing Scale</option>
                        <option value="edit_order_tax">Edit Order Tax</option>
                        <option value="add_payment_row">dd Payment Row</option>
                        <option value="finalize_payment">Finalize Payment:</option>
                        <option value="add_new_product">Add new product</option>
                        <option value="disable_mulitple_pay">Disable Multiple Pa</option>
                        <option value="disable_draft">Display Draft</option>
                        <option value="disable_express_checkout">Display Express Checkout</option>
                        <option value="show_product_suggestion"> Don't show product suggestion</option>
                        <option value="recent_transactions"> Don't show recent transactions</option>
                        <option value="disable_discount">Disable discount</option>
                        <option value="disable_order_tax"> Disable order tax</option>
                        <option value="subtotal_editable"> Subtotal Editable</option>
                        <option value="disable_suspend_sale">Disable Suspend Sale</option>
                        <option value="enable_transaction_date_on_pos_screen">Enable transaction date on POS screen </option>
                        <option value="enable_serviece_staff_in_product_line">Enable service staff in product line</option>
                        <option value="service_staff_required">Is service staff required?</option>
                        <option value="disable_credit_sale_btn">Disable credit sale button </option>
                        <option value="enable_weighting_scale">Enable Weighing Scale</option>
                        <option value="show_invoice_scheme">Show invoice scheme</option>
                        <option value="show_invoice_layout_dropdown">Show invoice layout dropdown</option>
                        <option value="print_invoice_on_suspend">Print invoice on suspend</option>
                        <option value="procing_on_product_suggestion_tooltip">Show pricing on product suggestion tooltip</option>
                        <option value="barcode_perfix">Barcode Prefix</option>
                        <option value="product_sku_length">Barcode Product sku length</option>
                        <option value="quantity_interger_part_length">Quantity integer part length</option>
                        <option value="quantity_fractional_part_length">Quantity fractional part length</option>
                        <option value="enable_line_discount_for_purchase">Enable Line Discount</option>
                        <option value="enable_editing_product_price_from_purchase_screen">Enable editing product price from purchase screen</option>
                        <option value="enable_pruchase_status">Enable Purchase Status </option>
                        <option value="enable_lot_number">Enable Lot number</option>
                        <option value="enable_purchase_order">Enable purchase order</option>


                        {{-- payment Tab --}}
                        <option value="cash_denominations">Enable cash denomination on</option>
                        <option value="enable_cash_denomination_for_payment_methods">Enable cash denomination for payment methods</option>
                        <option value="strict_check">Strict Check</option>

                        {{-- dahsboard --}}
                        <option value="view_storck_expiry_alert_for">View Stock Expiry Alert For</option>
                        {{-- system --}}
                        <option value="theme_color">Theme Color</option>
                        <option value="default_page_entries">Default datatable page entries</option>
                        <option value="show_help_text">Show help text</option>


                        {{-- prefix --}}
                        <option value="purchase_return">Purchase Return</option>
                        <option value="purchase_order">Purchase Order</option>
                        <option value="stock_transfer">Stock Transfer</option>
                        <option value="stock_adjustment">Stock Adjustment</option>
                        <option value="sell_return">Sell Return</option>
                        <option value="expenses">Expenses</option>
                        <option value="contacts">Contacts</option>
                        <option value="purchase_payment">Purchase Payment</option>
                        <option value="sell_payment">Sell Payment</option>
                        <option value="expense_payment">Expense Payment</option>
                        <option value="business_location">Business Location</option>
                        <option value="perfixes_username">Prefix Username</option>
                        <option value="subscription_no">Subscription No</option>
                        <option value="perfixes_draft">Prefix Draft</option>
                        <option value="sales_order">Sales Order</option>


                        {{-- email setting --}}

                        <option value="mail_driver">Mail Driver</option>
                        <option value="mail_host">Mail Host</option>
                        <option value="mail_port">Mail Port</option>
                        <option value="mail_username">Mail Username</option>
                        <option value="mail_password">Mail Password</option>
                        <option value="mail_encryption">Mail Encryption</option>
                        <option value="mail_form_address">Mail address(From)</option>
                        <option value="mail_from_name">Mail Name (From)</option>


                        {{-- sms --}}

                        <option value="sms_service">SMS service</option>
                        <option value="sms_url">SMS URL</option>
                        <option value="parameter_name">Send to parameter name</option>
                        <option value="message_para_name">Message parameter name</option>
                        <option value="request_method">Request Method For SMS</option>
                        <option value="header_1_key">Header 1 Key</option>
                        <option value="header_1_value">Header 1 value</option>
                        <option value="header_2_key">Header 2 key</option>
                        <option value="header_2_value">Header 2 value:</option>
                        <option value="header_3_key">Header 3 key</option>
                        <option value="header_3_value">Header 3 value</option>

                        <option value="parameter_1_value">Parameter 1 value</option>
                        <option value="parameter_1_key">Parameter 1 key</option>

                        <option value="parameter_2_value">Parameter 2 value</option>
                        <option value="parameter_2_key">Parameter 2 key</option>

                        <option value="parameter_3_value">Parameter 3 value</option>
                        <option value="parameter_3_key">Parameter 3 key</option>

                        <option value="parameter_4_value">Parameter 4 value</option>
                        <option value="parameter_4_key">Parameter 4 key</option>

                        <option value="parameter_5_value">Parameter 5 value</option>
                        <option value="parameter_5_key">Parameter 5 key</option>

                        <option value="parameter_6_value">Parameter 6 value</option>
                        <option value="parameter_6_key">Parameter 6 key</option>

                        <option value="parameter_7_value">Parameter 7 value</option>
                        <option value="parameter_7_key">Parameter 7 key</option>

                        <option value="parameter_8_value">Parameter 8 value</option>
                        <option value="parameter_8_key">Parameter 8 key</option>

                        <option value="parameter_9_value">Parameter 9 value</option>
                        <option value="parameter_9_key">Parameter 9 key</option>

                        <option value="parameter_10_value">Parameter 10 value</option>
                        <option value="parameter_10_key">Parameter 10 key</option>

                        <option value="nexmo_key">Nexmo Key</option>
                        <option value="nexmo_secret">Nexmo Secret</option>
                        <option value="nexmo_from">From(nexmo)</option>

                        <option value="twilio_key">Twilio Key</option>
                        <option value="twilio_secret">Twilio Secret</option>
                        <option value="twilio_from">From (twilio)</option>


                        {{-- reward Point --}}

                        <option value="enable_reward_point">Enable Reward Point</option>
                        <option value="reward_point_display_name">Reward Point Display Name</option>
                        <option value="amount_spend_for_unit_point">Amount spend for unit point</option>
                        <option value="minimun_order_total_to_earn_reward">Minimum order total to earn reward</option>
                        <option value="maximum_points_per_order">Maximum points per order</option>

                        <option value="redeem_amount_per_unit_point">Redeem amount per unit point</option>
                        <option value="minimun_order_total_to_redeem_reward">Minimum order total to redeem points</option>
                        <option value="minium_redeem_point">Minimum redeem point</option>
                        <option value="maximum_redeem_point_per_order">Maximum redeem point per order</option>
                        <option value="reward_point_expiry_period">Reward Point expiry period</option>

                        <option value="module_purchases">Purchases</option>
                        <option value="module_add_sale">Add Sale</option>
                        <option value="module_pos">POS</option>
                        <option value="module_stock_transfers"> Stock Transfers</option>
                        <option value="modulse_stock_adjustment"> Stock Adjustment</option>
                        <option value="module_expenses">Expenses(module)</option>
                        <option value="module_account">Account</option>
                        <option value="module_table">Table</option>
                        <option value="module_modifiers">Modifiers</option>
                        <option value="module_service_staff"> Service staff</option>
                        <option value="modulse_enable_bookings"> Enable Bookings</option>
                        <option value="module_order_display_restaurants">Order (For restaurants)</option>
                        <option value="module_enable_subscription">Enable Subscription</option>
                        <option value="module_type_of_service">Types of service</option>

                        {{-- custom label --}}
                        <option value="custom_payment_1">Custom Payment 1</option>
                        <option value="custom_payment_2">Custom Payment 2</option>
                        <option value="custom_payment_3">Custom Payment 3</option>
                        <option value="custom_payment_4">Custom Payment 4</option>
                        <option value="custom_payment_5">Custom Payment 5</option>
                        <option value="custom_payment_6">Custom Payment 6</option>
                        <option value="custom_payment_7">Custom Payment 7</option>

                        <option value="contact_custom_field_1">Contact Custom Field 1</option>
                        <option value="contact_custom_field_2">Contact Custom Field 2</option>
                        <option value="contact_custom_field_3">Contact Custom Field 3</option>
                        <option value="contact_custom_field_4">Contact Custom Field 4</option>
                        <option value="contact_custom_field_5">Contact Custom Field 5</option>
                        <option value="contact_custom_field_6">Contact Custom Field 6</option>
                        <option value="contact_custom_field_7">Contact Custom Field 7</option>
                        <option value="contact_custom_field_8">Contact Custom Field 8</option>
                        <option value="contact_custom_field_9">Contact Custom Field 9</option>
                        <option value="contact_custom_field_10">Contact Custom Field 10</option>

                        <option value="product_custom_field_1">Product Custom Field 1</option>
                        <option value="product_custom_field_2">Product Custom Field 2</option>
                        <option value="product_custom_field_3">Product Custom Field 3</option>
                        <option value="product_custom_field_4">Product Custom Field 4</option>


                        <option value="location_custom_field_1">Location Custom Field 1</option>
                        <option value="location_custom_field_2">Location Custom Field 2</option>
                        <option value="location_custom_field_3">Location Custom Field 3</option>
                        <option value="location_custom_field_4">Location Custom Field 4</option>

                        <option value="purchase_shipping_customer_field_1">Purchase shipping Custom Field 1</option>
                        <option value="purchase_shipping_customer_field_2">Purchase shipping Custom Field 2</option>
                        <option value="purchase_shipping_customer_field_3">Purchase shipping Custom Field 3</option>
                        <option value="purchase_shipping_customer_field_4">Purchase shipping Custom Field 4</option>
                        <option value="purchase_shipping_customer_field_5">Purchase shipping Custom Field 5</option>

                        <option value="sell_custom_field_1">Sell Custom Field 1</option>
                        <option value="sell_custom_field_2">Sell Custom Field 2</option>
                        <option value="sell_custom_field_3">Sell Custom Field 3</option>
                        <option value="sell_custom_field_4">Sell Custom Field 4</option>

                        <option value="sale_shipping_custom_field_1">Sale Shipping Custom Field 1</option>
                        <option value="sale_shipping_custom_field_2">Sale Shipping Custom Field 2</option>
                        <option value="sale_shipping_custom_field_3">Sale Shipping Custom Field 3</option>
                        <option value="sale_shipping_custom_field_4">Sale Shipping Custom Field 4</option>
                        <option value="sale_shipping_custom_field_5">Sale Shipping Custom Field 5</option>

                        <option value="types_of_service_custom_1">Service Custom Payment 1</option>
                        <option value="types_of_service_custom_2">Service Custom Payment 2</option>
                        <option value="types_of_service_custom_3">Service Custom Payment 3</option>
                        <option value="types_of_service_custom_4">Service Custom Payment 4</option>
                        <option value="types_of_service_custom_5">Service Custom Payment 5</option>
                        <option value="types_of_service_custom_6">Service Custom Payment 6</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row flex-lg-row mt-5">
            <!--begin::Sidebar-->
            <div class="d-flex " style="height: 100%">
                <!--begin::Sticky aside-->
                <div class="card card-flush mb-0" >
                    <!--begin::Aside content-->
                    <div class="card-body px-3">
                        <div class="menu menu-column menu-rounded menu-state-bg menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary mb-5">
                            <div class="nav w-125 flex-column">
                                    <div class="menu-item  mb-3 d-block ">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link active "  data-bs-toggle="tab" href="#business_setting_business" data-bs-target="#business_setting_business" >
                                            <span class="menu-title fw-bold">Business</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_tax"  data-bs-target="#business_setting_tax" >
                                            <span class="menu-title fw-bold">Tax</span>
                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Registered Tax Number For Your Business."></i>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_product"  data-bs-target="#business_setting_product" >
                                            <span class="menu-title fw-bold">Product</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_contact"  data-bs-target="#business_setting_contact" >
                                            <span class="menu-title fw-bold">Contact</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_sale"  data-bs-target="#business_setting_sale" >
                                            <span class="menu-title fw-bold">Sale</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_pos"  data-bs-target="#business_setting_pos" >
                                            <span class="menu-title fw-bold">POS</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_purchases"  data-bs-target="#business_setting_purchases" >
                                            <span class="menu-title fw-bold">Purchases</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_payment"  data-bs-target="#business_setting_payment" >
                                            <span class="menu-title fw-bold">Payment</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_dashboard"  data-bs-target="#business_setting_dashboard" >
                                            <span class="menu-title fw-bold">Dashboard</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_system"  data-bs-target="#business_setting_system" >
                                            <span class="menu-title fw-bold">System</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_prefix"  data-bs-target="#business_setting_prefix" >
                                            <span class="menu-title fw-bold">Prefix</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_emailSetting"  data-bs-target="#business_setting_emailSetting" >
                                            <span class="menu-title fw-bold">Email&ensp;Setting</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_sms"  data-bs-target="#business_setting_sms" >
                                            <span class="menu-title fw-bold">SMS&ensp;Setting</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_reward_point_setting"  data-bs-target="#business_setting_reward_point_setting" >
                                            <span class="menu-title fw-bold">Reward&ensp;Point&ensp;Setting</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_reward_point_modules"  data-bs-target="#business_setting_reward_point_modules" >
                                            <span class="menu-title fw-bold">Modules</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>
                                    <div class="menu-item  mb-3 d-block">
                                        <!--begin::Marked-->
                                        <span class="menu-link setting-link "  data-bs-toggle="tab" href="#business_setting_custom_labels"  data-bs-target="#business_setting_custom_labels" >
                                            <span class="menu-title fw-bold">Customs&ensp;Label</span>
                                        </span>
                                        <!--end::Marked-->
                                    </div>

                            </div>
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Aside content-->
                </div>
                <!--end::Sticky aside-->
            </div>
            <!--end::Sidebar-->
            <!--begin::Content-->
            <div class="flex-row-fluid ms-lg-1 ms-xl-10 " id="kt_content" >
                <!--begin::Container-->
                <div class="flex-row-fluid  ms-2 ">
                    <!--begin::Card-->
                    <form action="{{route('business_settings_create')}}" method="GET">
                        <div class="card " style="min-height: 100vh;">
                            <!--begin::Card body-->
                            <div class="card-body py-8 px-4 px-sm-7 px-md-10">
                                <!--begin:::Tab content-->

                                <div class="tab-content " id="myTabContent" >
                                    {{-- business-tab --}}
                                    <div class="tab-pane setting-tab fade active show" id="business_setting_business" role="tabpanel">
                                        <!--begin::Form-->
                                            <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-md-9 p-3">
                                                    <h2 class="">Business</h2>
                                                </div>
                                            </div>
                                            <!--end::Heading-->

                                            <div class="row fv-row row-cols ">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="business_name">
                                                        <span class="required">Business Name</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control form-control form-control-sm" name="name" id="business_name" value="{{$settingData['name']}}" />
                                                </div>

                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="business_name">
                                                        <span class="required">Owner Name</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control form-control form-control-sm" name="owner_id" id="owner" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="kt_datepicker_1">
                                                        <span class="required">Start Date:</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text" data-td-target="#kt_datepicker_1" data-td-toggle="datetimepicker">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                        <input class="form-control form-control form-control form-control-sm" name="start_date" placeholder="Pick a date"  id="kt_datepicker_1" value="{{date('d-m-Y')}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <div class="form-check form-check-custom">
                                                    <input type="checkbox" class="form-check-input border-gray-400 me-3" name="lot_control" id="lot_control" @checked($settingData['lot_control']=='on') disabled readonly>
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="lot_control">
                                                            <span >Lot Control</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                     <label class="fs-6 fw-semibold form-label mt-3" for="kt_datepicker_1">
                                                        <span class="required">Start Date:</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text" data-td-target="#kt_datepicker_1" data-td-toggle="datetimepicker">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                        <input class="form-control form-control form-control form-control-sm" name="start_date" placeholder="Pick a date"  id="kt_datepicker_1" value="{{date('d-m-Y')}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Input-->
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="default_profit_percent">
                                                        <span class="required">Default profit percent</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Default profit margin of a product."></i>
                                                    </label>
                                                    <!--end::Label-->
                                                    <div class="input-group">
                                                        <span class="input-group-text" >
                                                           <i class="fa-solid fa-circle-plus"></i>
                                                        </span>
                                                        <input type="number"  id="default_profit_percent" class="form-control form-control form-control form-control-sm" name="default_profit_percent" />
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols ">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="currency">
                                                        <span class="required">Currency</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" >
                                                            <i class="fa-solid fa-money-bill-1"></i>
                                                        </span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="currency_id" class="form-select rounded-start-0 form-select-sm" id="currency" data-control="select2" data-placeholder="Select Currency">
                                                                <option></option>
                                                                @foreach ($currencies as $c)
                                                                    <option value="{{$c->id}}" @selected($settingData['currency_id']==$c->id)>{{$c->name}} ({{$c->symbol}})</option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Input-->
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="currency_symbol_placement">
                                                        <span class="required">Currency Symbol Placement:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <select class="form-select  form-select-sm" name="currency_symbol_placement" id="currency_symbol_placement" data-control="select2" data-placeholder="Select Currency">
                                                        <option value="before" @selected($settingData['currency_symbol_placement']='before')>Before Amount</option>
                                                        <option value="after" @selected($settingData['currency_symbol_placement']='after')>After Amount</option>
                                                    </select>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="timezone">
                                                        <span class="required">Time Zone</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" >
                                                            <i class="fa-regular fa-clock"></i>
                                                        </span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select class="form-select rounded-start-0 form-select-sm" id="timezone" name="timezone" data-control="select2" data-placeholder="Select timezone">
                                                                <option value="1">Asia/Yangon</option>
                                                                <option value="2">Option 2</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols ">
                                                <div class="col-md-12 mb-7 col-lg-4 browseLogo">
                                                     <label class="fs-6 fw-semibold form-label mt-3" for="update_logo">
                                                        <span class="required">Upload Logo:</span>
                                                    </label>
                                                    <div class="input-group browseLogo input-group-sm">
                                                        <input type="file" class="form-control form-control form-control form-control-sm" id="update_logo" name="update_logo">
                                                        <button type="button" class="btn btn-sm btn-danger d-none" id="removeFileBtn"><i class="fa-solid fa-trash"></i></button>
                                                        <label class="input-group-text btn btn-primary rounded-end" for="update_logo">
                                                            Browse
                                                            <i class="fa-regular fa-folder-open"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="finanical_year_start_month">
                                                        <span class="required">Finanical year start month</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Starting month of the finanical year for your business."></i>
                                                    </label>
                                                    <!--end::Label-->
                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text" >
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="finanical_year_start_month" class="form-select rounded-start-0 form-select-sm" id="finanical_year_start_month" data-control="select2" data-placeholder="Select month">
                                                                <option></option>
                                                                <option value="1">Option 1</option>
                                                                <option value="2">Option 2</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <!--begin::Input-->
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="stock_accounting_method">
                                                        <span class="required">Stock Accounting Method</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Set the title of the store for SEO."></i>
                                                    </label>
                                                    <!--end::Label-->
                                                    <div class="input-group flex-nowrap input-group-sm">
                                                        <span class="input-group-text" >
                                                            <i class="fa-solid fa-calculator"></i>
                                                        </span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="accounting_method" data-hide-search="true" id="stock_accounting_method" class="form-select rounded-start-0 form-select-sm" data-control="select2" data-placeholder="">
                                                                <option value="fifo" @selected($settingData->accounting_method=='fifo')>FIFO (First In First Out)</option>
                                                                <option value="lifo" @selected($settingData->accounting_method=='lifo')>LIFO (Last In First Out)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>

                                            <div class="row fv-row row-cols ">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="transaction_edit_days">
                                                        <span class="required">Transaction Edit Days</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Number of days from Transaction Date till which a transaction can be edited."></i>
                                                    </label>
                                                    <!--end::Label-->
                                                    <div class="input-group">
                                                        <span class="input-group-text" >
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </span>
                                                        <input type="number" id="transaction_edit_days" class="form-control form-control form-control form-control-sm" name="transaction_edit_days">
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="date_format">
                                                            <span class="required">Date Format</span>
                                                        </label>
                                                        <div class="input-group flex-nowrap">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-calendar"></i>
                                                            </span>
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="date_format" id="date_format" data-hide-search="true" class="form-select rounded-start-0 form-select-sm" data-control="select2" data-placeholder="">
                                                                    <option value="1">dd-mm-yyyy</option>
                                                                    <option value="2">mm-dd-yyyy</option>
                                                                    <option value="2">dd/mm/yyyy</option>
                                                                    <option value="2">mm/dd/yyyy</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                      <label class="fs-6 fw-semibold form-label mt-3" for="time_format">
                                                            <span class="required">Time Format</span>
                                                        </label>
                                                        <div class="input-group flex-nowrap">
                                                            <span class="input-group-text" >
                                                                <i class="fas fa-calendar"></i>
                                                            </span>
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="time_format" id="time_format" data-hide-search="true" class="form-select rounded-start-0 form-select-sm" data-control="select2" data-placeholder="">
                                                                    <option value="1">24 hours</option>
                                                                    <option value="2">12 hours</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>

                                            <div class="row fv-row row-cols ">
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="currency_position">
                                                        <span class="required">Currency Decimal Places</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Number of digits after decimal point for currency value.Example:0.00 for value 2, 0.000 for value 3, 0.0000 for value 4"></i>
                                                    </label>
                                                    <!--end::Label-->
                                                    <select name="currency_decimal_places" id="currency_position" class="form-select form-select-sm" data-control="select2" data-placeholder="Select month">
                                                        <option value="0" @selected($settingData['currency_decimal_places'] == 0)>0</option>
                                                        <option value="1" @selected($settingData['currency_decimal_places'] == 1)>1</option>
                                                        <option value="2" @selected($settingData['currency_decimal_places'] == 2)>2</option>
                                                        <option value="3" @selected($settingData['currency_decimal_places'] == 3)>3</option>
                                                        <option value="4" @selected($settingData['currency_decimal_places'] == 4)>4</option>

                                                    </select>

                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="quantity_precision">
                                                        <span class="required">Quantity Decimal Places</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Number of digits after decimal point for quantity value.Example:0.00 for value 2, 0.000 for value 3, 0.0000 for value 4"></i>
                                                    </label>
                                                    <!--end::Label-->
                                                    <select name="quantity_decimal_places" id="quantity_precision" class="form-select form-select-sm" data-control="select2" data-placeholder="Select month">
                                                        <option value="0" @selected($settingData['quantity_decimal_places'] == 0)>0</option>
                                                        <option value="1" @selected($settingData['quantity_decimal_places'] == 1)>1</option>
                                                        <option value="2" @selected($settingData['quantity_decimal_places'] == 2)>2</option>
                                                        <option value="3" @selected($settingData['quantity_decimal_places'] == 3)>3</option>
                                                        <option value="4" @selected($settingData['quantity_decimal_places'] == 4)>4</option>
                                                    </select>

                                                </div>
                                            </div>
                                        <!--end::Form-->
                                    </div>

                                    {{-- tax-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_tax" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-md-9 p-3">
                                                    <h2 class="">Tax</h2>
                                                </div>
                                            </div>
                                            <!--end::Heading-->

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="tax_1_name">
                                                        <span class="required">Tax 1 Name</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control form-control form-control-sm" name="tax_1_name" id="tax_1_name" value="" placeholder="GST/VAT/OTHER" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="tax_1_no">
                                                            <span class="required">Tax 1 No</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="number" class="form-control form-control form-control form-control-sm" id="tax_1_no" name="tax_1_no" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="tax_2_name">
                                                        <span class="required">Tax 2 Name</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control form-control form-control-sm" id="tax_2_name" name="tax_2_name" value="" placeholder="GST/VAT/OTHER" />
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols ">
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="tax_2_no">
                                                            <span class="required">Tax 2 No</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="number" class="form-control form-control form-control form-control-sm" id="tax_2_no" name="tax_2_no" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 col-lg-4 d-flex align-items-center">
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="" id="enable_inline_tax_in_purchase_and_sell">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_inline_tax_in_purchase_and_sell">
                                                            <span > Enable inline tax in purchase and sell</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>

                                    {{-- product-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_product" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-md-9 p-3">
                                                    <h2  class="">Product</h2>
                                                </div>
                                            </div>
                                            <!--end::Heading-->

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sku_prefix">
                                                        <span class="required">SKU prefix</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control form-control form-control-sm" id="sku_prefix" name="sku" value="" placeholder="GST/VAT/OTHER" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="enable_product_expiry_check">
                                                        <span class="required">Enable Product Expiry</span>
                                                    </label>
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text">
                                                            <div class="form-check form-check-custom">
                                                            <input type="checkbox" name="enable_product_expiry_check" class="form-check-input border-gray-400 w-20px h-20px border-gray-500" id="enable_product_expiry_check">
                                                            </div>
                                                        </span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="enable_product_expiry" data-hide-search="true"  id="enable_product_expiry" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                                <option value="fifo">Add Item Expriy</option>
                                                                <option value="lifo">Add Manufacturing date & expriy period</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4 d-none" id="sell_expiry">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3">
                                                        <span class="required">On Product Expiry</span>
                                                    </label>
                                                    {{-- <!--end::Label-->data-control="select2" --}}
                                                   <div class="input-group flex-nowrap">
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="stop_selling_before" disabled data-hide-search="true" data-control="select2" id="add_sell_expriy" class="form-select rounded-end-0 min-w-125px "  data-placeholder="">
                                                                <option value="keep_sell">Keep Selling</option>
                                                                <option value="stop_sell">Stop Selling n day before</option>
                                                            </select>
                                                        </div>
                                                        <input type="text" disabled value="0" id="n_day" name="form-control form-control" class="form-control form-control min-w-125px" id="check_product_ex">
                                                    </div>

                                                </div>

                                            </div>


                                            <div class="row fv-row row-cols ">
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_brands" id="enable_brands">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_brands">
                                                            <span > Enable Brands</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_categories" id="enable_categories">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_categories">
                                                            <span > Enable Categories</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4 d-none" id="enable_sub_cat_div">
                                                    <div class="form-check form-check-custom   d-flex align-items-center">
                                                        <input type="checkbox" checked class="form-check-input border-gray-400 me-3" name="enable_sub_categories" id="enable_sub_categories">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_sub_categories">
                                                            <span > Enable Sub Categories</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_price_and_tax_info" id="enable_price_and_tax_info">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_price_and_tax_info">
                                                            <span > Enable Price & Tax info</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                      <label class="fs-6 fw-semibold form-label mt-3" for="default_units">
                                                            <span class="required">Default Units</span>
                                                        </label>
                                                        <div class="input-group flex-nowrap">
                                                            <span class="input-group-text" >
                                                              <i class="fa-solid fa-scale-balanced"></i>
                                                            </span>
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="default_units" id="default_units" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                                    <option value="1">Please Select</option>
                                                                    <option value="2">Kg</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_sub_unit" id="enable_sub_unit">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_sub_unit">
                                                            <span > Enable Sub Units</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_racks" id="enable_racks">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_racks">
                                                            <span > Enable Racks</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_row" id="enable_row">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_row">
                                                            <span > Enable Row</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_position" id="enable_position">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_position">
                                                            <span >  Enable Position</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 border-gray-400 me-3" name="enable_warranty" id="enable_warranty">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_warranty">
                                                            <span > Enable Warranty</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_secondary_unit" id="enable_secondary_unit">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_secondary_unit">
                                                            <span >  Enable Secondary Unit</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>

                                    {{-- contact-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_contact" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-md-9 p-3">
                                                    <h2  class="">Contact</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="default_credit_limit">
                                                        <span class="required" >Default credit limit:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                      <input type="text" class="form-control form-control" id="default_credit_limit" placeholder="Default credit limit">
                                                </div>

                                            </div>


                                    </div>

                                    {{-- sale-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_sale" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-md-9 p-3">
                                                    <h2  class="">Sale</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="default_sale_discount">
                                                        <span class="required">Default Sale Discount</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Number of days from Transaction Date till which a transaction can be edited."></i>
                                                    </label>
                                                    <!--end::Label-->
                                                    <div class="input-group">
                                                        <span class="input-group-text" >
                                                            <i class="fa-solid fa-percent"></i>
                                                        </span>
                                                        <input type="number" class="form-control form-control input-number" id="default_sale_discount" aria-invalid="false" name="default_sale_discount">
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                      <label class="fs-6 fw-semibold form-label mt-3" for="default_sale_tax">
                                                            <span class="required">Default Sale Tax</span>
                                                        </label>
                                                        <div class="input-group flex-nowrap">
                                                            <span class="input-group-text">
                                                              <i class="fa-solid fa-info"></i>
                                                            </span>
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="default_sale_tax" id="default_sale_tax" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                                    <option value="1">Default Sale Tax</option>
                                                                    <option value="2" selected>None</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                 <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sales_item_addition_method">
                                                        <span class="required">Sales Item Addition Method:</span>
                                                    </label>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="sales_item_addition_method" id="sales_item_addition_method" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                            <option value="1">Add item in new row</option>
                                                            <option value="2" selected>Increase item quantity if it already exists</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                      <label class="fs-6 fw-semibold form-label mt-3" for="amount_rounding_method">
                                                            <span class="required">Amount rounding method:</span>
                                                        </label>
                                                        <select name="amount_rounding_method" id="amount_rounding_method" data-hide-search="true" class="form-select" data-control="select2" data-placeholder="">
                                                            <option value="1">None</option>
                                                            <option value="2" selected>Round to nearest whole number</option>
                                                        </select>
                                                </div>
                                                 <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="minimun_selling_price" id="minimun_selling_price">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="minimun_selling_price">
                                                            <span > Sales price is minimum selling price</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="allow_overselling" id="allow_overselling">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="allow_overselling">
                                                            <span > Allow Overselling</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                {{-- <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input me-3 border-gray-400" name="enable_sale_order" id="enable_sale_order">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_sale_order">
                                                            <span > Enable Sales Order</span>
                                                        </label>
                                                    </div>
                                                </div> --}}
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input @checked($settingData['enable_line_discount_for_sale']=='1')  type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_line_discount_for_sale" id="enable_line_discount_for_sale" value="1">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_line_discount_for_sale">
                                                        <span>Enable Line Discount</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="pay_required" id="pay_required">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="pay_required">
                                                            <span > Is pay term required?</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator border-gray-600 my-4"></div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="row mb-7">
                                                    <div class="col-md-9 p-3">
                                                        <h2 class="text-primary">Commession Agent</h2>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sales_commission_agent">
                                                        <span class="required">Sales Commission Agent:</span>
                                                    </label>
                                                        <div class="input-group flex-nowrap">
                                                            <span class="input-group-text">
                                                              <i class="fa-solid fa-info"></i>
                                                            </span>
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="sales_commission_agent" id="sales_commission_agent" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="sales_commission_agent">
                                                                    <option value="1">Disabled</option>
                                                                    <option value="2" selected>Logged in user</option>
                                                                    <option value="2" selected>Select from user's list</option>
                                                                    <option value="2" selected>Select from commession agent's list</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                      <label class="fs-6 fw-semibold form-label mt-3" for="commission_calc_type">
                                                            <span class="required">Commission Calculation Type:</span>
                                                        </label>
                                                        <div class="input-group flex-nowrap">
                                                            <span class="input-group-text" >
                                                              <i class="fa-solid fa-info"></i>
                                                            </span>
                                                            <div class="overflow-hidden flex-grow-1">
                                                                <select name="commission_calc_type" id="commission_calc_type" data-hide-search="true" class="form-select rounded-start-0" data-control="select2" data-placeholder="">
                                                                    <option value="1">Invoice Value</option>
                                                                    <option value="2" selected>Payment Received</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4  d-flex align-items-center" >
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="commission_agent_required" id="commission_agent_required">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="commission_agent_required">
                                                            <span > Is commission agent required?</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator border-gray-600 my-4"></div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="row mb-7">
                                                    <div class="col-md-9 p-3">
                                                        <h2 class="d-inline text-primary">Payment Link</h2>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                    </div>
                                                </div>
                                                <div class="col-12  d-flex align-items-center py-3">
                                                    <div class="form-check form-check-custom">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_payment_link" id="enable_payment_link">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_payment_link">
                                                            <span > Enable payment link</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="row mt-4 mb-1">
                                                    <div class="col-md-9 p-3">
                                                        <h2 class="d-inline text-primary">Razorpay:</h2>
                                                        <span class="text-muted">(For INR India)</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="razorpay_key_id">
                                                        <span class="required">Key ID:</span>
                                                    </label>
                                                    <input type="text" name="razorpay_key_id" id="razorpay_key_id" class="form-control form-control">
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                      <label class="fs-6 fw-semibold form-label mt-3" for="razorpay_key_secret">
                                                            <span class="required">Key Secret:</span>
                                                        </label>
                                                        <input type="text" name="razorpay_key_secret" id="razorpay_key_secret" class="form-control form-control">
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="row mt-4 mb-1">
                                                    <div class="col-md-9 p-3">
                                                        <h2 class="d-inline">Stripe:</h2>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="stripe_public_key">
                                                        <span class="required">Stripe public key:</span>
                                                    </label>
                                                    <input type="text" name="stripe_public_key" id="stripe_public_key" class="form-control form-control">
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                      <label class="fs-6 fw-semibold form-label mt-3" for="stripe_secret_key">
                                                            <span class="required">Stripe secret key:</span>
                                                        </label>
                                                        <input type="text" name="stripe_secret_key" id="stripe_secret_key" class="form-control form-control">
                                                </div>
                                            </div>

                                    </div>

                                    {{-- pos-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_pos" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-12 p-3">
                                                    <h2>POS</h2>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-12">
                                                    <h3 class="fw-lighter fs-2 text-primary">
                                                        Add keyboard shortcuts
                                                    </h3>
                                                    <p class="text-gray-600 py-2 fs-6">
                                                        Shortcut should be the names of the keys separated by <b>'+'</b>; Example: <b>ctrl+shift+b, ctrl+h</b>
                                                    </p>
                                                    <div class="help-class ">
                                                        <h4 class=" text-gray-600 d-block fs-3">Available key names are:</h4>
                                                        <p class="fs-6 text-gray-600">
                                                            shift, ctrl, alt, backspace, tab, enter, return, capslock, esc, escape, space, pageup, pagedown, end, home,
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row  flex-wrap mt-4 mb-7">
                                                {{-- left setting --}}
                                                <div class="col-12 col-lg-6 px-5">
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                                <span class="fw-bold text-primary">Operations</span>
                                                            </label>
                                                        </div>
                                                         <div class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" >
                                                                <span class="fw-blod text-primary">Keyboard Shortcut</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="express_checkout">
                                                                <span class="">Express Checkout:</span>
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="express_checkout" value="shift+e" id="express_checkout" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="pay_checkout">
                                                                <span class="">Pay & Checkout:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="pay_checkout" value="shift+p" id="pay_checkout" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="draft">
                                                                <span class="">Draft:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="draft" value="shift+d" id="draft" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="cancle">
                                                                <span class="">Cancel:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="cancle" value="shift+c" id="cancle" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="go_product_qt">
                                                                <span class="">Go to product quantity:	</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="go_product_qt" value="f2" id="go_product_qt" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="weighing_scale">
                                                                <span class="">Weighing Scale:</span>
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
                                                                <span class="fw-bold text-primary">Operations</span>
                                                            </label>
                                                        </div>
                                                         <div class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" >
                                                                <span class="fw-blod text-primary">Keyboard Shortcut</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="edit_discount">
                                                                <span class="">Edit Discount:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="edit_discount" value="shift+i" id="edit_discount" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="edit_order_tax">
                                                                <span class="">Edit Order Tax:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="edit_order_tax" value="shift+t" id="edit_order_tax" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="add_payment_row">
                                                                <span class="">Add Payment Row:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="add_payment_row" value="shift+r" id="add_payment_row" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="finalize_payment">
                                                                <span class="">Finalize Payment:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="finalize_payment" value="shift+f" id="finalize_payment" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 row-cols flex-wrap">
                                                        <div  class="col-sm-6">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="add_new_product">
                                                                <span class="">Add new product:</span>
                                                            </label>
                                                        </div>
                                                        <div  class="col-sm-6">
                                                            <input type="text" name="add_new_product" value="f4" id="add_new_product" class="form-control form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                             <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-md-9 p-3">
                                                    <h2  class="text-primary">POS settings:</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_mulitple_pay" id="disable_mulitple_pay">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_mulitple_pay">
                                                            <span > Disable Multiple Pay</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_draft" id="disable_draft">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_draft">
                                                            <span >Disable Draft</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_express_checkout" id="disable_express_checkout">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_express_checkout">
                                                            <span > Disable Express Checkout</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                               <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="show_product_suggestion" id="show_product_suggestion">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="show_product_suggestion">
                                                            <span > Don't show product suggestion</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="recent_transactions" id="recent_transactions">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="recent_transactions">
                                                            <span> Don't show recent transactions</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_discount" id="disable_discount">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_discount">
                                                            <span > Disable Discount</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_order_tax" id="disable_order_tax">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_order_tax">
                                                            <span > Disable order tax</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="subtotal_editable" id="subtotal_editable">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="subtotal_editable">
                                                            <span> Subtotal Editable <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Check this to make Subtotal field editable for each product in POS screen"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_suspend_sale" id="disable_suspend_sale">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_suspend_sale">
                                                            <span > Disable Suspend Sale</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                               <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_transaction_date_on_pos_screen" id="enable_transaction_date_on_pos_screen">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_transaction_date_on_pos_screen">
                                                            <span >Enable transaction date on POS screen </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_serviece_staff_in_product_line" id="enable_serviece_staff_in_product_line">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_serviece_staff_in_product_line">
                                                            <span > Enable service staff in product line <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="If enabled different service staffs can be assigned for different products for an order/sale"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="service_staff_required" id="service_staff_required">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="service_staff_required">
                                                            <span> Is service staff required?</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="disable_credit_sale_btn" id="disable_credit_sale_btn">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="disable_credit_sale_btn">
                                                            <span> Disable credit sale button  <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="If enabled credit sale button will be shown in place of Card button on pos screen"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_weighting_scale" id="enable_weighting_scale">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_weighting_scale">
                                                            <span >Enable Weighing Scale</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="show_invoice_scheme" id="show_invoice_scheme">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="show_invoice_scheme">
                                                            <span>Show invoice scheme</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="show_invoice_layout_dropdown" id="show_invoice_layout_dropdown">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="show_invoice_layout_dropdown">
                                                            <span>Show invoice layout dropdown</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="print_invoice_on_suspend" id="print_invoice_on_suspend">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="print_invoice_on_suspend">
                                                            <span>Print invoice on suspend </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-7 row-cols flex-wrap">
                                                <div class="col-12 d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" name="procing_on_product_suggestion_tooltip" id="procing_on_product_suggestion_tooltip">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="procing_on_product_suggestion_tooltip">
                                                            <span>Show pricing on product suggestion tooltip</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="separator border-gray-600 my-7"></div>

                                            <div class="row row-cols">
                                                <div class="col-12">
                                                    <h3 class="fw-lighter fs-2 text-primary">
                                                        Weighing Scale barcode Setting:
                                                    </h3>
                                                    <p class="text-gray-600 py-2 fs-6">
                                                        Configure barcode as per your weighing scale.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="barcode_perfix">
                                                        <span class="required">Prefix:</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control" id="barcode_perfix">
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="product_sku_length">
                                                        <span class="">Product sku length:</span>
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
                                                            <span class="">Quantity integer part length:</span>
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
                                            <div class="row row-cols">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="quantity_fractional_part_length">
                                                        <span class="">Quantity fractional part length:</span>
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
                                            <div class="row mb-7">
                                                <div class="col-12 p-3">
                                                    <h2>Purchases</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input @checked($settingData['enable_line_discount_for_purchase']=='1')  type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_line_discount_for_purchase" id="enable_line_discount_for_purchase" value="1">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_line_discount_for_purchase">
                                                        <span>Enable Line Discount</span>
                                                    </div>
                                                </div>
                                               <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_editing_product_price_from_purchase_screen" id="enable_editing_product_price_from_purchase_screen">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_editing_product_price_from_purchase_screen">
                                                        <span>Enable editing product price from purchase screen
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="If enable product purchase price and selling price will be updated after a purchase is added or updated"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_pruchase_status" id="enable_pruchase_status">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_pruchase_status">
                                                            <span>Enable Purchase Status <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="On disable all purchases will be marked as item received"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                               <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_lot_number" id="enable_lot_number">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_lot_number">
                                                        <span> Enable Lot number
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="This will enable you to enter Lot number for each purcahse line in purchase screen"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="enable_purchase_order" id="enable_purchase_order">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_purchase_order">
                                                            <span>Enable purchase order
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
                                            <div class="row mb-7">
                                                <div class="col-12 p-3">
                                                    <h2>Payment</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                               <div class="col-12">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="cash_denominations">
                                                        <span class="">Cash Denominations:</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control" id="cash_denominations">
                                                    <span class="text-gray-700 py-2 d-block">Comma separated values Example: 100,200,500,2000</span>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12  mb-7  col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="enable_cash_denomination">
                                                        <span class="">Enable cash denomination on:</span>
                                                    </label>
                                                    <select name="enable_cash_denomination" id="enable_cash_denomination" data-hide-search="true" class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">POS screen</option>
                                                        <option value="2" >All screen</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="enable_cash_denomination_for_payment_methods">
                                                        <span class="">Enable cash denomination for payment methods:</span>
                                                    </label>
                                                    <select name="enable_cash_denomination_for_payment_methods[]" class="form-select form-select " id="enable_cash_denomination_for_payment_methods" data-control="select2" data-close-on-select="false" data-placeholder="" data-allow-clear="true" multiple="multiple">
                                                        <option></option>
                                                        <option value="cash">Cash</option>
                                                        <option value="card" >Card</option>
                                                        <option value="cheque" >Cheque</option>
                                                        <option value="bank_transfer" >Bank Transfer</option>
                                                        <option value="other" >Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" id="strict_check" name="strict_check">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="strict_check">
                                                            <span>Strict check
                                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="
                                                                    If enabled payment amount must be equal to sum of cash
                                                                    denominations
                                                                "></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    {{-- dashobard-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_dashboard" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-12 p-3">
                                                    <h2>Dashobard Setting</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="view_storck_expiry_alert_for">
                                                        <span class="required">View Stock Expiry Alert For:</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                        <input class="form-control form-control" name="view_storck_expiry_alert_for" placeholder="" id="view_storck_expiry_alert_for" value="" />
                                                        <span class="input-group-text">
                                                            Day
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                    {{-- system-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_system" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-12 p-3">
                                                    <h2>System Setting</h2>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12  mb-7  col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="theme_color">
                                                        <span class="">Theme Color</span>
                                                    </label>
                                                    <select name="theme_color" id="theme_color"  class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">Blue</option>
                                                        <option value="2" ></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12  mb-7  col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="default_page_entries">
                                                        <span class="">Default datatable page entries</span>
                                                    </label>
                                                    <select name="default_page_entries" id="default_page_entries"  class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">25</option>
                                                        <option value="2" >50</option>
                                                        <option value="2" >100</option>
                                                        <option value="2" >125</option>

                                                    </select>
                                                </div>

                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-13 col-lg-6  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" id="show_help_text" name="show_help_text">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="show_help_text">
                                                            <span>Show help text
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                    {{-- prefix-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_prefix" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-12 p-3">
                                                    <h2>Prefix</h2>
                                                </div>
                                            </div>

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase">
                                                        <span class="">Purchase:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="purchase" name="purchase" value="PO" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="purchase_return">
                                                            <span class="">Purchase Return:</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="purchase_return" id="purchase_return" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_order">
                                                        <span class="">Purchase Order:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="purchase_order" id="purchase_order" value=""  />
                                                </div>
                                            </div>

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="stock_transfer">
                                                        <span class="">Stock Transfer:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="stock_transfer" name="stock_transfer" value="ST" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="stock_adjustment">
                                                            <span class="">Stock Adjustment:</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="stock_adjustment" id="stock_adjustment" value="SA" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_return">
                                                        <span class="">Sell Return:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="sell_return" id="sell_return" value="SN"  />
                                                </div>
                                            </div>

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="expenses">
                                                        <span class="">Expenses:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="expenses" name="expenses" value="EP" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="contacts">
                                                            <span class="">Contacts:</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="contacts" id="contacts" value="CO" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_payment">
                                                        <span class="">Purchase Payment:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="purchase_payment" id="purchase_payment" value="PP"  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_payment">
                                                        <span class="">Sell Payment:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="sell_payment" name="sell_payment" value="SP" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="expense_payment">
                                                            <span class="">Expense Payment:</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="expense_payment" id="expense_payment" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="business_location">
                                                        <span class="">Business Location:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="business_location" id="business_location" value="BL"  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="perfixes_username">
                                                        <span class="">Username:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="perfixes_username" name="perfixes_username" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="subscription_no">
                                                            <span class="">Subscription No.:</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="subscription_no" id="subscription_no" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="perfixes_draft">
                                                        <span class="">Draft:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="perfixes_draft" id="perfixes_draft" value=""  />
                                                </div>
                                            </div>

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sales_order">
                                                        <span class="">Sales Order:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="sales_order" name="sales_order" value="" />
                                                </div>
                                            </div>
                                    </div>

                                    {{-- emailSetting-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_emailSetting" role="tabpanel">
                                         <!--begin::Heading-->
                                        <div class="row mb-7">
                                            <div class="col-12 p-3">
                                                <h2>emailSetting</h2>
                                            </div>
                                        </div>

                                        <div class="row fv-row row-cols flex-wrap">
                                            <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_driver">
                                                        <span class="">Mail Driver</span>
                                                    </label>
                                                    <select name="mail_driver" data-hide-search="true" id="mail_driver"  class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="1">SMTP</option>
                                                    </select>
                                                </div>
                                            <div class="col-md-12  mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_host">
                                                        <span class="">Host:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="mail_host" id="mail_host" value="" placeholder="" />
                                            </div>
                                            <div class="col-md-12 mb-7 col-lg-4">
                                                <!--begin::Label-->
                                                <label class="fs-6 fw-semibold form-label mt-3" for="mail_port">
                                                    <span class="">Port:</span>
                                                </label>
                                                <!--end::Label-->
                                                <input type="text" class="form-control form-control" name="mail_port" id="mail_port" value=""  />
                                            </div>
                                        </div>


                                        <div class="row fv-row row-cols flex-wrap">
                                            <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_driver">
                                                        <span class="">Username:</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control" id="mail_username">
                                                </div>
                                            <div class="col-md-12  mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_password">
                                                        <span class="">Password:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="password" class="form-control form-control" name="mail_password" id="mail_password" value="" placeholder="" />
                                            </div>
                                            <div class="col-md-12 mb-7 col-lg-4">
                                                <!--begin::Label-->
                                                <label class="fs-6 fw-semibold form-label mt-3" for="mail_encryption">
                                                    <span class="">Encryption:</span>
                                                </label>
                                                <!--end::Label-->
                                                <input type="text" class="form-control form-control" name="mail_encryption" id="mail_encryption" value="" placeholder="tls/ ssl" />
                                            </div>
                                        </div>


                                        <div class="row fv-row row-cols flex-wrap">
                                            <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_form_address">
                                                        <span class="">From Address:</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control" id="mail_form_address">
                                                </div>
                                            <div class="col-md-12  mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="mail_from_name">
                                                        <span class="">From Name:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="password" class="form-control form-control" name="mail_from_name" id="mail_from_name" value="" placeholder="" />
                                            </div>
                                        </div>
                                        <button class="btn btn-success btn-sm float-end">Send Test Mail</button>

                                    </div>

                                    {{-- sms-tab --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_sms" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-12 p-3">
                                                    <h2>SMS Setting</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7  col-lg-4">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sms_service">
                                                        <span class="">SMS Service</span>
                                                    </label>
                                                    <select name="sms_service" data-hide-search="true" id="sms_service"  class="form-select" data-control="select2" data-placeholder="">
                                                        <option value="nexmo">Nexmo</option>
                                                        <option value="twilio" >Twilio</option>
                                                        <option value="other" selected >other</option>
                                                    </select>
                                                </div>
                                                <div id="other-tab">
                                                    <div class="row fv-row row-cols flex-wrap">
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="sms_url">
                                                                <span class="">URL:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" id="sms_url" name="sms_url" value="" placeholder="url"/>
                                                        </div>
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_name">
                                                                    <span class="">Send to parameter name:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_name" id="parameter_name" value="" placeholder="Send to parameter name" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="message_para_name">
                                                                <span class="">Message parameter name:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="message_para_name" id="message_para_name" value="" placeholder="Message parameter name" />
                                                        </div>

                                                    </div>
                                                    <div class="row row-cols flex-wrap">
                                                        <div class="col-md-12  mb-7  col-lg-4">
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="request_method">
                                                                <span class="">Request Method</span>
                                                            </label>
                                                            <select name="request_method" data-hide-search="true" id="request_method"  class="form-select" data-control="select2" data-placeholder="">
                                                                <option value="get">GET</option>
                                                                <option value="post" >POST</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="separator border-gray-600 my-4"></div>

                                                    <div class="row row-cols flex-wrap">
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="header_1_key">
                                                                    <span class="">Header 1 key:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="header_1_key" id="header_1_key" value="" placeholder="Send to parameter name" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="header_1_value">
                                                                <span class="">Header 1 value:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="header_1_value" id="header_1_value" value="" placeholder="Message parameter name" />
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols flex-wrap">
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="header_2_key">
                                                                    <span class="">Header 2 key:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="header_2_key" id="header_2_key" value="" placeholder="Send to parameter name" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="header_2_value">
                                                                <span class="">Header 2 value:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" name="header_2_value" id="header_2_value" value="" placeholder="Message parameter name" />
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols flex-wrap">
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="header_3_key">
                                                                    <span class="">Header 3 key:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="header_3_key" id="header_3_key" value="" placeholder="Send to parameter name" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="header_3_value">
                                                                <span class="">Header 3 value:</span>
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
                                                                        <span class="">Parameter 1 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_1_key" id="parameter_1_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_1_value">
                                                                    <span class="">Parameter 1 value:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_1_value" id="parameter_1_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_2_key">
                                                                        <span class="">Parameter 2 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_2_key" id="parameter_2_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_2_value">
                                                                    <span class="">Parameter 2 value:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_2_value" id="parameter_2_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_3_key">
                                                                        <span class="">Parameter 3 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_3_key" id="parameter_3_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_3_value">
                                                                    <span class="">Parameter 3 value:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_3_value" id="parameter_3_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>

                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_4_key">
                                                                        <span class="">Parameter 4 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_4_key" id="parameter_4_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_4_value">
                                                                    <span class="">Parameter 4 value:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_4_value" id="parameter_4_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_5_key">
                                                                        <span class="">Parameter 5 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_5_key" id="parameter_5_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_5_value">
                                                                    <span class="">Parameter 5 value:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_5_value" id="parameter_5_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_6_key">
                                                                        <span class="">Parameter 6 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_6_key" id="parameter_6_key" value="" placeholder="Send to parameter name" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_6_value">
                                                                    <span class="">Parameter 6 value:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_6_value" id="parameter_6_value" value="" placeholder="Message parameter name" />
                                                            </div>
                                                        </div>

                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_7_key">
                                                                        <span class="">Parameter 7 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_7_key" id="parameter_7_key" value="" placeholder="Parameter 7 key" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_7_value">
                                                                    <span class="">Parameter 7 value:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_7_value" id="parameter_7_value" value="" placeholder="Parameter 7 value" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_8_key">
                                                                        <span class="">Parameter 8 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_8_key" id="parameter_8_key" value="" placeholder="Parameter 8 key" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_8_value">
                                                                    <span class="">Parameter 8 value:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_8_value" id="parameter_8_value" value="" placeholder="Parameter 8 value" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_9_key">
                                                                        <span class="">Parameter 9 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_9_key" id="parameter_9_key" value="" placeholder="Parameter 9 key" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_9_value">
                                                                    <span class="">Parameter 9 value:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="parameter_9_value" id="parameter_9_value" value="" placeholder="Parameter9 value" />
                                                            </div>
                                                        </div>
                                                        <div class="row row-cols flex-wrap">
                                                            <div class="col-md-12  mb-7 col-lg-4">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-semibold form-label mt-3" for="parameter_10_key">
                                                                        <span class="">Parameter 10 key:</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <input type="text" class="form-control form-control" name="parameter_10_key" id="parameter_10_key" value="" placeholder="Parameter 10 key" />
                                                            </div>
                                                            <div class="col-md-12 mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="parameter_10_value">
                                                                    <span class="">Parameter 10 value:</span>
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
                                                <div id="nexmo-tab" class=" d-none">
                                                        <div class="row fv-row row-cols flex-wrap">
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="nexmo_key">
                                                                <span class="">Nexmo Key:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" id="nexmo_key" name="nexmo_key" value="" placeholder="Nexmo key"/>
                                                        </div>
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="nexmo_secret">
                                                                    <span class="">Nexmo Secret:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="nexmo_secret" id="nexmo_secret" value="" placeholder="Nexmo Secret:" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="nexmo_from">
                                                                <span class="">From:</span>
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
                                                                <span class="">Twilio Key:</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <input type="text" class="form-control form-control" id="twilio_key" name="twilio_key" value="" placeholder="Nexmo key"/>
                                                        </div>
                                                        <div class="col-md-12  mb-7 col-lg-4">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold form-label mt-3" for="twilio_secret">
                                                                    <span class="">Twilio Secret:</span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <input type="text" class="form-control form-control" name="twilio_secret" id="twilio_secret" value="" placeholder="Nexmo Secret:" />
                                                        </div>
                                                        <div class="col-md-12 mb-7 col-lg-4">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-semibold form-label mt-3" for="twilio_from">
                                                                <span class="">From:</span>
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
                                            <div class="row mb-7">
                                                <div class="col-md-9 p-3">
                                                    <h2  class="text-primary">Contact</h2>
                                                </div>
                                            </div>
                                            <div class="row row-cols">
                                                <div class="col-md-12 mb-13 col-lg-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input type="checkbox" class="form-check-input border-gray-400 me-3" id="enable_reward_point" name="enable_reward_point">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="enable_reward_point">
                                                            <span> Enable Reward Point
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="reward_point_display_name">
                                                        <span class="">Reward Point Display Name:</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="reward_point_display_name" name="reward_point_display_name" value="" placeholder="Reward Point Display Name"/>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h2 class="d-inline text-primary">Earning Points Settings:</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">

                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="amount_spend_for_unit_point">
                                                        <span class="">Amount spend for unit point:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="amount_spend_for_unit_point" name="amount_spend_for_unit_point" value="" placeholder="Amount spend for unit point"/>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="minimun_order_total_to_earn_reward">
                                                        <span class="">Minimum order total to earn reward:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="minimun_order_total_to_earn_reward" name="minimun_order_total_to_earn_reward" value="" placeholder="Minimum order total to earn reward:"/>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="maximum_points_per_order">
                                                        <span class="">Maximum points per order:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="maximum_points_per_order" name="maximum_points_per_order" value="" placeholder="maximum_points_per_order"/>
                                                </div>

                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h2 class="d-inline text-primary">Redeem Points Settings:</h2>
                                                </div>
                                            </div>
                                            <div class="separator border-gray-600 my-4"></div>

                                            <div class="row fv-row row-cols flex-wrap">

                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="redeem_amount_per_unit_point">
                                                        <span class="">Redeem amount per unit point:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="redeem_amount_per_unit_point" name="redeem_amount_per_unit_point" value="" placeholder="Redeem amount per unit point"/>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-5">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="minimun_order_total_to_redeem_reward">
                                                        <span class="">Minimum order total to redeem points:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="By enabling users can pay invoice using payment link."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="minimun_order_total_to_redeem_reward" name="minimun_order_total_to_redeem_reward" value="" placeholder="Minimum order total to earn reward:"/>
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-3">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="minium_redeem_point">
                                                        <span class="">Minimum redeem point:
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
                                                        <span class="">Maximum redeem point per order:
                                                              <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="."></i>
                                                        </span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="maximum_redeem_point_per_order" name="maximum_redeem_point_per_order" value="" placeholder="Maximum redeem point per order"/>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-7">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="reward_point_expiry_period">
                                                            <span class="required">Reward Point expiry period: </span>
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
                                            <div class="row mb-7">
                                                <div class="col-12 p-3">
                                                    <h2>Modules</h2>
                                                </div>
                                            </div>
                                             <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-md-9 p-3">
                                                    <h2  class="text-primary">Enable/Disable Modules</h2>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_purchases" id="module_purchases">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_purchases">
                                                            <span >Purchases</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_add_sale" id="module_add_sale">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_add_sale">
                                                            <span >Add Sale</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_pos" id="module_pos">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_pos">
                                                            <span >POS</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_stock_transfers" id="module_stock_transfers">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_stock_transfers">
                                                            <span> Stock Transfers</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="modulse_stock_adjustment" id="modulse_stock_adjustment">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="modulse_stock_adjustment">
                                                            <span> Stock Adjustment</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_expenses" id="module_expenses">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_expenses">
                                                            <span >Expenses</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_account" id="module_account">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_account">
                                                            <span> Account</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_table" id="module_table">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_table">
                                                            <span>Tables</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_modifiers" id="module_modifiers">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_modifiers">
                                                            <span >Modifiers</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_service_staff" id="module_service_staff">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_service_staff">
                                                            <span> Service staff</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="modulse_enable_bookings" id="modulse_enable_bookings">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="modulse_enable_bookings">
                                                            <span> Enable Bookings</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_order_display_restaurants" id="module_order_display_restaurants">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_order_display_restaurants">
                                                            <span > Order (For restaurants)</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_enable_subscription" id="module_enable_subscription">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_enable_subscription">
                                                            <span> Enable Subscription</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-13 col-md-4  d-flex align-items-center">
                                                    <div class="form-check form-check-custom ">
                                                        <input checked type="checkbox" class="form-check-input border-gray-400 me-3" name="module_type_of_service" id="module_type_of_service">
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="module_type_of_service">
                                                            <span> Types of service</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                    {{-- business_setting_custom_labels --}}
                                    <div class="tab-pane setting-tab fade" id="business_setting_custom_labels" role="tabpanel">
                                         <!--begin::Heading-->
                                            <div class="row mb-7">
                                                <div class="col-12 p-3">
                                                    <h2>Custom Label</h2>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">Labels for custom payments:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_1">
                                                        <span class="">Custom Payment 1</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="custom_payment_1" name="custom_payment_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_2">
                                                            <span class="">Custom Payment 2</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="custom_payment_2" id="custom_payment_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_3">
                                                        <span class="">Custom Payment 3</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="custom_payment_3" id="custom_payment_3" value=""  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_4">
                                                        <span class="">Custom Payment 4</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="custom_payment_4" name="custom_payment_4" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_5">
                                                            <span class="">Custom Payment 5</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="custom_payment_5" id="custom_payment_5" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_6">
                                                        <span class="">Custom Payment 6</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="custom_payment_6" id="custom_payment_6" value=""  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="custom_payment_7">
                                                        <span class="">Custom Payment 7</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="custom_payment_7" name="custom_payment_7" value="" />
                                                </div>
                                            </div>

                                            <div class="separator border-gray-600 my-4"></div>

                                            {{-- contact customer field --}}
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">Labels for contact custom fields</h3>
                                                </div>
                                            </div>

                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_1">
                                                        <span class="">Custom Field 1</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_1" name="contact_custom_field_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_2">
                                                            <span class="">Custom Field 2</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="contact_custom_field_2" id="contact_custom_field_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_3">
                                                        <span class="">Custom Field 3</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="contact_custom_field_3" id="contact_custom_field_3" value=""  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_4">
                                                        <span class="">Custom Field 4</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_4" name="contact_custom_field_4" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_5">
                                                            <span class="">Custom Field 5</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="contact_custom_field_5" id="contact_custom_field_5" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_6">
                                                        <span class="">Custom Field 6</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="contact_custom_field_6" id="contact_custom_field_6" value=""  />
                                                </div>
                                            </div>


                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_7">
                                                        <span class="">Custom Field 7</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_7" name="contact_custom_field_7" value="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_8">
                                                        <span class="">Custom Field 8</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_8" name="contact_custom_field_8" value="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_9">
                                                        <span class="">Custom Field 9</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_9" name="contact_custom_field_9" value="" />
                                                </div>
                                            </div>

                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="contact_custom_field_10">
                                                        <span class="">Custom Field 10</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="contact_custom_field_10" name="contact_custom_field_10" value="" />
                                                </div>
                                            </div>


                                            {{-- product custom --}}
                                            <div class="separator border-gray-600 my-4"></div>
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">Labels for product custom fields:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="product_custom_field_1">
                                                        <span class="">Custom Field 1</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="product_custom_field_1" name="product_custom_field_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="product_custom_field_2">
                                                            <span class="">Custom Field 2</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="product_custom_field_2" id="product_custom_field_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="product_custom_field_3">
                                                        <span class="">Custom Field 3</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="product_custom_field_3" id="product_custom_field_3" value=""  />
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="product_custom_field_4">
                                                        <span class="">Custom Field 4</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="product_custom_field_4" id="product_custom_field_4" value=""  />
                                                </div>
                                            </div>
                                            {{-- location custom --}}
                                            <div class="separator border-gray-600 my-4"></div>
                                                <div class="row mb-2">
                                                    <div class="col-md-9 p-3">
                                                        <h3 class="text-primary">Labels for location custom fields:</h3>
                                                    </div>
                                                </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="location_custom_field_1">
                                                        <span class="">Custom Field 1</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="location_custom_field_1" name="location_custom_field_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="location_custom_field_2">
                                                            <span class="">Custom Field 2</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="location_custom_field_2" id="location_custom_field_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="location_custom_field_3">
                                                        <span class="">Custom Field 3</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="location_custom_field_3" id="location_custom_field_3" value=""  />
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="location_custom_field_4">
                                                        <span class="">Custom Field 4</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="location_custom_field_4" id="location_custom_field_4" value=""  />
                                                </div>
                                            </div>
                                            {{-- user custom --}}
                                            <div class="separator border-gray-600 my-4"></div>
                                             <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">Labels for user custom fields:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="user_custom_field_1">
                                                        <span class="">Custom Field 1</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="user_custom_field_1" name="user_custom_field_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="user_custom_field_2">
                                                            <span class="">Custom Field 2</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="user_custom_field_2" id="user_custom_field_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="user_custom_field_3">
                                                        <span class="">Custom Field 3</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="user_custom_field_3" id="user_custom_field_3" value=""  />
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="user_custom_field_4">
                                                        <span class="">Custom Field 4</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="user_custom_field_4" id="user_custom_field_4" value=""  />
                                                </div>
                                            </div>
                                            {{-- shipping custom fields --}}
                                            <div class="separator border-gray-600 my-4"></div>

                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">Labels for purchase shipping custom fields:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_1">
                                                        <span >Custom Field 1</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_1" placeholder="" id="purchase_shipping_customer_field_1" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_1_required" id="purchase_shipping_customer_field_1_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_1_required">
                                                                <span >Is required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_2">
                                                        <span >Custom Field 2</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_2" placeholder="" id="purchase_shipping_customer_field_2" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_2_required" id="purchase_shipping_customer_field_2_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_2_required">
                                                                <span >Is required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_3">
                                                        <span >Custom Field 3</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_3" placeholder="" id="purchase_shipping_customer_field_3" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_3_required" id="purchase_shipping_customer_field_3_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_3_required">
                                                                <span >Is required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_4">
                                                        <span >Custom Field 4</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_4" placeholder="" id="purchase_shipping_customer_field_4" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_4_required" id="purchase_shipping_customer_field_4_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_4_required">
                                                                <span >Is required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="purchase_shipping_customer_field_5">
                                                        <span >Custom Field 5</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="purchase_shipping_customer_field_5" placeholder="" id="purchase_shipping_customer_field_5" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="purchase_shipping_customer_field_5_required" id="purchase_shipping_customer_field_5_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="purchase_shipping_customer_field_5_required">
                                                                <span >Is required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="separator border-gray-600 my-4"></div>
                                            {{-- sell custom --}}
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">Labels for sell custom fields:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_custom_field_1">
                                                        <span >Custom Field 1</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="sell_custom_field_1" placeholder="" id="sell_custom_field_1" value="sell_custom_field_1" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sell_custom_field_1_required" id="sell_custom_field_1_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sell_custom_field_1_required">
                                                                <span >Is required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_custom_field_2">
                                                        <span >Custom Field 2</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="sell_custom_field_2" placeholder="" id="sell_custom_field_2" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sell_custom_field_2_required" id="sell_custom_field_2_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sell_custom_field_2_required">
                                                                <span >Is required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_custom_field_3">
                                                        <span >Custom Field 3</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="sell_custom_field_3" placeholder="" id="sell_custom_field_3" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sell_custom_field_3_required" id="sell_custom_field_3_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sell_custom_field_3_required">
                                                                <span >Is required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-6">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sell_custom_field_4">
                                                        <span >Custom Field 4</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control" name="sell_custom_field_4" placeholder="" id="sell_custom_field_4" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sell_custom_field_4_required" id="sell_custom_field_4_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sell_custom_field_4_required">
                                                                <span >Is required</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator border-gray-600 my-4"></div>
                                                <div class="row mb-2">
                                                    <div class="col-md-9 p-3">
                                                        <h3 class="text-primary">Labels for sale shipping custom fields:</h3>
                                                    </div>
                                                </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_1">
                                                        <span >Custom Field 1</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_1" placeholder="" id="sale_shipping_custom_field_1" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_1_required" id="sale_shipping_custom_field_1_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_1_required">
                                                                <span >Is required</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_1_default_contact" id="sale_shipping_custom_field_1_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_1_default_contact">
                                                                <span > Is default for contact?</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_2">
                                                        <span >Custom Field 2</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_2" placeholder="" id="sale_shipping_custom_field_2" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_2_required" id="sale_shipping_custom_field_2_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_2_required">
                                                                <span >Is required</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_2_default_contact" id="sale_shipping_custom_field_2_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_2_default_contact">
                                                                <span > Is default for contact?</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_3">
                                                        <span >Custom Field 3</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_3" placeholder="" id="sale_shipping_custom_field_3" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_3_required" id="sale_shipping_custom_field_3_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_3_required">
                                                                <span >Is required</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_3_default_contact" id="sale_shipping_custom_field_3_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_3_default_contact">
                                                                <span > Is default for contact?</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_4">
                                                        <span >Custom Field 4</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_4" placeholder="" id="sale_shipping_custom_field_4" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_4_required" id="sale_shipping_custom_field_4_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_4_required">
                                                                <span >Is required</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_4_default_contact" id="sale_shipping_custom_field_4_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_4_default_contact">
                                                                <span > Is default for contact?</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-12  mb-7 ">
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="sale_shipping_custom_field_5">
                                                        <span >Custom Field 5</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input class="form-control form-control flex-grow-1" name="sale_shipping_custom_field_5" placeholder="" id="sale_shipping_custom_field_5" value="" />
                                                        <div class="form-check form-check-custom input-group-text input-group-sm p-3 justify-content-center">
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_5_required" id="sale_shipping_custom_field_5_required">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_5_required">
                                                                <span >Is required</span>
                                                            </label>
                                                            <input  type="checkbox" class="form-check-input border-gray-400 " name="sale_shipping_custom_field_5_default_contact" id="sale_shipping_custom_field_5_default_contact">
                                                            <label class="fs-6 fw-semibold form-label mb-0 px-3" for="sale_shipping_custom_field_5_default_contact">
                                                                <span > Is default for contact?</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator border-gray-600 my-4"></div>
                                            {{-- label for type fo service --}}
                                            <div class="row mb-2">
                                                <div class="col-md-9 p-3">
                                                    <h3 class="text-primary">Labels for types of service custom fields:</h3>
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_1">
                                                        <span class="">Custom Payment 1</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="types_of_service_custom_1" name="types_of_service_custom_1" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_2">
                                                            <span class="">Custom Payment 2</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="types_of_service_custom_2" id="types_of_service_custom_2" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_3">
                                                        <span class="">Custom Payment 3</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" name="types_of_service_custom_3" id="types_of_service_custom_3" value=""  />
                                                </div>
                                            </div>
                                            <div class="row fv-row row-cols flex-wrap">
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_4">
                                                        <span class="">Custom Payment 4</span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <input type="text" class="form-control form-control" id="types_of_service_custom_4" name="types_of_service_custom_4" value="" />
                                                </div>
                                                <div class="col-md-12  mb-7 col-lg-4">
                                                        <!--begin::Label-->
                                                        <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_5">
                                                            <span class="">Custom Payment 5</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input type="text" class="form-control form-control" name="types_of_service_custom_5" id="types_of_service_custom_5" value="" placeholder="" />
                                                </div>
                                                <div class="col-md-12 mb-7 col-lg-4">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mt-3" for="types_of_service_custom_6">
                                                        <span class="">Custom Payment 6</span>
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
                                    <button type="reset" data-kt-ecommerce-settings-type="cancel" class="btn btn-light me-3">Cancel</button>

                                    <button class="btn btn-primary" type="submit">Save</button>
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
@endpush

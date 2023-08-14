@extends('App.main.navBar')
@section('contact_active','active')
@section('contact_active_show','active show')
@section('supplier_here_show','here show')
@section('styles')
<style>
    .individual-div.hide,
    .business-div.hide,
    .customer-group.hide,
    .credit-limit.hide {
        display: none;
    }
</style>
@endsection

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Edit Supplier</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Contact</li>
    <li class="breadcrumb-item text-muted">Supplier</li>
    <li class="breadcrumb-item text-dark">Edit Supplier</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::container-->
    <div class="container-xxl" id="kt_content_container">
        @if(session('error'))
        <div class="alert alert-dismissible bg-light-danger d-flex align-items-center flex-sm-row p-5 mb-10">
            <span class="text-danger">{{ session('error') }}</span>
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        @endif
        <form action="{{route('suppliers.update', $supplier->id)}}" method="POST" id="edit_supplier_form">
            @csrf
            @method('PUT')
            <!--begin::Card-->
            <div class="card card-p-4 card-flush">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <label for="contact-type" class="required form-label">Contact Type</label>
                            <select name="type" class="form-select form-select-sm fs-7" id="contact-type" required aria-label="Select example" onchange="showCustomerGroup()">
                                <option value="Supplier" {{ $supplier->type == "Supplier"  ? 'selected' : '' }}>Suppliers</option>
                                <option value="Customer" {{ $supplier->type == "Customer"  ? 'selected' : '' }}>Customers</option>
                                <option value="Both" {{ $supplier->type == "Both"  ? 'selected' : '' }}>Both (Suppliers and Customers)</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 mt-6 mb-6">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-check form-check-custom form-check-solid me-5">
                                        <input class="form-check-input" type="radio" id="individual" value="" />
                                        <span class="form-check-label">
                                            Individual
                                        </span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" id="business" value="" />
                                        <span class="form-check-label">
                                            Business
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-6">
                            <label for="contact_id" class="form-label">Contact ID</label>
                            <input type="text" name="contact_id" id="contact_id" value="{{old('contact_id',$supplier->contact_id)}}" class="form-control form-control-sm fs-7" placeholder="Contact ID" />
                            <span class="text-gray-400">Leave empty to autogenerate</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8 customer-group hide" @if($supplier->type == 'Both') style="display: block;" @endif>
                            <label for="customer_group_id" class="form-label">Customer Group</label>
                            @php
                            $customer_groups = \App\Models\Contact\CustomerGroup::all();
                            @endphp
                            <select name="customer_group_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="None" data-hide-search="true">
                                <option></option>
                                @foreach($customer_groups as $customer_group)
                                <option value="{{$customer_group->id}}" {{ old('customer_group_id', $supplier->customer_group_id) == $customer_group->id ? 'selected' : ''}}>
                                    {{$customer_group->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8 customer-group hide" @if($supplier->type == 'Both') style="display: block;" @endif>
                            <label class="form-label">Price List</label>
                            @php
                            $price_lists = \App\Models\Product\PriceLists::all();
                            @endphp
                            <select name="pricelist_id" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="None" data-hide-search="true">
                                <option></option>
                                @foreach($price_lists as $price_list)
                                <option value="{{$price_list->id}}" {{ old('pricelist_id', $supplier->pricelist_id) == $price_list->id ? 'selected' : ''}}>
                                    {{$price_list->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8 business-div">
                            <label for="company_name" class="form-label">Business Name</label>
                            <input type="text" name="company_name" id="company_name" value="{{old('company_name',$supplier->company_name)}}" class="form-control form-control-sm fs-7" placeholder="Business Name" />
                        </div>
                    </div>
                    <div class="row individual-div">
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="prefix" class="form-label">Prefix</label>
                            <input type="text" name="prefix" id="prefix" value="{{old('prefix',$supplier->prefix)}}" class="form-control form-control-sm fs-7" placeholder="Mr / Mrs / Miss" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{old('first_name',$supplier->first_name)}}" class="form-control form-control-sm fs-7" placeholder="First Name" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" value="{{old('middle_name',$supplier->middle_name)}}" class="form-control form-control-sm fs-7" placeholder="Middle Name" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{old('last_name',$supplier->last_name)}}" class="form-control form-control-sm fs-7" placeholder="Last Name" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="mobile" class="required form-label">Mobile</label>
                            <input type="text" name="mobile" id="mobile" value="{{old('mobile',$supplier->mobile)}}" class="form-control form-control-sm fs-7" placeholder="Mobile" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="alternate_number" class="form-label">Alternate Contact Number</label>
                            <input type="text" name="alternate_number" value="{{old('alternate_number',$supplier->alternate_number)}}" id="alternate_number" class="form-control form-control-sm fs-7" placeholder="Alternate Contact Numbe" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="landline" class="form-label">Landline</label>
                            <input type="text" name="landline" id="landline" value="{{old('landline',$supplier->landline)}}" class="form-control form-control-sm fs-7" placeholder="Landline" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="email" id="email" value="{{old('email',$supplier->email)}}" class="form-control form-control-sm fs-7" placeholder="Email" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="address_line_1" class="form-label">Address Line 1</label>
                            <input type="text" name="address_line_1" id="address_line_1" value="{{old('address_line_1',$supplier->address_line_1)}}" class="form-control form-control-sm fs-7" placeholder="Address Line 1">
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="address_line_2" class="form-label">Address Line 2</label>
                            <input type="text" name="address_line_2" id="address_line_2" value="{{old('address_line_2',$supplier->address_line_2)}}" class="form-control form-control-sm fs-7" placeholder="Address Line 2">
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" value="{{old('city',$supplier->city)}}" class="form-control form-control-sm fs-7" placeholder="City">
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="state" class="form-label">State</label>
                            <input type="text" name="state" id="state" value="{{old('state',$supplier->state)}}" class="form-control form-control-sm fs-7" placeholder="State">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="country" class="form-label">Country</label>
                            <select name="country" id="country" class="form-select form-select-sm fs-7" data-control="select2" data-placeholder="Select Country">
                                <option></option>
                                <option value="Afghanistan" {{ $supplier->country == "Afghanistan"  ? 'selected' : '' }}>Afghanistan</option>
                                <option value="Aland Islands" {{ $supplier->country == "Aland Islands"  ? 'selected' : '' }}>Aland Islands</option>
                                <option value="Albania" {{ $supplier->country == "Albania"  ? 'selected' : '' }}>Albania</option>
                                <option value="Algeria" {{ $supplier->country == "Algeria"  ? 'selected' : '' }}>Algeria</option>
                                <option value="American Samoa" {{ $supplier->country == "American Samoa"  ? 'selected' : '' }}>American Samoa</option>
                                <option value="Andorra" {{ $supplier->country == "Andorra"  ? 'selected' : '' }}>Andorra</option>
                                <option value="Angola" {{ $supplier->country == "Angola"  ? 'selected' : '' }}>Angola</option>
                                <option value="Anguilla" {{ $supplier->country == "Anguilla"  ? 'selected' : '' }}>Anguilla</option>
                                <option value="Antigua and Barbuda" {{ $supplier->country == "Antigua and Barbuda"  ? 'selected' : '' }}>Antigua and Barbuda</option>
                                <option value="Argentina" {{ $supplier->country == "Argentina"  ? 'selected' : '' }}>Argentina</option>
                                <option value="Armenia" {{ $supplier->country == "Armenia"  ? 'selected' : '' }}>Armenia</option>
                                <option value="Aruba" {{ $supplier->country == "Aruba"  ? 'selected' : '' }}>Aruba</option>
                                <option value="Australia" {{ $supplier->country == "Australia"  ? 'selected' : '' }}>Australia</option>
                                <option value="Austria" {{ $supplier->country == "Austria"  ? 'selected' : '' }}>Austria</option>
                                <option value="Azerbaijan" {{ $supplier->country == "Azerbaijan"  ? 'selected' : '' }}>Azerbaijan</option>
                                <option value="Bahamas" {{ $supplier->country == "Bahamas"  ? 'selected' : '' }}>Bahamas</option>
                                <option value="Bahrain" {{ $supplier->country == "Bahrain"  ? 'selected' : '' }}>Bahrain</option>
                                <option value="Bangladesh" {{ $supplier->country == "Bangladesh"  ? 'selected' : '' }}>Bangladesh</option>
                                <option value="Barbados" {{ $supplier->country == "Barbados"  ? 'selected' : '' }}>Barbados</option>
                                <option value="Belarus" {{ $supplier->country == "Belarus"  ? 'selected' : '' }}>Belarus</option>
                                <option value="Belgium" {{ $supplier->country == "Belgium"  ? 'selected' : '' }}>Belgium</option>
                                <option value="Belize" {{ $supplier->country == "Belize"  ? 'selected' : '' }}>Belize</option>
                                <option value="Benin" {{ $supplier->country == "Benin"  ? 'selected' : '' }}>Benin</option>
                                <option value="Bermuda" {{ $supplier->country == "Bermuda"  ? 'selected' : '' }}>Bermuda</option>
                                <option value="Bhutan" {{ $supplier->country == "Bhutan"  ? 'selected' : '' }}>Bhutan</option>
                                <option value="Bolivia, Plurinational State of" {{ $supplier->country == "Bolivia, Plurinational State of"  ? 'selected' : '' }}>Bolivia, Plurinational State of</option>
                                <option value="Bonaire, Sint Eustatius and Saba" {{ $supplier->country == "Bonaire, Sint Eustatius and Saba"  ? 'selected' : '' }}>Bonaire, Sint Eustatius and Saba</option>
                                <option value="Bosnia and Herzegovina" {{ $supplier->country == "Bosnia and Herzegovina"  ? 'selected' : '' }}>Bosnia and Herzegovina</option>
                                <option value="Botswana" {{ $supplier->country == "Botswana"  ? 'selected' : '' }}>Botswana</option>
                                <option value="Brazil" {{ $supplier->country == "Brazil"  ? 'selected' : '' }}>Brazil</option>
                                <option value="British Indian Ocean Territory" {{ $supplier->country == "British Indian Ocean Territory"  ? 'selected' : '' }}>British Indian Ocean Territory</option>
                                <option value="Brunei Darussalam" {{ $supplier->country == "Brunei Darussalam"  ? 'selected' : '' }}>Brunei Darussalam</option>
                                <option value="Bulgaria" {{ $supplier->country == "Bulgaria"  ? 'selected' : '' }}>Bulgaria</option>
                                <option value="Burkina Faso" {{ $supplier->country == "Burkina Faso"  ? 'selected' : '' }}>Burkina Faso</option>
                                <option value="Burundi" {{ $supplier->country == "Burundi"  ? 'selected' : '' }}>Burundi</option>
                                <option value="Cambodia" {{ $supplier->country == "Cambodia"  ? 'selected' : '' }}>Cambodia</option>
                                <option value="Cameroon" {{ $supplier->country == "Cameroon"  ? 'selected' : '' }}>Cameroon</option>
                                <option value="Canada" {{ $supplier->country == "Canada"  ? 'selected' : '' }}>Canada</option>
                                <option value="Cape Verde" {{ $supplier->country == "Cape Verde"  ? 'selected' : '' }}>Cape Verde</option>
                                <option value="Cayman Islands" {{ $supplier->country == "Cayman Islands"  ? 'selected' : '' }}>Cayman Islands</option>
                                <option value="Central African Republic" {{ $supplier->country == "Central African Republic"  ? 'selected' : '' }}>Central African Republic</option>
                                <option value="Chad" {{ $supplier->country == "Chad"  ? 'selected' : '' }}>Chad</option>
                                <option value="Chile" {{ $supplier->country == "Chile"  ? 'selected' : '' }}>Chile</option>
                                <option value="China" {{ $supplier->country == "China"  ? 'selected' : '' }}>China</option>
                                <option value="Christmas Island" {{ $supplier->country == "Christmas Island"  ? 'selected' : '' }}>Christmas Island</option>
                                <option value="Cocos (Keeling) Islands" {{ $supplier->country == "Cocos (Keeling) Islands"  ? 'selected' : '' }}>Cocos (Keeling) Islands</option>
                                <option value="Colombia" {{ $supplier->country == "Colombia"  ? 'selected' : '' }}>Colombia</option>
                                <option value="Comoros" {{ $supplier->country == "Comoros"  ? 'selected' : '' }}>Comoros</option>
                                <option value="Cook Islands" {{ $supplier->country == "Cook Islands"  ? 'selected' : '' }}>Cook Islands</option>
                                <option value="Costa Rica" {{ $supplier->country == "Costa Rica"  ? 'selected' : '' }}>Costa Rica</option>
                                <option value="Côte d'Ivoire" {{ $supplier->country == "Côte d'Ivoire"  ? 'selected' : '' }}>Côte d'Ivoire</option>
                                <option value="Croatia" {{ $supplier->country == "Croatia"  ? 'selected' : '' }}>Croatia</option>
                                <option value="Cuba" {{ $supplier->country == "Cuba"  ? 'selected' : '' }}>Cuba</option>
                                <option value="Curaçao" {{ $supplier->country == "Curaçao"  ? 'selected' : '' }}>Curaçao</option>
                                <option value="Czech Republic" {{ $supplier->country == "Czech Republic"  ? 'selected' : '' }}>Czech Republic</option>
                                <option value="Denmark" {{ $supplier->country == "Denmark"  ? 'selected' : '' }}>Denmark</option>
                                <option value="Djibouti" {{ $supplier->country == "Djibouti"  ? 'selected' : '' }}>Djibouti</option>
                                <option value="Dominica" {{ $supplier->country == "Dominica"  ? 'selected' : '' }}>Dominica</option>
                                <option value="Dominican Republic" {{ $supplier->country == "Dominican Republic"  ? 'selected' : '' }}>Dominican Republic</option>
                                <option value="Ecuador" {{ $supplier->country == "Ecuador"  ? 'selected' : '' }}>Ecuador</option>
                                <option value="Egypt" {{ $supplier->country == "Egypt"  ? 'selected' : '' }}>Egypt</option>
                                <option value="El Salvador" {{ $supplier->country == "El Salvador"  ? 'selected' : '' }}>El Salvador</option>
                                <option value="Equatorial Guinea" {{ $supplier->country == "Equatorial Guinea"  ? 'selected' : '' }}>Equatorial Guinea</option>
                                <option value="Eritrea" {{ $supplier->country == "Eritrea"  ? 'selected' : '' }}>Eritrea</option>
                                <option value="Estonia" {{ $supplier->country == "Estonia"  ? 'selected' : '' }}>Estonia</option>
                                <option value="Ethiopia" {{ $supplier->country == "Ethiopia"  ? 'selected' : '' }}>Ethiopia</option>
                                <option value="Falkland Islands (Malvinas)" {{ $supplier->country == "Falkland Islands (Malvinas)"  ? 'selected' : '' }}>Falkland Islands (Malvinas)</option>
                                <option value="Fiji" {{ $supplier->country == "Fiji"  ? 'selected' : '' }}>Fiji</option>
                                <option value="Finland" {{ $supplier->country == "Finland"  ? 'selected' : '' }}>Finland</option>
                                <option value="France" {{ $supplier->country == "France"  ? 'selected' : '' }}>France</option>
                                <option value="French Polynesia" {{ $supplier->country == "French Polynesia"  ? 'selected' : '' }}>French Polynesia</option>
                                <option value="Gabon" {{ $supplier->country == "Gabon"  ? 'selected' : '' }}>Gabon</option>
                                <option value="Gambia" {{ $supplier->country == "Gambia"  ? 'selected' : '' }}>Gambia</option>
                                <option value="Georgia" {{ $supplier->country == "Georgia"  ? 'selected' : '' }}>Georgia</option>
                                <option value="Germany" {{ $supplier->country == "Germany"  ? 'selected' : '' }}>Germany</option>
                                <option value="Ghana" {{ $supplier->country == "Ghana"  ? 'selected' : '' }}>Ghana</option>
                                <option value="Gibraltar" {{ $supplier->country == "Gibraltar"  ? 'selected' : '' }}>Gibraltar</option>
                                <option value="Greece" {{ $supplier->country == "Greece"  ? 'selected' : '' }}>Greece</option>
                                <option value="Greenland" {{ $supplier->country == "Greenland"  ? 'selected' : '' }}>Greenland</option>
                                <option value="Grenada" {{ $supplier->country == "Grenada"  ? 'selected' : '' }}>Grenada</option>
                                <option value="Guam" {{ $supplier->country == "Guam"  ? 'selected' : '' }}>Guam</option>
                                <option value="Guatemala" {{ $supplier->country == "Guatemala"  ? 'selected' : '' }}>Guatemala</option>
                                <option value="Guernsey" {{ $supplier->country == "Guernsey"  ? 'selected' : '' }}>Guernsey</option>
                                <option value="Guinea" {{ $supplier->country == "Guinea"  ? 'selected' : '' }}>Guinea</option>
                                <option value="Guinea-Bissau" {{ $supplier->country == "Guinea-Bissau"  ? 'selected' : '' }}>Guinea-Bissau</option>
                                <option value="Haiti" {{ $supplier->country == "Haiti"  ? 'selected' : '' }}>Haiti</option>
                                <option value="Holy See (Vatican City State)" {{ $supplier->country == "Holy See (Vatican City State)"  ? 'selected' : '' }}>Holy See (Vatican City State)</option>
                                <option value="Honduras" {{ $supplier->country == "Honduras"  ? 'selected' : '' }}>Honduras</option>
                                <option value="Hong Kong" {{ $supplier->country == "Hong Kong"  ? 'selected' : '' }}>Hong Kong</option>
                                <option value="Hungary" {{ $supplier->country == "Hungary"  ? 'selected' : '' }}>Hungary</option>
                                <option value="Iceland" {{ $supplier->country == "Iceland"  ? 'selected' : '' }}>Iceland</option>
                                <option value="India" {{ $supplier->country == "India"  ? 'selected' : '' }}>India</option>
                                <option value="Indonesia" {{ $supplier->country == "Indonesia"  ? 'selected' : '' }}>Indonesia</option>
                                <option value="Iran, Islamic Republic of" {{ $supplier->country == "Iran, Islamic Republic of"  ? 'selected' : '' }}>Iran, Islamic Republic of</option>
                                <option value="Iraq" {{ $supplier->country == "Iraq"  ? 'selected' : '' }}>Iraq</option>
                                <option value="Ireland" {{ $supplier->country == "Ireland"  ? 'selected' : '' }}>Ireland</option>
                                <option value="Isle of Man" {{ $supplier->country == "Isle of Man"  ? 'selected' : '' }}>Isle of Man</option>
                                <option value="Israel" {{ $supplier->country == "Israel"  ? 'selected' : '' }}>Israel</option>
                                <option value="Italy" {{ $supplier->country == "Italy"  ? 'selected' : '' }}>Italy</option>
                                <option value="Jamaica" {{ $supplier->country == "Jamaica"  ? 'selected' : '' }}>Jamaica</option>
                                <option value="Japan" {{ $supplier->country == "Japan"  ? 'selected' : '' }}>Japan</option>
                                <option value="Jersey" {{ $supplier->country == "Jersey"  ? 'selected' : '' }}>Jersey</option>
                                <option value="Jordan" {{ $supplier->country == "Jordan"  ? 'selected' : '' }}>Jordan</option>
                                <option value="Kazakhstan" {{ $supplier->country == "Kazakhstan"  ? 'selected' : '' }}>Kazakhstan</option>
                                <option value="Kenya" {{ $supplier->country == "Kenya"  ? 'selected' : '' }}>Kenya</option>
                                <option value="Kiribati" {{ $supplier->country == "Kiribati"  ? 'selected' : '' }}>Kiribati</option>
                                <option value="Korea, Democratic People's Republic of" {{ $supplier->country == "Korea, Democratic People's Republic of"  ? 'selected' : '' }}>Korea, Democratic People's Republic of</option>
                                <option value="Kuwait" {{ $supplier->country == "Kuwait"  ? 'selected' : '' }}>Kuwait</option>
                                <option value="Kyrgyzstan" {{ $supplier->country == "Kyrgyzstan"  ? 'selected' : '' }}>Kyrgyzstan</option>
                                <option value="Lao People's Democratic Republic" {{ $supplier->country == "Lao People's Democratic Republic"  ? 'selected' : '' }}>Lao People's Democratic Republic</option>
                                <option value="Latvia" {{ $supplier->country == "Latvia"  ? 'selected' : '' }}>Latvia</option>
                                <option value="Lebanon" {{ $supplier->country == "Lebanon"  ? 'selected' : '' }}>Lebanon</option>
                                <option value="Lesotho" {{ $supplier->country == "Lesotho"  ? 'selected' : '' }}>Lesotho</option>
                                <option value="Liberia" {{ $supplier->country == "Liberia"  ? 'selected' : '' }}>Liberia</option>
                                <option value="Libya" {{ $supplier->country == "Libya"  ? 'selected' : '' }}>Libya</option>
                                <option value="Liechtenstein" {{ $supplier->country == "Liechtenstein"  ? 'selected' : '' }}>Liechtenstein</option>
                                <option value="Lithuania" {{ $supplier->country == "Lithuania"  ? 'selected' : '' }}>Lithuania</option>
                                <option value="Luxembourg" {{ $supplier->country == "Luxembourg"  ? 'selected' : '' }}>Luxembourg</option>
                                <option value="Macao" {{ $supplier->country == "Macao"  ? 'selected' : '' }}>Macao</option>
                                <option value="Madagascar" {{ $supplier->country == "Madagascar"  ? 'selected' : '' }}>Madagascar</option>
                                <option value="Malawi" {{ $supplier->country == "Malawi"  ? 'selected' : '' }}>Malawi</option>
                                <option value="Malaysia" {{ $supplier->country == "Malaysia"  ? 'selected' : '' }}>Malaysia</option>
                                <option value="Maldives" {{ $supplier->country == "Maldives"  ? 'selected' : '' }}>Maldives</option>
                                <option value="Mali" {{ $supplier->country == "Mali"  ? 'selected' : '' }}>Mali</option>
                                <option value="Malta" {{ $supplier->country == "Malta"  ? 'selected' : '' }}>Malta</option>
                                <option value="Marshall Islands" {{ $supplier->country == "Marshall Islands"  ? 'selected' : '' }}>Marshall Islands</option>
                                <option value="Martinique" {{ $supplier->country == "Martinique"  ? 'selected' : '' }}>Martinique</option>
                                <option value="Mauritania" {{ $supplier->country == "Mauritania"  ? 'selected' : '' }}>Mauritania</option>
                                <option value="Mauritius" {{ $supplier->country == "Mauritius"  ? 'selected' : '' }}>Mauritius</option>
                                <option value="Mexico" {{ $supplier->country == "Mexico"  ? 'selected' : '' }}>Mexico</option>
                                <option value="Micronesia, Federated States of" {{ $supplier->country == "Micronesia, Federated States of"  ? 'selected' : '' }}>Micronesia, Federated States of</option>
                                <option value="Moldova, Republic of" {{ $supplier->country == "Moldova, Republic of"  ? 'selected' : '' }}>Moldova, Republic of</option>
                                <option value="Monaco" {{ $supplier->country == "Monaco"  ? 'selected' : '' }}>Monaco</option>
                                <option value="Mongolia" {{ $supplier->country == "Mongolia"  ? 'selected' : '' }}>Mongolia</option>
                                <option value="Montenegro" {{ $supplier->country == "Montenegro"  ? 'selected' : '' }}>Montenegro</option>
                                <option value="Montserrat" {{ $supplier->country == "Montserrat"  ? 'selected' : '' }}>Montserrat</option>
                                <option value="Morocco" {{ $supplier->country == "Morocco"  ? 'selected' : '' }}>Morocco</option>
                                <option value="Mozambique" {{ $supplier->country == "Mozambique"  ? 'selected' : '' }}>Mozambique</option>
                                <option value="Myanmar" {{ $supplier->country == "Myanmar"  ? 'selected' : '' }}>Myanmar</option>
                                <option value="Namibia" {{ $supplier->country == "Namibia"  ? 'selected' : '' }}>Namibia</option>
                                <option value="Nauru" {{ $supplier->country == "Nauru"  ? 'selected' : '' }}>Nauru</option>
                                <option value="Nepal" {{ $supplier->country == "Nepal"  ? 'selected' : '' }}>Nepal</option>
                                <option value="Netherlands" {{ $supplier->country == "Netherlands"  ? 'selected' : '' }}>Netherlands</option>
                                <option value="New Zealand" {{ $supplier->country == "New Zealand"  ? 'selected' : '' }}>New Zealand</option>
                                <option value="Nicaragua" {{ $supplier->country == "Nicaragua"  ? 'selected' : '' }}>Nicaragua</option>
                                <option value="Niger" {{ $supplier->country == "Niger"  ? 'selected' : '' }}>Niger</option>
                                <option value="Nigeria" {{ $supplier->country == "Nigeria"  ? 'selected' : '' }}>Nigeria</option>
                                <option value="Niue" {{ $supplier->country == "Niue"  ? 'selected' : '' }}>Niue</option>
                                <option value="Norfolk Island" {{ $supplier->country == "Norfolk Island"  ? 'selected' : '' }}>Norfolk Island</option>
                                <option value="Northern Mariana Islands" {{ $supplier->country == "Northern Mariana Islands"  ? 'selected' : '' }}>Northern Mariana Islands</option>
                                <option value="Norway" {{ $supplier->country == "Norway"  ? 'selected' : '' }}>Norway</option>
                                <option value="Oman" {{ $supplier->country == "Oman"  ? 'selected' : '' }}>Oman</option>
                                <option value="Pakistan" {{ $supplier->country == "Pakistan"  ? 'selected' : '' }}>Pakistan</option>
                                <option value="Palau" {{ $supplier->country == "Palau"  ? 'selected' : '' }}>Palau</option>
                                <option value="Palestinian Territory, Occupied" {{ $supplier->country == "Palestinian Territory, Occupied"  ? 'selected' : '' }}>Palestinian Territory, Occupied</option>
                                <option value="Panama" {{ $supplier->country == "Panama"  ? 'selected' : '' }}>Panama</option>
                                <option value="Papua New Guinea" {{ $supplier->country == "Papua New Guinea"  ? 'selected' : '' }}>Papua New Guinea</option>
                                <option value="Paraguay" {{ $supplier->country == "Paraguay"  ? 'selected' : '' }}>Paraguay</option>
                                <option value="Peru" {{ $supplier->country == "Peru"  ? 'selected' : '' }}>Peru</option>
                                <option value="Philippines" {{ $supplier->country == "Philippines"  ? 'selected' : '' }}>Philippines</option>
                                <option value="Poland" {{ $supplier->country == "Poland"  ? 'selected' : '' }}>Poland</option>
                                <option value="Portugal" {{ $supplier->country == "Portugal"  ? 'selected' : '' }}>Portugal</option>
                                <option value="Puerto Rico" {{ $supplier->country == "Puerto Rico"  ? 'selected' : '' }}>Puerto Rico</option>
                                <option value="Qatar" {{ $supplier->country == "Qatar"  ? 'selected' : '' }}>Qatar</option>
                                <option value="Romania" {{ $supplier->country == "Romania"  ? 'selected' : '' }}>Romania</option>
                                <option value="Russian Federation" {{ $supplier->country == "Russian Federation"  ? 'selected' : '' }}>Russian Federation</option>
                                <option value="Rwanda" {{ $supplier->country == "Rwanda"  ? 'selected' : '' }}>Rwanda</option>
                                <option value="Saint Barthélemy" {{ $supplier->country == "Saint Barthélemy"  ? 'selected' : '' }}>Saint Barthélemy</option>
                                <option value="Saint Kitts and Nevis" {{ $supplier->country == "Saint Kitts and Nevis"  ? 'selected' : '' }}>Saint Kitts and Nevis</option>
                                <option value="Saint Lucia" {{ $supplier->country == "Saint Lucia"  ? 'selected' : '' }}>Saint Lucia</option>
                                <option value="Saint Martin (French part)" {{ $supplier->country == "Saint Martin (French part)"  ? 'selected' : '' }}>Saint Martin (French part)</option>
                                <option value="Saint Vincent and the Grenadines" {{ $supplier->country == "Saint Vincent and the Grenadines"  ? 'selected' : '' }}>Saint Vincent and the Grenadines</option>
                                <option value="Samoa" {{ $supplier->country == "Samoa"  ? 'selected' : '' }}>Samoa</option>
                                <option value="San Marino" {{ $supplier->country == "San Marino"  ? 'selected' : '' }}>San Marino</option>
                                <option value="Sao Tome and Principe" {{ $supplier->country == "Sao Tome and Principe"  ? 'selected' : '' }}>Sao Tome and Principe</option>
                                <option value="Saudi Arabia" {{ $supplier->country == "Saudi Arabia"  ? 'selected' : '' }}>Saudi Arabia</option>
                                <option value="Senegal" {{ $supplier->country == "Senegal"  ? 'selected' : '' }}>Senegal</option>
                                <option value="Serbia" {{ $supplier->country == "Serbia"  ? 'selected' : '' }}>Serbia</option>
                                <option value="Seychelles" {{ $supplier->country == "Seychelles"  ? 'selected' : '' }}>Seychelles</option>
                                <option value="Sierra Leone" {{ $supplier->country == "Sierra Leone"  ? 'selected' : '' }}>Sierra Leone</option>
                                <option value="Singapore" {{ $supplier->country == "Singapore"  ? 'selected' : '' }}>Singapore</option>
                                <option value="Sint Maarten (Dutch part)" {{ $supplier->country == "Sint Maarten (Dutch part)"  ? 'selected' : '' }}>Sint Maarten (Dutch part)</option>
                                <option value="Slovakia" {{ $supplier->country == "Slovakia"  ? 'selected' : '' }}>Slovakia</option>
                                <option value="Slovenia" {{ $supplier->country == "Slovenia"  ? 'selected' : '' }}>Slovenia</option>
                                <option value="Solomon Islands" {{ $supplier->country == "Solomon Islands"  ? 'selected' : '' }}>Solomon Islands</option>
                                <option value="Somalia" {{ $supplier->country == "Somalia"  ? 'selected' : '' }}>Somalia</option>
                                <option value="South Africa" {{ $supplier->country == "South Africa"  ? 'selected' : '' }}>South Africa</option>
                                <option value="South Korea" {{ $supplier->country == "South Korea"  ? 'selected' : '' }}>South Korea</option>
                                <option value="South Sudan" {{ $supplier->country == "South Sudan"  ? 'selected' : '' }}>South Sudan</option>
                                <option value="Spain" {{ $supplier->country == "Spain"  ? 'selected' : '' }}>Spain</option>
                                <option value="Sri Lanka" {{ $supplier->country == "Sri Lanka"  ? 'selected' : '' }}>Sri Lanka</option>
                                <option value="Sudan" {{ $supplier->country == "Sudan"  ? 'selected' : '' }}>Sudan</option>
                                <option value="Suriname" {{ $supplier->country == "Suriname"  ? 'selected' : '' }}>Suriname</option>
                                <option value="Swaziland" {{ $supplier->country == "Swaziland"  ? 'selected' : '' }}>Swaziland</option>
                                <option value="Sweden" {{ $supplier->country == "Sweden"  ? 'selected' : '' }}>Sweden</option>
                                <option value="Switzerland" {{ $supplier->country == "Switzerland"  ? 'selected' : '' }}>Switzerland</option>
                                <option value="Syrian Arab Republic" {{ $supplier->country == "Syrian Arab Republic"  ? 'selected' : '' }}>Syrian Arab Republic</option>
                                <option value="Taiwan, Province of China" {{ $supplier->country == "Taiwan, Province of China"  ? 'selected' : '' }}>Taiwan, Province of China</option>
                                <option value="Tajikistan" {{ $supplier->country == "Tajikistan"  ? 'selected' : '' }}>Tajikistan</option>
                                <option value="Tanzania, United Republic of" {{ $supplier->country == "Tanzania, United Republic of"  ? 'selected' : '' }}>Tanzania, United Republic of</option>
                                <option value="Thailand" {{ $supplier->country == "Thailand"  ? 'selected' : '' }}>Thailand</option>
                                <option value="Togo" {{ $supplier->country == "Togo"  ? 'selected' : '' }}>Togo</option>
                                <option value="Tokelau" {{ $supplier->country == "Tokelau"  ? 'selected' : '' }}>Tokelau</option>
                                <option value="Tonga" {{ $supplier->country == "Tonga"  ? 'selected' : '' }}>Tonga</option>
                                <option value="Trinidad and Tobago" {{ $supplier->country == "Trinidad and Tobago"  ? 'selected' : '' }}>Trinidad and Tobago</option>
                                <option value="Tunisia" {{ $supplier->country == "Tunisia"  ? 'selected' : '' }}>Tunisia</option>
                                <option value="Turkey" {{ $supplier->country == "Turkey"  ? 'selected' : '' }}>Turkey</option>
                                <option value="Turkmenistan" {{ $supplier->country == "Turkmenistan"  ? 'selected' : '' }}>Turkmenistan</option>
                                <option value="Turks and Caicos Islands" {{ $supplier->country == "Turks and Caicos Islands"  ? 'selected' : '' }}>Turks and Caicos Islands</option>
                                <option value="Tuvalu" {{ $supplier->country == "Tuvalu"  ? 'selected' : '' }}>Tuvalu</option>
                                <option value="Uganda" {{ $supplier->country == "Uganda"  ? 'selected' : '' }}>Uganda</option>
                                <option value="Ukraine" {{ $supplier->country == "Ukraine"  ? 'selected' : '' }}>Ukraine</option>
                                <option value="United Arab Emirates" {{ $supplier->country == "United Arab Emirates"  ? 'selected' : '' }}>United Arab Emirates</option>
                                <option value="United Kingdom" {{ $supplier->country == "United Kingdom"  ? 'selected' : '' }}>United Kingdom</option>
                                <option value="United States" {{ $supplier->country == "United States"  ? 'selected' : '' }}>United States</option>
                                <option value="Uruguay" {{ $supplier->country == "Uruguay"  ? 'selected' : '' }}>Uruguay</option>
                                <option value="Uzbekistan" {{ $supplier->country == "Uzbekistan"  ? 'selected' : '' }}>Uzbekistan</option>
                                <option value="Vanuatu" {{ $supplier->country == "Vanuatu"  ? 'selected' : '' }}>Vanuatu</option>
                                <option value="Venezuela, Bolivarian Republic of" {{ $supplier->country == "Venezuela, Bolivarian Republic of"  ? 'selected' : '' }}>Venezuela, Bolivarian Republic of</option>
                                <option value="Vietnam" {{ $supplier->country == "Vietnam"  ? 'selected' : '' }}>Vietnam</option>
                                <option value="Virgin Islands" {{ $supplier->country == "Virgin Islands"  ? 'selected' : '' }}>Virgin Islands</option>
                                <option value="Yemen" {{ $supplier->country == "Yemen"  ? 'selected' : '' }}>Yemen</option>
                                <option value="Zambia" {{ $supplier->country == "Zambia"  ? 'selected' : '' }}>Zambia</option>
                                <option value="Zimbabwe" {{ $supplier->country == "Zimbabwe"  ? 'selected' : '' }}>Zimbabwe</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <input type="text" name="zip_code" id="zip_code" value="{{old('zip_code',$supplier->zip_code)}}" class="form-control form-control-sm fs-7" placeholder="Zip Code">
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8 individual-div">
                            <label for="dob" class="form-label">Date of birth</label>
                            <input type="text" name="dob" id="dob" value="{{ old('dob', !empty($supplier->dob) ? date('d/m/Y', strtotime($supplier->dob)) : null) }}" class="form-control form-control-sm fs-7" placeholder="Date of birth" autocomplete="off" />
                        </div>
                        <div class="col-md-3 col-sm-12 mb-8">
                            <label for="" class="form-label">Assigned to</label>
                            <input type="text" class="form-control form-control-sm fs-7" placeholder="" />
                        </div>
                    </div>
                    <div class="form-group text-center mt-5">
                        <button type="button" onclick="showMoreInfo()" class="btn btn-primary btn-sm moreBtn rounded text-white">More Informations <i class="fa-solid fa-chevron-down text-white ms-4"></i></button>
                    </div>
                    <div id="more-info-fields" class="mt-8" style="display: none;">
                        <div class="row">
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="tax_number" class="form-label">Tax Number</label>
                                <input type="text" name="tax_number" id="tax_number" value="{{old('tax_number',$supplier->tax_number)}}" class="form-control form-control-sm fs-7" placeholder="Tax Number">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="pay_term_number" class="form-label">Pay Term</label>
                                <div class="input-group flex-nowrap">
                                    <input type="number" name="pay_term_number" id="pay_term_number" value="{{old('pay_term_number',$supplier->pay_term_number)}}" class="form-control form-control-sm rounded-end-0 fs-7">
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="pay_term_type" class="form-select form-select-sm rounded-start-0 fs-7" data-control="select2" data-placeholder="Please select">
                                            <option></option>
                                            <option value="Months" {{ $supplier->pay_term_type == "Months"  ? 'selected' : '' }}>Months</option>
                                            <option value="Days" {{ $supplier->pay_term_type == "Days"  ? 'selected' : '' }}>Days</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="receivable_amount" class="form-label">Receivable Amount</label>
                                <input type="number" name="receivable_amount" id="receivable_amount" value="{{old('receivable_amount',$supplier->receivable_amount)}}" class="form-control form-control-sm fs-7" placeholder="Receivable Amount">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="payable_amount" class="form-label">Payable Amount</label>
                                <input type="number" name="payable_amount" id="payable_amount" value="{{old('payable_amount',$supplier->payable_amount)}}" class="form-control form-control-sm fs-7" placeholder="Payable Amount">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-12 mb-8 credit-limit hide" @if($supplier->type == 'Both') style="display: block;" @endif>
                                <label for="credit_limit" class="form-label">Credit Limit</label>
                                <input type="number" name="credit_limit" id="credit_limit" value="{{old('credit_limit',$supplier->credit_limit)}}" class="form-control form-control-sm fs-7" placeholder="Credit Limit">
                            </div>
                        </div>
                        <hr style="opacity: 0.3;">
                        <div class="row mt-6">
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_1" class="form-label">Custom Field 1</label>
                                <input type="text" name="custom_field_1" id="custom_field_1" value="{{old('custom_field_1',$supplier->custom_field_1)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 1">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_2" class="form-label">Custom Field 2</label>
                                <input type="text" name="custom_field_2" id="custom_field_2" value="{{old('custom_field_2',$supplier->custom_field_2)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 2">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_3" class="form-label">Custom Field 3</label>
                                <input type="text" name="custom_field_3" id="custom_field_3" value="{{old('custom_field_3',$supplier->custom_field_3)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 3">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_4" class="form-label">Custom Field 4</label>
                                <input type="text" name="custom_field_4" id="custom_field_4" value="{{old('custom_field_4',$supplier->custom_field_4)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 4">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_5" class="form-label">Custom Field 5</label>
                                <input type="text" name="custom_field_5" id="custom_field_5" value="{{old('custom_field_5',$supplier->custom_field_5)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 5">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_6" class="form-label">Custom Field 6</label>
                                <input type="text" name="custom_field_6" id="custom_field_6" value="{{old('custom_field_6',$supplier->custom_field_6)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 6">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_7" class="form-label">Custom Field 7</label>
                                <input type="text" name="custom_field_7" id="custom_field_7" value="{{old('custom_field_7',$supplier->custom_field_7)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 7">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_8" class="form-label">Custom Field 8</label>
                                <input type="text" name="custom_field_8" id="custom_field_8" value="{{old('custom_field_8',$supplier->custom_field_8)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 8">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_9" class="form-label">Custom Field 9</label>
                                <input type="text" name="custom_field_9" id="custom_field_9" value="{{old('custom_field_9',$supplier->custom_field_9)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 9">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-8">
                                <label for="custom_field_10" class="form-label">Custom Field 10</label>
                                <input type="text" name="custom_field_10" id="custom_field_10" value="{{old('custom_field_10',$supplier->custom_field_10)}}" class="form-control form-control-sm fs-7" placeholder="Custom Field 10">
                            </div>
                        </div>
                        <hr style="opacity: 0.3;">
                        <div class="row mt-6">
                            <div class="col-md-4 col-sm-12">
                                <label for="shipping_address" class="form-label">Shipping Address</label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control form-control-sm" style="resize: none;">{{ old('shipping_address',!empty($supplier->shipping_address) ? $supplier->shipping_address : null) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </div>
            <!--end::Card-->
        </form>
    </div>
    <!--end::container-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
<script src="{{asset('customJs/contact/contact.js')}}"></script>
@endpush
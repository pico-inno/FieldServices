<style>
    .individual-div.hide, .business-div.hide, .customer-group.hide, .credit-limit.hide{
        display: none;
    }
</style>

{{-- <div class="modal fade" tabindex="-1" id="edit_contact_modal">  --}}
    <div class="modal-dialog " >
        <div class="modal-content">
            <form action="{{route('customers.update', $customer->id)}}" method="POST" id="edit_customer_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="from_pos">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Contact</h3>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times fs-2"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body ">
                    <div class="card card-p-4 card-flush">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <label for="contact-type" class="required form-label">Contact Type</label>
                                    <select name="type" class="form-select form-select-sm fs-7" id="contact-type" aria-label="Select example"
                                        onclick="showCG()" required>
                                        <option value="Supplier" {{ $customer->type == "Supplier" ? 'selected' : '' }}>Suppliers</option>
                                        <option value="Customer" {{ $customer->type == "Customer" ? 'selected' : '' }}>Customers</option>
                                        <option value="Both" {{ $customer->type == "Both" ? 'selected' : '' }}>Both (Suppliers and Customers)
                                        </option>
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
                                    <input type="text" name="contact_id" id="contact_id" value="{{old('contact_id',$customer->contact_id)}}"
                                        class="form-control form-control-sm fs-7" placeholder="Contact ID" />
                                    <span class="text-gray-400">Leave empty to autogenerate</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 mb-8 customer-group">
                                    <label class="form-label">Customer Group</label>
                                    @php
                                    $customer_groups = \App\Models\Contact\CustomerGroup::all();
                                    @endphp
                                    <select name="customer_group_id" class="form-select form-select-sm fs-7" data-control="select2"
                                        data-placeholder="None" data-allow-clear="true">
                                        <option></option>
                                        @foreach($customer_groups as $customer_group)
                                        <option value="{{$customer_group->id}}" {{ old('customer_group_id', $customer->customer_group_id) ==
                                            $customer_group->id ? 'selected' : ''}}>
                                            {{$customer_group->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8 customer-group">
                                    <label class="form-label">Price List</label>
                                    @php
                                    $price_lists = \App\Models\Product\PriceLists::all();
                                    @endphp
                                    <select name="price_list_id" class="form-select form-select-sm fs-7" data-control="select2"
                                        data-placeholder="None" data-allow-clear="true">
                                        <option></option>
                                        @foreach($price_lists as $price_list)
                                        <option value="{{$price_list->id}}" {{ old('price_list_id', $customer->price_list_id) == $price_list->id ?
                                            'selected' : ''}}>
                                            {{$price_list->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8 business-div">
                                    <label for="company_name" class="form-label">Business Name</label>
                                    <input type="text" name="company_name" id="company_name" value="{{old('company_name',$customer->company_name)}}"
                                        class="form-control form-control-sm fs-7" placeholder="Business Name" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mt-3 mb-10">
                                <div class="col-6 col-md-1 fs-5  fw-semibold  text-primary">
                                    General Info
                                </div>
                                <div class="separator   border-gray-300 col-md-11 col-6"></div>
                            </div>
                            <div class="row individual-div">
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="prefix" class="form-label">Prefix</label>
                                    <input type="text" name="prefix" id="prefix" value="{{old('prefix',$customer->prefix)}}"
                                        class="form-control form-control-sm fs-7" placeholder="Mr / Mrs / Miss" />
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" name="first_name" id="first_name" value="{{old('first_name',$customer->first_name)}}"
                                        class="form-control form-control-sm fs-7" placeholder="First Name" />
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" value="{{old('middle_name',$customer->middle_name)}}"
                                        class="form-control form-control-sm fs-7" placeholder="Middle Name" />
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" value="{{old('last_name',$customer->last_name)}}"
                                        class="form-control form-control-sm fs-7" placeholder="Last Name" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="mobile" class="required form-label">Mobile</label>
                                    <input type="text" name="mobile" id="mobile" value="{{old('mobile',$customer->mobile)}}"
                                        class="form-control form-control-sm fs-7" placeholder="Mobile" />
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" class="form-select form-select-sm" placeholder="Gender"
                                        data-placeholder="Gender" data-control="select2" data-allow-clear="true" data-hide-search="true">
                                        <option disabled selected>Select Gender</option>
                                        <option value="male" @selected($customer->gender=="male")>
                                            Male
                                        </option>
                                        <option value="female" @selected($customer->gender=="female")>
                                            Female
                                        </option>
                                        <option value="Prefer not to say" @selected($customer->gender=="Prefer not to say")>
                                            Prefer not to say
                                        </option>
                                        <option value="others" @selected($customer->gender=="others")>
                                            Others...
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8 individual-div">
                                    <label for="dob" class="form-label">Date of birth</label>
                                    <input type="text" name="dob" id="dob_edit" class="form-control form-control-sm fs-7"
                                        value="{{ old('dob', !empty($customer->dob) ? date('d/m/Y', strtotime($customer->dob)) : null) }}"
                                        placeholder="Date of birth" autocomplete="off" />
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="text" name="age" id="age" value="{{old('age',$customer->age)}}"
                                        class="form-control form-control-sm fs-7 age" placeholder="Age" />
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="alternate_number" class="form-label">Alternate Contact Number</label>
                                    <input type="text" name="alternate_number" id="alternate_number"
                                        value="{{old('alternate_number',$customer->alternate_number)}}" class="form-control form-control-sm fs-7"
                                        placeholder="Alternate Contact Numbe" />
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="landline" class="form-label">Landline</label>
                                    <input type="text" name="landline" id="landline" value="{{old('landline',$customer->landline)}}"
                                        class="form-control form-control-sm fs-7" placeholder="Landline" />
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" name="email" id="email" value="{{old('email',$customer->email)}}"
                                        class="form-control form-control-sm fs-7" placeholder="Email" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mt-3 mb-10">
                                <div class="col-6 col-md-1 fs-5  fw-semibold  text-primary">
                                    Address
                                </div>
                                <div class="separator   border-gray-300 col-md-11 col-6"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="address_line_1" class="form-label">Address Line 1</label>
                                    <input type="text" name="address_line_1" id="address_line_1"
                                        value="{{old('address_line_1',$customer->address_line_1)}}" class="form-control form-control-sm fs-7"
                                        placeholder="Address Line 1">
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="address_line_2" class="form-label">Address Line 2</label>
                                    <input type="text" name="address_line_2" id="address_line_2"
                                        value="{{old('address_line_2',$customer->address_line_2)}}" class="form-control form-control-sm fs-7"
                                        placeholder="Address Line 2">
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" name="city" id="city" value="{{old('city',$customer->city)}}"
                                        class="form-control form-control-sm fs-7" placeholder="City">
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" name="state" id="state" value="{{old('state',$customer->state)}}"
                                        class="form-control form-control-sm fs-7" placeholder="State">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="country" class="form-label">Country</label>
                                    <select name="country" id="country" class="form-select form-select-sm fs-7" data-control="select2"
                                        data-placeholder="Select Country">
                                        <option></option>
                                        <option value="Afghanistan" {{ $customer->country == "Afghanistan" ? 'selected' : '' }}>Afghanistan</option>
                                        <option value="Aland Islands" {{ $customer->country == "Aland Islands" ? 'selected' : '' }}>Aland Islands
                                        </option>
                                        <option value="Albania" {{ $customer->country == "Albania" ? 'selected' : '' }}>Albania</option>
                                        <option value="Algeria" {{ $customer->country == "Algeria" ? 'selected' : '' }}>Algeria</option>
                                        <option value="American Samoa" {{ $customer->country == "American Samoa" ? 'selected' : '' }}>American Samoa
                                        </option>
                                        <option value="Andorra" {{ $customer->country == "Andorra" ? 'selected' : '' }}>Andorra</option>
                                        <option value="Angola" {{ $customer->country == "Angola" ? 'selected' : '' }}>Angola</option>
                                        <option value="Anguilla" {{ $customer->country == "Anguilla" ? 'selected' : '' }}>Anguilla</option>
                                        <option value="Antigua and Barbuda" {{ $customer->country == "Antigua and Barbuda" ? 'selected' : ''
                                            }}>Antigua and Barbuda</option>
                                        <option value="Argentina" {{ $customer->country == "Argentina" ? 'selected' : '' }}>Argentina</option>
                                        <option value="Armenia" {{ $customer->country == "Armenia" ? 'selected' : '' }}>Armenia</option>
                                        <option value="Aruba" {{ $customer->country == "Aruba" ? 'selected' : '' }}>Aruba</option>
                                        <option value="Australia" {{ $customer->country == "Australia" ? 'selected' : '' }}>Australia</option>
                                        <option value="Austria" {{ $customer->country == "Austria" ? 'selected' : '' }}>Austria</option>
                                        <option value="Azerbaijan" {{ $customer->country == "Azerbaijan" ? 'selected' : '' }}>Azerbaijan</option>
                                        <option value="Bahamas" {{ $customer->country == "Bahamas" ? 'selected' : '' }}>Bahamas</option>
                                        <option value="Bahrain" {{ $customer->country == "Bahrain" ? 'selected' : '' }}>Bahrain</option>
                                        <option value="Bangladesh" {{ $customer->country == "Bangladesh" ? 'selected' : '' }}>Bangladesh</option>
                                        <option value="Barbados" {{ $customer->country == "Barbados" ? 'selected' : '' }}>Barbados</option>
                                        <option value="Belarus" {{ $customer->country == "Belarus" ? 'selected' : '' }}>Belarus</option>
                                        <option value="Belgium" {{ $customer->country == "Belgium" ? 'selected' : '' }}>Belgium</option>
                                        <option value="Belize" {{ $customer->country == "Belize" ? 'selected' : '' }}>Belize</option>
                                        <option value="Benin" {{ $customer->country == "Benin" ? 'selected' : '' }}>Benin</option>
                                        <option value="Bermuda" {{ $customer->country == "Bermuda" ? 'selected' : '' }}>Bermuda</option>
                                        <option value="Bhutan" {{ $customer->country == "Bhutan" ? 'selected' : '' }}>Bhutan</option>
                                        <option value="Bolivia, Plurinational State of" {{ $customer->country == "Bolivia, Plurinational State of" ?
                                            'selected' : '' }}>Bolivia, Plurinational State of</option>
                                        <option value="Bonaire, Sint Eustatius and Saba" {{ $customer->country == "Bonaire, Sint Eustatius and Saba"
                                            ? 'selected' : '' }}>Bonaire, Sint Eustatius and Saba</option>
                                        <option value="Bosnia and Herzegovina" {{ $customer->country == "Bosnia and Herzegovina" ? 'selected' : ''
                                            }}>Bosnia and Herzegovina</option>
                                        <option value="Botswana" {{ $customer->country == "Botswana" ? 'selected' : '' }}>Botswana</option>
                                        <option value="Brazil" {{ $customer->country == "Brazil" ? 'selected' : '' }}>Brazil</option>
                                        <option value="British Indian Ocean Territory" {{ $customer->country == "British Indian Ocean Territory" ?
                                            'selected' : '' }}>British Indian Ocean Territory</option>
                                        <option value="Brunei Darussalam" {{ $customer->country == "Brunei Darussalam" ? 'selected' : '' }}>Brunei
                                            Darussalam</option>
                                        <option value="Bulgaria" {{ $customer->country == "Bulgaria" ? 'selected' : '' }}>Bulgaria</option>
                                        <option value="Burkina Faso" {{ $customer->country == "Burkina Faso" ? 'selected' : '' }}>Burkina Faso
                                        </option>
                                        <option value="Burundi" {{ $customer->country == "Burundi" ? 'selected' : '' }}>Burundi</option>
                                        <option value="Cambodia" {{ $customer->country == "Cambodia" ? 'selected' : '' }}>Cambodia</option>
                                        <option value="Cameroon" {{ $customer->country == "Cameroon" ? 'selected' : '' }}>Cameroon</option>
                                        <option value="Canada" {{ $customer->country == "Canada" ? 'selected' : '' }}>Canada</option>
                                        <option value="Cape Verde" {{ $customer->country == "Cape Verde" ? 'selected' : '' }}>Cape Verde</option>
                                        <option value="Cayman Islands" {{ $customer->country == "Cayman Islands" ? 'selected' : '' }}>Cayman Islands
                                        </option>
                                        <option value="Central African Republic" {{ $customer->country == "Central African Republic" ? 'selected' :
                                            '' }}>Central African Republic</option>
                                        <option value="Chad" {{ $customer->country == "Chad" ? 'selected' : '' }}>Chad</option>
                                        <option value="Chile" {{ $customer->country == "Chile" ? 'selected' : '' }}>Chile</option>
                                        <option value="China" {{ $customer->country == "China" ? 'selected' : '' }}>China</option>
                                        <option value="Christmas Island" {{ $customer->country == "Christmas Island" ? 'selected' : '' }}>Christmas
                                            Island</option>
                                        <option value="Cocos (Keeling) Islands" {{ $customer->country == "Cocos (Keeling) Islands" ? 'selected' : ''
                                            }}>Cocos (Keeling) Islands</option>
                                        <option value="Colombia" {{ $customer->country == "Colombia" ? 'selected' : '' }}>Colombia</option>
                                        <option value="Comoros" {{ $customer->country == "Comoros" ? 'selected' : '' }}>Comoros</option>
                                        <option value="Cook Islands" {{ $customer->country == "Cook Islands" ? 'selected' : '' }}>Cook Islands
                                        </option>
                                        <option value="Costa Rica" {{ $customer->country == "Costa Rica" ? 'selected' : '' }}>Costa Rica</option>
                                        <option value="Côte d'Ivoire" {{ $customer->country == "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire
                                        </option>
                                        <option value="Croatia" {{ $customer->country == "Croatia" ? 'selected' : '' }}>Croatia</option>
                                        <option value="Cuba" {{ $customer->country == "Cuba" ? 'selected' : '' }}>Cuba</option>
                                        <option value="Curaçao" {{ $customer->country == "Curaçao" ? 'selected' : '' }}>Curaçao</option>
                                        <option value="Czech Republic" {{ $customer->country == "Czech Republic" ? 'selected' : '' }}>Czech Republic
                                        </option>
                                        <option value="Denmark" {{ $customer->country == "Denmark" ? 'selected' : '' }}>Denmark</option>
                                        <option value="Djibouti" {{ $customer->country == "Djibouti" ? 'selected' : '' }}>Djibouti</option>
                                        <option value="Dominica" {{ $customer->country == "Dominica" ? 'selected' : '' }}>Dominica</option>
                                        <option value="Dominican Republic" {{ $customer->country == "Dominican Republic" ? 'selected' : ''
                                            }}>Dominican Republic</option>
                                        <option value="Ecuador" {{ $customer->country == "Ecuador" ? 'selected' : '' }}>Ecuador</option>
                                        <option value="Egypt" {{ $customer->country == "Egypt" ? 'selected' : '' }}>Egypt</option>
                                        <option value="El Salvador" {{ $customer->country == "El Salvador" ? 'selected' : '' }}>El Salvador</option>
                                        <option value="Equatorial Guinea" {{ $customer->country == "Equatorial Guinea" ? 'selected' : ''
                                            }}>Equatorial Guinea</option>
                                        <option value="Eritrea" {{ $customer->country == "Eritrea" ? 'selected' : '' }}>Eritrea</option>
                                        <option value="Estonia" {{ $customer->country == "Estonia" ? 'selected' : '' }}>Estonia</option>
                                        <option value="Ethiopia" {{ $customer->country == "Ethiopia" ? 'selected' : '' }}>Ethiopia</option>
                                        <option value="Falkland Islands (Malvinas)" {{ $customer->country == "Falkland Islands (Malvinas)" ?
                                            'selected' : '' }}>Falkland Islands (Malvinas)</option>
                                        <option value="Fiji" {{ $customer->country == "Fiji" ? 'selected' : '' }}>Fiji</option>
                                        <option value="Finland" {{ $customer->country == "Finland" ? 'selected' : '' }}>Finland</option>
                                        <option value="France" {{ $customer->country == "France" ? 'selected' : '' }}>France</option>
                                        <option value="French Polynesia" {{ $customer->country == "French Polynesia" ? 'selected' : '' }}>French
                                            Polynesia</option>
                                        <option value="Gabon" {{ $customer->country == "Gabon" ? 'selected' : '' }}>Gabon</option>
                                        <option value="Gambia" {{ $customer->country == "Gambia" ? 'selected' : '' }}>Gambia</option>
                                        <option value="Georgia" {{ $customer->country == "Georgia" ? 'selected' : '' }}>Georgia</option>
                                        <option value="Germany" {{ $customer->country == "Germany" ? 'selected' : '' }}>Germany</option>
                                        <option value="Ghana" {{ $customer->country == "Ghana" ? 'selected' : '' }}>Ghana</option>
                                        <option value="Gibraltar" {{ $customer->country == "Gibraltar" ? 'selected' : '' }}>Gibraltar</option>
                                        <option value="Greece" {{ $customer->country == "Greece" ? 'selected' : '' }}>Greece</option>
                                        <option value="Greenland" {{ $customer->country == "Greenland" ? 'selected' : '' }}>Greenland</option>
                                        <option value="Grenada" {{ $customer->country == "Grenada" ? 'selected' : '' }}>Grenada</option>
                                        <option value="Guam" {{ $customer->country == "Guam" ? 'selected' : '' }}>Guam</option>
                                        <option value="Guatemala" {{ $customer->country == "Guatemala" ? 'selected' : '' }}>Guatemala</option>
                                        <option value="Guernsey" {{ $customer->country == "Guernsey" ? 'selected' : '' }}>Guernsey</option>
                                        <option value="Guinea" {{ $customer->country == "Guinea" ? 'selected' : '' }}>Guinea</option>
                                        <option value="Guinea-Bissau" {{ $customer->country == "Guinea-Bissau" ? 'selected' : '' }}>Guinea-Bissau
                                        </option>
                                        <option value="Haiti" {{ $customer->country == "Haiti" ? 'selected' : '' }}>Haiti</option>
                                        <option value="Holy See (Vatican City State)" {{ $customer->country == "Holy See (Vatican City State)" ?
                                            'selected' : '' }}>Holy See (Vatican City State)</option>
                                        <option value="Honduras" {{ $customer->country == "Honduras" ? 'selected' : '' }}>Honduras</option>
                                        <option value="Hong Kong" {{ $customer->country == "Hong Kong" ? 'selected' : '' }}>Hong Kong</option>
                                        <option value="Hungary" {{ $customer->country == "Hungary" ? 'selected' : '' }}>Hungary</option>
                                        <option value="Iceland" {{ $customer->country == "Iceland" ? 'selected' : '' }}>Iceland</option>
                                        <option value="India" {{ $customer->country == "India" ? 'selected' : '' }}>India</option>
                                        <option value="Indonesia" {{ $customer->country == "Indonesia" ? 'selected' : '' }}>Indonesia</option>
                                        <option value="Iran, Islamic Republic of" {{ $customer->country == "Iran, Islamic Republic of" ? 'selected'
                                            : '' }}>Iran, Islamic Republic of</option>
                                        <option value="Iraq" {{ $customer->country == "Iraq" ? 'selected' : '' }}>Iraq</option>
                                        <option value="Ireland" {{ $customer->country == "Ireland" ? 'selected' : '' }}>Ireland</option>
                                        <option value="Isle of Man" {{ $customer->country == "Isle of Man" ? 'selected' : '' }}>Isle of Man</option>
                                        <option value="Israel" {{ $customer->country == "Israel" ? 'selected' : '' }}>Israel</option>
                                        <option value="Italy" {{ $customer->country == "Italy" ? 'selected' : '' }}>Italy</option>
                                        <option value="Jamaica" {{ $customer->country == "Jamaica" ? 'selected' : '' }}>Jamaica</option>
                                        <option value="Japan" {{ $customer->country == "Japan" ? 'selected' : '' }}>Japan</option>
                                        <option value="Jersey" {{ $customer->country == "Jersey" ? 'selected' : '' }}>Jersey</option>
                                        <option value="Jordan" {{ $customer->country == "Jordan" ? 'selected' : '' }}>Jordan</option>
                                        <option value="Kazakhstan" {{ $customer->country == "Kazakhstan" ? 'selected' : '' }}>Kazakhstan</option>
                                        <option value="Kenya" {{ $customer->country == "Kenya" ? 'selected' : '' }}>Kenya</option>
                                        <option value="Kiribati" {{ $customer->country == "Kiribati" ? 'selected' : '' }}>Kiribati</option>
                                        <option value="Korea, Democratic People's Republic of" {{ $customer->country == "Korea, Democratic People's
                                            Republic of" ? 'selected' : '' }}>Korea, Democratic People's Republic of</option>
                                        <option value="Kuwait" {{ $customer->country == "Kuwait" ? 'selected' : '' }}>Kuwait</option>
                                        <option value="Kyrgyzstan" {{ $customer->country == "Kyrgyzstan" ? 'selected' : '' }}>Kyrgyzstan</option>
                                        <option value="Lao People's Democratic Republic" {{ $customer->country == "Lao People's Democratic Republic"
                                            ? 'selected' : '' }}>Lao People's Democratic Republic</option>
                                        <option value="Latvia" {{ $customer->country == "Latvia" ? 'selected' : '' }}>Latvia</option>
                                        <option value="Lebanon" {{ $customer->country == "Lebanon" ? 'selected' : '' }}>Lebanon</option>
                                        <option value="Lesotho" {{ $customer->country == "Lesotho" ? 'selected' : '' }}>Lesotho</option>
                                        <option value="Liberia" {{ $customer->country == "Liberia" ? 'selected' : '' }}>Liberia</option>
                                        <option value="Libya" {{ $customer->country == "Libya" ? 'selected' : '' }}>Libya</option>
                                        <option value="Liechtenstein" {{ $customer->country == "Liechtenstein" ? 'selected' : '' }}>Liechtenstein
                                        </option>
                                        <option value="Lithuania" {{ $customer->country == "Lithuania" ? 'selected' : '' }}>Lithuania</option>
                                        <option value="Luxembourg" {{ $customer->country == "Luxembourg" ? 'selected' : '' }}>Luxembourg</option>
                                        <option value="Macao" {{ $customer->country == "Macao" ? 'selected' : '' }}>Macao</option>
                                        <option value="Madagascar" {{ $customer->country == "Madagascar" ? 'selected' : '' }}>Madagascar</option>
                                        <option value="Malawi" {{ $customer->country == "Malawi" ? 'selected' : '' }}>Malawi</option>
                                        <option value="Malaysia" {{ $customer->country == "Malaysia" ? 'selected' : '' }}>Malaysia</option>
                                        <option value="Maldives" {{ $customer->country == "Maldives" ? 'selected' : '' }}>Maldives</option>
                                        <option value="Mali" {{ $customer->country == "Mali" ? 'selected' : '' }}>Mali</option>
                                        <option value="Malta" {{ $customer->country == "Malta" ? 'selected' : '' }}>Malta</option>
                                        <option value="Marshall Islands" {{ $customer->country == "Marshall Islands" ? 'selected' : '' }}>Marshall
                                            Islands</option>
                                        <option value="Martinique" {{ $customer->country == "Martinique" ? 'selected' : '' }}>Martinique</option>
                                        <option value="Mauritania" {{ $customer->country == "Mauritania" ? 'selected' : '' }}>Mauritania</option>
                                        <option value="Mauritius" {{ $customer->country == "Mauritius" ? 'selected' : '' }}>Mauritius</option>
                                        <option value="Mexico" {{ $customer->country == "Mexico" ? 'selected' : '' }}>Mexico</option>
                                        <option value="Micronesia, Federated States of" {{ $customer->country == "Micronesia, Federated States of" ?
                                            'selected' : '' }}>Micronesia, Federated States of</option>
                                        <option value="Moldova, Republic of" {{ $customer->country == "Moldova, Republic of" ? 'selected' : ''
                                            }}>Moldova, Republic of</option>
                                        <option value="Monaco" {{ $customer->country == "Monaco" ? 'selected' : '' }}>Monaco</option>
                                        <option value="Mongolia" {{ $customer->country == "Mongolia" ? 'selected' : '' }}>Mongolia</option>
                                        <option value="Montenegro" {{ $customer->country == "Montenegro" ? 'selected' : '' }}>Montenegro</option>
                                        <option value="Montserrat" {{ $customer->country == "Montserrat" ? 'selected' : '' }}>Montserrat</option>
                                        <option value="Morocco" {{ $customer->country == "Morocco" ? 'selected' : '' }}>Morocco</option>
                                        <option value="Mozambique" {{ $customer->country == "Mozambique" ? 'selected' : '' }}>Mozambique</option>
                                        <option value="Myanmar" {{ $customer->country == "Myanmar" ? 'selected' : '' }}>Myanmar</option>
                                        <option value="Namibia" {{ $customer->country == "Namibia" ? 'selected' : '' }}>Namibia</option>
                                        <option value="Nauru" {{ $customer->country == "Nauru" ? 'selected' : '' }}>Nauru</option>
                                        <option value="Nepal" {{ $customer->country == "Nepal" ? 'selected' : '' }}>Nepal</option>
                                        <option value="Netherlands" {{ $customer->country == "Netherlands" ? 'selected' : '' }}>Netherlands</option>
                                        <option value="New Zealand" {{ $customer->country == "New Zealand" ? 'selected' : '' }}>New Zealand</option>
                                        <option value="Nicaragua" {{ $customer->country == "Nicaragua" ? 'selected' : '' }}>Nicaragua</option>
                                        <option value="Niger" {{ $customer->country == "Niger" ? 'selected' : '' }}>Niger</option>
                                        <option value="Nigeria" {{ $customer->country == "Nigeria" ? 'selected' : '' }}>Nigeria</option>
                                        <option value="Niue" {{ $customer->country == "Niue" ? 'selected' : '' }}>Niue</option>
                                        <option value="Norfolk Island" {{ $customer->country == "Norfolk Island" ? 'selected' : '' }}>Norfolk Island
                                        </option>
                                        <option value="Northern Mariana Islands" {{ $customer->country == "Northern Mariana Islands" ? 'selected' :
                                            '' }}>Northern Mariana Islands</option>
                                        <option value="Norway" {{ $customer->country == "Norway" ? 'selected' : '' }}>Norway</option>
                                        <option value="Oman" {{ $customer->country == "Oman" ? 'selected' : '' }}>Oman</option>
                                        <option value="Pakistan" {{ $customer->country == "Pakistan" ? 'selected' : '' }}>Pakistan</option>
                                        <option value="Palau" {{ $customer->country == "Palau" ? 'selected' : '' }}>Palau</option>
                                        <option value="Palestinian Territory, Occupied" {{ $customer->country == "Palestinian Territory, Occupied" ?
                                            'selected' : '' }}>Palestinian Territory, Occupied</option>
                                        <option value="Panama" {{ $customer->country == "Panama" ? 'selected' : '' }}>Panama</option>
                                        <option value="Papua New Guinea" {{ $customer->country == "Papua New Guinea" ? 'selected' : '' }}>Papua New
                                            Guinea</option>
                                        <option value="Paraguay" {{ $customer->country == "Paraguay" ? 'selected' : '' }}>Paraguay</option>
                                        <option value="Peru" {{ $customer->country == "Peru" ? 'selected' : '' }}>Peru</option>
                                        <option value="Philippines" {{ $customer->country == "Philippines" ? 'selected' : '' }}>Philippines</option>
                                        <option value="Poland" {{ $customer->country == "Poland" ? 'selected' : '' }}>Poland</option>
                                        <option value="Portugal" {{ $customer->country == "Portugal" ? 'selected' : '' }}>Portugal</option>
                                        <option value="Puerto Rico" {{ $customer->country == "Puerto Rico" ? 'selected' : '' }}>Puerto Rico</option>
                                        <option value="Qatar" {{ $customer->country == "Qatar" ? 'selected' : '' }}>Qatar</option>
                                        <option value="Romania" {{ $customer->country == "Romania" ? 'selected' : '' }}>Romania</option>
                                        <option value="Russian Federation" {{ $customer->country == "Russian Federation" ? 'selected' : ''
                                            }}>Russian Federation</option>
                                        <option value="Rwanda" {{ $customer->country == "Rwanda" ? 'selected' : '' }}>Rwanda</option>
                                        <option value="Saint Barthélemy" {{ $customer->country == "Saint Barthélemy" ? 'selected' : '' }}>Saint
                                            Barthélemy</option>
                                        <option value="Saint Kitts and Nevis" {{ $customer->country == "Saint Kitts and Nevis" ? 'selected' : ''
                                            }}>Saint Kitts and Nevis</option>
                                        <option value="Saint Lucia" {{ $customer->country == "Saint Lucia" ? 'selected' : '' }}>Saint Lucia</option>
                                        <option value="Saint Martin (French part)" {{ $customer->country == "Saint Martin (French part)" ?
                                            'selected' : '' }}>Saint Martin (French part)</option>
                                        <option value="Saint Vincent and the Grenadines" {{ $customer->country == "Saint Vincent and the Grenadines"
                                            ? 'selected' : '' }}>Saint Vincent and the Grenadines</option>
                                        <option value="Samoa" {{ $customer->country == "Samoa" ? 'selected' : '' }}>Samoa</option>
                                        <option value="San Marino" {{ $customer->country == "San Marino" ? 'selected' : '' }}>San Marino</option>
                                        <option value="Sao Tome and Principe" {{ $customer->country == "Sao Tome and Principe" ? 'selected' : ''
                                            }}>Sao Tome and Principe</option>
                                        <option value="Saudi Arabia" {{ $customer->country == "Saudi Arabia" ? 'selected' : '' }}>Saudi Arabia
                                        </option>
                                        <option value="Senegal" {{ $customer->country == "Senegal" ? 'selected' : '' }}>Senegal</option>
                                        <option value="Serbia" {{ $customer->country == "Serbia" ? 'selected' : '' }}>Serbia</option>
                                        <option value="Seychelles" {{ $customer->country == "Seychelles" ? 'selected' : '' }}>Seychelles</option>
                                        <option value="Sierra Leone" {{ $customer->country == "Sierra Leone" ? 'selected' : '' }}>Sierra Leone
                                        </option>
                                        <option value="Singapore" {{ $customer->country == "Singapore" ? 'selected' : '' }}>Singapore</option>
                                        <option value="Sint Maarten (Dutch part)" {{ $customer->country == "Sint Maarten (Dutch part)" ? 'selected'
                                            : '' }}>Sint Maarten (Dutch part)</option>
                                        <option value="Slovakia" {{ $customer->country == "Slovakia" ? 'selected' : '' }}>Slovakia</option>
                                        <option value="Slovenia" {{ $customer->country == "Slovenia" ? 'selected' : '' }}>Slovenia</option>
                                        <option value="Solomon Islands" {{ $customer->country == "Solomon Islands" ? 'selected' : '' }}>Solomon
                                            Islands</option>
                                        <option value="Somalia" {{ $customer->country == "Somalia" ? 'selected' : '' }}>Somalia</option>
                                        <option value="South Africa" {{ $customer->country == "South Africa" ? 'selected' : '' }}>South Africa
                                        </option>
                                        <option value="South Korea" {{ $customer->country == "South Korea" ? 'selected' : '' }}>South Korea</option>
                                        <option value="South Sudan" {{ $customer->country == "South Sudan" ? 'selected' : '' }}>South Sudan</option>
                                        <option value="Spain" {{ $customer->country == "Spain" ? 'selected' : '' }}>Spain</option>
                                        <option value="Sri Lanka" {{ $customer->country == "Sri Lanka" ? 'selected' : '' }}>Sri Lanka</option>
                                        <option value="Sudan" {{ $customer->country == "Sudan" ? 'selected' : '' }}>Sudan</option>
                                        <option value="Suriname" {{ $customer->country == "Suriname" ? 'selected' : '' }}>Suriname</option>
                                        <option value="Swaziland" {{ $customer->country == "Swaziland" ? 'selected' : '' }}>Swaziland</option>
                                        <option value="Sweden" {{ $customer->country == "Sweden" ? 'selected' : '' }}>Sweden</option>
                                        <option value="Switzerland" {{ $customer->country == "Switzerland" ? 'selected' : '' }}>Switzerland</option>
                                        <option value="Syrian Arab Republic" {{ $customer->country == "Syrian Arab Republic" ? 'selected' : ''
                                            }}>Syrian Arab Republic</option>
                                        <option value="Taiwan, Province of China" {{ $customer->country == "Taiwan, Province of China" ? 'selected'
                                            : '' }}>Taiwan, Province of China</option>
                                        <option value="Tajikistan" {{ $customer->country == "Tajikistan" ? 'selected' : '' }}>Tajikistan</option>
                                        <option value="Tanzania, United Republic of" {{ $customer->country == "Tanzania, United Republic of" ?
                                            'selected' : '' }}>Tanzania, United Republic of</option>
                                        <option value="Thailand" {{ $customer->country == "Thailand" ? 'selected' : '' }}>Thailand</option>
                                        <option value="Togo" {{ $customer->country == "Togo" ? 'selected' : '' }}>Togo</option>
                                        <option value="Tokelau" {{ $customer->country == "Tokelau" ? 'selected' : '' }}>Tokelau</option>
                                        <option value="Tonga" {{ $customer->country == "Tonga" ? 'selected' : '' }}>Tonga</option>
                                        <option value="Trinidad and Tobago" {{ $customer->country == "Trinidad and Tobago" ? 'selected' : ''
                                            }}>Trinidad and Tobago</option>
                                        <option value="Tunisia" {{ $customer->country == "Tunisia" ? 'selected' : '' }}>Tunisia</option>
                                        <option value="Turkey" {{ $customer->country == "Turkey" ? 'selected' : '' }}>Turkey</option>
                                        <option value="Turkmenistan" {{ $customer->country == "Turkmenistan" ? 'selected' : '' }}>Turkmenistan
                                        </option>
                                        <option value="Turks and Caicos Islands" {{ $customer->country == "Turks and Caicos Islands" ? 'selected' :
                                            '' }}>Turks and Caicos Islands</option>
                                        <option value="Tuvalu" {{ $customer->country == "Tuvalu" ? 'selected' : '' }}>Tuvalu</option>
                                        <option value="Uganda" {{ $customer->country == "Uganda" ? 'selected' : '' }}>Uganda</option>
                                        <option value="Ukraine" {{ $customer->country == "Ukraine" ? 'selected' : '' }}>Ukraine</option>
                                        <option value="United Arab Emirates" {{ $customer->country == "United Arab Emirates" ? 'selected' : ''
                                            }}>United Arab Emirates</option>
                                        <option value="United Kingdom" {{ $customer->country == "United Kingdom" ? 'selected' : '' }}>United Kingdom
                                        </option>
                                        <option value="United States" {{ $customer->country == "United States" ? 'selected' : '' }}>United States
                                        </option>
                                        <option value="Uruguay" {{ $customer->country == "Uruguay" ? 'selected' : '' }}>Uruguay</option>
                                        <option value="Uzbekistan" {{ $customer->country == "Uzbekistan" ? 'selected' : '' }}>Uzbekistan</option>
                                        <option value="Vanuatu" {{ $customer->country == "Vanuatu" ? 'selected' : '' }}>Vanuatu</option>
                                        <option value="Venezuela, Bolivarian Republic of" {{ $customer->country == "Venezuela, Bolivarian Republic
                                            of" ? 'selected' : '' }}>Venezuela, Bolivarian Republic of</option>
                                        <option value="Vietnam" {{ $customer->country == "Vietnam" ? 'selected' : '' }}>Vietnam</option>
                                        <option value="Virgin Islands" {{ $customer->country == "Virgin Islands" ? 'selected' : '' }}>Virgin Islands
                                        </option>
                                        <option value="Yemen" {{ $customer->country == "Yemen" ? 'selected' : '' }}>Yemen</option>
                                        <option value="Zambia" {{ $customer->country == "Zambia" ? 'selected' : '' }}>Zambia</option>
                                        <option value="Zimbabwe" {{ $customer->country == "Zimbabwe" ? 'selected' : '' }}>Zimbabwe</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12 mb-8">
                                    <label for="zip_code" class="form-label">Zip Code</label>
                                    <input type="text" name="zip_code" id="zip_code" value="{{old('zip_code',$customer->zip_code)}}"
                                        class="form-control form-control-sm fs-7" placeholder="Zip Code">
                                </div>
                            </div>
                            <div class="form-group text-center mt-5">
                                <button type="button" onclick="showMoreInfo()" class="btn btn-primary btn-sm moreBtn rounded text-white">More
                                    Informations <i class="fa-solid fa-chevron-down text-white ms-4"></i></button>
                            </div>
                            <div id="more-info-fields" class="mt-8" style="display: none;">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="tax_number" class="form-label">Tax Number</label>
                                        <input type="text" name="tax_number" id="tax_number" value="{{old('tax_number',$customer->tax_number)}}"
                                            class="form-control form-control-sm fs-7" placeholder="Tax Number">
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="pay_term_value" class="form-label">Pay Term</label>
                                        <div class="input-group">
                                            <input type="number" name="pay_term_value" id="pay_term_value"
                                                value="{{old('pay_term_value',$customer->pay_term_value)}}"
                                                class="form-control form-control-sm rounded-end-0 fs-7">
                                            <div class="overflow-hidden flex-grow-1">
                                                <select name="pay_term_type" class="form-select form-select-sm rounded-start-0 fs-7"
                                                    data-control="select2" data-placeholder="Please select">
                                                    <option></option>
                                                    <option value="Months" {{ $customer->pay_term_type == "Months" ? 'selected' : '' }}>Months
                                                    </option>
                                                    <option value="Days" {{ $customer->pay_term_type == "Days" ? 'selected' : '' }}>Days</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="receivable_amount" class="form-label">Receivable Amount</label>
                                        <input type="number" name="receivable_amount" id="receivable_amount"
                                            value="{{old('receivable_amount',$customer->receivable_amount)}}"
                                            class="form-control form-control-sm fs-7" placeholder="Receivable Amount">
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="payable_amount" class="form-label">Payable Amount</label>
                                        <input type="number" name="payable_amount" id="payable_amount"
                                            value="{{old('payable_amount',$customer->payable_amount)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Payable Amount">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12 mb-8 credit-limit">
                                        <label for="credit_limit" class="form-label">Credit Limit</label>
                                        <input type="number" name="credit_limit" id="credit_limit"
                                            value="{{old('credit_limit',$customer->credit_limit)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Credit Limit">
                                    </div>
                                </div>
                                <hr style="opacity: 0.3;">
                                <div class="row mt-6">
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_1" class="form-label">Custom Field 1</label>
                                        <input type="text" name="custom_field_1" id="custom_field_1"
                                            value="{{old('custom_field_1',$customer->custom_field_1)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 1">
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_2" class="form-label">Custom Field 2</label>
                                        <input type="text" name="custom_field_2" id="custom_field_2"
                                            value="{{old('custom_field_2',$customer->custom_field_2)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 2">
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_3" class="form-label">Custom Field 3</label>
                                        <input type="text" name="custom_field_3" id="custom_field_3"
                                            value="{{old('custom_field_3',$customer->custom_field_3)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 3">
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_4" class="form-label">Custom Field 4</label>
                                        <input type="text" name="custom_field_4" id="custom_field_4"
                                            value="{{old('custom_field_4',$customer->custom_field_4)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 4">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_5" class="form-label">Custom Field 5</label>
                                        <input type="text" name="custom_field_5" id="custom_field_5"
                                            value="{{old('custom_field_5',$customer->custom_field_5)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 5">
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_6" class="form-label">Custom Field 6</label>
                                        <input type="text" name="custom_field_6" id="custom_field_6"
                                            value="{{old('custom_field_6',$customer->custom_field_6)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 6">
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_7" class="form-label">Custom Field 7</label>
                                        <input type="text" name="custom_field_7" id="custom_field_7"
                                            value="{{old('custom_field_7',$customer->custom_field_7)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 7">
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_8" class="form-label">Custom Field 8</label>
                                        <input type="text" name="custom_field_8" id="custom_field_8"
                                            value="{{old('custom_field_8',$customer->custom_field_8)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 8">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_9" class="form-label">Custom Field 9</label>
                                        <input type="text" name="custom_field_9" id="custom_field_9"
                                            value="{{old('custom_field_9',$customer->custom_field_9)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 9">
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-8">
                                        <label for="custom_field_10" class="form-label">Custom Field 10</label>
                                        <input type="text" name="custom_field_10" id="custom_field_10"
                                            value="{{old('custom_field_10',$customer->custom_field_10)}}" class="form-control form-control-sm fs-7"
                                            placeholder="Custom Field 10">
                                    </div>
                                </div>
                                <hr style="opacity: 0.3;">
                                <div class="row mt-6">
                                    <div class="col-md-4 col-sm-12">
                                        <label for="shipping_address" class="form-label">Shipping Address</label>
                                        <textarea name="shipping_address" id="shipping_address" class="form-control form-control-sm"
                                            style="resize: none;">{{ old('shipping_address',!empty($customer->shipping_address) ? $customer->shipping_address : null) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
{{-- </div> --}}
<script>
    $(document).ready(function() {
        $('[data-control="select2"]').each(function() {
            $(this).select2({ dropdownParent: $(this).parent()});
        })

        // toggle details
        $(document).on('click', '.toggle-details-edit-btn', function() {
            $('.show_detail_edit, .hide_detail_edit').toggleClass('d-none');
            $('.detail-toggle-class-edit').toggleClass('d-none');
        })
    })
    document.getElementById('edit_contact_modal').addEventListener('shown.bs.modal', function() {

        tempusDominus.extend(tempusDominus.plugins.customDateFormat);

        new tempusDominus.TempusDominus(document.getElementById('dob_edit'), {
                localization: {
                locale: "en",
                format: "dd/MM/yyyy",
            }
        });


        $(document).on('change', '#dob_edit', function () {
            let dobString = $('#dob_edit').val();
            let dobParts = dobString.split('/');
            let day = parseInt(dobParts[0], 10);
            let month = parseInt(dobParts[1], 10) - 1;
            let year = parseInt(dobParts[2], 10);

            let dob = new Date(year, month, day);
            if (dob) {
                let today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                let monthDiff = today.getMonth() - dob.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                $(this).closest('#edit_customer_form').find('.age').val(age);
            }
        })
        let modal = $('#edit_contact_modal');
        let individual = modal.find('#individual');
        let individualdivs = modal.find('.individual-div');
        let business = modal.find('#business');
        let businessdiv = modal.find('.business-div');

        let show_more_info = modal.find('#show_more_info');
        let contact_type = modal.find('#contact-type');

        $(individual).on('change', function() {
            for(let i = 0; i < individualdivs.length; i++){
                business.prop('checked', false);
                $(individualdivs[i]).removeClass('hide');
                businessdiv.addClass('hide');
            }
        })

        $(business).on('change', function() {
            for(let i = 0; i < individualdivs.length; i++){
                individual.prop('checked', false);
                $(individualdivs[i]).addClass('hide');
                businessdiv.removeClass('hide');
            }
        })

        // $(show_more_info).on('click', function() {
        //     let moreInfo = modal.find("#more-info-fields");
        //     let moreInfoBtn = modal.find(".moreBtn")

        //     if (moreInfo.css("display") === "none") {
        //         moreInfo.css("display", "block");
        //         moreInfoBtn.html(`Less Informations <i class="fa-solid fa-chevron-up text-white ms-4"></i>`);
        //     } else {
        //         moreInfo.css("display", "none");
        //         moreInfoBtn.html(`More Informations <i class="fa-solid fa-chevron-down text-white ms-4"></i>`);
        //     }
        // })

        $(contact_type).on('click', function(){
            let contactTypeSelect = modal.find('#contact-type');
            let customerGroupDiv = modal.find('.customer-group');
            let creditLimitDiv = modal.find('.credit-limit');

            if (contactTypeSelect.val() === "Customer") {
                customerGroupDiv.removeClass('hide');
                creditLimitDiv.removeClass('hide');
            } else if (contactTypeSelect.val() === "Supplier") {
                customerGroupDiv.addClass('hide');
                creditLimitDiv.addClass('hide');
            } else if (contactTypeSelect.val() === "Both") {
                customerGroupDiv.removeClass('hide');
                creditLimitDiv.removeClass('hide');
            }
        })
    });

</script>

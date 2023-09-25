@extends('App.main.navBar')
@section('contact_active','active')
@section('contact_active_show','active show')
@section('import_contacts_active','active')

@section('styles')

@endsection

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Import Contacts</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Contact</li>
    <li class="breadcrumb-item text-dark">Import Contacts</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div class="container-xxl" id="kt_content_container">
        <!--begin::Card-->
        <div class="card card-p-4 card-flush">
            <div class="card-body">
                <div class="row mb-8">
                    <div class="col-6">
                        <form action="">
                            <label for="" class="required form-label">File to import:</label>
                            <input type="file" class="form-control form-control-sm" name="" accept=".xls" />
                        </form>
                    </div>
                    <div class="col-6 mt-8">
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <a href="{{route('download-contact-excel')}}" class="btn btn-success btn-sm" download=""><i class="fa fa-download"></i>
                            Download template file</a>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Card-->

        <!--begin::Instruction Card-->
        <div class="card card-p-4 card-flush mt-8">
            <div class="card-body">
                <h4>Instructions</h4>
                <span class="d-inline-block mt-8">
                    <strong>Follow the instructions carefully before importing the file.</strong><br>
                    The columns of the file should be in the following order.
                </span>
                <div class="table-responsive mt-10">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Column Number</th>
                                <th>Column Name</th>
                                <th>Instruction</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Contact type <small class="text-muted">(Required)</small></td>
                                <td>
                                    Available Options: <strong><br> 1 = Customer, <br> 2 = Supplier <br> 3 =
                                        Both</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Prefix <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>First Name <small class="text-muted">(Required)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Middle name <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Last Name <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Business Name <br><small class="text-muted">(Required if contact type is
                                        supplier or both)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Contact ID <small class="text-muted">(Optional)</small></td>
                                <td>Leave blank to auto generate Contact ID</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Tax number <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Opening Balance <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Pay term <br><small class="text-muted">(Required if contact type is
                                        supplier or both)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Pay term period <br><small class="text-muted">(Required if contact type
                                        is supplier or both)</small></td>
                                <td><strong>Available Options: days and months</strong></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Credit Limit <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Email <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Mobile <small class="text-muted">(Required)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Alternate contact number <small class="text-muted">(Optional)</small>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Landline <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>City <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>State <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Country <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Address line 1 <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Address line 2 <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Zip Code <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Date of birth <small class="text-muted">(Optional)</small></td>
                                <td>Format Y-m-d (2023-03-25)</td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Custom Field 1 <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>25</td>
                                <td>Custom Field 2 <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>26</td>
                                <td>Custom Field 3 <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>27</td>
                                <td>Custom Field 4 <small class="text-muted">(Optional)</small></td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Instruction Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Content-->

@endsection

@push('scrips')
{{-- script for this page --}}
@endpush
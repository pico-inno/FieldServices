@extends('App.main.navBar')

@section('user_active','active')
@section('user_active_show','active show')
@section('user_here_show','here show')
@section('user_import_active_show','active show')

@section('styles')

@endsection

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-3">Import Users</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">Users</li>
    <li class="breadcrumb-item text-dark">Import</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
    @if(session('failures'))
        <div class="modal fade" data-bs-backdrop="static"  data-bs-keyboard="false"  aria-hidden="true" tabindex="-1" id="error_modal">
            <div class="modal-dialog modal-dialog-scrollable mw-850px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error Found in excel</h5>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive table-striped">
                            <div class="table-body">
                                <table class="table table-row-dashed table-row-gray-300">
                                    <thead>
                                    <tr class="fw-bold fs-5 text-danger border-bottom border-gray-200">
                                        <th>Row No</th>
                                        <th>Reason</th>
                                        <th>Values</th>
                                    </tr>
                                    </thead>
                                    <tbody style="max-height: 300px; overflow-y: auto;">
                                    @foreach (session('failures') as $failure)
                                        <tr>
                                            <td class="text-danger">{{ $failure->row() }}</td>
                                            <td class="text-danger">{{ implode(', ', $failure->errors()) }}</td>
                                            <td class="">
                                                Username : <span class="text-gray-700">{{ $failure->values()['username'] }}</span><br>
                                                Email  : <span class="text-gray-700">{{ $failure->values()['email'] }}</span> <br>
                                                Role Name : <span class="text-gray-700">{{ $failure->values()['role_name'] }}</span> <br>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div class="container-xxl" id="kt_content_container">
        @if(session('error-swal'))
            <div class="alert alert-danger">
                {{ session('error-swal') }}
            </div>
        @endif
        <!--begin::Card-->
        <div class="card card-p-4 card-flush">
            <div class="card-body">
                <div class="row mb-8">
                    <form id="importForm" action="{{route('users.import.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-6">
                            <label for="" class="required form-label">File to import:</label>
                            <input type="file" class="form-control form-control-sm" name="user_template_file" />
                        </div>
                        <div class="col-6 mt-8">
                            <button id="submitBtn" type="submit" class="btn btn-primary btn-sm me-10">
                                <span id="indicator-label">Submit</span>
                                <span id="loadingSpinner" class="indicator-progress" style="display: none;">
                                        User importing... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                <input type="hidden" value="0" id="checkBtn">
                            </button>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <a href="{{route('users.import.template-download')}}" class="btn btn-success btn-sm" download=""><i class="fa fa-download"></i>
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


            <div class="modal fade"  id="import_process_modal" data-bs-backdrop="static"  data-bs-keyboard="false"  aria-hidden="true"  tabindex="-1" aria-modal="true" role="dialog">
                <!--begin::Modal dialog-->
                <div class="modal-dialog mw-700px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header pb-0 border-0 d-flex justify-content-end">

                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-10 pt-0 pb-15">
                            <!--begin::Heading-->
                            <div class="text-center mb-13">
                                <!--begin::Title-->
                                <h1 class="d-flex justify-content-center align-items-center mb-3">Importing Users<span class="loading-dots"></span></h1>
                                <!--end::Title-->
                                <!--begin::Description-->
                                <div class="text-muted fw-semibold fs-5">Please wait until the import process is complete.</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Heading-->
                            <!--begin::Users-->
                            <div class="mh-475px scroll-y me-n7 pe-7">

                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                            </div>
                            <!--end::Users-->
                        </div>
                        <!--end::Modal Body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>

    </div>
    <!--end::Container-->
</div>
<!--end::Content-->

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#error_modal').modal('show');
        });
    </script>
    <script>
        var interval;

        function startLoadingDots() {
            var dots = 0;
            interval = setInterval(function () {
                $('.loading-dots').text(Array(dots % 4).fill('.').join(''));
                dots++;
            }, 500);
        }

        @if(session('success-swal'))
        Swal.fire({
            text: '{{session('success-swal')}}',
            icon: "success",
            buttonsStyling: false,
            showCancelButton: false,
            confirmButtonText: "Ok, got it.",
            customClass: {
                confirmButton: "btn btn-primary",
            }
        })
        clearInterval(interval);
        @endif

        $(document).ready(function () {
            $('#importForm').submit(function (event) {
                console.log('imput submit');
                $('#submitBtn').prop('disabled', true);

                $('#loadingSpinner').css('display', 'inline-block');
                $('#indicator-label').css('display', 'none');
                $('#import_process_modal').modal('show');
                console.log('model show submit');
                startLoadingDots();
            });

        });


    </script>
@endpush

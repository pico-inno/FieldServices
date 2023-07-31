@extends('App.main.navBar')

@section('styles')
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        h1 {
            color: #343a40;
            font-weight: bold;
            font-size: 3rem;
            margin-top: 3rem;
            margin-bottom: 2rem;
        }

        .form-control {
            height: 3rem;
            border-radius: 5px;
            border: none;
            box-shadow: none;
            /* border-bottom: 1px solid #ced4da; */
            font-size: 1.2rem;
            /* padding-left: 2rem; */
            background-image: none;
            background-color: rgb(245, 243, 243);
            color: #495057;
            margin-bottom: 1.5rem;
        }

        .form-control:focus {
            border-color: #4dabf5;
            box-shadow: none;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .form-group label {
            font-weight: bold;
            color: #343a40;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .btn-primary {
            background-color: #5fa4ee;
            border-radius: 0;
            padding: 1rem 4rem;
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 2rem;
            transition: all 0.2s ease-in-out;
            border: none;
        }

        .btn-primary:hover {
            background-color: #3382d6;
            border: none;
        }

    </style>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-6 shadow-lg">
                    <h1 class="text-center mt-0 mb-5">Add Discount</h1>
                    <form class="mt-5">
                        <div class="form-group mb-5">
                            <label for="contact-type">Date *</label>
                            <input type="date" name="" id="" class="form-control">
                        </div>

                        <div class="form-group mb-5">
                            <label for="contact-type">Amount *</label>
                            <input type="text" name="" id="" value="0" class="form-control">
                        </div>

                        <div class="form-group mb-5">
                            <label for="contact-type">Note :</label>
                            <textarea name="paymentNote" class="form-control" id="" cols="30" rows="6"></textarea>
                        </div>

                         <div class="form-group text-center mt-5 justify-content-end d-flex">
                            <button type="submit" class=" btn-primary btn-lg btn-block rounded text-white">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>

    </script>
@endpush

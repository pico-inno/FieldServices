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
                    <h1 class="text-center mt-0 mb-5">Add Payment</h1>
                    <div class="row fs-3">
                        {{-- first card in card --}}
                        <div class="card p-5 col-5 bg-secondary ">
                            <div>Supplier :</div>
                            <div>Business :</div>
                        </div>
                        {{-- second card in card --}}
                        <div class="card p-5 col-6 offset-1 bg-secondary">
                            <div>Total Purchase: Ks 0</div>
                            <div>Total Paid: Ks 0</div>
                            <div>Total Purchase Due: Ks 0</div>
                            <div>Opening Balance: Ks 0</div>
                            <div>Opening Balance Due: Ks 0</div>
                        </div>
                    </div>
                    <form class="mt-5">
                        <div class="form-group mb-5">
                            <label for="contact-type">Payment Method *</label>
                            <select class="form-select form-select-lg mb-3"  id="contact-type" aria-label=".form-select-lg example" onclick="showCG()">
                                <option value="cash" selected>Cash</option>
                                <option value="card" >Card</option>
                                <option value="cheque">Cheque</option>
                                <option value="bankTransfer">Bank Transfer</option>
                                <option value="other">other</option>
                            </select>
                        </div>

                        <div class="form-group mb-5">
                            <label for="contact-type">Paid On *</label>
                            <input type="date" name="" id="" class="form-control">
                        </div>

                        <div class="form-group mb-5">
                            <label for="contact-type">Amount *</label>
                            <input type="text" name="" id="" value="0" class="form-control">
                        </div>

                        <div class="form-group mb-5">
                            <label for="contact-type">Attach Document :</label>
                            <input type="file" name="" id="" class="form-control">
                            <div><small class="fs-4">Allowed File: .pdf, .csv, .zip, .doc, .docx, .jpeg, .jpg, .png</small></div>
                        </div>

                        <div class="form-group mb-5">
                            <label for="contact-type">Payment Account :</label>
                            <select class="form-select form-select-lg mb-3"  id="contact-type" aria-label=".form-select-lg example" onclick="showCG()">
                                <option value="none" selected>None</option>
                                <option value="Agoda" >Agoda</option>
                                <option value="Htee Hlaing Shin">Htee Hlaing Shin</option>
                                <option value="Cash in Hand">Cash in Hand</option>
                                <option value="KBZ Special">KBZ Special</option>
                                <option value="K Pay">K Pay</option>
                            </select>
                        </div>

                        <div class="form-group mb-5">
                            <label for="contact-type">Payment Note :</label>
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

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="container">
            <div id="invoicenode-app">
                <!-- The following lines can be used in any template as along as you include them on the same page. -->
                <invoicenodeapp v-cloak></invoicenodeapp>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/feikwok/laravel-invoice-node/js/app.js') }}"></script>
    <style>

    </style>
@endsection
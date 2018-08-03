@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="container">
            <div id="invoicenova-app">
                <!-- The following lines can be used in any template as along as you include them on the same page. -->
                <invoicenovaapp v-cloak></invoicenovaapp>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/feikwok/laravel-invoice-nova/js/app.js') }}"></script>
    <style>

    </style>
@endsection
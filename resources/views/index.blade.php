<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('meta')
        <title>@yield('title')</title>
        <link rel="shortcut icon" href="{{ asset('images/favicon.svg') }}">
        @yield('css-lib')
        <link href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('js/config.js') }}"></script>
        <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
        <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/style.css?v=' . Helper::$css_asset_version) }}" rel="stylesheet" type="text/css" />
        @yield('page-css')
    </head>
    <body>
        <div class="wrapper">
            @include('includes.topbar')
            @include('includes.sidebar')
            <div class="content-page">
                <div class="content mb-3">
                    @yield('content')
                </div>
                @include('includes.footer')
            </div>
        </div>
        <div class="dynamic-page-modals"></div>
        <script src="{{ asset('js/vendor.min.js') }}"></script>
        <script src="{{ asset('js/app.min.js') }}"></script>
        @yield('js-lib')
        <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('js/base.js?v=' . Helper::$js_asset_version) }}"></script>
        @yield('js')
    </body>
</html>

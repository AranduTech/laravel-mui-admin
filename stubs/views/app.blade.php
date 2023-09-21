<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="root"></div>
    @auth
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
            @csrf
        </form>
    @endauth
    @if (isset($react))
        <div id="react-injections" style="display: none">    
            @foreach ($react->routes() as $key => $value)
                <div id="route-data-{{ $key }}" data-value="{{ $value }}"></div>
            @endforeach
            
            @foreach ($react->all() as $key => $value)
                <div id="react-data-{{ $key }}" data-value="{{ is_array($value) ? json_encode($value) : $value }}" {{ is_array($value) ? 'data-json=1' : '' }}></div>
            @endforeach
            
            @foreach ($react->catchables() as $errorKey)
                @error($errorKey)
                    <div id="error-{{ $errorKey }}" data-value="{{ $message }}"></div>
                @enderror
            @endforeach
        </div>
    @endif
     <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    @yield('script')
</body>
</html>

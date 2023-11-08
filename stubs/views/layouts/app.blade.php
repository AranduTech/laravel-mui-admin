<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @vite(['resources/sass/app.scss'])
</head>
<body>
    <div id="root"></div>
    @auth
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
            @csrf
        </form>
    @endauth
    <div id="react-injections" style="display: none">
        @if (isset($js))
            @foreach ($js->catchables() as $errorKey)
                @error($errorKey)
                    <div id="error-{{ $errorKey }}" data-value="{{ $message }}"></div>
                @enderror
            @endforeach
        @endif
    </div>
    
     <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    @yield('script')
</body>
</html>

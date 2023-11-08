@extends('layouts.app')

@section('script')
<!-- <script src="{{ mix('js/authenticated.js') }}" defer></script> -->
@viteReactRefresh
@vite(['resources/js/authenticated.js'])
@endsection

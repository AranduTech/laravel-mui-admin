@extends('layouts.app')

@section('script')
<!-- <script src="{{ mix('js/admin.js') }}" defer></script> -->
@viteReactRefresh
@vite(['resources/js/admin.js'])
@endsection

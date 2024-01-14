@extends('layouts.app')

@section('script')
@viteReactRefresh
@vite(['resources/js/authenticated.js'])
@endsection

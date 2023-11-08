@extends('layouts.app')

@section('script')
@viteReactRefresh
@vite(['resources/js/guest.js'])
@endsection

@extends('layouts.app')

@section('script')
@viteReactRefresh
@vite(['resources/js/admin.js'])
@endsection

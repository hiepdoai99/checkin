@extends('layout.tenant')

@section('title', __t('departments'))

@section('contents')
    {{-- <app-departments></app-departments> --}}
    <app-tabs-departments></app-tabs-departments>
@endsection

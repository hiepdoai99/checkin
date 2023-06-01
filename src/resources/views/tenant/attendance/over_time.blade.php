@extends('layout.tenant')

@section('title', __t('over_time'))

@section('contents')
    {{-- @php
        $banner = settings('tenant_banner', 'app_banner');
        $banner = $banner ? asset($banner) : asset('images/system-upgrade.jpg');
    @endphp
    <div class="bg-white h-100">
        <div class="row justify-content-center text-center">
            <div class="col-6">
                <img class="w-50" src="{{ $banner }}" alt="...">
            </div>
            <div class="col-12">
                <h2>
                    Tính năng đang được phát triển
                </h2>
            </div>
        </div>
    </div> --}}
    <app-over-time
            details-id="{{ request()->query('details_id') }}"
            attendance-id="{{ request()->query('id') }}"
            >
    </app-over-time>
@endsection

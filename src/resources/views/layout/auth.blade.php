@extends('common.master')

@section('master')
    <div class="row">

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-4">
            @yield('contents')
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-8 col-xxl-8">
            @php
                $banner = settings('tenant_banner', 'app_banner');
                $banner = $banner ? asset($banner) : asset('images/bg_login.jpg');
            @endphp
            <div class="back-image" style="background-image: url({{ $banner }})">
                {{-- <div class="back-image"> --}}
                <div class="content-image">
                    <div class="text-back-image">
                        <p> <span>Vicheckin</span> xin chào bạn!</p>
                        <p>Ứng dụng quản lý nhân sự 4.0 sử dụng công nghệ Camera AI</p>
                        <a href="javascript:void(0)">Tìm hiểu thêm</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

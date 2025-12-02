@php
    $bgShapes = ['shape1','shape2','shape3','shape4','shape5','shape6','shape7','shape8'];
    $logoUrl = $settings->site_logo ? Storage::url($settings->site_logo) : asset('mobile/img/bg-img/12.png');
@endphp

<div class="login-wrapper d-flex align-items-center justify-content-center">
    <div class="login-shape">
        <img src="{{ asset('mobile/img/core-img/login.png') }}" alt="">
    </div>
    <div class="login-shape2">
        <img src="{{ asset('mobile/img/core-img/login2.png') }}" alt="">
    </div>

    <div class="container">
        <div class="login-text text-center">
            <img class="login-img" src="{{ $logoUrl }}" alt="{{ $settings->site_name }}">
            <h3 class="mb-0">Selamat Datang!</h3>
            <div class="bg-shapes">
                @foreach($bgShapes as $shape)
                    <div class="{{ $shape }}"></div>
                @endforeach
            </div>
        </div>

        <div class="register-form mt-5 px-3">
            <form action="/admin/login" method="post">
                @csrf
                <div class="form-group text-start mb-4">
                    <label for="email">
                        <i class="ti ti-user-circle"></i>
                    </label>
                    <input class="form-control" id="email" type="text" name="email" placeholder="Email" required>
                </div>
                <div class="form-group text-start mb-4">
                    <label for="password">
                        <i class="ti ti-circle-key"></i>
                    </label>
                    <input class="form-control" id="password" type="password" name="password" placeholder="Password" required>
                </div>
                <button class="btn btn-primary btn-lg w-100" type="submit">Masuk</button>
            </form>
        </div>

        <div class="login-meta-data text-center">
            <a class="forgot-password d-block mt-3 mb-1" href="/admin/forgot-password">Lupa kata sandi?</a>
            <p class="mb-0">Belum punya akun?<a class="ms-2" href="{{ route('mobile.register') }}">Daftar</a></p>
        </div>
    </div>
</div>

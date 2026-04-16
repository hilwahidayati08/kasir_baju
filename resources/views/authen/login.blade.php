@extends('authen.temp')
@section('content')
<div class="login-box">
    <div class="login-logo">
      <a href=""><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="{{ route('proseslogin')}}" method="POST">
            {{ csrf_field() }}
          <div class="input-group mb-3">
            <input type="email" class="form-control" name="user_email" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('user_email'))
          <span class="text-danger">{{ $errors->first('user_email') }}</span>
          @endif

          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
        </div>
        @if ($errors->has('password'))
        <span class="text-danger">{{ $errors->first('password') }}</span>
        @endif

          <div class="row">

            <div class="col-12">
              <button type="submit" class="btn btn-info btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>@extends('authen.temp')

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">

            <!-- Login Card -->
            <div class="card">
                <div class="card-body">

                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-3">
                        <a href="#" class="app-brand-link gap-2">
                            <!-- Tambahkan Logo Jika Ada -->
                        </a>
                    </div>

                    <h4 class="mb-1 text-center">Selamat Datang</h4>
                    <h4 class="mb-4 text-center">di House of Hilwa</h4>
                    <p class="mb-4 text-center">Silahkan Masuk dengan Akun Anda</p>

                    <!-- Flash Error -->
                    @if (session('error'))
                        <div id="error-alert" class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form Login -->
                    <form action="{{ route('proseslogin') }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="user_email"
                                placeholder="Email Anda"
                                value="{{ old('user_email') }}"
                                required
                            >
                            @error('user_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Kata Sandi</label>
                            </div>

                            <div class="input-group input-group-merge">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control"
                                    name="password"
                                    placeholder="***********"
                                    required
                                >
                                <span class="input-group-text cursor-pointer">
                                    <i class="bx bx-hide"></i>
                                </span>
                            </div>

                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Button -->
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">
                                Masuk
                            </button>
                        </div>

                    </form>

                </div>
            </div>
            <!-- /Login Card -->

        </div>
    </div>
</div>
@endsection

<!-- Auto-hide error alert -->
<script>
    setTimeout(() => {
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            errorAlert.style.display = "none";
        }
    }, 3000);
</script>

@endsection
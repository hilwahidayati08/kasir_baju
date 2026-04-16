@extends('admin.admin')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card" style="max-width: 600px; margin:auto;">
        <div class="card-header">
            <h4 class="mb-0">Profile Settings</h4>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- FOTO PREVIEW --}}
                <div class="text-center mb-3">
<img src="{{ $user->photo ? Storage::url($user->photo) : asset('default.png') }}"
     class="rounded-circle mb-2"
     width="120"
     height="120"
     style="object-fit:cover;">



                    <input type="file" name="photo"
                           class="form-control @error('photo') is-invalid @enderror"
                           style="max-width:250px;margin:auto">

                    @error('photo')
                        <div class="invalid-feedback text-center">{{ $message }}</div>
                    @enderror
                </div>

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="user_name"
                        value="{{ old('user_name', $user->user_name) }}"
                        class="form-control @error('user_name') is-invalid @enderror">

                    @error('user_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="user_email"
                        value="{{ old('user_email', $user->user_email) }}"
                        class="form-control @error('user_email') is-invalid @enderror">

                    @error('user_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-3">
                    <label class="form-label">Password Baru (opsional)</label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror">

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary w-100">Update Profile</button>
            </form>

        </div>
    </div>

</div>

@endsection

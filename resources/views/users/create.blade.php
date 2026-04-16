@extends('admin.admin')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Form Ubah Pengguna</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        {{ csrf_field() }}

                        {{-- Name --}}
                        <div class="row mb-3">
                            <label for="user_name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" id="user_name" name="user_name" class="form-control" 
                                       value="{{ old('user_name') }}" required/>
                                @if ($errors->has('user_name'))
                                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="row mb-3">
                            <label for="user_email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" id="user_email" name="user_email" class="form-control" 
                                       value="{{ old('user_email') }}" required/>
                                @if ($errors->has('user_email'))
                                    <span class="text-danger">{{ $errors->first('user_email') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="row mb-3">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" id="password" name="password" class="form-control" required/>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10 d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" id="admin" name="role" 
                                           value="Admin" {{ old('role') == 'Admin' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="admin">Admin Pusat</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="cabang" name="role" 
                                           value="Cabang" {{ old('role') == 'Cabang' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cabang">Admin Cabang</label>
                                </div>                                
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="cashier" name="role" 
                                           value="Cashier" {{ old('role') == 'Cashier' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cashier">Cashier</label>
                                </div>
                                @if ($errors->has('role'))
                                    <span class="text-danger ms-2">{{ $errors->first('role') }}</span>
                                @endif
                            </div>

                        <div class="row mb-3">
                            <label for="branch_id" class="col-sm-2 col-form-label">cabang</label>
                            <div class="col-sm-10">
                                <select name="branch_id" id="branch_id" class="form-select">
                                    <option value="">Select cabang</option>
                                    @foreach ($dataBranch as $v)
                                        <option value="{{ $v->branch_id }}" {{ old('branch_id') == $v->branch_id ? 'selected' : '' }}>
                                            {{ $v->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('branch_id'))
                                    <span class="text-danger">{{ $errors->first('branch_id') }}</span>
                                @endif
                            </div>
                        </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                <a href="{{ route('users.index')}}" class="btn btn-warning">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

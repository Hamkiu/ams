@extends('layouts.master')
@section('title', 'Edit Pengguna')
@section('content')
@include('include.error')
<div class="card-body">
    <form action="{{ route('user.update', encode($user->id)) }}" method="POST" id="edit_user_form" enctype="multipart/form-data">
        @csrf
        {{-- <h4 class="card-title">Special title treatment</h4> --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="mb-4">Edit Pengguna</h5>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">
                                No Pekerja <span class="text-danger">*</span>
                            </label>
                        
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="material-icons-outlined fs-5">badge</i>
                                    </span>
                        
                                    <input type="text"
                                           class="form-control"
                                           id="paynumber"
                                           name="no_pekerja"
                                           placeholder="Sila Masukkan No Pekerja" value="{{ $user->no_pekerja }}">
                        
                                    <button class="btn btn-primary btn-sm" type="button" id="btnSearchPekerja">
                                        <i class="material-icons-outlined">search</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                            <div class="row mb-3">
                                <label for="input49" class="col-sm-3 col-form-label">Nama Pengguna</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="material-icons-outlined fs-5">person</i></span>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Pengguna" readonly value="{{ $user->name }}">
                                      </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input53" class="col-sm-3 col-form-label">Role Pengguna<span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="material-icons-outlined fs-5">format_list_bulleted</i></span>
                                        <select class="form-select" id="role" name="role">
                                            <option value="" selected disabled>
                                                --- Pilih Role ---
                                            </option>
                                        
                                            @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role', $user->roles->pluck('id')->first()) == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                        </select>
                                      </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input50" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="material-icons-outlined fs-5">email</i></span>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $user->email }}">
                                      </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input51" class="col-sm-3 col-form-label">Jabatan</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="material-icons-outlined fs-5">business</i></span>
                                        <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan" value="{{ $user->jabatan }}" readonly>
                                      </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input52" class="col-sm-3 col-form-label">Jawatan</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="material-icons-outlined fs-5">work</i></span>
                                        <input type="text" class="form-control" id="jawatan" name="jawatan" placeholder="Jawatan" value="{{ $user->jawatan }}" readonly>
                                      </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input54" class="col-sm-3 col-form-label">Kata Laluan (sama dengan SPBT)</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="material-icons-outlined fs-5">lock</i></span>
                                        <input type="text" class="form-control" id="password" name="password" placeholder="Kata Laluan" value="{{ $user->password }}" readonly>
                                      </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-success">Kemaskini</button>
                                        <a href="{{ route('user') }}" class="btn btn-secondary">Kembali</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('btnSearchPekerja').addEventListener('click', function () {

        const paynumber = document.getElementById('paynumber').value.trim();

        if (!paynumber) {
            Swal.fire({
                    icon: 'warning',
                    title: 'Sila Masukkan No Pekerja',
                    timer: 1500,
                    showConfirmButton: true
                });
                return;
        }

        fetch('/api/cari-pekerja', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            paynumber: document.getElementById('paynumber').value
        })
        })
        .then(res => res.json())
        .then(res => {
            const data = res.body;

            if (!data.maklumatUser || data.maklumatUser.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maklumat Tidak Dijumpai',
                    text: 'Maklumat pekerja tidak dijumpai.',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                return;
            }


            const u = data.maklumatUser[0];

            document.getElementById('name').value = u.MAS_STAFFNAME ?? '';
            document.getElementById('jawatan').value = u.JAW_JAWATNAME ?? '';
            document.getElementById('jabatan').value = u.PTJ_PTJPKNAME ?? '';
            document.getElementById('password').value = u.USE_PASSWORDS ?? '';
        })

            .catch(err => {
                console.error(err);
                alert('Ralat semasa carian maklumat pekerja');
            });
    });
</script>
@endpush
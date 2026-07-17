@extends('layouts.master')
@section('title', 'Senarai Pengguna')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-0">
                <i data-feather="users"></i>
                Senarai Pengguna
            </h5>
    
            <small class="text-muted">
                Pengurusan pengguna sistem
            </small>
        </div>
    
        <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">
            <i data-feather="plus"></i>
            Tambah Pengguna
        </a>
    
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="userTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pekerja</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Jabatan</th>
                        <th>Jawatan</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    $('#userTable').DataTable({

        processing: true,
        serverSide: true,
        pageLength: 25,

        lengthMenu: [
            [25, 50, 75, -1],
            [25, 50, 75, "Semua"]
        ],

        ajax: {
            url: "{{ route('user.list') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            }
        },

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', width: '3%'},
            {
                data: 'no_pekerja',
                name: 'no_pekerja',
                className: 'text-center',
                width: '3%'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'roles',
                name: 'roles'
            },
            {
                data: 'jabatan',
                name: 'jabatan'
            },
            {
                data: 'jawatan',
                name: 'jawatan'
            },
            {
                data: 'status',
                name: 'status',
                className: 'text-center align-middle'
            },
            {
                data: 'tindakan',
                name: 'tindakan',
                orderable: false,
                searchable: false
            }
        ],

        dom:
        "<'row mb-3 align-items-center'<'col-md-6 d-flex align-items-center gap-3'lB><'col-md-6 text-end'f>>" +
        "<'row'<'col-12'tr>>" +
        "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",

        buttons: [
            {
                extend: 'excel',
                className: 'btn-sm'
            },
            {
                extend: 'pdf',
                className: 'btn-sm'
            },
            {
                extend: 'print',
                className: ' btn-sm'
            }
        ]

    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berjaya!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: true
        });        
    @endif

});
</script>
@endpush
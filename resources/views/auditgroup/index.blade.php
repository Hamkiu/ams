@extends('layouts.master')
@section('title', 'Senarai Audit Group')
@section('content')
@include('include.error')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i data-feather="users"></i>
                Senarai Audit Group
            </h5>
        </div>
        <div>
            <a href="{{ route('auditgroup.create') }}" class="btn btn-primary">
                <i data-feather="plus"></i>
                Tambah Group
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="auditGroupTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Id Kumpulan</th>
                        <th>Template</th>
                        <th>Nama Kumpulan</th>
                        <th>Jabatan / Unit</th>
                        <th>Bil. Juruaudit</th>
                        <th>Status</th>
                        <th>Dicipta Oleh</th>
                        <th>Dikemaskini Oleh</th>
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
        $('#auditGroupTable').DataTable({

            processing: true,
            serverSide: true,
            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],

            ajax: {
                url: "{{ route('auditgroup.list') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            },

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', width: '2%'},
                {
                    data: 'id',
                    name: 'id',
                    width: '4%'
                },
                {
                    data: 'audit_template_id',
                    name: 'audit_template_id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'jabatan',
                    name: 'jabatan'
                },
                {
                    data: 'bil_juruaudit',
                    name: 'bil_juruaudit',
                    width: '5%',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    width: '5%',
                    className: 'text-center'
                },
                {
                    data: 'created_by',
                    name: 'created_by'
                },
                {
                    data: 'updated_by',
                    name: 'updated_by'
                },
                {
                    data: 'tindakan',
                    name: 'tindakan',
                    orderable: false,
                    searchable: false
                }
            ],

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
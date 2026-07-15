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
            <table id="userTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pekerja</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Jabatan</th>
                        <th>Jawatan</th>
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
    $(function () {
        $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                type: 'POST',
                url: "{{ route('user.list') }}",
                data: function (d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', width: '3%'},
                { data: 'no_pekerja', name: 'no_pekerja', width: '3%'},
                { data: 'name', name: 'name' },
                { data: 'roles', name: 'roles' },
                { data: 'jabatan', name: 'jabatan' },
                { data: 'jawatan', name: 'jawatan' },
                { data: 'tindakan', name: 'tindakan', searchable: false, orderable: false },
            ],
            "lengthMenu": [
                [25, 50, 75, -1],
                [25, 50, 75, "All"]
            ],
            "pageLength": 25
        });
    });
</script>
@endpush
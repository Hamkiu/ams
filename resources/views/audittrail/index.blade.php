@extends('layouts.master')
@section('title', 'Audit Log')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-0">
                <i data-feather="activity"></i>
                Senarai Audit Log
            </h5>
    
            <small class="text-muted">
                Pengurusan audit log sistem
            </small>
        </div>
    
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="auditTrailTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Module</th>
                        <th>Component</th>
                        <th>Action</th>
                        <th>Action By</th>
                        <th>Action At</th>
                        <th>Description</th>
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

    $('#auditTrailTable').DataTable({

        processing: true,
        serverSide: true,
        pageLength: 25,

        lengthMenu: [
            [25, 50, 75, -1],
            [25, 50, 75, "Semua"]
        ],

        ajax: {
            url: "{{ route('audittrail.list') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            }
        },

        columns: [
            {
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex'
            },
            {
                data: 'module', 
                name: 'module'
            },
            {
                data: 'component', 
                name: 'component'
            },
            {
                data: 'action', 
                name: 'action'
            },
            {
                data: 'actionby', 
                name: 'actionby'
            },
            {
                data: 'actionat', 
                name: 'actionat'
            },
            {
                data: 'description', 
                name: 'description'
            },
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
});
</script>
@endpush
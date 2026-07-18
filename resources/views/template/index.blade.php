@extends('layouts.master')
@section('title', 'Senarai Audit Template')
@section('content')
@include('include.error')
@include('template.create')

{{-- list table template --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i data-feather="file-text"></i>
                Senarai Audit Template
            </h5>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="templateTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Template</th>
                        <th>No Rujukan</th>
                        <th>No Pindaan</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th>Dicipta Oleh</th>
                        <th>Dikemaskini Oleh</th>
                        <th>Dicipta Pada</th>
                        <th>Dikemaskini Pada</th>
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
{{-- end list table template --}}
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#version').on('blur', function () {

            let value = parseFloat($(this).val());

            if (!isNaN(value)) {
                $(this).val(value.toFixed(1));
            }

        });


    });
</script>
@endpush
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
                        <th>No Template</th>
                        <th>Nama Template</th>
                        <th>No Rujukan</th>
                        <th>No Pindaan</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th>Tarikh Berkuatkuasa</th>
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
{{-- end list table template --}}
@endsection
@push('modal')
    <div class="modal fade" id="aMd1" tabindex="-1" role="dialog" aria-labelledby="aMdl" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="aMd1_content">
                
            </div>
        </div>
    </div>
@endpush
@push('scripts')
<script>
    $(document).ready(function () {
        $('#version').on('blur', function () {

            let value = parseFloat($(this).val());

            if (!isNaN(value)) {
                $(this).val(value.toFixed(1));
            }

        });

        $('#templateTable').DataTable({

            processing: true,
            serverSide: true,
            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],

            ajax: {
                url: "{{ route('audittemplate.list') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            },

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', width: '2%'},
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'no_rujukan',
                    name: 'no_rujukan'
                },
                {
                    data: 'no_pindaan',
                    name: 'no_pindaan',
                    className: 'text-center',
                    width: '3%'
                },
                {
                    data: 'version',
                    name: 'version',
                    className: 'text-center',
                    width: '3%'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'tarikh_berkuatkuasa',
                    name: 'tarikh_berkuatkuasa',
                    width: '5%'
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

        $('body').on('click','.editTemplate', function (e){
            var id = $(this).data("id");
            var url = '{{ route("audittemplate.show", ":id") }}';
            var new_url = url.replace(':id', id);
            $.ajax({
                url: new_url,
                type:'GET',
                success: function(data) {
                    $('#aMd1_content').html(data);
                    $('#aMd1').modal('show');
                }
            });
        });
    });
</script>
@endpush
@extends('layouts.master')
@section('title', 'Senarai Checklist')
@section('content')
@include('include.error')
@include('checklist.create')

{{-- list table template --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i data-feather="file-text"></i>
                Senarai Penemuan Audit
            </h5>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="checklistTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Penemuan Audit</th>
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
        $('#checklistTable').DataTable({

            processing: true,
            serverSide: true,
            autoWidth: false,
            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],

            ajax: {
                url: "{{ route('audittemplate.checklist.list', encode($auditTemplateItems->id)) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            },

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', width: '2%'},
                {
                    data: 'name',
                    name: 'name'
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

        $('body').on('click','.editChecklist', function (e){
            var id = $(this).data("id");
            var url = '{{ route("audittemplate.checklist.edit", ":id") }}';
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


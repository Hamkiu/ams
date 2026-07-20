@extends('layouts.master')
@section('title', 'Edit Audit Group')
@section('content')
@include('include.error')
    <form action="{{ route('auditgroup.update', encode($auditGroup->id)) }}" method="POST" id="store_audit_group_form" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i data-feather="users"></i>
                        Edit Maklumat Group
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="name">Nama Template</label>
                            <select name="template" class="form-control select2">
                                <option value="">-- Pilih Template --</option>
                                @forelse ($auditTemplates as $auditTemplate)
                                    <option value="{{ $auditTemplate->id }}" {{ $auditTemplate->id == $auditGroup->audit_template_id ? 'selected' : '' }}>{{ $auditTemplate->name }}</option>
                                @empty
                                @endforelse
                            </select>                    
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="name">Nama / Nombor Group</label>
                            <input type="text" name="name" class="form-control" value="{{ $auditGroup->name }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="name">Jabatan / Unit</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ $auditGroup->jabatan }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="name">Cadangan Tarikh</label>
                            <input type="date" name="tarikh" class="form-control" value="{{ old('tarikh', optional($auditGroup->tarikh)->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    Kemaskini
                </button>
                <a href="{{ route('auditgroup') }}" class="btn btn-secondary ms-2">
                    Kembali
                </a>
            </div>
        </div>
    </form>
    @include('auditgroup.member.index')
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: true
            });        
        @endif

        $('#user_id').on('change', function () {

            let jabatan = $(this).find(':selected').data('jabatan') || '';

            $('#jabatan').val(jabatan);

        });

        $('body').on('click','.addAttOpen',function (e) {
            e.preventDefault();
            var user_id = document.forms["auditGroupMemberForm"]["user_id"].value;
            var role = document.forms["auditGroupMemberForm"]["role"].value;
            if (user_id == "" || role == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Sila pilih juruaudit dan isi peranan',
                    showConfirmButton: false,
                    timer: 1500
                })
                return;
            }
            var fd = new FormData($("#auditGroupMemberForm")[0]);

                Swal.fire({
                    title: 'Adakah anda pasti?',
                    text: "Anda tidak akan dapat membatalkan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, tambahkan!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('auditgroupmember.store', encode($auditGroup->id)) }}',
                            data: fd,
                            dataType:'JSON',
                            type:'POST',
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true,
                                    timer: 5000
                                }).then(function () {
                                    window.location.href ="{{ route('auditgroup.edit', encode ($auditGroup->id)) }}"
                                });
                            },error: function(jqXhr, json, errorThrown){
                                var errors = jqXhr.responseJSON;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: errors.errors,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        });
                    }else{
                        Swal.fire({
                            icon: 'info',
                            title: 'Dibatalkan',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
        });

        $('#groupMember').DataTable({

            processing: true,
            serverSide: true,
            pageLength: 5,

            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],

            ajax: {
                url: "{{ route('auditgroupmember.list', encode($auditGroup->id)) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            },

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', width: '2%'},
                {
                    data: 'sort',
                    name: 'sort',
                    width: '2%',
                    className: 'text-center'
                },
                {
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    data: 'jabatan',
                    name: 'jabatan',
                    width: '4%'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'created_by',
                    name: 'created_by'
                },
                {
                    data: 'tindakan',
                    name: 'tindakan',
                    orderable: false,
                    searchable: false
                }
            ],

            });
    });
</script>
@endpush
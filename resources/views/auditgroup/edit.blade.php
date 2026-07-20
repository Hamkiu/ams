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
                    Edit Audit Group
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
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#store_audit_group_form').validate({
            rules: {
                name: { required: true },
            }
        });
    });
</script>
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
    });
</script>
@endpush
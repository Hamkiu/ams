@extends('layouts.master')
@section('title', 'Tambah Audit Group')
@section('content')
@include('include.error')
<form action="{{ route('auditgroup.store') }}" method="POST" id="store_audit_group_form" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i data-feather="users"></i>
                    Tambah Audit Group
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
                                <option value="{{ $auditTemplate->id }}">{{ $auditTemplate->name }}</option>
                            @empty
                            @endforelse
                        </select>                    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label for="name">Nama / Nombor Group</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="name">Jabatan / Unit</label>
                        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label for="name">Cadangan Tarikh</label>
                        <input type="date" name="tarikh" class="form-control" value="{{ old('tarikh', now()->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                Simpan
            </button>
            <a href="{{ route('auditgroup') }}" class="btn btn-secondary ms-2">
                Kembali
            </a>
        </div>
    </div>
</form>
@endsection
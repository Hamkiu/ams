<form action="{{ route('audittemplate.store') }}" method="POST" id="create_audit_template_form"
    enctype="multipart/form-data">
    @csrf
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i data-feather="check-square"></i>
                    Cipta Audit Template
                </h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group mb-3">
                        <label for="name">Nama Template</label>
                        <input type="text" name="name" class="form-control text-uppercase"
                            value="{{ old('name') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label for="no_rujukan">No Rujukan</label>
                        <input type="text" name="no_rujukan" class="form-control text-uppercase"
                            value="{{ old('no_rujukan') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label for="no_pindaan">No Pindaan</label>
                        <input type="number" name="no_pindaan" class="form-control" min="0" step="1"
                            value="{{ old('no_pindaan', 0) }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label for="version">Version</label>
                        <input type="text" name="version" class="form-control" id="version"
                            value="{{ old('version', '1.0') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label for="no_rujukan">Klausa</label>
                        <input type="text" name="klausa" class="form-control text-uppercase"
                            value="{{ old('klausa') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label for="tarikh_berkuatkuasa">Tarikh Berkuatkuasa</label>
                        <input type="date" name="tarikh_berkuatkuasa" class="form-control"
                            value="{{ old('tarikh_berkuatkuasa', now()->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success float-end">Simpan</button>
        </div>
    </div>
</form>

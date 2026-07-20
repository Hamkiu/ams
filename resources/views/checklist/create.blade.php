<form action="{{ route('audittemplate.checklist.store', encode($auditTemplateItems->id)) }}" method="POST" id="create_audit_template_form" enctype="multipart/form-data">
    @csrf
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i data-feather="check-square"></i>
                    Cipta Penemuan Audit
                </h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="name">Penemuan Audit</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success float-end">Simpan</button>
            <a href="{{ route('audittemplate.items', encode($auditTemplate->id)) }}" class="btn btn-secondary float-end me-2">Kembali</a>
        </div>
    </div>
</form>
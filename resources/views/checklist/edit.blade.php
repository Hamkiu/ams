<form action="{{ route('audittemplate.checklist.update', encode($auditItemChecklist->id)) }}" method="POST">
    @csrf
    <div class="modal-header border-bottom-0 py-2">
        <h5 class="modal-title">
            Edit Senarai Semak
        </h5>
    
        <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
            <i class="material-icons-outlined">close</i>
        </a>
    </div>

    <div class="modal-body">

        <div class="row">

            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label>Nama</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $auditItemChecklist->name) }}">
                </div>
            </div>

        </div>
    </div>

    <div class="modal-footer border-top-0">
        <button type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal">
            Tutup
        </button>
    
        <button type="submit" class="btn btn-primary">
            Simpan
        </button>
    </div>

</form>
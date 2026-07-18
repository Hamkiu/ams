<form action="{{ route('audittemplate.items.update', encode($auditTemplateItems->id)) }}" method="POST">
    @csrf
    <div class="modal-header border-bottom-0 py-2">
        <h5 class="modal-title">
            Edit Item : No Klausa ({{ $auditTemplateItems->no_klausa }})
        </h5>
    
        <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
            <i class="material-icons-outlined">close</i>
        </a>
    </div>

    <div class="modal-body">

        <div class="row">

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label>Sort</label>
                    <input type="number"
                           min="0"
                           step="1"
                           name="sort"
                           class="form-control"
                           value="{{ old('sort', $auditTemplateItems->sort) }}">
                </div>
            </div>

            <div class="col-md-10">
                <div class="form-group mb-3">
                    <label>Perkara</label>
                    <input type="text"
                           name="perkara"
                           class="form-control"
                           value="{{ old('perkara', $auditTemplateItems->perkara) }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label>No Klausa</label>
                    <input type="text"
                           name="no_klausa"
                           class="form-control"
                           value="{{ old('no_klausa', $auditTemplateItems->no_klausa) }}">
                </div>
            </div>

            <div class="col-md-10">
                <div class="form-group mb-3">
                    <label>Klausa</label>
                    <input type="text"
                           name="klausa"
                           class="form-control"
                           value="{{ old('klausa', $auditTemplateItems->klausa) }}">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12">
                <div class="form-group mb-3">
                        <label>Keterangan</label>
                    <textarea name="keterangan"
                              class="form-control"
                              rows="3">{{ old('keterangan', $auditTemplateItems->keterangan) }}</textarea>
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
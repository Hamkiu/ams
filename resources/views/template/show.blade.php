<form action="{{ route('audittemplate.update', encode($auditTemplate->id)) }}" method="POST">
    @csrf
    <div class="modal-header border-bottom-0 py-2">
        <h5 class="modal-title">
            Edit Template : {{ $auditTemplate->id }}
        </h5>
    
        <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
            <i class="material-icons-outlined">close</i>
        </a>
    </div>

    <div class="modal-body">

        <div class="row">

            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label>Nama Template</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $auditTemplate->name) }}">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label>No Rujukan</label>
                    <input type="text"
                           name="no_rujukan"
                           class="form-control"
                           value="{{ old('no_rujukan', $auditTemplate->no_rujukan) }}">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label>No Pindaan</label>
                    <input type="number"
                           name="no_pindaan"
                           class="form-control"
                           min="0"
                           step="1"
                           value="{{ old('no_pindaan', $auditTemplate->no_pindaan) }}">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label>Version</label>
                    <input type="text"
                           name="version"
                           class="form-control"
                           value="{{ old('version', $auditTemplate->version) }}">
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-10">
                <div class="form-group mb-3">
                    <label>Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="3">{{ old('description', $auditTemplate->description) }}</textarea>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label>Tarikh Berkuatkuasa</label>
                    <input type="date"
                           name="tarikh_berkuatkuasa"
                           class="form-control"
                           value="{{ old('tarikh_berkuatkuasa', optional($auditTemplate->tarikh_berkuatkuasa)->format('Y-m-d')) }}">
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
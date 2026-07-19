<div class="card mt-2">
    <div class="card-header" id="head2">
        <section class="mb-0 mt-0">
            <div role="menu" class="collapsed d-flex justify-content-center align-items-center" data-bs-toggle="collapse" data-bs-target="#defaultAccordionTwo" aria-expanded="false" aria-controls="defaultAccordionTwo">
                <i data-feather="plus-square"></i>&nbsp;<b>Tambah Item</b><span class="badge bg-danger ms-2 blink-badge">Klik Di Sini</span>
            </div>
        </section>
    </div>
    <div id="defaultAccordionTwo" class="collapse" aria-labelledby="head2" data-bs-parent="#toggleAccordion">
        <form action="{{ route('audittemplate.items.store', encode($auditTemplate->id)) }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="name">Sort</label>
                            <input type="number" name="sort" class="form-control" value="{{ old('sort') }}" min="0" step="1" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="perkara">Perkara</label>
                            <input type="text" name="perkara" class="form-control" value="{{ old('perkara') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="no_klausa">No Klausa</label>
                            <small class="text-muted">(Sila ikut format)</small>
                            <input type="text" name="no_klausa" class="form-control" value="{{ old('no_klausa') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="klausa">Klausa</label>
                            <input type="text" name="klausa" class="form-control" id="klausa" value="{{ old('klausa') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan</label>
                            <small class="text-muted">(Sekiranya perlu)</small>
                            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success float-end">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
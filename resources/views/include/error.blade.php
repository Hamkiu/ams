@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger border-0 bg-grd-danger alert-dismissible fade show mb-3">
            <div class="d-flex align-items-center">

                <div class="font-35 text-white">
                    <span class="material-icons-outlined fs-2">
                        report_gmailerrorred
                    </span>
                </div>

                <div class="ms-3">
                    <h6 class="mb-0 text-white">
                        Ralat
                    </h6>

                    <div class="text-white">
                        {{ $error }}
                    </div>
                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endforeach
@endif
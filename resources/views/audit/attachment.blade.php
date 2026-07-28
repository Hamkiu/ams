<div class="card-body">
    <div class="row mb-4">

        {{-- TABLE --}}
        <div class="col-lg-9">

            <table class="table table-bordered dtattcment" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>File Name</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

            </table>

        </div>

        {{-- UPLOAD PANEL --}}
        <div class="col-lg-3">

            <div class="card border">

                <div class="card-header bg-light">
                    <strong>Attachment(s)</strong>
                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <input type="file" class="form-control" name="tfiles[]" multiple>

                        <small class="text-muted">
                            doc, docx, pdf, txt, jpeg, png,
                            jpg, gif, svg
                            <br>
                            <strong>Maximum 10 MB</strong>
                        </small>

                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary">
                            <i data-feather="upload" class="me-1"></i>
                            Add
                        </button>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

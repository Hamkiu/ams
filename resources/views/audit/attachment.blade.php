<div class="card-body">
    <div class="row mb-4">
        @if ($answer)
            {{-- Papar upload attachment --}}
            {{-- TABLE --}}
            <div class="col-lg-9">

                <table class="table attachment-table" id="dtattachment_{{ $answer->id }}"
                    data-answer="{{ encode($answer->id) }}" style="width: 100%;">
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

                <div class="card border shadow-sm">

                    <div class="card-header bg-light">
                        <strong>Attachment(s)</strong>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <input type="file" class="form-control attachment-file"
                                id="attachment_{{ $answer->id }}" data-answer="{{ encode($answer->id) }}" multiple
                                @disabled($readonly)>

                            <small class="text-muted">
                                doc, docx, pdf, txt, jpeg, png, jpg, gif, svg
                                <br>
                                <strong>Maximum 10 MB</strong>
                            </small>

                        </div>

                        <div class="d-grid">
                            @if (!$readonly)
                                <button type="button" class="btn btn-primary btn-upload"
                                    data-answer="{{ encode($answer->id) }}" data-id="{{ $answer->id }}">

                                    <i data-feather="upload" class="me-1"></i>

                                    Add

                                </button>
                            @endif
                        </div>

                    </div>

                </div>

            </div>
        @else
            <div class="alert alert-warning mb-0">
                Sila simpan item audit terlebih dahulu sebelum memuat naik lampiran.
            </div>
        @endif



    </div>
</div>

{{-- RUMUSAN / KESIMPULAN --}}
<div class="card">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            {{-- KIRI --}}
            <div>
                <h5 class="mb-0">
                    Rumusan / Kesimpulan Ketua Audit
                </h5>

                <small class="text-muted">
                    Sila isi ulasan terlebih dahulu sebelum memuat naik lampiran.
                </small>
            </div>


            {{-- KANAN --}}
            @if ($auditGroup->review?->submitted_at)
                <div class="text-end">

                    <span class="badge bg-success mb-1">
                        SELESAI
                    </span>

                    <div class="small text-muted">
                        Dihantar:
                        {{ $auditGroup->review->submitted_at->format('d/m/Y H:i:s') }}
                    </div>

                </div>
            @endif

        </div>

    </div>

    <form action="{{ route('auditadminreview.store') }}" method="POST">
        @csrf

        <input type="hidden" name="audit_group_id" value="{{ encode($auditGroup->id) }}">

        <div class="card-body">

            <div class="mb-4">

                <label for="review" class="form-label">
                    Rumusan / Kesimpulan
                </label>

                <textarea name="review" id="review" class="form-control" rows="8">{{ old('review', $auditGroup->review?->review) }}</textarea>

                @error('review')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>
        @if (!$readonly)
            <div class="card-footer clearfix">

                <button type="submit" class="btn btn-warning float-end">

                    @if ($auditGroup->review)
                        <i data-feather="edit" class="me-1"></i>
                        Kemaskini
                    @else
                        <i data-feather="save" class="me-1"></i>
                        Simpan
                    @endif

                </button>

            </div>
        @endif
    </form>

</div>


{{-- ATTACHMENT RUMUSAN --}}
@if ($auditGroup->review)
    <div class="card mt-4">

        <div class="card-header">
            <h5 class="mb-0">Lampiran Ulasan</h5>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- TABLE ATTACHMENT --}}
                <div class="col-lg-9 mb-3 mb-lg-0">

                    <table class="table attachment-table" id="dtattachment_review_{{ $auditGroup->review->id }}"
                        data-ref="{{ encode($auditGroup->review->id) }}" data-type="review" style="width: 100%;">

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
                    @if (!$readonly)
                        <div class="card border shadow-sm">

                            <div class="card-header bg-light">
                                <strong>Attachment(s)</strong>
                            </div>

                            <div class="card-body">

                                <div class="mb-3">

                                    <input type="file" class="form-control attachment-file"
                                        id="attachment_review_{{ $auditGroup->review->id }}"
                                        data-ref="{{ encode($auditGroup->review->id) }}" data-type="review" multiple>

                                    <small class="text-muted">
                                        doc, docx, pdf, txt, jpeg, png, jpg, gif, svg
                                        <br>
                                        <strong>Maximum 10 MB</strong>
                                    </small>

                                </div>

                                <div class="d-grid">

                                    <button type="button" class="btn btn-primary btn-upload"
                                        data-ref="{{ encode($auditGroup->review->id) }}" data-type="review"
                                        data-id="{{ $auditGroup->review->id }}">

                                        <i data-feather="upload" class="me-1"></i>
                                        Add

                                    </button>

                                </div>

                            </div>

                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
    {{-- FINAL SUBMIT --}}
    @if (is_null($auditGroup->review->submitted_at))
        <div class="mt-4">

            <button type="button" class="btn btn-success w-100 py-2" id="btnSubmitReview"
                data-id="{{ encode($auditGroup->review->id) }}">

                <i data-feather="send" class="me-1"></i>
                Hantar Ulasan

            </button>

        </div>
        <br>
    @endif
@else
    <div class="alert alert-warning mt-3">
        Sila simpan ulasan terlebih dahulu sebelum memuat naik lampiran.
    </div>
@endif

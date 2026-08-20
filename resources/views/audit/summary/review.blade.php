@extends('layouts.master')

@section('title', 'Pinda Jawapan Audit')

@section('content')

    @include('include.error')

    @push('styles')
        <style>
            /*
                    |--------------------------------------------------------------------------
                    | CKEDITOR
                    |--------------------------------------------------------------------------
                    */

            .ck-editor {
                width: 100%;
                max-width: 100%;
            }

            .ck-editor__editable {
                min-height: 250px;
            }

            .ck-content img,
            .ck-content figure,
            .ck-content table {
                max-width: 100%;
            }

            /*
                    |--------------------------------------------------------------------------
                    | CHECKLIST
                    |--------------------------------------------------------------------------
                    */

            .checklist-item {
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 12px;
                background: #fff;
            }

            .checklist-name {
                font-weight: 600;
                margin-bottom: 10px;
            }

            /*
                    |--------------------------------------------------------------------------
                    | ORIGINAL ANSWER INFO
                    |--------------------------------------------------------------------------
                    */

            .original-info {
                background: #f8f9fa;
                border-left: 4px solid #6c757d;
                padding: 12px 15px;
                border-radius: 4px;
            }

            /*
                    |--------------------------------------------------------------------------
                    | RESPONSIVE
                    |--------------------------------------------------------------------------
                    */

            @media (max-width: 767.98px) {

                .checklist-options {
                    flex-direction: column;
                    gap: 8px !important;
                }

                .action-buttons {
                    flex-direction: column;
                }

                .action-buttons .btn {
                    width: 100%;
                }
            }
        </style>
    @endpush


    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="card mb-4">

        <div class="card-header">

            <div
                class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center gap-2">

                <div>

                    <h5 class="mb-1">

                        <i class="material-icons-outlined align-middle me-1">
                            edit_note
                        </i>

                        Pinda Jawapan Audit

                    </h5>

                    <small class="text-muted">
                        Ketua Kumpulan Audit boleh membuat pindaan sebelum
                        rumusan dihantar kepada Admin.
                    </small>

                </div>


                <div>

                    @if ($answer->review)
                        <span class="badge bg-warning text-dark">
                            TELAH DIPINDA
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            JAWAPAN ASAL
                        </span>
                    @endif

                </div>

            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- KUMPULAN --}}
                <div class="col-12 col-md-3">

                    <small class="text-muted d-block">
                        Kumpulan Audit
                    </small>

                    <div class="fw-bold text-break">
                        {{ $answer->auditGroup->name ?? '-' }}
                    </div>

                </div>


                {{-- AUDITOR --}}
                <div class="col-12 col-md-3">

                    <small class="text-muted d-block">
                        Juruaudit
                    </small>

                    <div class="fw-bold text-break">
                        {{ $answer->auditor->name ?? '-' }}
                    </div>

                </div>


                {{-- ITEM --}}
                <div class="col-12 col-md-4">

                    <small class="text-muted d-block">
                        Item Audit
                    </small>

                    <div class="fw-bold text-break">
                        {{ $answer->auditItem->sort ?? '' }}.
                        {{ $answer->auditItem->perkara ?? '-' }}
                    </div>

                </div>


                {{-- NO KLAUSA --}}
                <div class="col-12 col-md-2">

                    <small class="text-muted d-block">
                        No. Klausa
                    </small>

                    <div class="fw-bold">
                        {{ $answer->auditItem->no_klausa ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        NOTIS
    ========================================================== --}}
    <div class="alert alert-warning">

        <div class="d-flex align-items-start">

            <i class="material-icons-outlined me-2 flex-shrink-0">
                info
            </i>

            <div>

                <strong>Pindaan Ketua Kumpulan Audit</strong>

                <div class="mt-1">
                    Pindaan ini tidak akan mengubah jawapan asal juruaudit.
                    Jawapan asal akan kekal disimpan sebagai rekod audit.
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FORM PINDAAN
    ========================================================== --}}
    <form action="{{ route('audit.answer-review.store') }}" method="POST" id="reviewForm">

        @csrf

        <input type="hidden" name="audit_answer_id" value="{{ encode($answer->id) }}">


        {{-- =====================================================
            CHECKLIST
        ====================================================== --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-1">
                    Checklist
                </h5>

                <small class="text-muted">
                    Sila semak dan pilih keputusan bagi setiap checklist.
                </small>

            </div>


            <div class="card-body">

                @forelse ($answer->auditItem->checklists as $checklist)
                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | JAWAPAN ASAL AUDITOR
                        |--------------------------------------------------------------------------
                        */

                        $originalChecklist = $answer->checklists->firstWhere('audit_checklist_id', $checklist->id);

                        /*
                        |--------------------------------------------------------------------------
                        | PINDAAN KETUA
                        |--------------------------------------------------------------------------
                        */

                        $reviewChecklist = $answer->review?->checklists?->firstWhere(
                            'audit_checklist_id',
                            $checklist->id,
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | STATUS YANG DIPAPARKAN
                        |--------------------------------------------------------------------------
                        |
                        | Priority:
                        |
                        | old()
                        |      ↓
                        | review Ketua
                        |      ↓
                        | jawapan asal auditor
                        |
                        */

                        $status = old(
                            'checklist_status.' . $checklist->id,
                            $reviewChecklist?->status ?? $originalChecklist?->status,
                        );

                    @endphp


                    <div class="checklist-item">

                        {{-- NAMA CHECKLIST --}}
                        <div class="checklist-name text-break">

                            {{ $checklist->name }}

                        </div>


                        {{-- JAWAPAN ASAL --}}
                        <div class="mb-3">

                            <small class="text-muted">
                                Jawapan asal Juruaudit:
                            </small>

                            @if ($originalChecklist?->status === 'AKUR')
                                <span class="badge bg-success ms-1">
                                    AKUR
                                </span>
                            @elseif ($originalChecklist?->status === 'TIDAK AKUR')
                                <span class="badge bg-danger ms-1">
                                    TIDAK AKUR
                                </span>
                            @elseif ($originalChecklist?->status === 'TIDAK BERKAITAN')
                                <span class="badge bg-secondary ms-1">
                                    TIDAK BERKAITAN
                                </span>
                            @else
                                <span class="badge bg-light text-dark ms-1">
                                    TIADA JAWAPAN
                                </span>
                            @endif

                        </div>


                        {{-- RADIO BUTTON --}}
                        <div class="d-flex flex-wrap gap-4 checklist-options">

                            {{-- AKUR --}}
                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="checklist_status[{{ $checklist->id }}]"
                                    id="akur_{{ $checklist->id }}" value="AKUR" @checked($status === 'AKUR') required>

                                <label class="form-check-label" for="akur_{{ $checklist->id }}">

                                    Akur

                                </label>

                            </div>


                            {{-- TIDAK AKUR --}}
                            <div class="form-check">

                                <input class="form-check-input" type="radio"
                                    name="checklist_status[{{ $checklist->id }}]" id="tidak_akur_{{ $checklist->id }}"
                                    value="TIDAK AKUR" @checked($status === 'TIDAK AKUR') required>

                                <label class="form-check-label" for="tidak_akur_{{ $checklist->id }}">

                                    Tidak Akur

                                </label>

                            </div>


                            {{-- TIDAK BERKAITAN --}}
                            <div class="form-check">

                                <input class="form-check-input" type="radio"
                                    name="checklist_status[{{ $checklist->id }}]"
                                    id="tidak_berkaitan_{{ $checklist->id }}" value="TIDAK BERKAITAN"
                                    @checked($status === 'TIDAK BERKAITAN') required>

                                <label class="form-check-label" for="tidak_berkaitan_{{ $checklist->id }}">

                                    Tidak Berkaitan

                                </label>

                            </div>

                        </div>


                        @error('checklist_status.' . $checklist->id)
                            <div class="text-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                @empty

                    <div class="alert alert-info mb-0">
                        Tiada checklist untuk item audit ini.
                    </div>
                @endforelse


                @error('checklist_status')
                    <div class="text-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>


        {{-- =====================================================
            PENEMUAN LAIN
        ====================================================== --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-1">
                    Penemuan Lain
                </h5>

                <small class="text-muted">
                    Semak dan pinda penemuan juruaudit sekiranya perlu.
                </small>

            </div>


            <div class="card-body">

                {{-- JAWAPAN ASAL --}}
                <div class="original-info mb-4">

                    <small class="text-muted d-block mb-1">
                        Jawapan asal Juruaudit
                    </small>

                    <div class="text-break">
                        {{ $answer->penemuan_lain ?? '-' }}
                    </div>

                </div>


                {{-- PINDAAN --}}
                <div>

                    <label for="penemuan_lain" class="form-label fw-bold">

                        Penemuan Lain
                    </label>


                    <textarea name="penemuan_lain" id="penemuan_lain" class="form-control @error('penemuan_lain') is-invalid @enderror"
                        rows="5" required>{{ old('penemuan_lain', $answer->review?->penemuan_lain ?? $answer->penemuan_lain) }}</textarea>


                    @error('penemuan_lain')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- =====================================================
            BUKTI AUDIT
        ====================================================== --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-1">
                    Bukti Audit
                </h5>

                <small class="text-muted">
                    Semak dan pinda bukti audit sekiranya perlu.
                </small>

            </div>


            <div class="card-body">

                {{-- JAWAPAN ASAL --}}
                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Bukti Audit Asal Juruaudit
                    </label>

                    <div class="border rounded bg-light p-3 ck-content">

                        {!! $answer->bukti_audit ?: '<span class="text-muted">Tiada bukti audit.</span>' !!}

                    </div>

                </div>


                {{-- PINDAAN KETUA --}}
                <div>

                    <label for="bukti_audit" class="form-label fw-bold">

                        Bukti Audit Selepas Pindaan

                    </label>


                    <textarea name="bukti_audit" id="bukti_audit" class="form-control @error('bukti_audit') is-invalid @enderror"
                        rows="8">{{ old('bukti_audit', $answer->review?->bukti_audit ?? $answer->bukti_audit) }}</textarea>


                    @error('bukti_audit')
                        <div class="text-danger mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- =====================================================
            LAMPIRAN AUDITOR
        ====================================================== --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-1">
                    Lampiran Juruaudit
                </h5>

                <small class="text-muted">
                    Lampiran asal juruaudit dipaparkan sebagai rujukan.
                </small>

            </div>


            <div class="card-body">

                @forelse ($answer->files as $file)
                    <div class="border rounded p-3 mb-2">

                        <div
                            class="d-flex flex-column flex-md-row
                                    justify-content-between
                                    align-items-md-center gap-3">

                            {{-- FILE --}}
                            <div class="d-flex align-items-start flex-grow-1" style="min-width: 0;">

                                <i class="material-icons-outlined me-2 flex-shrink-0" style="font-size: 20px;">

                                    attach_file

                                </i>

                                <div class="text-break">

                                    {{ $file->file_name_ori }}

                                </div>

                            </div>


                            {{-- DOWNLOAD --}}
                            <div class="flex-shrink-0">

                                <a href="{{ route('auditfiles.download', encode($file->id)) }}"
                                    class="btn btn-primary btn-sm">

                                    <i class="material-icons-outlined align-middle" style="font-size: 17px;">

                                        download

                                    </i>

                                    Muat Turun

                                </a>

                            </div>

                        </div>

                    </div>

                @empty

                    <span class="text-muted">
                        Tiada lampiran.
                    </span>
                @endforelse

            </div>

        </div>


        {{-- =====================================================
            ACTION
        ====================================================== --}}
        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2 action-buttons">

                    {{-- KEMBALI --}}
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">

                        <i class="material-icons-outlined align-middle" style="font-size: 18px;">

                            arrow_back

                        </i>

                        Kembali

                    </a>


                    {{-- SIMPAN --}}
                    <button type="submit" class="btn btn-warning" id="btnSaveReview">

                        <i class="material-icons-outlined align-middle" style="font-size: 18px;">

                            save

                        </i>

                        @if ($answer->review)
                            Kemaskini Pindaan
                        @else
                            Simpan Pindaan
                        @endif

                    </button>

                </div>

            </div>

        </div>

    </form>

@endsection


@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        let reviewEditor = null;

        /*
        |--------------------------------------------------------------------------
        | CKEDITOR BUKTI AUDIT
        |--------------------------------------------------------------------------
        */

        const buktiAuditTextarea = document.querySelector('#bukti_audit');

        if (buktiAuditTextarea) {

            ClassicEditor
                .create(buktiAuditTextarea, {

                    ckfinder: {

                        uploadUrl: '{{ route('image.upload', ['_token' => csrf_token()]) }}'

                    }

                })
                .then(editor => {

                    reviewEditor = editor;

                    console.log('Review editor loaded');

                })
                .catch(error => {

                    console.error(error);

                });

        }


        /*
        |--------------------------------------------------------------------------
        | SUBMIT FORM
        |--------------------------------------------------------------------------
        */

        $('#reviewForm').on('submit', function(e) {

            e.preventDefault();

            /*
            |--------------------------------------------------------------------------
            | SYNC CKEDITOR
            |--------------------------------------------------------------------------
            */

            if (reviewEditor) {

                $('#bukti_audit').val(
                    reviewEditor.getData()
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CHECK SEMUA RADIO BUTTON
            |--------------------------------------------------------------------------
            */

            let valid = true;

            $('.checklist-item').each(function() {

                const checked = $(this)
                    .find('input[type="radio"]:checked')
                    .length;

                if (checked === 0) {

                    valid = false;

                }

            });


            if (!valid) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Checklist Belum Lengkap',

                    text: 'Sila pilih Akur, Tidak Akur atau Tidak Berkaitan bagi semua checklist.'

                });

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | CONFIRM
            |--------------------------------------------------------------------------
            */

            Swal.fire({

                title: 'Simpan Pindaan?',

                text: 'Pindaan ini akan digunakan sebagai jawapan semakan Ketua Kumpulan Audit.',

                icon: 'question',

                showCancelButton: true,

                confirmButtonText: 'Ya, Simpan',

                cancelButtonText: 'Batal'

            }).then((result) => {

                if (result.isConfirmed) {

                    /*
                    |--------------------------------------------------------------------------
                    | DISABLE BUTTON
                    |--------------------------------------------------------------------------
                    */

                    $('#btnSaveReview')
                        .prop('disabled', true)
                        .html(
                            '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | SUBMIT FORM
                    |--------------------------------------------------------------------------
                    */

                    this.submit();

                }

            });

        });


        /*
        |--------------------------------------------------------------------------
        | SUCCESS MESSAGE
        |--------------------------------------------------------------------------
        */

        @if (session('success'))

            Swal.fire({

                icon: 'success',

                title: 'Berjaya!',

                text: "{{ session('success') }}",

                timer: 3000,

                showConfirmButton: true

            });
        @endif
    </script>
@endpush

@extends('layouts.master')

@section('title', 'Jawapan Audit')

@section('content')
    @push('styles')
        <style>
            /*
        |--------------------------------------------------------------------------
        | CKEDITOR CONTENT
        |--------------------------------------------------------------------------
        */

            .audit-content {
                display: block;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;

                overflow: hidden !important;

                overflow-wrap: anywhere;
                word-break: break-word;

                box-sizing: border-box;
            }

            /*
        |--------------------------------------------------------------------------
        | CKEDITOR IMAGE WRAPPER
        |--------------------------------------------------------------------------
        */

            .audit-content figure,
            .audit-content figure.image,
            .audit-content .image,
            .audit-content .image_resized {
                display: block !important;

                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;

                margin: 0 !important;
                padding: 0 !important;

                float: none !important;

                box-sizing: border-box !important;
            }

            /*
        |--------------------------------------------------------------------------
        | CKEDITOR IMAGE
        |--------------------------------------------------------------------------
        */

            .audit-content img,
            .audit-content figure img,
            .audit-content figure.image img,
            .audit-content .image img,
            .audit-content .image_resized img {
                display: block !important;

                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;

                height: auto !important;

                margin: 0 auto !important;
                padding: 0 !important;

                object-fit: contain !important;

                box-sizing: border-box !important;
            }

            /*
        |--------------------------------------------------------------------------
        | CKEDITOR ALIGNMENT
        |--------------------------------------------------------------------------
        */

            .audit-content .image-style-side,
            .audit-content .image-style-align-left,
            .audit-content .image-style-align-right,
            .audit-content .image-style-align-center {
                float: none !important;

                width: 100% !important;
                max-width: 100% !important;

                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */

            .audit-content table {
                width: 100% !important;
                max-width: 100% !important;
            }

            /*
        |--------------------------------------------------------------------------
        | VIDEO / IFRAME
        |--------------------------------------------------------------------------
        */

            .audit-content iframe,
            .audit-content video {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
            }


            /*
                        |--------------------------------------------------------------------------
                        | LONG TEXT
                        |--------------------------------------------------------------------------
                        */

            #auditAnswers .text-break {
                overflow-wrap: anywhere;
                word-break: break-word;
            }


            /*
                        |--------------------------------------------------------------------------
                        | ACCORDION
                        |--------------------------------------------------------------------------
                        */

            #auditAnswers,
            #auditAnswers .accordion-item,
            #auditAnswers .accordion-collapse,
            #auditAnswers .accordion-body {
                max-width: 100%;
                min-width: 0;
            }


            /*
                        |--------------------------------------------------------------------------
                        | AUDITOR CARD
                        |--------------------------------------------------------------------------
                        */

            .auditor-answer-card {
                width: 100%;
                max-width: 100%;
                min-width: 0;
                overflow: hidden;
            }

            .auditor-answer-card .card-body {
                max-width: 100%;
                min-width: 0;
            }


            /*
                        |--------------------------------------------------------------------------
                        | TABLET / MOBILE
                        |--------------------------------------------------------------------------
                        */

            @media (max-width: 767.98px) {

                /*
                             * Download button penuh pada mobile/tablet kecil.
                             */
                .download-wrapper {
                    width: 100%;
                }

                .download-btn {
                    width: 100%;
                }

                /*
                             * Pastikan kandungan CKEditor tidak menyebabkan
                             * horizontal overflow.
                             */
                .audit-content {
                    max-width: 100%;
                    overflow-x: hidden;
                }

                .audit-content figure,
                .audit-content .image,
                .audit-content .image_resized {
                    max-width: 100% !important;
                }

                .audit-content img,
                .audit-content figure img,
                .audit-content .image img,
                .audit-content .image_resized img {
                    max-width: 100% !important;
                    height: auto !important;
                }
            }


            /*
                        |--------------------------------------------------------------------------
                        | MOBILE
                        |--------------------------------------------------------------------------
                        */

            @media (max-width: 575.98px) {

                /*
                             * Kurangkan padding card utama.
                             */
                .card-body {
                    padding-left: 0.75rem;
                    padding-right: 0.75rem;
                }

                /*
                             * Accordion.
                             */
                #auditAnswers .accordion-button {
                    padding: 0.85rem;
                }

                #auditAnswers .accordion-body {
                    padding: 0.75rem;
                }

                /*
                             * Card jawapan auditor.
                             */
                #auditAnswers .auditor-answer-card .card-header {
                    padding: 0.75rem;
                }

                #auditAnswers .auditor-answer-card .card-body {
                    padding: 0.75rem;
                }

                /*
                             * Elakkan horizontal scroll.
                             */
                #auditAnswers {
                    width: 100%;
                    max-width: 100%;
                    overflow-x: hidden;
                }

                /*
                             * Gambar CKEditor pada mobile.
                             */
                .audit-content figure,
                .audit-content .image,
                .audit-content .image_resized {
                    max-width: 100% !important;
                }

                .audit-content img,
                .audit-content figure img,
                .audit-content .image img,
                .audit-content .image_resized img {
                    display: block !important;
                    max-width: 100% !important;
                    width: auto !important;
                    height: auto !important;
                    object-fit: contain;
                }

                /*
                             * Footer button.
                             */
                .card-footer .btn {
                    width: 100%;
                }
            }
        </style>
    @endpush

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

                <div>
                    <h5 class="mb-1">
                        <i data-feather="clipboard"></i>
                        Keputusan Audit
                    </h5>

                    <small class="text-muted">
                        {{ $auditGroup->name }}
                    </small>
                </div>

                <div>
                    <span class="badge bg-success">
                        {{ $auditGroup->status }}
                    </span>
                </div>

            </div>

        </div>


        <div class="card-body">

            {{-- =========================================================
                MAKLUMAT GROUP
            ========================================================== --}}
            <div class="row g-3 mb-4">

                <div class="col-12 col-sm-6 col-lg-3">

                    <label class="text-muted d-block">
                        ID Kumpulan
                    </label>

                    <div class="fw-bold text-break">
                        {{ $auditGroup->name }}
                    </div>

                </div>


                <div class="col-12 col-sm-6 col-lg-3">

                    <label class="text-muted d-block">
                        Template
                    </label>

                    <div class="fw-bold text-break">
                        {{ $auditGroup->auditTemplate->name ?? '-' }}
                    </div>

                </div>


                <div class="col-12 col-sm-8 col-lg-4">

                    <label class="text-muted d-block">
                        Jabatan / Unit
                    </label>

                    <div class="fw-bold text-break">
                        {{ $auditGroup->jabatan ?? '-' }}
                    </div>

                </div>


                <div class="col-12 col-sm-4 col-lg-2">

                    <label class="text-muted d-block">
                        Bil. Juruaudit
                    </label>

                    <div>
                        <span class="badge bg-primary">
                            {{ $auditGroup->members->count() }} Juruaudit
                        </span>
                    </div>

                </div>

            </div>


            {{-- =========================================================
                SENARAI JURUAUDIT
            ========================================================== --}}
            <div class="card bg-light mb-4">

                <div class="card-body">

                    <h6 class="mb-3">
                        Juruaudit
                    </h6>

                    <div class="row g-2">

                        @foreach ($auditGroup->members as $member)
                            <div class="col-12 col-sm-6 col-lg-4">

                                <div class="border rounded bg-white p-3 h-100">

                                    <div class="d-flex align-items-start">

                                        <div class="me-2 flex-shrink-0">

                                            <i class="material-icons-outlined" style="font-size: 22px;">
                                                person
                                            </i>

                                        </div>

                                        <div class="flex-grow-1" style="min-width: 0;">

                                            <div class="fw-bold text-break">
                                                {{ $member->pengguna->name ?? '-' }}
                                            </div>

                                            <small class="text-muted d-block text-break">
                                                {{ $member->jabatan ?? '-' }}
                                            </small>

                                            <div class="mt-2">

                                                @if ($member->role == 'Leader')
                                                    <span class="badge bg-primary">
                                                        Ketua Juruaudit
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        Ahli Juruaudit
                                                    </span>
                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>


            {{-- =========================================================
                TAJUK ITEM
            ========================================================== --}}
            <div
                class="d-flex flex-column flex-sm-row
                        justify-content-between align-items-sm-center
                        gap-2 mb-3">

                <h5 class="mb-0">
                    Jawapan Mengikut Item Audit
                </h5>

                <div>
                    <span class="badge bg-secondary">
                        {{ $auditGroup->auditTemplate->items->count() }} Item
                    </span>
                </div>

            </div>


            {{-- =========================================================
                ACCORDION ITEM
            ========================================================== --}}
            <div class="accordion" id="auditAnswers">

                @forelse ($auditGroup->auditTemplate->items as $item)

                    <div class="accordion-item">

                        {{-- HEADER ITEM --}}
                        <h2 class="accordion-header" id="heading{{ $item->id }}">

                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#item{{ $item->id }}" aria-expanded="false"
                                aria-controls="item{{ $item->id }}">

                                <div class="d-flex align-items-start w-100">

                                    <span class="fw-bold me-2 flex-shrink-0">
                                        {{ $item->sort }}.
                                    </span>

                                    <span class="fw-bold text-break">
                                        {{ $item->perkara }}
                                    </span>

                                </div>

                            </button>

                        </h2>


                        {{-- CONTENT ITEM --}}
                        <div id="item{{ $item->id }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $item->id }}" data-bs-parent="#auditAnswers">

                            <div class="accordion-body">


                                {{-- =================================================
                                    MAKLUMAT ITEM
                                ================================================== --}}
                                <div class="row g-3 mb-4">

                                    <div class="col-12 col-md-3">

                                        <small class="text-muted d-block">
                                            No. Klausa
                                        </small>

                                        <div class="fw-bold text-break">
                                            {{ $item->no_klausa ?? '-' }}
                                        </div>

                                    </div>


                                    <div class="col-12 col-md-9">

                                        <small class="text-muted d-block">
                                            Klausa
                                        </small>

                                        <div class="fw-bold text-break">
                                            {{ $item->klausa ?? '-' }}
                                        </div>

                                    </div>

                                </div>


                                {{-- =================================================
                                    JAWAPAN SETIAP MEMBER
                                ================================================== --}}
                                @foreach ($auditGroup->members as $member)
                                    @php
                                        $answer = $auditGroup->answers
                                            ->where('audit_item_id', $item->id)
                                            ->where('user_id', $member->user_id)
                                            ->first();
                                    @endphp


                                    <div class="card auditor-answer-card mb-3">


                                        {{-- =========================================
                                            AUDITOR HEADER
                                        ========================================== --}}
                                        <div class="card-header">

                                            <div
                                                class="d-flex flex-column flex-sm-row
                                                        justify-content-between
                                                        align-items-sm-center gap-2">

                                                <div class="d-flex align-items-center" style="min-width: 0;">

                                                    <i class="material-icons-outlined
                                                              me-2 flex-shrink-0"
                                                        style="font-size: 20px;">
                                                        person
                                                    </i>

                                                    <strong class="text-break">
                                                        {{ $member->pengguna->name ?? '-' }}
                                                    </strong>

                                                </div>


                                                <div class="flex-shrink-0">

                                                    @if ($member->role == 'Leader')
                                                        <span class="badge bg-primary">
                                                            Ketua Juruaudit
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            Ahli Juruaudit
                                                        </span>
                                                    @endif

                                                </div>

                                            </div>

                                        </div>


                                        {{-- =========================================
                                            JAWAPAN AUDITOR
                                        ========================================== --}}
                                        <div class="card-body">

                                            @if ($answer)
                                                {{-- =================================
                                                    CHECKLIST
                                                ================================== --}}
                                                <div class="mb-4">

                                                    <h6 class="mb-3">
                                                        Checklist
                                                    </h6>


                                                    @forelse ($item->checklists as $checklist)
                                                        @php
                                                            $isChecked = $answer->checklists
                                                                ->where('audit_checklist_id', $checklist->id)
                                                                ->isNotEmpty();
                                                        @endphp


                                                        <div class="d-flex align-items-start mb-2">

                                                            <div class="me-2 flex-shrink-0">

                                                                @if ($isChecked)
                                                                    <span class="text-success fw-bold">
                                                                        ✓
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">
                                                                        -
                                                                    </span>
                                                                @endif

                                                            </div>


                                                            <div class="flex-grow-1 text-break" style="min-width: 0;">

                                                                {{ $checklist->name }}

                                                            </div>

                                                        </div>

                                                    @empty

                                                        <span class="text-muted">
                                                            Tiada checklist.
                                                        </span>
                                                    @endforelse

                                                </div>


                                                {{-- =================================
                                                    PENEMUAN LAIN
                                                ================================== --}}
                                                <div class="mb-4">

                                                    <h6>
                                                        Penemuan Lain
                                                    </h6>

                                                    <div
                                                        class="border rounded
                                                                p-2 p-md-3
                                                                bg-light
                                                                text-break">

                                                        {{ $answer->penemuan_lain ?? '-' }}

                                                    </div>

                                                </div>


                                                {{-- =================================
                                                    BUKTI AUDIT
                                                ================================== --}}
                                                <div class="mb-4">

                                                    <h6>
                                                        Bukti Audit
                                                    </h6>

                                                    <div
                                                        class="border rounded
                                                                p-2 p-md-3
                                                                bg-light
                                                                audit-content">

                                                        {!! $answer->bukti_audit ?? '-' !!}

                                                    </div>

                                                </div>


                                                {{-- =================================
                                                    LAMPIRAN
                                                ================================== --}}
                                                <div>

                                                    <h6 class="mb-3">
                                                        Lampiran
                                                    </h6>


                                                    @forelse ($answer->files as $file)
                                                        <div
                                                            class="border rounded
                                                                    p-2 p-md-3 mb-2">

                                                            <div
                                                                class="d-flex
                                                                        flex-column
                                                                        flex-md-row
                                                                        justify-content-between
                                                                        align-items-md-center
                                                                        gap-3">


                                                                {{-- NAMA FAIL --}}
                                                                <div class="d-flex
                                                                            align-items-start
                                                                            flex-grow-1"
                                                                    style="min-width: 0;">

                                                                    <i class="material-icons-outlined
                                                                              me-2
                                                                              flex-shrink-0"
                                                                        style="font-size: 20px;">
                                                                        attach_file
                                                                    </i>

                                                                    <span class="text-break">
                                                                        {{ $file->file_name_ori }}
                                                                    </span>

                                                                </div>


                                                                {{-- DOWNLOAD --}}
                                                                <div
                                                                    class="flex-shrink-0
                                                                            download-wrapper">

                                                                    <a href="{{ route('auditfiles.download', encode($file->id)) }}"
                                                                        class="btn btn-primary btn-sm
                                                                               download-btn"
                                                                        title="Muat Turun">

                                                                        <i class="material-icons-outlined
                                                                                  align-middle"
                                                                            style="font-size: 18px;">
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
                                            @else
                                                <div class="alert alert-warning mb-0">

                                                    Tiada jawapan direkodkan untuk
                                                    juruaudit ini.

                                                </div>
                                            @endif

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="alert alert-info mb-0">
                        Tiada item audit.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- =========================================================
            FOOTER
        ========================================================== --}}
        <div class="card-footer">

            <div class="d-flex justify-content-end">

                <a href="{{ route('auditgroup') }}" class="btn btn-secondary">

                    <i class="material-icons-outlined align-middle" style="font-size: 18px;">
                        arrow_back
                    </i>

                    Kembali

                </a>

            </div>

        </div>

    </div>

@endsection

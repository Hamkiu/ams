@extends('layouts.master')

@section('title', 'Jawapan Audit')

@section('content')
    @include('include.error')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('template/assets/css/maklumbalas-audit.css') }}">
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
                        Bil. Juruaudit Dalaman
                    </label>

                    <div>
                        <span class="badge bg-primary">
                            {{ $auditGroup->members->count() }} Juruaudit Dalaman
                        </span>
                    </div>

                </div>

            </div>


            {{-- =========================================================
                SENARAI JURUAUDIT DALAMAN
            ========================================================== --}}
            <div class="card bg-light mb-4">

                <div class="card-body">

                    <h6 class="mb-3">
                        Juruaudit Dalaman
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
                                                        Ketua Kumpulan Audit
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        Juruaudit Dalaman
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
                            data-item="{{ $item->id }}" aria-labelledby="heading{{ $item->id }}"
                            data-bs-parent="#auditAnswers">

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

                                        /*
                                |--------------------------------------------------------------------------
                                | JAWAPAN ASAL AUDITOR
                                |--------------------------------------------------------------------------
                                */
                                        $answer = $auditGroup->answers
                                            ->where('audit_item_id', $item->id)
                                            ->where('user_id', $member->user_id)
                                            ->first();

                                        /*
                                |--------------------------------------------------------------------------
                                | PINDAAN KETUA
                                |--------------------------------------------------------------------------
                                */
                                        $review = $answer?->review;

                                    @endphp


                                    <div class="card auditor-answer-card mb-3 bg-light shadow-sm">


                                        {{-- =========================================
                                            AUDITOR HEADER
                                        ========================================== --}}
                                        <div class="card-header bg-dark text-white">

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


                                                <div class="d-flex flex-wrap align-items-center gap-2">

                                                    {{-- ROLE --}}
                                                    @if ($member->role == 'Leader')
                                                        <span class="badge bg-primary">
                                                            Ketua Kumpulan Audit
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            Juruaudit Dalaman
                                                        </span>
                                                    @endif


                                                    {{-- ADA PINDAAN KETUA --}}
                                                    @if ($review)
                                                        <span class="badge bg-warning text-dark">

                                                            <i class="material-icons-outlined align-middle"
                                                                style="font-size: 14px;">
                                                                edit
                                                            </i>

                                                            TELAH DIPINDA

                                                        </span>


                                                        <button type="button" class="btn btn-info btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#comparisonModal{{ $answer->id }}">

                                                            <i class="material-icons-outlined align-middle"
                                                                style="font-size: 16px;">
                                                                compare
                                                            </i>

                                                            Lihat Perbandingan

                                                        </button>
                                                    @endif

                                                </div>

                                            </div>

                                        </div>


                                        {{-- =========================================
                                            JAWAPAN AUDITOR
                                        ========================================== --}}
                                        <div class="card-body bg-light">

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

                                                            /*
                                                            |--------------------------------------------------------------------------
                                                            | STATUS ASAL AUDITOR
                                                            |--------------------------------------------------------------------------
                                                            */
                                                            $originalChecklist = $answer->checklists->firstWhere(
                                                                'audit_checklist_id',
                                                                $checklist->id,
                                                            );

                                                            /*
                                                            |--------------------------------------------------------------------------
                                                            | STATUS PINDAAN KETUA
                                                            |--------------------------------------------------------------------------
                                                            */
                                                            $reviewChecklist = $review?->checklists?->firstWhere(
                                                                'audit_checklist_id',
                                                                $checklist->id,
                                                            );

                                                            /*
                                                            |--------------------------------------------------------------------------
                                                            | STATUS FINAL UNTUK ADMIN
                                                            |--------------------------------------------------------------------------
                                                            |
                                                            | Jika Ketua pinda → guna pindaan
                                                            | Jika tidak       → guna asal
                                                            |
                                                            */
                                                            $status =
                                                                $reviewChecklist?->status ??
                                                                $originalChecklist?->status;

                                                        @endphp


                                                        <div class="border rounded bg-white p-3 mb-2">

                                                            <div
                                                                class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">

                                                                <div class="flex-grow-1 text-break" style="min-width: 0;">

                                                                    {{ $checklist->name }}

                                                                </div>


                                                                <div class="flex-shrink-0">

                                                                    @if ($status === 'AKUR')
                                                                        <span class="badge bg-success">
                                                                            AKUR
                                                                        </span>
                                                                    @elseif ($status === 'TIDAK AKUR')
                                                                        <span class="badge bg-danger">
                                                                            TIDAK AKUR
                                                                        </span>
                                                                    @elseif ($status === 'TIDAK BERKAITAN')
                                                                        <span class="badge bg-secondary">
                                                                            TIDAK BERKAITAN
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-light text-dark">
                                                                            BELUM DIJAWAB
                                                                        </span>
                                                                    @endif

                                                                </div>

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

                                                    <div class="d-flex align-items-center gap-2 mb-2">

                                                        <h6 class="mb-0">
                                                            Penemuan Lain
                                                        </h6>

                                                        @if ($review)
                                                            <span class="badge bg-warning text-dark">
                                                                Versi Ketua
                                                            </span>
                                                        @endif

                                                    </div>


                                                    <div class="border rounded p-2 p-md-3 bg-white text-break">

                                                        {{ $review?->penemuan_lain ?? ($answer->penemuan_lain ?? '-') }}

                                                    </div>

                                                </div>


                                                {{-- =================================
                                                    BUKTI AUDIT
                                                ================================== --}}
                                                <div class="mb-4">

                                                    <div class="d-flex align-items-center gap-2 mb-2">

                                                        <h6 class="mb-0">
                                                            Bukti Audit
                                                        </h6>

                                                        @if ($review)
                                                            <span class="badge bg-warning text-dark">
                                                                Versi Ketua
                                                            </span>
                                                        @endif

                                                    </div>


                                                    <textarea class="form-control bukti-audit-admin" id="bukti_audit_{{ $item->id }}_{{ $member->user_id }}"
                                                        rows="6">{{ $review?->bukti_audit ?? ($answer->bukti_audit ?? '') }}</textarea>

                                                </div>


                                                {{-- =================================
                                                    LAMPIRAN
                                                ================================== --}}
                                                <div>

                                                    <div class="d-flex align-items-center gap-2 mb-3">

                                                        <h6 class="mb-0">
                                                            Lampiran
                                                        </h6>

                                                        <span class="badge bg-light text-dark">
                                                            Lampiran Asal Juruaudit
                                                        </span>

                                                    </div>


                                                    @forelse ($answer->files as $file)
                                                        <div
                                                            class="border rounded
                                                                    p-2 p-md-3 mb-2 attachment-item">

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
                                                    juruaudit dalaman ini.

                                                </div>
                                            @endif

                                        </div>


                                    </div>
                                    @include('auditadminreview.partials.comparison')
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

    @include('auditadminreview.partials.conclusion')

@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        const adminEditors = {};

        document.querySelectorAll('#auditAnswers .accordion-collapse')
            .forEach(function(collapse) {

                collapse.addEventListener('shown.bs.collapse', function() {

                    const itemId = this.dataset.item;

                    /*
                     * Dalam satu item mungkin ada 2-3 auditor.
                     * Jadi cari SEMUA textarea CKEditor dalam accordion ini.
                     */
                    this.querySelectorAll('.bukti-audit-admin')
                        .forEach(function(textarea) {

                            const editorId = textarea.id;

                            /*
                             * Kalau editor sudah pernah initialize,
                             * jangan initialize semula.
                             */
                            if (adminEditors[editorId]) {
                                return;
                            }


                            ClassicEditor
                                .create(textarea)
                                .then(editor => {

                                    adminEditors[editorId] = editor;

                                    /*
                                     * Admin hanya boleh melihat.
                                     */
                                    editor.enableReadOnlyMode('admin');


                                    /*
                                     * Sembunyikan toolbar sebab Admin
                                     * tidak perlu edit.
                                     */
                                    const toolbar =
                                        editor.ui.view.toolbar.element;

                                    if (toolbar) {
                                        toolbar.style.display = 'none';
                                    }


                                    console.log(
                                        'Admin readonly editor ' +
                                        editorId +
                                        ' loaded'
                                    );

                                })
                                .catch(error => {

                                    console.error(error);

                                });

                        });

                });

            });

        const conclusionTextarea = document.querySelector('#conclusion');

        if (conclusionTextarea) {

            ClassicEditor
                .create(conclusionTextarea)
                .then(editor => {

                    editor.enableReadOnlyMode('admin');

                    const toolbar = editor.ui.view.toolbar.element;

                    if (toolbar) {
                        toolbar.style.display = 'none';
                    }

                })
                .catch(error => {
                    console.error(error);
                });

        }

        const reviewTextarea = document.querySelector('#review');

        if (reviewTextarea) {

            ClassicEditor
                .create(reviewTextarea, {
                    ckfinder: {
                        uploadUrl: '{{ route('image.upload', ['_token' => csrf_token()]) }}'
                    }
                })
                .then(editor => {

                    @if ($readonly)

                        editor.enableReadOnlyMode('admin-review');

                        const toolbar = editor.ui.view.toolbar.element;

                        if (toolbar) {
                            toolbar.style.display = 'none';
                        }
                    @endif

                })
                .catch(error => {
                    console.error(error);
                });

        }

        $(document).on('click', '.btn-upload', function() {

            let encodedRef = $(this).data('ref');
            let refType = $(this).data('type');
            let refId = $(this).data('id');

            let input = $('#attachment_' + refType + '_' + refId)[0];

            if (!input || input.files.length === 0) {

                Swal.fire({
                    icon: 'warning',
                    text: 'Sila pilih fail terlebih dahulu.'
                });

                return;
            }

            let formData = new FormData();

            formData.append('ref_id', encodedRef);
            formData.append('ref_type', refType);

            $.each(input.files, function(i, file) {
                formData.append('tfiles[]', file);
            });

            $.ajax({

                url: "{{ route('auditadminreview.attachment') }}",

                type: "POST",

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: formData,

                processData: false,
                contentType: false,

                success: function(res) {

                    Swal.fire({
                        icon: 'success',
                        text: res.message
                    }).then(() => {
                        window.location.reload();
                    });

                },

                error: function(xhr) {

                    Swal.fire({
                        icon: 'error',
                        text: xhr.responseJSON?.message ??
                            'Ralat semasa memuat naik lampiran.'
                    });

                }

            });

        });

        $('.attachment-table').each(function() {

            let table = $(this);

            // Generic reference
            let refId = table.data('ref');
            let refType = table.data('type');

            table.DataTable({

                processing: true,

                serverSide: true,

                pageLength: 10,

                ajax: {

                    url: "{{ route('auditadminreview.listattachment') }}",

                    type: "POST",

                    data: function(d) {

                        d._token = "{{ csrf_token() }}";

                        d.ref_id = refId;
                        d.ref_type = refType;

                    }

                },

                columns: [

                    {
                        data: 'DT_RowIndex',
                        className: 'text-center',
                        width: '2%'
                    },

                    {
                        data: 'file_name'
                    },

                    {
                        data: 'created_at'
                    },

                    {
                        data: 'tindakan',
                        orderable: false,
                        searchable: false
                    }

                ]

            });

        });

        $(document).on('click', '#btnSubmitReview', function() {

            let reviewId = $(this).data('id');

            Swal.fire({
                title: 'Selesaikan Audit?',
                text: 'Ulasan yang telah dihantar tidak boleh dikemaskini lagi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hantar',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({

                    url: "{{ route('auditadminreview.submit') }}",

                    type: "POST",

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    data: {
                        review_id: reviewId
                    },

                    success: function(res) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berjaya!',
                            text: res.message
                        }).then(() => {

                            window.location.href =
                                "{{ route('auditadminreview') }}";

                        });

                    },

                    error: function(xhr) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Tidak Berjaya',
                            text: xhr.responseJSON?.message ??
                                'Ralat semasa menghantar ulasan.'
                        });

                    }

                });

            });

        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                timer: 3000,
                showConfirmButton: true
            });
        @endif
    </script>
@endpush

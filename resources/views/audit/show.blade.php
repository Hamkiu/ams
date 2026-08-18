@extends('layouts.master')
@section('title', 'Audit')
@section('content')
    @include('include.error')
    @php
        $readonly = $member->isCompleted();
    @endphp
    @if ($readonly)
        <div class="alert alert-success">

            <i data-feather="check-circle"></i>

            Audit ini telah dihantar dan tidak lagi boleh diubah.

        </div>
    @endif
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            @php
                $member = $group->members->firstWhere('user_id', auth()->id());

                $badge = match ($member?->status) {
                    'BELUM BERMULA' => 'secondary',
                    'DALAM PROSES' => 'warning',
                    'SELESAI' => 'success',
                    default => 'secondary',
                };
            @endphp

            <div class="row align-items-start">

                {{-- Maklumat Audit --}}
                <div class="col-12 col-lg-9">

                    <h4 class="fw-bold mb-3">
                        {{ $group->name }}
                    </h4>

                    <div class="table-responsive">

                        <table class="table table-borderless table-sm mb-0">

                            <tbody>

                                <tr>
                                    <td style="width:220px;" class="fw-semibold">
                                        Template
                                    </td>
                                    <td>
                                        : {{ $group->auditTemplate->name }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold">
                                        Jabatan
                                    </td>
                                    <td>
                                        : {{ $group->jabatan }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="fw-semibold">
                                        Tarikh Cadangan Audit
                                    </td>
                                    <td>
                                        : {{ $group->tarikh->format('d-m-Y') }}
                                    </td>
                                </tr>

                                @if ($member)
                                    <tr>
                                        <td class="fw-semibold">
                                            Tarikh Mulakan Audit
                                        </td>
                                        <td>
                                            : {{ $member->started_at?->format('d-m-Y H:i') ?? '-' }}
                                        </td>
                                    </tr>
                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- Status --}}
                <div class="col-12 col-lg-3 mt-3 mt-lg-0">

                    @if ($member)
                        <div class="text-lg-end">

                            <span class="badge bg-{{ $badge }} px-4 py-2 fs-6">
                                {{ $member->status }}
                            </span>

                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="accordion mt-4" id="auditAccordion">

        @foreach ($group->auditTemplate->items as $item)
            @php
                $answer = $answers[$item->id] ?? null;
            @endphp

            <form action="{{ route('audit.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="audit_group_id" value="{{ $group->id }}">
                <input type="hidden" name="audit_item_id" value="{{ $item->id }}">

                <div class="accordion-item shadow-sm mb-3">

                    <h2 class="accordion-header" id="heading{{ $item->id }}">

                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $item->id }}">

                            <div
                                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center w-100">

                                <strong class="text-wrap pe-lg-3">
                                    {{ $item->sort }}. {{ $item->perkara }}
                                </strong>

                                <div class="mt-2 mt-lg-0">

                                    @if ($answer?->isCompleted())
                                        <span class="badge bg-success">
                                            Selesai
                                        </span>
                                    @elseif($answer)
                                        <span class="badge bg-warning text-dark">
                                            Dalam Proses
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Belum Dijawab
                                        </span>
                                    @endif

                                </div>

                            </div>

                        </button>

                    </h2>

                    <div id="collapse{{ $item->id }}" class="accordion-collapse collapse"
                        data-bs-parent="#auditAccordion" data-item="{{ $item->id }}">

                        <div class="accordion-body">

                            <div class="card border-0 bg-light">

                                <div class="card-body">

                                    <div class="row mb-3">

                                        <label class="col-lg-3 col-md-4 fw-bold">
                                            Bil
                                        </label>

                                        <div class="col-lg-9 col-md-8">
                                            {{ $item->sort }}
                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <label class="col-lg-3 col-md-4 fw-bold">
                                            Perkara
                                        </label>

                                        <div class="col-lg-9 col-md-8">
                                            {{ $item->perkara }}
                                        </div>

                                    </div>

                                    <div class="row mb-4">

                                        <label class="col-lg-3 col-md-4 fw-bold">
                                            Klausa
                                        </label>

                                        <div class="col-lg-9 col-md-8">

                                            <strong>{{ $item->no_klausa }}</strong>

                                            <br>

                                            {{ $item->klausa }}

                                        </div>

                                    </div>

                                    <hr>

                                    <div class="row mb-4">

                                        <label class="col-lg-3 col-md-4 fw-bold">
                                            Senarai Semak
                                        </label>

                                        <div class="col-lg-9 col-md-8">

                                            @foreach ($item->checklists as $checklist)
                                                @php
                                                    $checklistAnswer = $answer?->checklists->firstWhere(
                                                        'audit_checklist_id',
                                                        $checklist->id,
                                                    );

                                                    $status = $checklistAnswer?->status;
                                                @endphp

                                                <div class="mb-3">

                                                    {{-- NAMA CHECKLIST --}}
                                                    <div class="fw-semibold mb-2">
                                                        {{ $checklist->name }}
                                                    </div>

                                                    {{-- PILIHAN --}}
                                                    <div class="d-flex flex-wrap gap-3">

                                                        {{-- AKUR --}}
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="checklist_status[{{ $checklist->id }}]"
                                                                id="akur_{{ $item->id }}_{{ $checklist->id }}"
                                                                value="AKUR" @checked($status === 'AKUR')
                                                                @disabled($readonly)>

                                                            <label class="form-check-label"
                                                                for="akur_{{ $item->id }}_{{ $checklist->id }}">
                                                                Akur
                                                            </label>
                                                        </div>


                                                        {{-- TIDAK AKUR --}}
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="checklist_status[{{ $checklist->id }}]"
                                                                id="tidak_akur_{{ $item->id }}_{{ $checklist->id }}"
                                                                value="TIDAK AKUR" @checked($status === 'TIDAK AKUR')
                                                                @disabled($readonly)>

                                                            <label class="form-check-label"
                                                                for="tidak_akur_{{ $item->id }}_{{ $checklist->id }}">
                                                                Tidak Akur
                                                            </label>
                                                        </div>


                                                        {{-- TIDAK BERKAITAN --}}
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="checklist_status[{{ $checklist->id }}]"
                                                                id="tidak_berkaitan_{{ $item->id }}_{{ $checklist->id }}"
                                                                value="TIDAK BERKAITAN" @checked($status === 'TIDAK BERKAITAN')
                                                                @disabled($readonly)>

                                                            <label class="form-check-label"
                                                                for="tidak_berkaitan_{{ $item->id }}_{{ $checklist->id }}">
                                                                Tidak Berkaitan
                                                            </label>
                                                        </div>

                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>

                                    </div>

                                    <div class="row mb-4">

                                        <label class="col-lg-3 col-md-4 fw-bold">
                                            Lain-lain Penemuan
                                        </label>

                                        <div class="col-lg-9 col-md-8">

                                            <textarea class="form-control" rows="4" style="resize:vertical" name="penemuan_lain"
                                                id="penemuan_lain_{{ $item->id }}" @readonly($readonly)>{{ $answer->penemuan_lain ?? '' }}</textarea>

                                        </div>

                                    </div>

                                    <div class="row mb-4">

                                        <label class="col-lg-3 col-md-4 fw-bold">
                                            Bukti Audit
                                        </label>

                                        <div class="col-lg-9 col-md-8">

                                            <textarea class="form-control bukti_audit" rows="6" style="resize:vertical" name="bukti_audit"
                                                id="bukti_audit_{{ $item->id }}">{{ $answer->bukti_audit ?? '' }}</textarea>

                                        </div>

                                    </div>

                                    @include('audit.attachment')

                                </div>

                            </div>
                            @if (!$readonly)
                                <div class="d-grid d-md-flex justify-content-md-end mt-4">

                                    <button type="submit" class="btn btn-primary">

                                        <i class="bx bx-save me-1"></i>

                                        Simpan Item

                                    </button>

                                </div>
                            @endif
                        </div>

                    </div>

                </div>

            </form>
        @endforeach

    </div>
    @if (!$readonly)
        <div class="card-footer mt-4 text-center">

            <button type="button" class="btn btn-success px-5 btn-submit-audit" data-group="{{ encode($group->id) }}">

                <i data-feather="send" class="me-1"></i>

                <b>Hantar Audit</b>

            </button>

        </div>
        <br>
    @endif

@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        const editors = {};

        document.querySelectorAll('.accordion-collapse').forEach(function(collapse) {

            collapse.addEventListener('shown.bs.collapse', function() {

                let itemId = this.dataset.item;

                if (editors[itemId]) {
                    return;
                }

                let textarea = document.querySelector('#bukti_audit_' + itemId);

                if (!textarea) {
                    return;
                }

                ClassicEditor
                    .create(textarea, {
                        ckfinder: {
                            uploadUrl: '{{ route('image.upload', ['_token' => csrf_token()]) }}'
                        }
                    })
                    .then(editor => {

                        editors[itemId] = editor;

                        console.log('Editor ' + itemId + ' loaded');
                        if (@json($readonly)) {

                            editor.enableReadOnlyMode('audit');

                        }

                    })
                    .catch(error => {

                        console.error(error);

                    });

            });

        });

        $(document).ready(function() {

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

                        url: "{{ route('audit.listattachment') }}",

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


            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: true
                });
            @endif

        });

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

                url: "{{ route('audit.attachment') }}",

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

        $(document).on('click', '.btn-submit-audit', function() {

            let groupId = $(this).data('group');

            Swal.fire({

                title: 'Hantar Audit?',
                text: 'Selepas dihantar, audit akan ditandakan sebagai selesai.',
                icon: 'question',

                showCancelButton: true,

                confirmButtonText: 'Ya, Hantar',

                cancelButtonText: 'Batal'

            }).then((result) => {

                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({

                    url: "{{ route('audit.submit') }}",

                    type: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        audit_group_id: groupId

                    },

                    success: function(res) {

                        Swal.fire({

                            icon: 'success',

                            text: res.message

                        }).then(() => {

                            window.location.href = "{{ route('audit') }}";

                        });

                    },

                    error: function(xhr) {

                        Swal.fire({

                            icon: 'error',

                            text: xhr.responseJSON.message

                        });

                    }

                });

            });

        });
    </script>
@endpush

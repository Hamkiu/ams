@extends('layouts.master')

@section('title', 'Audit')
@section('content')
    @include('include.error')
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

                $completed = $answer && !empty($answer->bukti_audit) && $answer->checklists->count() > 0;
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

                                    @if ($completed)
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
                                                <div class="form-check mb-2">

                                                    <input class="form-check-input" type="checkbox" name="checklist_id[]"
                                                        value="{{ $checklist->id }}" @checked($answer && $answer->checklists->contains('audit_checklist_id', $checklist->id))>

                                                    <label class="form-check-label">

                                                        {{ $checklist->name }}

                                                    </label>

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
                                                id="penemuan_lain_{{ $item->id }}">{{ $answer->penemuan_lain ?? '' }}</textarea>

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

                            <div class="d-grid d-md-flex justify-content-md-end mt-4">

                                <button type="submit" class="btn btn-primary">

                                    <i class="bx bx-save me-1"></i>

                                    Simpan Item

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>
        @endforeach

    </div>

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

                    })
                    .catch(error => {

                        console.error(error);

                    });

            });

        });

        $(document).ready(function() {
            $('.attachment-table').each(function() {

                let table = $(this);

                let answer = table.data('answer');

                table.DataTable({

                    processing: true,

                    serverSide: true,

                    pageLength: 10,

                    ajax: {

                        url: "{{ route('audit.listattachment') }}",

                        type: "POST",

                        data: function(d) {

                            d._token = "{{ csrf_token() }}";

                            d.answer = answer;

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

            let encodedAnswer = $(this).data('answer');
            let answerId = $(this).data('id');

            let input = $('#attachment_' + answerId)[0];

            if (input.files.length === 0) {

                Swal.fire({
                    icon: 'warning',
                    text: 'Sila pilih fail terlebih dahulu.'
                });

                return;
            }

            let formData = new FormData();

            formData.append('audit_answer_id', encodedAnswer);

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

                    input.value = "";

                    $('#attachmentTable_' + answerId)
                        .DataTable()
                        .ajax.reload();

                },

                error: function(xhr) {

                    Swal.fire({
                        icon: 'error',
                        text: xhr.responseJSON.message
                    });

                }

            });

        });
    </script>
@endpush

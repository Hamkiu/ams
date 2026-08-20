{{-- =========================================================
    RUMUSAN / KESIMPULAN KETUA KUMPULAN AUDIT
========================================================= --}}
<div class="card mt-4">

    {{-- HEADER / COLLAPSE BUTTON --}}
    <div class="card-header p-0">

        <button class="btn w-100 text-start p-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseConclusion" aria-expanded="true" aria-controls="collapseConclusion">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0">
                        <i class="material-icons-outlined align-middle me-1" style="font-size: 20px;">
                            summarize
                        </i>

                        Rumusan / Kesimpulan Ketua Kumpulan
                    </h5>
                </div>

                <div class="d-flex align-items-center gap-2">

                    <span class="badge bg-success">
                        Telah Dihantar
                    </span>

                    <i class="material-icons-outlined">
                        expand_more
                    </i>

                </div>

            </div>

        </button>

    </div>


    {{-- COLLAPSE CONTENT --}}
    <div id="collapseConclusion" class="collapse">

        <div class="card-body">

            @if ($auditGroup->conclusion)

                {{-- RUMUSAN --}}
                <div class="mb-3">

                    <label class="form-label">
                        Rumusan / Kesimpulan
                    </label>

                    <textarea id="conclusion" class="form-control" rows="8">{{ $auditGroup->conclusion->conclusion }}</textarea>

                </div>


                {{-- TARIKH HANTAR --}}
                @if ($auditGroup->conclusion->submitted_at)
                    <small class="text-muted">

                        Dihantar pada:
                        {{ $auditGroup->conclusion->submitted_at->format('d/m/Y H:i:s') }}

                    </small>
                @endif


                <hr>


                {{-- =====================================================
                    LAMPIRAN
                ====================================================== --}}
                <div>

                    <h6 class="mb-3">
                        Lampiran
                    </h6>

                    @forelse ($auditGroup->conclusion->files as $file)
                        <div class="border rounded p-2 p-md-3 mb-2 attachment-item">

                            <div
                                class="d-flex
                                flex-column
                                flex-md-row
                                justify-content-between
                                align-items-md-center
                                gap-3">

                                {{-- NAMA FAIL --}}
                                <div class="d-flex align-items-start flex-grow-1" style="min-width: 0;">

                                    <i class="material-icons-outlined me-2 flex-shrink-0" style="font-size: 20px;">
                                        attach_file
                                    </i>

                                    <span class="text-break">
                                        {{ $file->file_name_ori }}
                                    </span>

                                </div>


                                {{-- DOWNLOAD --}}
                                <div class="flex-shrink-0 download-wrapper">

                                    <a href="{{ route('auditfiles.download', encode($file->id)) }}"
                                        class="btn btn-primary btn-sm download-btn" title="Muat Turun">

                                        <i class="material-icons-outlined align-middle" style="font-size: 18px;">
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
                    Tiada rumusan / kesimpulan direkodkan.
                </div>

            @endif

        </div>

    </div>

</div>

@include('auditadminreview.partials.review')

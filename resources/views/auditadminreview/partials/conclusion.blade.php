{{-- =========================================================
    RUMUSAN / KESIMPULAN KETUA KUMPULAN AUDIT
========================================================= --}}

<div class="card mt-4 shadow-sm">

    {{-- =====================================================
        HEADER / COLLAPSE BUTTON
    ====================================================== --}}
    <div class="card-header p-0 border-bottom" style="background-color: #e2e6ea;">

        <button class="btn w-100 text-start p-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseConclusion" aria-expanded="false" aria-controls="collapseConclusion"
            style="background-color: transparent; border: 0;">

            <div
                class="d-flex
                        flex-column
                        flex-sm-row
                        justify-content-between
                        align-items-sm-center
                        gap-2">

                {{-- KIRI --}}
                <div class="d-flex align-items-center">

                    <i class="material-icons-outlined me-2" style="font-size: 21px;">
                        summarize
                    </i>

                    <div>

                        <h5 class="mb-0">
                            Rumusan / Kesimpulan Ketua Kumpulan
                        </h5>

                    </div>

                </div>


                {{-- KANAN --}}
                <div class="d-flex align-items-center gap-2">

                    @if ($auditGroup->conclusion?->submitted_at)
                        <span class="badge bg-success">
                            Telah Dihantar
                        </span>
                    @else
                        <span class="badge bg-warning text-dark">
                            Belum Dihantar
                        </span>
                    @endif


                    <i class="material-icons-outlined">
                        expand_more
                    </i>

                </div>

            </div>

        </button>

    </div>


    {{-- =====================================================
        COLLAPSE CONTENT
    ====================================================== --}}
    <div id="collapseConclusion" class="collapse">

        <div class="card-body bg-white">

            @if ($auditGroup->conclusion)

                {{-- =================================================
                    RUMUSAN
                ================================================== --}}
                <div class="mb-3">

                    <label class="form-label fw-bold">

                        <i class="material-icons-outlined
                                  align-middle me-1"
                            style="font-size: 18px;">
                            description
                        </i>

                        Rumusan / Kesimpulan

                    </label>


                    <textarea id="conclusion" class="form-control" rows="8">{{ $auditGroup->conclusion->conclusion }}</textarea>

                </div>


                {{-- =================================================
                    TARIKH HANTAR
                ================================================== --}}
                @if ($auditGroup->conclusion->submitted_at)
                    <div class="d-flex align-items-center mb-3">

                        <i class="material-icons-outlined
                                  text-muted me-1"
                            style="font-size: 17px;">
                            schedule
                        </i>

                        <small class="text-muted">

                            Dihantar pada:

                            <strong>
                                {{ $auditGroup->conclusion->submitted_at->format('d/m/Y H:i:s') }}
                            </strong>

                        </small>

                    </div>
                @endif


                <hr>


                {{-- =================================================
                    LAMPIRAN
                ================================================== --}}
                <div>

                    <h6 class="mb-3 fw-bold">

                        <i class="material-icons-outlined
                                  align-middle me-1"
                            style="font-size: 18px;">
                            attach_file
                        </i>

                        Lampiran

                    </h6>


                    @forelse ($auditGroup->conclusion->files as $file)
                        <div
                            class="border rounded
                                    bg-light
                                    p-2 p-md-3
                                    mb-2
                                    attachment-item">

                            <div
                                class="d-flex
                                        flex-column
                                        flex-md-row
                                        justify-content-between
                                        align-items-md-center
                                        gap-3">


                                {{-- =================================
                                    NAMA FAIL
                                ================================== --}}
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


                                {{-- =================================
                                    DOWNLOAD
                                ================================== --}}
                                <div class="flex-shrink-0
                                            download-wrapper">

                                    <a href="{{ route('auditfiles.download', encode($file->id)) }}"
                                        class="btn btn-primary
                                               btn-sm
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

                        <div
                            class="border rounded
                                    bg-light
                                    p-3
                                    text-muted">

                            <i class="material-icons-outlined
                                      align-middle me-1"
                                style="font-size: 18px;">

                                attachment

                            </i>

                            Tiada lampiran.

                        </div>
                    @endforelse

                </div>
            @else
                {{-- =================================================
                    TIADA RUMUSAN
                ================================================== --}}
                <div class="alert alert-warning mb-0">

                    <div class="d-flex align-items-center">

                        <i class="material-icons-outlined me-2">
                            warning
                        </i>

                        <div>
                            Tiada rumusan / kesimpulan direkodkan.
                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =========================================================
    ULASAN ADMIN
========================================================= --}}
@include('auditadminreview.partials.review')

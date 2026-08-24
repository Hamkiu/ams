@if ($answer && $review)

    <div class="modal fade" id="comparisonModal{{ $answer->id }}" tabindex="-1"
        aria-labelledby="comparisonModalLabel{{ $answer->id }}" aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content">

                {{-- HEADER --}}
                <div class="modal-header">

                    <div>

                        <h5 class="modal-title" id="comparisonModalLabel{{ $answer->id }}">

                            Perbandingan Jawapan Audit

                        </h5>

                        <small class="text-muted">

                            {{ $member->pengguna->name ?? '-' }}

                            &mdash;

                            {{ $item->sort }}.
                            {{ $item->perkara }}

                        </small>

                    </div>


                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>


                {{-- BODY --}}
                <div class="modal-body">

                    <div class="alert alert-info">

                        <i class="material-icons-outlined
                                  align-middle me-1">
                            compare
                        </i>

                        Perbandingan antara
                        <strong>jawapan asal Juruaudit</strong>
                        dan
                        <strong>pindaan Ketua Kumpulan Audit</strong>.

                    </div>


                    {{-- =========================================
                        CHECKLIST
                    ========================================== --}}
                    <div class="mb-4">

                        <h6 class="fw-bold mb-3">
                            Checklist
                        </h6>


                        <div class="table-responsive">

                            <table class="table table-bordered align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            Checklist
                                        </th>

                                        <th class="text-center">
                                            Jawapan Asal
                                        </th>

                                        <th class="text-center">
                                            Pindaan Ketua
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse ($item->checklists as $checklist)
                                        @php

                                            $originalChecklistModal = $answer->checklists->firstWhere(
                                                'audit_checklist_id',
                                                $checklist->id,
                                            );

                                            $reviewChecklistModal = $review->checklists->firstWhere(
                                                'audit_checklist_id',
                                                $checklist->id,
                                            );

                                            $originalStatus = $originalChecklistModal?->status;

                                            $reviewStatus = $reviewChecklistModal?->status;

                                            $isChanged = $originalStatus !== $reviewStatus;

                                        @endphp


                                        <tr>

                                            <td>

                                                {{ $checklist->name }}

                                                @if ($isChanged)
                                                    <span
                                                        class="badge
                                                                 bg-warning
                                                                 text-dark
                                                                 ms-1">

                                                        Berubah

                                                    </span>
                                                @endif

                                            </td>


                                            {{-- ORIGINAL --}}
                                            <td class="text-center">

                                                @if ($originalStatus === 'AKUR')
                                                    <span class="badge bg-success">
                                                        AKUR
                                                    </span>
                                                @elseif ($originalStatus === 'TIDAK AKUR')
                                                    <span class="badge bg-danger">
                                                        TIDAK AKUR
                                                    </span>
                                                @elseif ($originalStatus === 'TIDAK BERKAITAN')
                                                    <span class="badge bg-secondary">
                                                        TIDAK BERKAITAN
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-dark">
                                                        -
                                                    </span>
                                                @endif

                                            </td>


                                            {{-- REVIEW --}}
                                            <td class="text-center">

                                                @if ($reviewStatus === 'AKUR')
                                                    <span class="badge bg-success">
                                                        AKUR
                                                    </span>
                                                @elseif ($reviewStatus === 'TIDAK AKUR')
                                                    <span class="badge bg-danger">
                                                        TIDAK AKUR
                                                    </span>
                                                @elseif ($reviewStatus === 'TIDAK BERKAITAN')
                                                    <span class="badge bg-secondary">
                                                        TIDAK BERKAITAN
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-dark">
                                                        -
                                                    </span>
                                                @endif

                                            </td>

                                        </tr>


                                    @empty

                                        <tr>

                                            <td colspan="3" class="text-center text-muted">

                                                Tiada checklist.

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- =========================================
                        PENEMUAN LAIN
                    ========================================== --}}
                    <div class="mb-4">

                        <h6 class="fw-bold mb-3">
                            Penemuan Lain
                        </h6>


                        <div class="row g-3">

                            {{-- ASAL --}}
                            <div class="col-12 col-lg-6">

                                <div class="card h-100 border-secondary">

                                    <div class="card-header bg-light">

                                        <strong>
                                            Jawapan Asal Juruaudit
                                        </strong>

                                    </div>

                                    <div class="card-body text-break">

                                        {{ $answer->penemuan_lain ?? '-' }}

                                    </div>

                                </div>

                            </div>


                            {{-- PINDAAN --}}
                            <div class="col-12 col-lg-6">

                                <div class="card h-100 border-warning">

                                    <div
                                        class="card-header
                                                bg-warning
                                                bg-opacity-25">

                                        <strong>
                                            Pindaan Ketua Kumpulan Audit
                                        </strong>

                                    </div>

                                    <div class="card-body text-break">

                                        {{ $review->penemuan_lain ?? '-' }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =========================================
                        BUKTI AUDIT
                    ========================================== --}}
                    <div>

                        <h6 class="fw-bold mb-3">
                            Bukti Audit
                        </h6>


                        <div class="row g-3">

                            {{-- ASAL --}}
                            <div class="col-12 col-lg-6">

                                <div class="card h-100 border-secondary">

                                    <div class="card-header bg-light">

                                        <strong>
                                            Jawapan Asal Juruaudit
                                        </strong>

                                    </div>

                                    <div class="card-body">

                                        <div class="ck-content text-break">

                                            {!! $answer->bukti_audit ?: '<span class="text-muted">Tiada bukti audit.</span>' !!}

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- PINDAAN --}}
                            <div class="col-12 col-lg-6">

                                <div class="card h-100 border-warning">

                                    <div
                                        class="card-header
                                                bg-warning
                                                bg-opacity-25">

                                        <strong>
                                            Pindaan Ketua Kumpulan Audit
                                        </strong>

                                    </div>

                                    <div class="card-body">

                                        <div class="ck-content text-break">

                                            {!! $review->bukti_audit ?: '<span class="text-muted">Tiada bukti audit.</span>' !!}

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer">

                    <div class="me-auto">

                        @if ($review->updated_at)
                            <small class="text-muted">

                                Pindaan terakhir:

                                <strong>
                                    {{ $review->updated_at->format('d/m/Y h:i A') }}
                                </strong>

                            </small>
                        @endif

                    </div>


                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>

@endif

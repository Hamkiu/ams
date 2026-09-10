@extends('layouts.master')

@section('title', 'Preview Audit Template')

@section('content')

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">

                <div>
                    <h5 class="mb-0">
                        <i data-feather="file-text"></i>
                        {{ $auditTemplate->name }}
                    </h5>
                </div>

                <div>
                    <span class="badge bg-info">
                        Paparan Sahaja
                    </span>
                </div>

            </div>
        </div>


        <div class="card-body">

            {{-- MAKLUMAT TEMPLATE --}}
            <div class="row g-3">

                <div class="col-12 col-sm-6 col-lg-4">
                    <label class="text-muted d-block">
                        Nama Template
                    </label>

                    <div class="fw-bold text-break">
                        {{ $auditTemplate->name }}
                    </div>
                </div>


                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="text-muted d-block">
                        No. Rujukan
                    </label>

                    <div class="fw-bold text-break">
                        {{ $auditTemplate->no_rujukan ?? '-' }}
                    </div>
                </div>


                <div class="col-6 col-sm-4 col-lg-2">
                    <label class="text-muted d-block">
                        No. Pindaan
                    </label>

                    <div class="fw-bold">
                        {{ $auditTemplate->no_pindaan ?? '-' }}
                    </div>
                </div>


                <div class="col-6 col-sm-4 col-lg-2">
                    <label class="text-muted d-block">
                        Versi
                    </label>

                    <div class="fw-bold">
                        {{ $auditTemplate->version ?? '-' }}
                    </div>
                </div>


                <div class="col-12 col-sm-4 col-lg-2">
                    <label class="text-muted d-block">
                        Status
                    </label>

                    <div>
                        <span class="badge bg-success">
                            {{ $auditTemplate->status }}
                        </span>
                    </div>
                </div>

            </div>


            <div class="row g-3 mt-2">

                <div class="col-12 col-md-4">
                    <label class="text-muted d-block">
                        Tarikh Berkuatkuasa
                    </label>

                    <div class="fw-bold">
                        {{ optional($auditTemplate->tarikh_berkuatkuasa)->format('d-m-Y') ?? '-' }}
                    </div>
                </div>


                <div class="col-12 col-md-8">
                    <label class="text-muted d-block">
                        Keterangan
                    </label>

                    <div class="fw-bold text-break">
                        {{ $auditTemplate->description ?? '-' }}
                    </div>
                </div>

            </div>


            <hr class="my-4">


            {{-- ITEM TEMPLATE --}}
            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="mb-0">
                    Item Template Audit
                </h5>

                <span class="badge bg-secondary">
                    {{ $auditTemplate->items->count() }} Item
                </span>

            </div>


            {{-- ACCORDION --}}
            <div class="accordion" id="templateItems">

                @forelse ($auditTemplate->items as $item)

                    <div class="accordion-item">

                        <h2 class="accordion-header" id="heading{{ $item->id }}">

                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#item{{ $item->id }}" aria-expanded="false"
                                aria-controls="item{{ $item->id }}">

                                <div class="d-flex align-items-start w-100">

                                    <span class="me-2 fw-bold">
                                        {{ $item->sort }}.
                                    </span>

                                    <span class="fw-bold text-break">
                                        {{ $item->perkara }}
                                    </span>

                                </div>

                            </button>

                        </h2>


                        <div id="item{{ $item->id }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $item->id }}" data-bs-parent="#templateItems">

                            <div class="accordion-body">

                                {{-- DESKTOP / TABLET --}}
                                <div class="d-none d-md-block">

                                    <div class="table-responsive">

                                        <table class="table table-bordered mb-0">

                                            <tbody>

                                                <tr>
                                                    <th style="width: 20%;">
                                                        Perkara
                                                    </th>

                                                    <td class="text-break">
                                                        {{ $item->perkara }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>
                                                        No. Klausa
                                                    </th>

                                                    <td class="text-break">
                                                        {{ $item->no_klausa ?? '-' }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>
                                                        Klausa
                                                    </th>

                                                    <td class="text-break">
                                                        {{ $item->klausa ?? '-' }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>
                                                        Keterangan
                                                    </th>

                                                    <td class="text-break">
                                                        {{ $item->keterangan ?? '-' }}
                                                    </td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>


                                {{-- MOBILE --}}
                                <div class="d-block d-md-none">

                                    <div class="mb-3">

                                        <small class="text-muted d-block">
                                            Perkara
                                        </small>

                                        <div class="fw-bold text-break">
                                            {{ $item->perkara }}
                                        </div>

                                    </div>


                                    <div class="mb-3">

                                        <small class="text-muted d-block">
                                            No. Klausa
                                        </small>

                                        <div class="fw-bold text-break">
                                            {{ $item->no_klausa ?? '-' }}
                                        </div>

                                    </div>


                                    <div class="mb-3">

                                        <small class="text-muted d-block">
                                            Klausa
                                        </small>

                                        <div class="text-break">
                                            {{ $item->klausa ?? '-' }}
                                        </div>

                                    </div>


                                    <div>

                                        <small class="text-muted d-block">
                                            Keterangan
                                        </small>

                                        <div class="text-break">
                                            {{ $item->keterangan ?? '-' }}
                                        </div>

                                    </div>

                                </div>


                                {{-- SENARAI SEMAK --}}
                                <div class="mt-4">

                                    <h6 class="mb-3">
                                        Senarai Semak Audit
                                    </h6>

                                    @forelse ($item->checklists as $index => $checklist)
                                        <div class="d-flex align-items-start mb-2">

                                            <div class="flex-shrink-0 me-2">
                                                <span class="badge bg-light text-dark">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>

                                            <div class="flex-grow-1 text-break">
                                                {{ $checklist->name }}
                                            </div>

                                        </div>

                                    @empty

                                        <div class="alert alert-light border mb-0">
                                            <span class="text-muted">
                                                Tiada senarai semak untuk item ini.
                                            </span>
                                        </div>
                                    @endforelse

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="alert alert-info mb-0">
                        Tiada item untuk template ini.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="card-footer">

            <div class="d-flex justify-content-end">

                <a href="{{ route('audittemplate') }}" class="btn btn-secondary">

                    <i class="material-icons-outlined align-middle" style="font-size: 18px;">
                        arrow_back
                    </i>

                    Kembali

                </a>

            </div>

        </div>

    </div>

@endsection

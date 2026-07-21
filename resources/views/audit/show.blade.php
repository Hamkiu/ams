@extends('layouts.master')

@section('title', 'Audit')
@section('content')
    @include('include.error')
    <div class="card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <h4 class="mb-1">
                        {{ $group->name }}
                    </h4>

                    <table class="table table-borderless table-sm mb-0">
                        <tbody>

                            <tr>
                                <td width="170"><strong>Template</strong></td>
                                <td>: {{ $group->auditTemplate->name }}</td>
                            </tr>

                            <tr>
                                <td><strong>Jabatan</strong></td>
                                <td>: {{ $group->jabatan }}</td>
                            </tr>

                            <tr>
                                <td><strong>Tarikh Cadangan Audit</strong></td>
                                <td>: {{ $group->tarikh->format('d-m-Y') }}</td>
                            </tr>

                        </tbody>
                    </table>

                </div>

                <div>

                    <span class="badge bg-warning fs-6">

                        {{ $group->status }}

                    </span>

                </div>

            </div>

        </div>

    </div>

    <div class="accordion mt-4" id="auditAccordion">

        @foreach ($group->auditTemplate->items as $item)
            <div class="accordion-item mb-3">

                <h2 class="accordion-header" id="heading{{ $item->id }}">

                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $item->id }}">

                        <strong>

                            {{ $item->sort }}

                            .

                            {{ $item->perkara }}

                        </strong>

                    </button>

                </h2>

                <div id="collapse{{ $item->id }}" class="accordion-collapse collapse" data-bs-parent="#auditAccordion">

                    <div class="accordion-body">

                        <form>

                            <table class="table table-bordered">

                                <tbody>

                                    <tr>

                                        <td width="5%">Bil</td>

                                        <td>

                                            {{ $item->sort }}

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>

                                            Perkara

                                        </td>

                                        <td>

                                            {{ $item->perkara }}

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>

                                            Klausa

                                        </td>

                                        <td>

                                            <b>

                                                {{ $item->no_klausa }}

                                            </b>

                                            <br>

                                            {{ $item->klausa }}

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>

                                            Senarai Semak

                                        </td>

                                        <td>

                                            @foreach ($item->checklists as $checklist)
                                                <div class="form-check mb-2">

                                                    <input type="checkbox" class="form-check-input">

                                                    <label class="form-check-label">

                                                        {{ $checklist->name }}

                                                    </label>

                                                </div>
                                            @endforeach

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>

                                            Lain-lain Penemuan

                                        </td>

                                        <td>

                                            <textarea class="form-control" rows="4"></textarea>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>

                                            Bukti Audit

                                        </td>

                                        <td>

                                            <textarea class="form-control" rows="5"></textarea>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>

                                            Lampiran

                                        </td>

                                        <td>

                                            <input type="file" multiple class="form-control">

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                            <div class="text-end">

                                <button type="submit" class="btn btn-primary">

                                    <i class="fadeIn animated bx bx-save"></i>

                                    Simpan Item

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
        @endforeach

    </div>
@endsection

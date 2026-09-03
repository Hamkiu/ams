@extends('layouts.master')

@section('title', 'Senarai Audit')

@section('content')
    @include('include.error')

    <div class="row">

        @forelse($groups as $member)

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-body">

                        {{-- =====================================================
                            MAKLUMAT GROUP
                        ====================================================== --}}

                        <h5>
                            <b>Nama Group :</b>
                            {{ $member->auditGroup->name }}
                        </h5>

                        <p>
                            <b>Template :</b>
                            {{ $member->auditGroup->auditTemplate->name }}
                        </p>

                        <p>
                            <b>Klausa :</b>
                            {{ $member->auditGroup->auditTemplate->klausa }}
                        </p>

                        <p>
                            <b>Tarikh Cadangan Audit:</b>
                            {{ date('d-m-Y', strtotime($member->auditGroup->tarikh)) }}
                        </p>

                        <p>
                            <b>Jabatan:</b>
                            {{ $member->auditGroup->jabatan }}
                        </p>

                        <p>
                            <b>Status :</b>
                            {{ $member->status }}
                        </p>


                        {{-- =====================================================
                            KETUA KUMPULAN
                        ====================================================== --}}

                        <p>
                            <b>Ketua Kumpulan :</b>

                            @php
                                $leader = $member->auditGroup->members->where('role', 'Leader')->first();
                            @endphp

                            {{ $leader?->pengguna?->name ?? '-' }}
                        </p>


                        {{-- =====================================================
                            AHLI KUMPULAN
                        ====================================================== --}}

                        <p class="mb-2">
                            <b>Ahli Kumpulan :</b>
                        </p>

                        <ul class="mb-3">

                            @foreach ($member->auditGroup->members as $groupMember)
                                <li class="mb-2">

                                    {{-- Nama Auditor --}}
                                    {{ $groupMember->pengguna->name }}


                                    {{-- =================================================
                                        ROLE
                                    ================================================== --}}

                                    @if ($groupMember->role == 'Leader')
                                        <span class="badge bg-primary ms-2">
                                            Ketua
                                        </span>
                                    @endif


                                    {{-- =================================================
                                        STATUS
                                    ================================================== --}}

                                    @if ($groupMember->status == 'SELESAI')
                                        <span class="badge bg-success ms-1">
                                            Selesai
                                        </span>
                                    @elseif ($groupMember->status == 'DALAM PROSES')
                                        <span class="badge bg-warning text-dark ms-1">
                                            Dalam Proses
                                        </span>
                                    @else
                                        <span class="badge bg-secondary ms-1">
                                            Belum Bermula
                                        </span>
                                    @endif


                                    {{-- =================================================
                                        AUDITI
                                    ================================================== --}}

                                    <div class="mt-1">

                                        <small class="text-muted">
                                            Auditi:
                                        </small>

                                        @if ($groupMember->auditi)
                                            <span class="fw-semibold">
                                                {{ $groupMember->auditi }}
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                Belum dipilih
                                            </span>
                                        @endif

                                    </div>

                                </li>
                            @endforeach

                        </ul>


                        {{-- =====================================================
                            AUDITI UNTUK USER YANG LOGIN
                        ====================================================== --}}

                        @if ($member->status == 'BELUM BERMULA')
                            <div class="border rounded p-3 mb-3 bg-light">

                                <div
                                    class="d-flex flex-column flex-md-row
                                            justify-content-between
                                            align-items-md-center
                                            gap-2">

                                    <div>

                                        <small class="text-muted d-block">
                                            Pegawai Yang Diaudit (Auditi)
                                        </small>

                                        @if ($member->auditi)
                                            <strong>
                                                {{ $member->auditi }}
                                            </strong>
                                        @else
                                            <span class="text-danger">
                                                Belum dipilih
                                            </span>
                                        @endif

                                    </div>


                                    {{-- Button Pilih / Tukar Auditi --}}

                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalAuditi{{ $member->id }}">

                                        <i class="material-icons-outlined align-middle" style="font-size:18px;">
                                            person_search
                                        </i>

                                        @if ($member->auditi)
                                            Tukar Auditi
                                        @else
                                            Pilih Auditi
                                        @endif

                                    </button>

                                </div>

                            </div>
                        @endif


                        {{-- =====================================================
                            BUTTON AUDIT
                        ====================================================== --}}

                        @if ($member->status == 'SELESAI')

                            {{-- Button lihat jawapan sendiri --}}

                            <a href="{{ route('audit.show', encode($member->auditGroup->id)) }}" class="btn btn-secondary">

                                Lihat Audit

                            </a>


                            {{-- =================================================
                                HANYA KETUA KUMPULAN
                            ================================================== --}}

                            @if ($member->role == 'Leader')
                                {{-- Belum hantar rumusan --}}

                                @if ($member->auditGroup->status == 'MENUNGGU KESIMPULAN')
                                    <a href="{{ route('audit.summary', encode($member->auditGroup->id)) }}"
                                        class="btn btn-success">

                                        <i class="material-icons-outlined align-middle" style="font-size:18px;">
                                            note_add
                                        </i>

                                        @if ($member->auditGroup->conclusion)
                                            Kemaskini Rumusan
                                        @else
                                            Tambah Rumusan
                                        @endif

                                    </a>


                                    {{-- Rumusan sudah dihantar --}}
                                @elseif ($member->auditGroup->status == 'MENUNGGU ULASAN' || $member->auditGroup->status == 'SELESAI')
                                    <a href="{{ route('audit.summary', encode($member->auditGroup->id)) }}"
                                        class="btn btn-info">

                                        <i class="material-icons-outlined align-middle" style="font-size:18px;">
                                            visibility
                                        </i>

                                        Lihat Rumusan

                                    </a>
                                @endif
                            @endif
                        @else
                            {{-- =================================================
                                BELUM PILIH AUDITI
                            ================================================== --}}

                            @if (!$member->auditi)
                                <button type="button" class="btn btn-secondary" disabled>

                                    <i class="material-icons-outlined align-middle" style="font-size:18px;">
                                        lock
                                    </i>

                                    Buka Audit

                                </button>

                                <small class="text-danger d-block mt-2">
                                    Sila pilih Auditi terlebih dahulu sebelum membuka audit.
                                </small>


                                {{-- =================================================
                                SUDAH PILIH AUDITI
                            ================================================== --}}
                            @else
                                <a href="{{ route('audit.show', encode($member->auditGroup->id)) }}"
                                    class="btn btn-primary">

                                    Buka Audit

                                </a>
                            @endif

                        @endif

                    </div>

                </div>

            </div>


            {{-- =============================================================
                MODAL PILIH AUDITI
            ============================================================== --}}

            @if ($member->status == 'BELUM BERMULA')
                <div class="modal fade" id="modalAuditi{{ $member->id }}" tabindex="-1"
                    aria-labelledby="modalAuditiLabel{{ $member->id }}" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content">

                            <form method="POST" action="{{ route('audit.store-auditi') }}">

                                @csrf

                                {{-- =================================================
                                    MODAL HEADER
                                ================================================== --}}
                                <div class="modal-header">

                                    <h5 class="modal-title" id="modalAuditiLabel{{ $member->id }}">

                                        Pilih Auditi

                                    </h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>

                                </div>


                                {{-- =================================================
                                    MODAL BODY
                                ================================================== --}}
                                <div class="modal-body">

                                    <input type="hidden" name="member_id" value="{{ encode($member->id) }}">


                                    {{-- =================================================
                                        MAKLUMAT
                                    ================================================== --}}
                                    <div class="alert alert-info">

                                        Sila nyatakan pegawai yang akan diaudit
                                        oleh anda bagi

                                        <strong>
                                            {{ $member->auditGroup->name }}
                                        </strong>.

                                    </div>


                                    {{-- =================================================
                                        NO PEKERJA
                                    ================================================== --}}
                                    <div class="mb-3">

                                        <label for="no_pekerja{{ $member->id }}" class="form-label">

                                            No. Pekerja
                                            <span class="text-danger">*</span>

                                        </label>


                                        <div class="input-group">

                                            {{-- ICON --}}
                                            <span class="input-group-text">

                                                <i class="material-icons-outlined" style="font-size: 18px;">
                                                    badge
                                                </i>

                                            </span>


                                            {{-- INPUT --}}
                                            <input type="text" class="form-control" id="no_pekerja{{ $member->id }}"
                                                name="no_pekerja" value="{{ $member->nombor_auditi }}"
                                                placeholder="Sila Masukkan No Pekerja" autocomplete="off">


                                            {{-- SEARCH BUTTON --}}
                                            <button type="button" class="btn btn-primary btnSearchPekerja"
                                                data-member-id="{{ $member->id }}">

                                                <i class="material-icons-outlined align-middle" style="font-size:20px;">
                                                    search
                                                </i>

                                            </button>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                        NAMA AUDITI
                                    ================================================== --}}
                                    <div class="mb-3">

                                        <label for="nama_auditi{{ $member->id }}" class="form-label">

                                            Nama Auditi
                                            <span class="text-danger">*</span>

                                        </label>


                                        <div class="input-group">

                                            {{-- ICON --}}
                                            <span class="input-group-text">

                                                <i class="material-icons-outlined" style="font-size:18px;">
                                                    person
                                                </i>

                                            </span>


                                            {{-- NAMA --}}
                                            <input type="text" class="form-control" id="nama_auditi{{ $member->id }}"
                                                name="auditi" value="{{ $member->auditi }}" placeholder="Nama Auditi"
                                                readonly>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                        NOTA
                                    ================================================== --}}
                                    <small class="text-muted">

                                        Masukkan No. Pekerja dan tekan butang carian
                                        untuk mendapatkan maklumat Auditi.

                                    </small>

                                </div>


                                {{-- =================================================
                                    MODAL FOOTER
                                ================================================== --}}
                                <div class="modal-footer">

                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                        Batal

                                    </button>


                                    <button type="submit" class="btn btn-primary">

                                        <i class="material-icons-outlined align-middle" style="font-size:18px;">
                                            save
                                        </i>

                                        Simpan Auditi

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>
            @endif

        @empty

            <div class="col-md-12">

                <div class="alert alert-warning">

                    Tiada audit diberikan.

                </div>

            </div>

        @endforelse

    </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | BUTTON CARI PEKERJA
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.btnSearchPekerja').forEach(function(button) {

                button.addEventListener('click', function() {

                    /*
                    |--------------------------------------------------------------------------
                    | DAPATKAN MEMBER ID
                    |--------------------------------------------------------------------------
                    */

                    const memberId = this.dataset.memberId;

                    const noPekerjaInput =
                        document.getElementById('no_pekerja' + memberId);

                    const namaAuditiInput =
                        document.getElementById('nama_auditi' + memberId);

                    const paynumber = noPekerjaInput.value.trim();


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDASI NO PEKERJA
                    |--------------------------------------------------------------------------
                    */

                    if (!paynumber) {

                        Swal.fire({
                            icon: 'warning',
                            title: 'Sila Masukkan No Pekerja',
                            timer: 1500,
                            showConfirmButton: true
                        });

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CLEAR NAMA LAMA
                    |--------------------------------------------------------------------------
                    */

                    namaAuditiInput.value = '';


                    /*
                    |--------------------------------------------------------------------------
                    | DISABLE BUTTON SEMASA SEARCH
                    |--------------------------------------------------------------------------
                    */

                    const originalHTML = this.innerHTML;

                    this.disabled = true;

                    this.innerHTML = `
                    <span class="spinner-border spinner-border-sm"
                          role="status"
                          aria-hidden="true">
                    </span>
                `;


                    /*
                    |--------------------------------------------------------------------------
                    | CALL API
                    |--------------------------------------------------------------------------
                    */

                    fetch('/api/cari-pekerja', {

                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },

                            body: JSON.stringify({
                                paynumber: paynumber
                            })

                        })

                        .then(response => {

                            if (!response.ok) {
                                throw new Error('Ralat semasa menghubungi API');
                            }

                            return response.json();

                        })

                        .then(response => {

                            const data = response.body;


                            /*
                            |--------------------------------------------------------------------------
                            | PEKERJA TIDAK DIJUMPAI
                            |--------------------------------------------------------------------------
                            */

                            if (
                                !data ||
                                !data.maklumatUser ||
                                data.maklumatUser.length === 0
                            ) {

                                namaAuditiInput.value = '';

                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Maklumat Tidak Dijumpai',
                                    text: 'Maklumat pekerja tidak dijumpai.',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });

                                return;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | AMBIL MAKLUMAT PEKERJA
                            |--------------------------------------------------------------------------
                            */

                            const u = data.maklumatUser[0];


                            /*
                            |--------------------------------------------------------------------------
                            | MASUKKAN NAMA AUDITI
                            |--------------------------------------------------------------------------
                            */

                            namaAuditiInput.value =
                                u.MAS_STAFFNAME ?? '';


                            /*
                            |--------------------------------------------------------------------------
                            | BERJAYA
                            |--------------------------------------------------------------------------
                            */



                        })

                        .catch(error => {

                            console.error(error);

                            namaAuditiInput.value = '';

                            Swal.fire({
                                icon: 'error',
                                title: 'Ralat',
                                text: 'Ralat semasa carian maklumat pekerja.'
                            });

                        })

                        .finally(() => {

                            /*
                            |--------------------------------------------------------------------------
                            | ENABLE SEMULA BUTTON
                            |--------------------------------------------------------------------------
                            */

                            this.disabled = false;

                            this.innerHTML = originalHTML;

                        });

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
    </script>
@endpush

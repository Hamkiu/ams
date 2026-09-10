<!DOCTYPE html>

<html lang="ms">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Nota Audit - {{ $auditGroup->name }}</title>

    <link rel="icon" sizes="16x16" href="{{ asset('template/assets/images/logo-mbi.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('template/assets/css/print.css') }}">

</head>

<body>


    {{-- =========================================================
        WATERMARK LOGO MBI
        KEKALKAN SEPERTI SEDIA ADA
    ========================================================== --}}

    <div class="watermark-container">

        <img src="{{ asset('template/assets/images/logo-mbi.png') }}" class="watermark-logo" alt="Watermark MBI">

    </div>



    {{-- =========================================================
        BUTTON PRINT
    ========================================================== --}}

    <div class="print-toolbar no-print">

        <button type="button" class="btn-print" onclick="window.print()">

            🖨️ Cetak Laporan

        </button>

    </div>



    {{-- =========================================================
        OUTER PRINT TABLE

        THEAD akan diulang secara automatik pada setiap page.
    ========================================================== --}}

    <table class="print-document-table">


        {{-- =====================================================
            REPEATING HEADER
        ====================================================== --}}

        <thead class="print-document-header">

            <tr>

                <td class="print-document-header-cell">

                    <div class="page-header-wrapper">

                        <table class="page-header-box">

                            <tr>

                                <td class="header-label">
                                    No. Rujukan Borang
                                </td>

                                <td class="header-colon">
                                    :
                                </td>

                                <td class="header-value">
                                    MBI/PK/PAB/01-04
                                </td>

                            </tr>


                            <tr>

                                <td class="header-label">
                                    No. Pindaan
                                </td>

                                <td class="header-colon">
                                    :
                                </td>

                                <td class="header-value">
                                    2
                                </td>

                            </tr>


                            <tr>

                                <td class="header-label">
                                    Tarikh Berkuatkuasa
                                </td>

                                <td class="header-colon">
                                    :
                                </td>

                                <td class="header-value">
                                    16 Jun 2026
                                </td>

                            </tr>

                        </table>

                    </div>

                </td>

            </tr>

        </thead>



        {{-- =====================================================
            SELURUH CONTENT LAPORAN
        ====================================================== --}}

        <tbody class="print-document-body">

            <tr>

                <td class="print-document-content">


                    <div class="page-container">


                        {{-- =================================================
                            HEADER LAPORAN
                        ================================================== --}}

                        <div class="report-header">

                            <img src="{{ asset('template/assets/images/logo-mbi.png') }}" class="header-logo"
                                alt="Logo MBI">

                            <h2>
                                Majlis Bandaraya Ipoh
                            </h2>

                            <h3>
                                Nota Audit
                            </h3>


                            @if (!empty($auditGroup->auditTemplate->no_rujukan))
                                <div class="reference">

                                    No. Rujukan:
                                    {{ $auditGroup->auditTemplate->no_rujukan }}

                                </div>
                            @endif

                        </div>



                        {{-- =================================================
                            A. MAKLUMAT AUDIT
                        ================================================== --}}

                        <div class="section">

                            <div class="section-title">
                                A. Maklumat Audit
                            </div>


                            <table class="table-info">

                                <tr>

                                    <th>
                                        Nama / No. Kumpulan
                                    </th>

                                    <td>
                                        {{ $auditGroup->name }}
                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Template Audit
                                    </th>

                                    <td>
                                        {{ $auditGroup->auditTemplate->name ?? '-' }}
                                    </td>

                                </tr>
                                <tr>

                                    <th>
                                        Klausa
                                    </th>

                                    <td>
                                        {{ $auditGroup->auditTemplate->klausa ?? '-' }}
                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Jabatan
                                    </th>

                                    <td>
                                        {{ $auditGroup->jabatan ?? '-' }}
                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Tarikh Pelan Audit
                                    </th>

                                    <td>

                                        @if ($auditGroup->tarikh)
                                            {{ \Carbon\Carbon::parse($auditGroup->tarikh)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Status
                                    </th>

                                    <td>
                                        {{ $auditGroup->status }}
                                    </td>

                                </tr>

                                @if ($auditGroup->started_at)
                                    <tr>

                                        <th>
                                            Tarikh Mula
                                        </th>

                                        <td>

                                            {{ \Carbon\Carbon::parse($auditGroup->started_at)->format('d/m/Y h:i A') }}

                                        </td>

                                    </tr>
                                @endif


                                {{-- @if ($auditGroup->completed_at)
                                    <tr>

                                        <th>
                                            Tarikh Selesai
                                        </th>

                                        <td>

                                            {{ \Carbon\Carbon::parse($auditGroup->completed_at)->format('d/m/Y h:i A') }}

                                        </td>

                                    </tr>
                                @endif --}}

                            </table>

                        </div>



                        {{-- =================================================
                            B. AHLI KUMPULAN AUDIT
                        ================================================== --}}

                        <div class="section">

                            <div class="section-title">
                                B. Ahli Kumpulan Audit
                            </div>


                            <table>

                                <thead>

                                    <tr>

                                        <th width="35" class="text-center">

                                            Bil.

                                        </th>

                                        <th>
                                            Nama Auditor
                                        </th>

                                        <th width="140">
                                            Peranan
                                        </th>

                                        <th>
                                            Auditi
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach ($auditGroup->members as $member)
                                        <tr>

                                            <td class="text-center">

                                                {{ $loop->iteration }}

                                            </td>


                                            <td>

                                                {{ $member->pengguna->name ?? '-' }}

                                            </td>


                                            <td>

                                                {{ $member->role == 'Leader' ? 'Ketua Kumpulan Audit' : 'Juruaudit Dalaman' }}

                                            </td>

                                            <td>

                                                {{ $member->auditi ?? '-' }}

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>



                        {{-- =================================================
                            C. HASIL AUDIT
                        ================================================== --}}

                        <div class="section">

                            <div class="section-title">
                                C. Hasil Audit
                            </div>


                            @forelse ($auditGroup->auditTemplate->items as $item)


                                {{-- =============================================
                                    ITEM AUDIT
                                ============================================== --}}

                                <div class="item-title">

                                    {{ $item->sort }}.
                                    {{ $item->perkara }}

                                </div>



                                {{-- =============================================
                                    MAKLUMAT ITEM
                                ============================================== --}}

                                <table class="table-info">

                                    <tr>

                                        <th width="20%">

                                            No. Klausa

                                        </th>

                                        <td>

                                            {{ $item->no_klausa ?? '-' }}

                                        </td>

                                    </tr>


                                    <tr>

                                        <th>

                                            Klausa

                                        </th>

                                        <td>

                                            {{ $item->klausa ?? '-' }}

                                        </td>

                                    </tr>

                                </table>



                                {{-- =============================================
                                    JAWAPAN SETIAP JURUAUDIT
                                ============================================== --}}

                                @foreach ($auditGroup->members as $member)
                                    @php

                                        /**
                                         * |--------------------------------------------------------------------------
                                         * | JAWAPAN ASAL AUDITOR
                                         * |--------------------------------------------------------------------------
                                         */

                                        $answer = $auditGroup->answers
                                            ->where('audit_item_id', $item->id)
                                            ->where('user_id', $member->user_id)
                                            ->first();

                                        /**
                                         * |--------------------------------------------------------------------------
                                         * | PINDAAN KETUA
                                         * |--------------------------------------------------------------------------
                                         *
                                         * | Jika null = Ketua tidak membuat pindaan.
                                         *
                                         */

                                        $answerReview = $answer?->review;

                                    @endphp



                                    <div class="answer-box">


                                        {{-- =========================================
                                            NAMA AUDITOR
                                        ========================================== --}}

                                        <div class="auditor-title">

                                            Juruaudit:

                                            {{ $member->pengguna->name ?? '-' }}


                                            @if ($member->role == 'Leader')
                                                <span class="role-label">

                                                    (Ketua Kumpulan Audit)
                                                </span>
                                            @endif

                                        </div>



                                        @if ($answer)
                                            {{-- =====================================
                                                SENARAI SEMAK
                                            ====================================== --}}

                                            <table class="table-checklist">

                                                <thead>

                                                    <tr>

                                                        <th class="col-no">

                                                            Bil.

                                                        </th>


                                                        <th>

                                                            Senarai Semak

                                                        </th>


                                                        <th class="col-status">

                                                            Status

                                                        </th>

                                                    </tr>

                                                </thead>


                                                <tbody>

                                                    @forelse ($item->checklists as $checklist)
                                                        @php

                                                            /**
                                                             * |--------------------------------------------------------------------------
                                                             * | CHECKLIST ASAL AUDITOR
                                                             * |--------------------------------------------------------------------------
                                                             */

                                                            $originalChecklist = $answer->checklists->firstWhere(
                                                                'audit_checklist_id',
                                                                $checklist->id,
                                                            );

                                                            /**
                                                             * |--------------------------------------------------------------------------
                                                             * | CHECKLIST PINDAAN KETUA
                                                             * |--------------------------------------------------------------------------
                                                             */

                                                            $reviewChecklist = $answerReview?->checklists?->firstWhere(
                                                                'audit_checklist_id',
                                                                $checklist->id,
                                                            );

                                                            /**
                                                             * |--------------------------------------------------------------------------
                                                             * | STATUS FINAL
                                                             * |--------------------------------------------------------------------------
                                                             *
                                                             * | Ada pindaan → gunakan pindaan Ketua.
                                                             * | Tiada       → gunakan jawapan asal.
                                                             *
                                                             */

                                                            $status =
                                                                $reviewChecklist?->status ??
                                                                $originalChecklist?->status;

                                                        @endphp


                                                        <tr>

                                                            <td class="text-center">

                                                                {{ $loop->iteration }}

                                                            </td>


                                                            <td>

                                                                {{ $checklist->name }}

                                                            </td>


                                                            <td class="text-center">

                                                                @if ($status === 'AKUR')
                                                                    <strong>
                                                                        AKUR
                                                                    </strong>
                                                                @elseif ($status === 'TIDAK AKUR')
                                                                    <strong>
                                                                        TIDAK AKUR
                                                                    </strong>
                                                                @elseif ($status === 'TIDAK BERKAITAN')
                                                                    <strong>
                                                                        TIDAK BERKAITAN
                                                                    </strong>
                                                                @else
                                                                    -
                                                                @endif

                                                            </td>

                                                        </tr>


                                                    @empty

                                                        <tr>

                                                            <td colspan="3" class="text-center text-muted">

                                                                Tiada senarai semak.

                                                            </td>

                                                        </tr>
                                                    @endforelse

                                                </tbody>

                                            </table>



                                            {{-- =====================================
                                                PENEMUAN LAIN + BUKTI AUDIT
                                            ====================================== --}}

                                            <table class="answer-detail">


                                                {{-- =================================
                                                    PENEMUAN LAIN
                                                ================================== --}}

                                                <tr>

                                                    <th width="25%">

                                                        Penemuan Lain

                                                    </th>


                                                    <td>

                                                        @php

                                                            /**
                                                             * |--------------------------------------------------------------------------
                                                             * | PENEMUAN FINAL
                                                             * |--------------------------------------------------------------------------
                                                             */

                                                            $penemuanFinal =
                                                                $answerReview?->penemuan_lain ?? $answer->penemuan_lain;

                                                        @endphp


                                                        @if (!empty($penemuanFinal))
                                                            {!! nl2br(e($penemuanFinal)) !!}
                                                        @else
                                                            -
                                                        @endif

                                                    </td>

                                                </tr>



                                                {{-- =================================
                                                    BUKTI AUDIT
                                                ================================== --}}

                                                <tr>

                                                    <th>

                                                        Bukti Audit

                                                    </th>


                                                    <td>

                                                        @php

                                                            /**
                                                             * |--------------------------------------------------------------------------
                                                             * | BUKTI AUDIT FINAL
                                                             * |--------------------------------------------------------------------------
                                                             */

                                                            $buktiFinal =
                                                                $answerReview?->bukti_audit ?? $answer->bukti_audit;

                                                        @endphp


                                                        @if (!empty($buktiFinal))
                                                            <div class="ckeditor-content">

                                                                {!! $buktiFinal !!}

                                                            </div>
                                                        @else
                                                            -
                                                        @endif

                                                    </td>

                                                </tr>

                                            </table>
                                        @else
                                            <div class="no-answer">

                                                Tiada jawapan direkodkan untuk juruaudit ini.

                                            </div>
                                        @endif


                                    </div>
                                @endforeach


                                @empty


                                    <div class="no-answer text-center">

                                        Tiada item audit.

                                    </div>
                                @endforelse

                            </div>



                            {{-- =================================================
                            D. RUMUSAN / KESIMPULAN KETUA AUDIT
                        ================================================== --}}

                            {{-- <div class="section">

                                <div class="section-title">

                                    D. Rumusan / Kesimpulan Ketua Kumpulan Audit

                                </div>


                                <div class="conclusion-box">

                                    @if (!empty($auditGroup->conclusion->conclusion))
                                        <div class="ckeditor-content">

                                            {!! $auditGroup->conclusion->conclusion !!}

                                        </div>
                                    @else
                                        -
                                    @endif

                                </div>

                            </div> --}}



                            {{-- =================================================
                            E. ULASAN ADMIN
                        ================================================== --}}

                            {{-- <div class="section">

                                <div class="section-title">

                                    E. Ulasan Pentadbir

                                </div>


                                <div class="review-box">

                                    @if (!empty($auditGroup->review->review))
                                        <div class="ckeditor-content">

                                            {!! $auditGroup->review->review !!}

                                        </div>
                                    @else
                                        -
                                    @endif

                                </div>

                            </div> --}}



                            {{-- =================================================
                            FOOTER LAST PAGE
                        ================================================== --}}

                            <div class="report-footer">

                                Nota Audit |
                                {{ $auditGroup->name }}

                                <br>

                                Dicetak pada:
                                {{ now()->format('d/m/Y h:i A') }}

                            </div>


                        </div>

                    </td>

                </tr>

            </tbody>

        </table>


    </body>

    </html>

<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Audit - {{ $auditGroup->name }}</title>
    <link rel="icon" sizes="16x16" href="{{ asset('template/assets/images/logo-mbi.png') }}" type="image/png">

    <style>
        /* =========================================================
         * PAGE SETUP
         * ========================================================= */
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5px;
            line-height: 1.4;
            color: #111;
            background: #fff;
        }

        .page-container {
            position: relative;
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        /* =========================================================
         * WATERMARK LOGO (TENGAH A4)
         * ========================================================= */
        .watermark-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1000;
            opacity: 0.06;
            pointer-events: none;
            text-align: center;
            width: 100%;
        }

        .watermark-logo {
            width: 380px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* =========================================================
         * PRINT BUTTON TOOLBAR
         * ========================================================= */
        .print-toolbar {
            margin-bottom: 20px;
            text-align: right;
        }

        .btn-print {
            display: inline-block;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background: #1a252f;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-print:hover {
            background: #2c3e50;
        }

        /* =========================================================
         * HEADER (WITH HEADER LOGO & WATERMARK SAFE)
         * ========================================================= */
        .report-header {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid #222;
        }

        .header-logo {
            height: 65px;
            width: auto;
            margin-bottom: 8px;
        }

        .report-header h2 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000;
        }

        .report-header h3 {
            margin: 3px 0 0;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
        }

        .report-header .reference {
            margin-top: 4px;
            font-size: 10px;
            font-weight: 600;
            color: #444;
        }

        /* =========================================================
         * SECTIONS & TITLES
         * ========================================================= */
        .section {
            margin-top: 16px;
            page-break-inside: auto;
        }

        .section-title {
            margin: 0 0 8px;
            padding: 6px 10px;
            border: 1px solid #111;
            background: #e9ecef;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .item-title {
            margin: 14px 0 6px;
            padding: 6px 10px;
            background: #f8f9fa;
            border: 1px solid #ccc;
            border-left: 4px solid #111;
            font-size: 11px;
            font-weight: bold;
        }

        .auditor-title {
            margin: 8px 0 4px;
            font-size: 10.5px;
            font-weight: bold;
            color: #222;
        }

        .role-label {
            font-size: 9.5px;
            color: #555;
            font-weight: normal;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #666;
        }

        /* =========================================================
         * TABLES
         * ========================================================= */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            background: transparent;
        }

        th,
        td {
            border: 1px solid #222;
            padding: 5px 8px;
            vertical-align: top;
            font-size: 10px;
        }

        th {
            background: #f1f3f5;
            font-weight: bold;
            text-align: left;
            color: #000;
        }

        .table-info th {
            width: 25%;
            background: #f8f9fa;
        }

        .table-checklist .col-no {
            width: 32px;
            text-align: center;
        }

        .table-checklist .col-status {
            width: 65px;
            text-align: center;
        }

        .answer-detail {
            margin-top: 4px;
        }

        /* =========================================================
         * STATUS ICONS
         * ========================================================= */
        .checkmark {
            font-weight: bold;
            color: #000;
        }

        .unchecked {
            color: #888;
        }

        /* =========================================================
         * CKEDITOR CONTENT FORMATTING
         * ========================================================= */
        .ckeditor-content {
            width: 100%;
            max-width: 100%;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .ckeditor-content p {
            margin: 0 0 4px 0;
        }

        .ckeditor-content p:last-child {
            margin-bottom: 0;
        }

        .ckeditor-content img {
            display: block;
            max-width: 100% !important;
            height: auto !important;
            margin: 4px 0;
        }

        .ckeditor-content figure {
            max-width: 100% !important;
            margin: 6px 0 !important;
        }

        .ckeditor-content figure.table {
            width: 100%;
            margin: 6px 0;
        }

        .ckeditor-content table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .ckeditor-content table td,
        .ckeditor-content table th {
            border: 1px solid #333 !important;
            padding: 4px 6px !important;
        }

        .ckeditor-content ul,
        .ckeditor-content ol {
            padding-left: 20px;
            margin: 4px 0;
        }

        /* =========================================================
         * BOXES & CONTAINERS
         * ========================================================= */
        .answer-box {
            margin-bottom: 12px;
            padding: 6px;
            border: 1px solid #dedede;
            background: rgba(255, 255, 255, 0.85);
            page-break-inside: avoid;
        }

        .conclusion-box,
        .review-box {
            min-height: 60px;
            border: 1px solid #222;
            padding: 8px 10px;
            background: rgba(255, 255, 255, 0.85);
            page-break-inside: avoid;
        }

        .no-answer {
            padding: 8px;
            font-style: italic;
            color: #666;
            font-size: 10px;
        }

        /* =========================================================
         * FOOTER
         * ========================================================= */
        .report-footer {
            margin-top: 20px;
            padding-top: 6px;
            border-top: 1px solid #888;
            font-size: 8.5px;
            color: #555;
            text-align: center;
            page-break-inside: avoid;
        }

        /* =========================================================
         * PRINT OPTIMIZATION
         * ========================================================= */
        @media print {
            @page {
                margin-top: 10mm;
                margin-bottom: 10mm;
            }

            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
                background: #fff;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .page-container {
                width: 100%;
                max-width: none;
                margin: 0;
            }

            .watermark-container {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.08 !important;
            }

            .section-title,
            th,
            .item-title {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .answer-box,
            .conclusion-box,
            .review-box,
            tr,
            img,
            figure {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    {{-- Watermark Logo MBI di Tengah Halaman A4 --}}
    <div class="watermark-container">
        <img src="{{ asset('template/assets/images/logo-mbi.png') }}" class="watermark-logo" alt="Watermark MBI">
    </div>

    <div class="page-container">

        {{-- Button Print --}}
        <div class="print-toolbar no-print">
            <button type="button" class="btn-print" onclick="window.print()">
                🖨️ Cetak Laporan
            </button>
        </div>

        {{-- Header dengan Logo & Tajuk --}}
        <div class="report-header">
            <img src="{{ asset('template/assets/images/logo-mbi.png') }}" class="header-logo" alt="Logo MBI">
            <h2>Majlis Bandaraya Ipoh</h2>
            <h3>Laporan Audit</h3>

            @if (!empty($auditGroup->auditTemplate->no_rujukan))
                <div class="reference">
                    No. Rujukan: {{ $auditGroup->auditTemplate->no_rujukan }}
                </div>
            @endif
        </div>

        {{-- A. Maklumat Audit --}}
        <div class="section">
            <div class="section-title">A. Maklumat Audit</div>
            <table class="table-info">
                <tr>
                    <th>Nama / No. Kumpulan</th>
                    <td>{{ $auditGroup->name }}</td>
                </tr>
                <tr>
                    <th>Template Audit</th>
                    <td>{{ $auditGroup->auditTemplate->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $auditGroup->jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tarikh Audit</th>
                    <td>
                        @if ($auditGroup->tarikh)
                            {{ \Carbon\Carbon::parse($auditGroup->tarikh)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $auditGroup->status }}</td>
                </tr>
                @if ($auditGroup->completed_at)
                    <tr>
                        <th>Tarikh Selesai</th>
                        <td>{{ \Carbon\Carbon::parse($auditGroup->completed_at)->format('d/m/Y h:i A') }}</td>
                    </tr>
                @endif
            </table>
        </div>

        {{-- B. Ahli Kumpulan Audit --}}
        <div class="section">
            <div class="section-title">B. Ahli Kumpulan Audit</div>
            <table>
                <thead>
                    <tr>
                        <th width="35" class="text-center">Bil.</th>
                        <th>Nama</th>
                        <th width="140">Peranan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($auditGroup->members as $member)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $member->pengguna->name ?? '-' }}</td>
                            <td>{{ $member->role == 'Leader' ? 'Ketua Kumpulan Audit' : 'Juruaudit Dalaman' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- C. Hasil Audit --}}
        <div class="section">
            <div class="section-title">C. Hasil Audit</div>

            @forelse ($auditGroup->auditTemplate->items as $item)

                {{-- =================================================
                ITEM AUDIT
            ================================================== --}}
                <div class="item-title">

                    {{ $item->sort }}. {{ $item->perkara }}

                </div>


                {{-- =================================================
                MAKLUMAT ITEM
            ================================================== --}}
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


                {{-- =================================================
                JAWAPAN SETIAP JURUAUDIT
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
                    |
                    | Jika null = Ketua tidak membuat pindaan.
                    |
                    */
                        $answerReview = $answer?->review;

                    @endphp


                    <div class="answer-box">

                        {{-- =================================================
                        NAMA AUDITOR
                    ================================================== --}}
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
                            {{-- =================================================
                            CHECKLIST
                        ================================================== --}}
                            <table class="table-checklist">

                                <thead>

                                    <tr>

                                        <th class="col-no">
                                            Bil.
                                        </th>

                                        <th>
                                            Checklist
                                        </th>

                                        <th class="col-status">
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse ($item->checklists as $checklist)
                                        @php

                                            /*
                                        |--------------------------------------------------------------------------
                                        | CHECKLIST ASAL AUDITOR
                                        |--------------------------------------------------------------------------
                                        */
                                            $originalChecklist = $answer->checklists->firstWhere(
                                                'audit_checklist_id',
                                                $checklist->id,
                                            );

                                            /*
                                        |--------------------------------------------------------------------------
                                        | CHECKLIST PINDAAN KETUA
                                        |--------------------------------------------------------------------------
                                        */
                                            $reviewChecklist = $answerReview?->checklists?->firstWhere(
                                                'audit_checklist_id',
                                                $checklist->id,
                                            );

                                            /*
                                        |--------------------------------------------------------------------------
                                        | STATUS FINAL
                                        |--------------------------------------------------------------------------
                                        |
                                        | Ada pindaan → gunakan pindaan Ketua.
                                        | Tiada       → gunakan jawapan asal.
                                        |
                                        */
                                            $status = $reviewChecklist?->status ?? $originalChecklist?->status;

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

                                                Tiada checklist.

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>


                            {{-- =================================================
                            PENEMUAN LAIN + BUKTI AUDIT
                        ================================================== --}}
                            <table class="answer-detail">

                                {{-- =============================================
                                PENEMUAN LAIN
                            ============================================== --}}
                                <tr>

                                    <th width="25%">
                                        Penemuan Lain
                                    </th>


                                    <td>

                                        @php

                                            /*
                                        |--------------------------------------------------------------------------
                                        | PENEMUAN FINAL
                                        |--------------------------------------------------------------------------
                                        */
                                            $penemuanFinal = $answerReview?->penemuan_lain ?? $answer->penemuan_lain;

                                        @endphp


                                        @if (!empty($penemuanFinal))
                                            {!! nl2br(e($penemuanFinal)) !!}
                                        @else
                                            -
                                        @endif

                                    </td>

                                </tr>


                                {{-- =============================================
                                BUKTI AUDIT
                            ============================================== --}}
                                <tr>

                                    <th>
                                        Bukti Audit
                                    </th>


                                    <td>

                                        @php

                                            /*
                                        |--------------------------------------------------------------------------
                                        | BUKTI AUDIT FINAL
                                        |--------------------------------------------------------------------------
                                        */
                                            $buktiFinal = $answerReview?->bukti_audit ?? $answer->bukti_audit;

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

            {{-- D. Rumusan / Kesimpulan Ketua Audit --}}
            <div class="section">
                <div class="section-title">D. Rumusan / Kesimpulan Ketua Audit</div>
                <div class="conclusion-box">
                    @if (!empty($auditGroup->conclusion->conclusion))
                        <div class="ckeditor-content">
                            {!! $auditGroup->conclusion->conclusion !!}
                        </div>
                    @else
                        -
                    @endif
                </div>
            </div>

            {{-- E. Ulasan Admin --}}
            <div class="section">
                <div class="section-title">E. Ulasan Admin</div>
                <div class="review-box">
                    @if (!empty($auditGroup->review->review))
                        <div class="ckeditor-content">
                            {!! $auditGroup->review->review !!}
                        </div>
                    @else
                        -
                    @endif
                </div>
            </div>

            {{-- Footer --}}
            <div class="report-footer">
                Laporan Audit | {{ $auditGroup->name }} <br>
                Dicetak pada: {{ now()->format('d/m/Y h:i A') }}
            </div>

        </div>

    </body>



    </html>

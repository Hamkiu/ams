@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div class="row">

        {{-- =========================================================
      PAGE HEADER
  ========================================================== --}}
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">

            <div>
                <h4 class="mb-1 fw-bold">
                    Dashboard Audit
                </h4>

                <p class="mb-0 text-muted">
                    Ringkasan prestasi dan status pelaksanaan audit
                </p>
            </div>

            {{-- FILTER TAHUN --}}
            <div>
                <form method="GET" action="{{ route('dashboard') }}">

                    <div class="d-flex align-items-center gap-2">

                        <label for="tahun" class="fw-semibold mb-0">
                            Tahun
                        </label>

                        <select name="tahun" id="tahun" class="form-select" onchange="this.form.submit()"
                            style="min-width: 130px;">

                            @foreach ($senaraiTahun as $item)
                                <option value="{{ $item }}" {{ $tahun == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                </form>
            </div>

        </div>


        {{-- =========================================================
      SUMMARY CARDS
  ========================================================== --}}
        <div class="row">

            {{-- JUMLAH AUDIT --}}
            <div class="col-12 col-sm-6 col-xl-3 d-flex align-items-stretch">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <p class="mb-1 text-muted">
                                    Jumlah Audit
                                </p>

                                <h3 class="mb-0 fw-bold">
                                    {{ $jumlahAudit }}
                                </h3>

                                <small class="text-muted">
                                    Tahun {{ $tahun }}
                                </small>

                            </div>

                            <div
                                class="wh-48 d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10">
                                <span class="material-icons-outlined text-primary">
                                    assignment
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- BELUM BERMULA --}}
            <div class="col-12 col-sm-6 col-xl-3 d-flex align-items-stretch">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <p class="mb-1 text-muted">
                                    Belum Bermula
                                </p>

                                <h3 class="mb-0 fw-bold">
                                    {{ $belumBermula }}
                                </h3>

                                <small class="text-muted">
                                    Audit
                                </small>

                            </div>

                            <div
                                class="wh-48 d-flex align-items-center justify-content-center rounded-3 bg-secondary bg-opacity-10">
                                <span class="material-icons-outlined text-secondary">
                                    schedule
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- DALAM PROSES --}}
            <div class="col-12 col-sm-6 col-xl-3 d-flex align-items-stretch">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <p class="mb-1 text-muted">
                                    Dalam Proses
                                </p>

                                <h3 class="mb-0 fw-bold">
                                    {{ $dalamProses }}
                                </h3>

                                <small class="text-muted">
                                    Audit
                                </small>

                            </div>

                            <div
                                class="wh-48 d-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10">
                                <span class="material-icons-outlined text-warning">
                                    pending_actions
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- SELESAI --}}
            <div class="col-12 col-sm-6 col-xl-3 d-flex align-items-stretch">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <p class="mb-1 text-muted">
                                    Selesai
                                </p>

                                <h3 class="mb-0 fw-bold">
                                    {{ $selesai }}
                                </h3>

                                <small class="text-muted">
                                    Audit
                                </small>

                            </div>

                            <div
                                class="wh-48 d-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10">
                                <span class="material-icons-outlined text-success">
                                    task_alt
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
      CHART ROW 1
  ========================================================== --}}
        <div class="row">

            {{-- =====================================================
          2.1 BILANGAN AUDIT MENGIKUT TAHUN
      ====================================================== --}}
            <div class="col-12 col-xl-7 d-flex align-items-stretch">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        <div class="d-flex align-items-start justify-content-between mb-4">

                            <div>

                                <h5 class="mb-1 fw-bold">
                                    Bilangan Audit Mengikut Tahun
                                </h5>

                                <p class="mb-0 text-muted">
                                    Jumlah audit yang didaftarkan berdasarkan tahun
                                </p>

                            </div>

                            <div
                                class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10">
                                <span class="material-icons-outlined text-primary">
                                    bar_chart
                                </span>
                            </div>

                        </div>

                        <div id="chartAuditTahun"></div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
          2.2 STATUS KESIAPAN
      ====================================================== --}}
            <div class="col-12 col-xl-5 d-flex align-items-stretch">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        <div class="d-flex align-items-start justify-content-between mb-3">

                            <div>

                                <h5 class="mb-1 fw-bold">
                                    Status Kesiapan Audit
                                </h5>

                                <p class="mb-0 text-muted">
                                    Tahun {{ $tahun }}
                                </p>

                            </div>

                            <div
                                class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10">
                                <span class="material-icons-outlined text-success">
                                    donut_large
                                </span>
                            </div>

                        </div>


                        <div id="chartStatusKesiapan"></div>


                        {{-- LEGEND --}}
                        <div class="d-flex flex-column gap-2 mt-3">

                            @foreach ($statusKesiapan as $label => $jumlah)
                                <div class="d-flex align-items-center justify-content-between">

                                    <span class="text-muted">
                                        {{ $label }}
                                    </span>

                                    <span class="fw-bold">
                                        {{ $jumlah }}
                                    </span>

                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
      CHART ROW 2
  ========================================================== --}}
        <div class="row">

            {{-- =====================================================
            2.3 STATUS TARIKH AUDIT
            ====================================================== --}}
            <div class="col-12 col-xl-6 d-flex align-items-stretch">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        <div class="d-flex align-items-start justify-content-between mb-4">

                            <div>

                                <h5 class="mb-1 fw-bold">
                                    Status Tarikh Audit Mengikut Perancangan
                                </h5>

                                <p class="mb-0 text-muted">
                                    Perbandingan tarikh pelan dengan tarikh audit bermula
                                </p>

                            </div>

                            <div
                                class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10">
                                <span class="material-icons-outlined text-warning">
                                    event
                                </span>
                            </div>

                        </div>

                        <div id="chartStatusTarikh"></div>


                        <div class="mt-3">

                            @foreach ($statusTarikh as $label => $jumlah)
                                <div class="d-flex align-items-center justify-content-between border-bottom py-2">

                                    <span>
                                        {{ $label }}
                                    </span>

                                    <span class="badge bg-primary rounded-pill">
                                        {{ $jumlah }}
                                    </span>

                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
    PRESTASI MENGIKUT KLAUSA TEMPLATE
====================================================== --}}

            <div class="col-12 col-xl-6 d-flex align-items-stretch">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        {{-- =====================================================
                HEADER
            ====================================================== --}}

                        <div class="d-flex align-items-start justify-content-between mb-4">

                            <div>

                                <h5 class="mb-1 fw-bold">
                                    Prestasi Mengikut Klausa
                                </h5>

                                <p class="mb-0 text-muted">
                                    Prestasi keseluruhan mengikut klausa bagi audit selesai tahun {{ $tahun }}
                                </p>

                            </div>


                            <div
                                class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10">

                                <span class="material-icons-outlined text-success">
                                    analytics
                                </span>

                            </div>

                        </div>


                        {{-- =====================================================
                CHART
            ====================================================== --}}

                        @if (count($chartKlausaTemplate) > 0)

                            <div id="chartPrestasiKlausaTemplate"></div>


                            {{-- =================================================
                    DETAIL
                ================================================== --}}

                            <div class="table-responsive mt-3">

                                <table class="table align-middle mb-0">

                                    <thead>

                                        <tr>

                                            <th>
                                                Klausa
                                            </th>

                                            <th class="text-center">
                                                Akur
                                            </th>

                                            <th class="text-center">
                                                Tidak Akur
                                            </th>

                                            <th class="text-center">
                                                Tidak Berkaitan
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach ($prestasiKlausaTemplate as $item)
                                            <tr>

                                                {{-- KLAUSA TEMPLATE --}}

                                                <td class="fw-semibold">

                                                    {{ $item['klausa'] }}

                                                </td>


                                                {{-- AKUR --}}

                                                <td class="text-center">

                                                    <span class="badge bg-success">

                                                        {{ $item['AKUR'] }}

                                                    </span>

                                                </td>


                                                {{-- TIDAK AKUR --}}

                                                <td class="text-center">

                                                    <span class="badge bg-danger">

                                                        {{ $item['TIDAK AKUR'] }}

                                                    </span>

                                                </td>


                                                {{-- TIDAK BERKAITAN --}}

                                                <td class="text-center">

                                                    <span class="badge bg-secondary">

                                                        {{ $item['TIDAK BERKAITAN'] }}

                                                    </span>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>
                        @else
                            {{-- =================================================
                    TIADA DATA
                ================================================== --}}

                            <div class="text-center py-5">

                                <span class="material-icons-outlined text-muted mb-2" style="font-size: 50px;">

                                    analytics

                                </span>

                                <h6 class="mb-1">

                                    Tiada Data Prestasi

                                </h6>

                                <p class="text-muted mb-0">

                                    Tiada data klausa bagi audit yang telah selesai.

                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>






        </div>
        <div class="row">
            {{-- =========================================================
            2.4 PRESTASI SENARAI SEMAK AUDIT
            ========================================================== --}}
            <div class="col-12">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        {{-- =====================================================
                HEADER
                ==================================================== --}}

                        <div
                            class="d-flex flex-column flex-lg-row align-items-lg-start justify-content-between gap-3 mb-4">

                            <div>

                                <h5 class="mb-1 fw-bold">
                                    Prestasi Senarai Semak Audit
                                </h5>

                                <p class="mb-0 text-muted">
                                    Prestasi mengikut template, klausa dan kumpulan audit tahun {{ $tahun }}
                                </p>

                            </div>


                            <div
                                class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-10">

                                <span class="material-icons-outlined text-info">
                                    fact_check
                                </span>

                            </div>

                        </div>


                        {{-- =====================================================
                FILTER
                ====================================================== --}}

                        <form method="GET" action="{{ route('dashboard') }}" class="mb-4">

                            {{-- Kekalkan tahun --}}
                            <input type="hidden" name="tahun" value="{{ $tahun }}">


                            <div class="row g-3">

                                {{-- =================================================
                        TEMPLATE
                    ================================================== --}}

                                <div class="col-12 col-md-6">

                                    <label for="template_id" class="form-label fw-semibold">
                                        Template Audit
                                    </label>

                                    <select name="template_id" id="template_id" class="form-select"
                                        onchange="this.form.submit()">

                                        @foreach ($senaraiTemplate as $template)
                                            <option value="{{ $template->id }}"
                                                {{ (string) $templateId === (string) $template->id ? 'selected' : '' }}>
                                                {{ $template->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                {{-- =================================================
                        GROUP
                    ================================================== --}}

                                <div class="col-12 col-md-6">

                                    <label for="group_id" class="form-label fw-semibold">
                                        Kumpulan Audit (Selesai)
                                    </label>

                                    <select name="group_id" id="group_id" class="form-select"
                                        onchange="this.form.submit()">

                                        <option value="">
                                            Semua Kumpulan
                                        </option>


                                        @foreach ($senaraiGroup as $group)
                                            <option value="{{ $group->id }}"
                                                {{ (string) $groupId === (string) $group->id ? 'selected' : '' }}>

                                                {{ $group->name }}

                                                @if ($group->jabatan)
                                                    - {{ $group->jabatan }}
                                                @endif

                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </form>


                        {{-- =====================================================
                CHART
                ====================================================== --}}

                        @if (count($chartKlausa) > 0)

                            <div id="chartPrestasiChecklist"></div>


                            {{-- =================================================
                    SUMMARY
                ================================================== --}}

                            <div class="row mt-4 text-center">

                                {{-- AKUR --}}

                                <div class="col-4">

                                    <h4 class="mb-1 text-success">
                                        {{ $akur }}
                                    </h4>

                                    <small class="text-muted">
                                        Akur
                                    </small>

                                </div>


                                {{-- TIDAK AKUR --}}

                                <div class="col-4">

                                    <h4 class="mb-1 text-danger">
                                        {{ $tidakAkur }}
                                    </h4>

                                    <small class="text-muted">
                                        Tidak Akur
                                    </small>

                                </div>


                                {{-- TIDAK BERKAITAN --}}

                                <div class="col-4">

                                    <h4 class="mb-1 text-secondary">
                                        {{ $tidakBerkaitan }}
                                    </h4>

                                    <small class="text-muted">
                                        Tidak Berkaitan
                                    </small>

                                </div>

                            </div>


                            {{-- =================================================
                    TABLE DETAILS
                ================================================== --}}

                            <div class="table-responsive mt-4">

                                <table class="table align-middle">

                                    <thead>

                                        <tr>

                                            <th>
                                                Klausa
                                            </th>

                                            <th>
                                                Perkara
                                            </th>

                                            <th class="text-center">
                                                Akur
                                            </th>

                                            <th class="text-center">
                                                Tidak Akur
                                            </th>

                                            <th class="text-center">
                                                Tidak Berkaitan
                                            </th>

                                            <th class="text-center">
                                                Jumlah
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach ($prestasiKlausa as $item)
                                            @php

                                                $jumlah =
                                                    $item['AKUR'] + $item['TIDAK AKUR'] + $item['TIDAK BERKAITAN'];

                                            @endphp


                                            <tr>

                                                {{-- KLAUSA --}}

                                                <td>

                                                    <strong>
                                                        {{ $item['no_klausa'] ?: '-' }}
                                                    </strong>

                                                </td>


                                                {{-- PERKARA --}}

                                                <td>

                                                    {{ $item['klausa'] ?: '-' }}

                                                </td>


                                                {{-- AKUR --}}

                                                <td class="text-center">

                                                    <span class="badge bg-success">

                                                        {{ $item['AKUR'] }}

                                                    </span>

                                                </td>


                                                {{-- TIDAK AKUR --}}

                                                <td class="text-center">

                                                    <span class="badge bg-danger">

                                                        {{ $item['TIDAK AKUR'] }}

                                                    </span>

                                                </td>


                                                {{-- TIDAK BERKAITAN --}}

                                                <td class="text-center">

                                                    <span class="badge bg-secondary">

                                                        {{ $item['TIDAK BERKAITAN'] }}

                                                    </span>

                                                </td>


                                                {{-- JUMLAH --}}

                                                <td class="text-center fw-bold">

                                                    {{ $jumlah }}

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>
                        @else
                            {{-- =================================================
                    TIADA DATA
                ================================================== --}}

                            <div class="text-center py-5">

                                <span class="material-icons-outlined text-muted mb-2" style="font-size: 50px;">
                                    bar_chart
                                </span>

                                <h6 class="mb-1">
                                    Tiada Data Prestasi
                                </h6>

                                <p class="text-muted mb-0">
                                    Tiada keputusan senarai semak bagi pilihan ini.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            JAVASCRIPT / APEXCHARTS
        ========================================================== --}}
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    /*
                    ============================================================
                    2.1 BILANGAN AUDIT MENGIKUT TAHUN
                    ============================================================
                    */

                    const chartTahun = @json($chartTahun);
                    const chartJumlahAudit = @json($chartJumlahAudit);

                    const optionsAuditTahun = {

                        series: [{
                            name: 'Jumlah Audit',
                            data: chartJumlahAudit
                        }],

                        chart: {
                            type: 'bar',
                            height: 350,
                            toolbar: {
                                show: false
                            }
                        },

                        plotOptions: {

                            bar: {
                                borderRadius: 6,
                                columnWidth: '45%',
                                dataLabels: {
                                    position: 'top'
                                }
                            }

                        },

                        dataLabels: {

                            enabled: true,

                            offsetY: -20,

                            style: {
                                fontSize: '12px'
                            }

                        },

                        xaxis: {

                            categories: chartTahun,

                            title: {
                                text: 'Tahun'
                            }

                        },

                        yaxis: {

                            min: 0,

                            forceNiceScale: true,

                            title: {
                                text: 'Bilangan Audit'
                            },

                            labels: {

                                formatter: function(value) {
                                    return Math.round(value);
                                }

                            }

                        },

                        grid: {

                            strokeDashArray: 4

                        },

                        tooltip: {

                            y: {

                                formatter: function(value) {
                                    return value + ' Audit';
                                }

                            }

                        }

                    };


                    const auditTahunElement =
                        document.querySelector('#chartAuditTahun');

                    if (auditTahunElement) {

                        new ApexCharts(
                            auditTahunElement,
                            optionsAuditTahun
                        ).render();

                    }


                    /*
                    ============================================================
                    2.2 STATUS KESIAPAN AUDIT
                    ============================================================
                    */

                    const statusKesiapan = @json(array_values($statusKesiapan));

                    const optionsStatusKesiapan = {

                        /*
                        |--------------------------------------------------------------------------
                        | DATA
                        |--------------------------------------------------------------------------
                        */

                        series: statusKesiapan,

                        labels: [
                            'BELUM BERMULA',
                            'DALAM PROSES',
                            'MENUNGGU KESIMPULAN',
                            'MENUNGGU ULASAN',
                            'SELESAI'
                        ],


                        /*
                        |--------------------------------------------------------------------------
                        | WARNA MENGIKUT STATUS
                        |--------------------------------------------------------------------------
                        |
                        | BELUM BERMULA        = Kelabu
                        | DALAM PROSES         = Biru
                        | MENUNGGU KESIMPULAN  = Kuning / Orange
                        | MENUNGGU ULASAN      = Ungu
                        | SELESAI              = Hijau
                        |
                        */

                        colors: [
                            '#6c757d', // BELUM BERMULA
                            '#0d6efd', // DALAM PROSES
                            '#ffc107', // MENUNGGU KESIMPULAN
                            '#6f42c1', // MENUNGGU ULASAN
                            '#198754' // SELESAI
                        ],


                        /*
                        |--------------------------------------------------------------------------
                        | CHART
                        |--------------------------------------------------------------------------
                        */

                        chart: {

                            type: 'donut',

                            height: 350,

                            toolbar: {
                                show: false
                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | DATA LABEL
                        |--------------------------------------------------------------------------
                        */

                        dataLabels: {

                            enabled: true,

                            formatter: function(value) {

                                return value.toFixed(1) + '%';

                            },

                            style: {

                                fontSize: '12px',

                                fontWeight: 'bold'

                            },

                            dropShadow: {

                                enabled: false

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | DONUT
                        |--------------------------------------------------------------------------
                        */

                        plotOptions: {

                            pie: {

                                donut: {

                                    size: '68%',

                                    labels: {

                                        show: true,


                                        /*
                                        |--------------------------------------------------------------------------
                                        | STATUS DI TENGAH APABILA HOVER
                                        |--------------------------------------------------------------------------
                                        */

                                        name: {

                                            show: true,

                                            fontSize: '14px',

                                            fontWeight: 500

                                        },


                                        /*
                                        |--------------------------------------------------------------------------
                                        | NILAI
                                        |--------------------------------------------------------------------------
                                        */

                                        value: {

                                            show: true,

                                            fontSize: '22px',

                                            fontWeight: 600,

                                            formatter: function(value) {

                                                return value;

                                            }

                                        },


                                        /*
                                        |--------------------------------------------------------------------------
                                        | JUMLAH KESELURUHAN
                                        |--------------------------------------------------------------------------
                                        */

                                        total: {

                                            show: true,

                                            showAlways: true,

                                            label: 'Jumlah Audit',

                                            fontSize: '14px',

                                            fontWeight: 500,

                                            formatter: function(w) {

                                                return w.globals.seriesTotals.reduce(
                                                    (a, b) => a + b,
                                                    0
                                                );

                                            }

                                        }

                                    }

                                }

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | LEGEND
                        |--------------------------------------------------------------------------
                        */

                        legend: {

                            show: true,

                            position: 'bottom',

                            horizontalAlign: 'center',

                            fontSize: '12px',

                            markers: {

                                width: 10,

                                height: 10,

                                radius: 10

                            },

                            itemMargin: {

                                horizontal: 6,

                                vertical: 3

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | TOOLTIP
                        |--------------------------------------------------------------------------
                        */

                        tooltip: {

                            enabled: true,

                            y: {

                                formatter: function(value) {

                                    return value + ' Audit';

                                }

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | STROKE
                        |--------------------------------------------------------------------------
                        */

                        stroke: {

                            show: true,

                            width: 2,

                            colors: [
                                '#ffffff'
                            ]

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | RESPONSIVE
                        |--------------------------------------------------------------------------
                        */

                        responsive: [

                            {

                                breakpoint: 480,

                                options: {

                                    chart: {

                                        height: 320

                                    },

                                    legend: {

                                        position: 'bottom'

                                    }

                                }

                            }

                        ]

                    };


                    /*
                    |--------------------------------------------------------------------------
                    | RENDER CHART
                    |--------------------------------------------------------------------------
                    */

                    const statusKesiapanElement =
                        document.querySelector('#chartStatusKesiapan');


                    if (statusKesiapanElement) {

                        new ApexCharts(

                            statusKesiapanElement,

                            optionsStatusKesiapan

                        ).render();

                    }


                    /*
                    ============================================================
                    2.3 STATUS TARIKH AUDIT
                    ============================================================
                    */

                    const statusTarikh = @json(array_values($statusTarikh));

                    const optionsStatusTarikh = {

                        series: [{
                            name: 'Jumlah Audit',
                            data: statusTarikh
                        }],

                        chart: {

                            type: 'bar',
                            height: 350,

                            toolbar: {
                                show: false
                            }

                        },

                        plotOptions: {

                            bar: {

                                horizontal: true,

                                borderRadius: 5,

                                barHeight: '50%',

                                dataLabels: {
                                    position: 'center'
                                }

                            }

                        },

                        dataLabels: {

                            enabled: true,

                            formatter: function(value) {
                                return value;
                            }

                        },

                        xaxis: {

                            categories: [
                                'Sebelum Tarikh Pelan',
                                'Mengikut Tarikh Pelan',
                                'Selepas Tarikh Pelan'
                            ],

                            min: 0,

                            labels: {

                                formatter: function(value) {
                                    return Math.round(value);
                                }

                            }

                        },

                        grid: {

                            strokeDashArray: 4

                        },

                        tooltip: {

                            y: {

                                formatter: function(value) {
                                    return value + ' Audit';
                                }

                            }

                        }

                    };


                    const statusTarikhElement =
                        document.querySelector('#chartStatusTarikh');

                    if (statusTarikhElement) {

                        new ApexCharts(
                            statusTarikhElement,
                            optionsStatusTarikh
                        ).render();

                    }

                    /**
                     * ============================================================
                     * PRESTASI MENGIKUT KLAUSA TEMPLATE
                     * ============================================================
                     *
                     * Klausa di sini datang daripada:
                     *
                     * audit_templates.klausa
                     *
                     */

                    const chartKlausaTemplate =
                        @json($chartKlausaTemplate);

                    const chartKlausaTemplateAkur =
                        @json($chartKlausaTemplateAkur);

                    const chartKlausaTemplateTidakAkur =
                        @json($chartKlausaTemplateTidakAkur);

                    const chartKlausaTemplateTidakBerkaitan =
                        @json($chartKlausaTemplateTidakBerkaitan);


                    /*
                    |--------------------------------------------------------------------------
                    | OPTIONS
                    |--------------------------------------------------------------------------
                    */

                    const optionsPrestasiKlausaTemplate = {

                        /*
                        |--------------------------------------------------------------------------
                        | SERIES
                        |--------------------------------------------------------------------------
                        */

                        series: [

                            {
                                name: 'AKUR',
                                data: chartKlausaTemplateAkur
                            },

                            {
                                name: 'TIDAK AKUR',
                                data: chartKlausaTemplateTidakAkur
                            },

                            {
                                name: 'TIDAK BERKAITAN',
                                data: chartKlausaTemplateTidakBerkaitan
                            }

                        ],


                        /*
                        |--------------------------------------------------------------------------
                        | WARNA
                        |--------------------------------------------------------------------------
                        */

                        colors: [

                            '#198754',

                            '#dc3545',

                            '#6c757d'

                        ],


                        /*
                        |--------------------------------------------------------------------------
                        | CHART
                        |--------------------------------------------------------------------------
                        */

                        chart: {

                            type: 'bar',

                            height: Math.max(
                                320,
                                chartKlausaTemplate.length * 100
                            ),

                            toolbar: {

                                show: false

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | BAR
                        |--------------------------------------------------------------------------
                        */

                        plotOptions: {

                            bar: {

                                horizontal: true,

                                borderRadius: 4,

                                barHeight: '65%'

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | DATA LABEL
                        |--------------------------------------------------------------------------
                        */

                        dataLabels: {

                            enabled: true,

                            formatter: function(value) {

                                if (value === 0) {

                                    return '';

                                }

                                return value;

                            },

                            style: {

                                fontSize: '11px',

                                fontWeight: 'bold',

                                colors: [

                                    '#ffffff'

                                ]

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | X AXIS
                        |--------------------------------------------------------------------------
                        */

                        xaxis: {

                            categories: chartKlausaTemplate,

                            min: 0,

                            forceNiceScale: true,

                            title: {

                                text: 'Bilangan Senarai Semak'

                            },

                            labels: {

                                formatter: function(value) {

                                    return Math.round(value);

                                }

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | Y AXIS
                        |--------------------------------------------------------------------------
                        */

                        yaxis: {

                            labels: {

                                maxWidth: 180,

                                style: {

                                    fontSize: '11px'

                                }

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | LEGEND
                        |--------------------------------------------------------------------------
                        */

                        legend: {

                            show: true,

                            position: 'top',

                            horizontalAlign: 'center'

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | GRID
                        |--------------------------------------------------------------------------
                        */

                        grid: {

                            strokeDashArray: 4

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | TOOLTIP
                        |--------------------------------------------------------------------------
                        */

                        tooltip: {

                            shared: true,

                            intersect: false,

                            y: {

                                formatter: function(value) {

                                    return value + ' Senarai Semak';

                                }

                            }

                        }

                    };


                    /*
                    |--------------------------------------------------------------------------
                    | RENDER
                    |--------------------------------------------------------------------------
                    */

                    const prestasiKlausaTemplateElement =
                        document.querySelector(
                            '#chartPrestasiKlausaTemplate'
                        );


                    if (
                        prestasiKlausaTemplateElement &&
                        chartKlausaTemplate.length > 0
                    ) {

                        new ApexCharts(

                            prestasiKlausaTemplateElement,

                            optionsPrestasiKlausaTemplate

                        ).render();

                    }


                    /*
                    ============================================================
                    2.4 PRESTASI SENARAI SEMAK
                    ============================================================
                    */

                    const chartKlausa = @json($chartKlausa);

                    const chartAkur = @json($chartAkur);

                    const chartTidakAkur = @json($chartTidakAkur);

                    const chartTidakBerkaitan = @json($chartTidakBerkaitan);


                    /*
                    |--------------------------------------------------------------------------
                    | PASTIKAN ADA DATA
                    |--------------------------------------------------------------------------
                    */

                    if (chartKlausa.length === 0) {

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | OPTIONS
                    |--------------------------------------------------------------------------
                    */

                    const optionsPrestasiChecklist = {

                        series: [

                            {
                                name: 'AKUR',
                                data: chartAkur
                            },

                            {
                                name: 'TIDAK AKUR',
                                data: chartTidakAkur
                            },

                            {
                                name: 'TIDAK BERKAITAN',
                                data: chartTidakBerkaitan
                            }

                        ],


                        chart: {

                            type: 'bar',

                            height: 400,

                            toolbar: {
                                show: false
                            }

                        },


                        plotOptions: {

                            bar: {

                                horizontal: false,

                                columnWidth: '60%',

                                borderRadius: 4,

                                dataLabels: {
                                    position: 'top'
                                }

                            }

                        },


                        dataLabels: {

                            enabled: true,

                            formatter: function(value) {

                                if (value === 0) {
                                    return '';
                                }

                                return value;

                            },

                            offsetY: -20,

                            style: {

                                fontSize: '12px',

                                colors: [
                                    '#304758'
                                ]

                            }

                        },


                        stroke: {

                            show: true,

                            width: 2,

                            colors: [
                                'transparent'
                            ]

                        },


                        xaxis: {

                            categories: chartKlausa,

                            title: {
                                text: 'Klausa'
                            }

                        },


                        yaxis: {

                            min: 0,

                            forceNiceScale: true,

                            title: {
                                text: 'Bilangan Senarai Semak'
                            },

                            labels: {

                                formatter: function(value) {

                                    return Math.round(value);

                                }

                            }

                        },


                        legend: {

                            position: 'top',

                            horizontalAlign: 'center'

                        },


                        grid: {

                            strokeDashArray: 4

                        },


                        tooltip: {

                            shared: true,

                            intersect: false,

                            y: {

                                formatter: function(value) {

                                    return value + ' Senarai Semak';

                                }

                            }

                        }

                    };


                    /*
                    |--------------------------------------------------------------------------
                    | RENDER
                    |--------------------------------------------------------------------------
                    */

                    const chartElement =
                        document.querySelector('#chartPrestasiChecklist');


                    if (chartElement) {

                        new ApexCharts(
                            chartElement,
                            optionsPrestasiChecklist
                        ).render();

                    }

                });
            </script>
        @endpush

    </div>
@endsection

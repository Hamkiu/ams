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
          2.4 PRESTASI SENARAI SEMAK
      ====================================================== --}}
            <div class="col-12 col-xl-6 d-flex align-items-stretch">

                <div class="card w-100 rounded-4">

                    <div class="card-body">

                        <div class="d-flex align-items-start justify-content-between mb-4">

                            <div>

                                <h5 class="mb-1 fw-bold">
                                    Prestasi Senarai Semak Audit
                                </h5>

                                <p class="mb-0 text-muted">
                                    Keseluruhan keputusan checklist tahun {{ $tahun }}
                                </p>

                            </div>

                            <div
                                class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-10">
                                <span class="material-icons-outlined text-info">
                                    fact_check
                                </span>
                            </div>

                        </div>

                        <div id="chartPrestasiChecklist"></div>


                        <div class="row mt-3 text-center">

                            <div class="col-4">

                                <h5 class="mb-0 text-success">
                                    {{ $akur }}
                                </h5>

                                <small class="text-muted">
                                    Akur
                                </small>

                            </div>


                            <div class="col-4">

                                <h5 class="mb-0 text-danger">
                                    {{ $tidakAkur }}
                                </h5>

                                <small class="text-muted">
                                    Tidak Akur
                                </small>

                            </div>


                            <div class="col-4">

                                <h5 class="mb-0 text-secondary">
                                    {{ $tidakBerkaitan }}
                                </h5>

                                <small class="text-muted">
                                    Tidak Berkaitan
                                </small>

                            </div>

                        </div>

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

                        series: statusKesiapan,

                        labels: [
                            'BELUM BERMULA',
                            'DALAM PROSES',
                            'MENUNGGU KESIMPULAN',
                            'MENUNGGU ULASAN',
                            'SELESAI'
                        ],

                        chart: {

                            type: 'donut',
                            height: 350

                        },

                        legend: {

                            position: 'bottom'

                        },

                        dataLabels: {

                            enabled: true

                        },

                        plotOptions: {

                            pie: {

                                donut: {

                                    size: '68%',

                                    labels: {

                                        show: true,

                                        name: {
                                            show: true
                                        },

                                        value: {
                                            show: true
                                        },

                                        total: {

                                            show: true,

                                            label: 'Jumlah Audit',

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

                        tooltip: {

                            y: {

                                formatter: function(value) {
                                    return value + ' Audit';
                                }

                            }

                        },

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


                    /*
                    ============================================================
                    2.4 PRESTASI SENARAI SEMAK
                    ============================================================
                    */

                    const optionsPrestasiChecklist = {

                        series: [

                            {
                                name: 'Jumlah',
                                data: [
                                    {{ $akur }},
                                    {{ $tidakAkur }},
                                    {{ $tidakBerkaitan }}
                                ]
                            }

                        ],

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

                                distributed: true

                            }

                        },

                        dataLabels: {

                            enabled: true,

                            style: {
                                fontSize: '13px'
                            }

                        },

                        legend: {

                            show: false

                        },

                        xaxis: {

                            categories: [
                                'AKUR',
                                'TIDAK AKUR',
                                'TIDAK BERKAITAN'
                            ]

                        },

                        yaxis: {

                            min: 0,

                            forceNiceScale: true,

                            title: {
                                text: 'Bilangan Checklist'
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
                                    return value + ' Checklist';
                                }

                            }

                        }

                    };


                    const prestasiChecklistElement =
                        document.querySelector('#chartPrestasiChecklist');

                    if (prestasiChecklistElement) {

                        new ApexCharts(
                            prestasiChecklistElement,
                            optionsPrestasiChecklist
                        ).render();

                    }

                });
            </script>
        @endpush

    </div>
@endsection

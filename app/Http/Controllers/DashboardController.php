<?php

namespace App\Http\Controllers;

use App\Models\AuditGroups;
use App\Models\AuditAnswerChecklists;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN DIPILIH
        |--------------------------------------------------------------------------
        */

        $tahun = $request->get('tahun', date('Y'));

        /*
        |--------------------------------------------------------------------------
        | SENARAI TAHUN
        |--------------------------------------------------------------------------
        */

        $senaraiTahun = AuditGroups::whereNotNull('tarikh')
            ->selectRaw('YEAR(tarikh) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        /*
        |--------------------------------------------------------------------------
        | SUMMARY CARD
        |--------------------------------------------------------------------------
        */

        $jumlahAudit = AuditGroups::whereYear('tarikh', $tahun)
            ->count();

        $belumBermula = AuditGroups::whereYear('tarikh', $tahun)
            ->where('status', 'BELUM BERMULA')
            ->count();

        $dalamProses = AuditGroups::whereYear('tarikh', $tahun)
            ->where('status', 'DALAM PROSES')
            ->count();

        $menungguKesimpulan = AuditGroups::whereYear('tarikh', $tahun)
            ->where('status', 'MENUNGGU KESIMPULAN')
            ->count();

        $menungguUlasan = AuditGroups::whereYear('tarikh', $tahun)
            ->where('status', 'MENUNGGU ULASAN')
            ->count();

        $selesai = AuditGroups::whereYear('tarikh', $tahun)
            ->where('status', 'SELESAI')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | 2.1 BILANGAN AUDIT MENGIKUT TAHUN
        |--------------------------------------------------------------------------
        */

        $auditMengikutTahun = AuditGroups::whereNotNull('tarikh')
            ->selectRaw('YEAR(tarikh) as tahun, COUNT(*) as jumlah')
            ->groupByRaw('YEAR(tarikh)')
            ->orderByRaw('YEAR(tarikh)')
            ->get();

        $chartTahun = $auditMengikutTahun
            ->pluck('tahun')
            ->map(fn($item) => (string) $item)
            ->values();

        $chartJumlahAudit = $auditMengikutTahun
            ->pluck('jumlah')
            ->map(fn($item) => (int) $item)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | 2.2 STATUS KESIAPAN AUDIT
        |--------------------------------------------------------------------------
        */

        $statusKesiapan = [
            'BELUM BERMULA' => $belumBermula,
            'DALAM PROSES' => $dalamProses,
            'MENUNGGU KESIMPULAN' => $menungguKesimpulan,
            'MENUNGGU ULASAN' => $menungguUlasan,
            'SELESAI' => $selesai,
        ];

        /*
        |--------------------------------------------------------------------------
        | 2.3 STATUS TARIKH AUDIT MENGIKUT PERANCANGAN
        |--------------------------------------------------------------------------
        |
        | tarikh     = Tarikh Pelan Audit
        | started_at = Tarikh Audit Sebenar Bermula
        |
        */

        $sebelumTarikh = AuditGroups::whereYear('tarikh', $tahun)
            ->whereNotNull('started_at')
            ->whereRaw('DATE(started_at) < DATE(tarikh)')
            ->count();

        $mengikutTarikh = AuditGroups::whereYear('tarikh', $tahun)
            ->whereNotNull('started_at')
            ->whereRaw('DATE(started_at) = DATE(tarikh)')
            ->count();

        $selepasTarikh = AuditGroups::whereYear('tarikh', $tahun)
            ->whereNotNull('started_at')
            ->whereRaw('DATE(started_at) > DATE(tarikh)')
            ->count();

        $statusTarikh = [
            'SEBELUM TARIKH PELAN AUDIT' => $sebelumTarikh,
            'MENGIKUT TARIKH PELAN AUDIT' => $mengikutTarikh,
            'SELEPAS TARIKH PELAN AUDIT' => $selepasTarikh,
        ];

        /*
        |--------------------------------------------------------------------------
        | 2.4 PRESTASI SENARAI SEMAK AUDIT
        |--------------------------------------------------------------------------
        |
        | Ambil checklist berdasarkan audit group bagi tahun dipilih.
        |
        */

        $prestasiChecklist = AuditAnswerChecklists::query()
            ->join(
                'audit_answers',
                'audit_answer_checklists.audit_answer_id',
                '=',
                'audit_answers.id'
            )
            ->join(
                'audit_groups',
                'audit_answers.audit_group_id',
                '=',
                'audit_groups.id'
            )
            ->whereYear('audit_groups.tarikh', $tahun)
            ->select(
                'audit_answer_checklists.status',
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('audit_answer_checklists.status')
            ->pluck('jumlah', 'status');

        $akur = (int) ($prestasiChecklist['AKUR'] ?? 0);
        $tidakAkur = (int) ($prestasiChecklist['TIDAK AKUR'] ?? 0);
        $tidakBerkaitan = (int) ($prestasiChecklist['TIDAK BERKAITAN'] ?? 0);

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'tahun',
            'senaraiTahun',

            'jumlahAudit',
            'belumBermula',
            'dalamProses',
            'menungguKesimpulan',
            'menungguUlasan',
            'selesai',

            'chartTahun',
            'chartJumlahAudit',

            'statusKesiapan',

            'statusTarikh',

            'akur',
            'tidakAkur',
            'tidakBerkaitan'
        ));
    }
}

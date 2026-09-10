<?php

namespace App\Http\Controllers;

use App\Models\AuditGroups;
use App\Models\AuditTemplate;
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
        | Struktur:
        |
        | Template
        |    ↓
        | Klausa
        |    ↓
        | Group
        |    ↓
        | AKUR / TIDAK AKUR / TIDAK BERKAITAN
        |
        */

        $templateId = $request->get('template_id');
        $groupId = $request->get('group_id');


        /*
        |--------------------------------------------------------------------------
        | SENARAI TEMPLATE
        |--------------------------------------------------------------------------
        */

        $senaraiTemplate = AuditTemplate::whereHas('items.answers.auditGroup', function ($query) use ($tahun) {

            $query->whereYear('tarikh', $tahun)
                ->where('status', 'SELESAI');
        })
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DEFAULT TEMPLATE
        |--------------------------------------------------------------------------
        |
        | Jika user belum pilih template, ambil template pertama yang mempunyai
        | audit pada tahun tersebut.
        |
        */

        if (!$templateId && $senaraiTemplate->isNotEmpty()) {

            $templateId = $senaraiTemplate->first()->id;
        }


        /*
        |--------------------------------------------------------------------------
        | SENARAI GROUP
        |--------------------------------------------------------------------------
        |
        | Group hanya berdasarkan template dan tahun yang sedang dipilih.
        |
        */

        $senaraiGroup = AuditGroups::query()
            ->whereYear('tarikh', $tahun)
            ->where('status', 'SELESAI')
            ->when($templateId, function ($query) use ($templateId) {

                $query->where('audit_template_id', $templateId);
            })
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | QUERY PRESTASI CHECKLIST
        |--------------------------------------------------------------------------
        */

        $prestasiQuery = AuditAnswerChecklists::query()

            /*
            |--------------------------------------------------------------------------
            | CHECKLIST ANSWER → AUDIT ANSWER
            |--------------------------------------------------------------------------
            */

            ->join(
                'audit_answers',
                'audit_answer_checklists.audit_answer_id',
                '=',
                'audit_answers.id'
            )

            /*
        |--------------------------------------------------------------------------
        | AUDIT ANSWER → AUDIT GROUP
        |--------------------------------------------------------------------------
        */

            ->join(
                'audit_groups',
                'audit_answers.audit_group_id',
                '=',
                'audit_groups.id'
            )

            /*
        |--------------------------------------------------------------------------
        | AUDIT ANSWER → TEMPLATE ITEM / KLAUSA
        |--------------------------------------------------------------------------
        */

            ->join(
                'audit_template_items',
                'audit_answers.audit_item_id',
                '=',
                'audit_template_items.id'
            )

            /*
        |--------------------------------------------------------------------------
        | TEMPLATE ITEM → TEMPLATE
        |--------------------------------------------------------------------------
        */

            ->join(
                'audit_templates',
                'audit_template_items.audit_template_id',
                '=',
                'audit_templates.id'
            )

            /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN &  HANYA GROUP YANG TELAH SELESAI
        |--------------------------------------------------------------------------
        */

            ->whereYear(
                'audit_groups.tarikh',
                $tahun
            )
            ->where(
                'audit_groups.status',
                'SELESAI'
            )

            /*
        |--------------------------------------------------------------------------
        | FILTER TEMPLATE
        |--------------------------------------------------------------------------
        */

            ->when($templateId, function ($query) use ($templateId) {

                $query->where(
                    'audit_templates.id',
                    $templateId
                );
            })

            /*
            
        |--------------------------------------------------------------------------
        | FILTER GROUP
        |--------------------------------------------------------------------------
        */

            ->when($groupId, function ($query) use ($groupId) {

                $query->where(
                    'audit_groups.id',
                    $groupId
                );
            })

            /*
        |--------------------------------------------------------------------------
        | SELECT
        |--------------------------------------------------------------------------
        */

            ->select(

                'audit_template_items.id as item_id',

                'audit_template_items.no_klausa',

                'audit_template_items.klausa',

                'audit_answer_checklists.status',

                DB::raw('COUNT(*) as jumlah')

            )

            /*
        |--------------------------------------------------------------------------
        | GROUP BY
        |--------------------------------------------------------------------------
        */

            ->groupBy(

                'audit_template_items.id',

                'audit_template_items.no_klausa',

                'audit_template_items.klausa',

                'audit_answer_checklists.status'

            )

            /*
        |--------------------------------------------------------------------------
        | ORDER
        |--------------------------------------------------------------------------
        */

            ->orderBy('audit_template_items.sort')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | SUSUN DATA MENGIKUT KLAUSA
        |--------------------------------------------------------------------------
        */

        $prestasiKlausa = [];

        foreach ($prestasiQuery as $row) {

            $key = $row->item_id;

            if (!isset($prestasiKlausa[$key])) {

                $prestasiKlausa[$key] = [

                    'no_klausa' => $row->no_klausa,

                    'klausa' => $row->klausa,

                    'AKUR' => 0,

                    'TIDAK AKUR' => 0,

                    'TIDAK BERKAITAN' => 0,

                ];
            }


            if (array_key_exists($row->status, $prestasiKlausa[$key])) {

                $prestasiKlausa[$key][$row->status] = (int) $row->jumlah;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK APEXCHART
        |--------------------------------------------------------------------------
        */

        $chartKlausa = [];
        $chartAkur = [];
        $chartTidakAkur = [];
        $chartTidakBerkaitan = [];


        foreach ($prestasiKlausa as $item) {

            /*
        |--------------------------------------------------------------------------
        | LABEL KLAUSA
        |--------------------------------------------------------------------------
        */

            $label = $item['no_klausa'];

            if (!$label) {

                $label = $item['klausa'];
            }

            $chartKlausa[] = $label;


            /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

            $chartAkur[] = $item['AKUR'];

            $chartTidakAkur[] = $item['TIDAK AKUR'];

            $chartTidakBerkaitan[] = $item['TIDAK BERKAITAN'];
        }


        /*
        |--------------------------------------------------------------------------
        | JUMLAH KESELURUHAN
        |--------------------------------------------------------------------------
        */

        $akur = array_sum($chartAkur);

        $tidakAkur = array_sum($chartTidakAkur);

        $tidakBerkaitan = array_sum($chartTidakBerkaitan);

        /*
|--------------------------------------------------------------------------
| PRESTASI MENGIKUT KLAUSA TEMPLATE
|--------------------------------------------------------------------------
|
| Klausa yang digunakan ialah:
|
| audit_templates.klausa
|
| BUKAN:
| audit_template_items.klausa
|
| Semua template yang mempunyai klausa yang sama akan digabungkan.
| Hanya audit group berstatus SELESAI bagi tahun yang dipilih.
|
*/

        $prestasiKlausaTemplateQuery = AuditAnswerChecklists::query()

            /*
|--------------------------------------------------------------------------
| CHECKLIST ANSWER -> AUDIT ANSWER
|--------------------------------------------------------------------------
*/

            ->join(
                'audit_answers',
                'audit_answer_checklists.audit_answer_id',
                '=',
                'audit_answers.id'
            )

            /*
|--------------------------------------------------------------------------
| AUDIT ANSWER -> AUDIT GROUP
|--------------------------------------------------------------------------
*/

            ->join(
                'audit_groups',
                'audit_answers.audit_group_id',
                '=',
                'audit_groups.id'
            )

            /*
|--------------------------------------------------------------------------
| AUDIT GROUP -> AUDIT TEMPLATE
|--------------------------------------------------------------------------
*/

            ->join(
                'audit_templates',
                'audit_groups.audit_template_id',
                '=',
                'audit_templates.id'
            )

            /*
|--------------------------------------------------------------------------
| FILTER TAHUN
|--------------------------------------------------------------------------
*/

            ->whereYear(
                'audit_groups.tarikh',
                $tahun
            )

            /*
|--------------------------------------------------------------------------
| HANYA AUDIT SELESAI
|--------------------------------------------------------------------------
*/

            ->where(
                'audit_groups.status',
                'SELESAI'
            )

            /*
|--------------------------------------------------------------------------
| KLAUSA TEMPLATE MESTI ADA
|--------------------------------------------------------------------------
*/

            ->whereNotNull(
                'audit_templates.klausa'
            )

            ->where(
                'audit_templates.klausa',
                '!=',
                ''
            )

            /*
|--------------------------------------------------------------------------
| SELECT
|--------------------------------------------------------------------------
*/

            ->select(
                'audit_templates.klausa',
                'audit_answer_checklists.status',
                DB::raw('COUNT(*) as jumlah')
            )

            /*
|--------------------------------------------------------------------------
| GROUP BERDASARKAN KLAUSA TEMPLATE
|--------------------------------------------------------------------------
*/

            ->groupBy(
                'audit_templates.klausa',
                'audit_answer_checklists.status'
            )

            ->orderBy(
                'audit_templates.klausa'
            )

            ->get();


        /*
|--------------------------------------------------------------------------
| SUSUN DATA PRESTASI MENGIKUT KLAUSA TEMPLATE
|--------------------------------------------------------------------------
*/

        $prestasiKlausaTemplate = [];


        foreach ($prestasiKlausaTemplateQuery as $row) {

            $klausaTemplate = trim($row->klausa);


            /*
|--------------------------------------------------------------------------
| INITIAL VALUE
|--------------------------------------------------------------------------
*/

            if (!isset($prestasiKlausaTemplate[$klausaTemplate])) {

                $prestasiKlausaTemplate[$klausaTemplate] = [

                    'klausa' => $klausaTemplate,

                    'AKUR' => 0,

                    'TIDAK AKUR' => 0,

                    'TIDAK BERKAITAN' => 0,

                ];
            }


            /*
|--------------------------------------------------------------------------
| MASUKKAN JUMLAH STATUS
|--------------------------------------------------------------------------
*/

            if (
                array_key_exists(
                    $row->status,
                    $prestasiKlausaTemplate[$klausaTemplate]
                )
            ) {

                $prestasiKlausaTemplate[$klausaTemplate][$row->status] =
                    (int) $row->jumlah;
            }
        }


        /*
|--------------------------------------------------------------------------
| DATA APEXCHART - PRESTASI MENGIKUT KLAUSA TEMPLATE
|--------------------------------------------------------------------------
*/

        $chartKlausaTemplate = [];

        $chartKlausaTemplateAkur = [];

        $chartKlausaTemplateTidakAkur = [];

        $chartKlausaTemplateTidakBerkaitan = [];


        foreach ($prestasiKlausaTemplate as $item) {

            /*
|--------------------------------------------------------------------------
| LABEL = audit_templates.klausa
|--------------------------------------------------------------------------
*/

            $chartKlausaTemplate[] =
                $item['klausa'];


            /*
|--------------------------------------------------------------------------
| NILAI
|--------------------------------------------------------------------------
*/

            $chartKlausaTemplateAkur[] =
                $item['AKUR'];

            $chartKlausaTemplateTidakAkur[] =
                $item['TIDAK AKUR'];

            $chartKlausaTemplateTidakBerkaitan[] =
                $item['TIDAK BERKAITAN'];
        }

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
            'senaraiTemplate',
            'senaraiGroup',

            'templateId',
            'groupId',

            'prestasiKlausa',

            'chartKlausa',
            'chartAkur',
            'chartTidakAkur',
            'chartTidakBerkaitan',

            'akur',
            'tidakAkur',
            'tidakBerkaitan',
            'prestasiKlausaTemplate',

            'chartKlausaTemplate',

            'chartKlausaTemplateAkur',

            'chartKlausaTemplateTidakAkur',

            'chartKlausaTemplateTidakBerkaitan'
        ));
    }
}

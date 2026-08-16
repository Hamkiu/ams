<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Models;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Models::truncate();

        $id = 1;
        $components_no = 1;
        $components = [
            'Dashboard',
            'Pengguna',
            'Audit Log',
            'Tetapan Audit',
            'Audit',
            'Ulasan Admin',
        ];

        $component_icon = [
            'Dashboard' => 'home',
            'Pengguna' => 'manage_accounts',
            'Audit Log' => 'history',
            'Tetapan Audit' => 'admin_panel_settings',
            'Audit' => 'fact_check',
            'Ulasan Admin' => 'cases',
        ];

        $sub_components = [];
        //Dashboard
        array_push($sub_components, ['sub_components_name' => 'Dashboard', 'sub_components' => 'dashboard', 'route' => 'dashboard', 'components_no' => 1]);

        //Pengguna
        array_push($sub_components, ['sub_components_name' => 'Pengguna', 'sub_components' => 'user', 'route' => 'user', 'components_no' => 2]);

        //Audit Log
        array_push($sub_components, ['sub_components_name' => 'Audit Log', 'sub_components' => 'audittrail', 'route' => 'audittrail', 'components_no' => 3]);

        // Tetapan Audit
        array_push($sub_components, ['sub_components_name' => 'Audit Template', 'sub_components' => 'audittemplate', 'route' => 'audittemplate', 'components_no' => 4]);
        array_push($sub_components, ['sub_components_name' => 'Audit Group', 'sub_components' => 'auditgroup', 'route' => 'auditgroup', 'components_no' => 4]);

        // Audit
        array_push($sub_components, ['sub_components_name' => 'Senarai Audit', 'sub_components' => 'audit', 'route' => 'audit', 'components_no' => 5]);

        // Ulasan Admin
        array_push($sub_components, ['sub_components_name' => 'Senarai Group', 'sub_components' => 'auditadminreview', 'route' => 'auditadminreview', 'components_no' => 6]);

        foreach ($components as $component) {
            $sub_component_no = 1;
            foreach ($sub_components as $sub_component) {
                if ($sub_component['components_no'] == $components_no) {
                    Models::create([
                        'id' => $id,
                        'module' => 'Administrator',
                        'component_no' => $components_no,
                        'components' => $component,
                        'sub_components_no' => $sub_component_no,
                        'sub_components_name' => $sub_component['sub_components_name'],
                        'sub_components' => $sub_component['sub_components'],
                        'route' => $sub_component['route'],
                        'comp_icon' => $component_icon[$component],
                        // 'icon'=>$sub_component['icon']
                    ]);
                    $id++;
                    $sub_component_no++;
                }
            }
            $components_no++;
        }
    }
}

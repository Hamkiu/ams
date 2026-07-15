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
            'Audit Log',
        ];

        $component_icon = [
            'Dashboard' => 'home',
            'Audit Log' => 'widgets',

        ];

        $sub_components = [];

        array_push($sub_components, ['sub_components_name' => 'Dashboard', 'sub_components' => 'dashboard', 'route' => 'dashboard', 'components_no' => 1]);
        array_push($sub_components, ['sub_components_name' => 'Audit Log', 'sub_components' => 'audittrail', 'route' => 'audittrail', 'components_no' => 2]);


        
        foreach($components as $component) {
            $sub_component_no = 1;
            foreach($sub_components as $sub_component) {
                if($sub_component['components_no'] == $components_no) {
                    Models::create([
                        'id'=>$id,
                        'module'=>'Administrator',
                        'component_no'=>$components_no,
                        'components'=>$component,
                        'sub_components_no'=>$sub_component_no,
                        'sub_components_name'=>$sub_component['sub_components_name'],
                        'sub_components'=>$sub_component['sub_components'],
                        'route'=>$sub_component['route'],
                        'comp_icon'=>$component_icon[$component],
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

<?php
    if (!function_exists('encode')) {
        function encode($param) {
            $encoded = \Illuminate\Support\Facades\Crypt::encryptString($param);

            return $encoded;
        }
    }

    if (!function_exists('decode')) {
        function decode($param) {
            $decoded = \Illuminate\Support\Facades\Crypt::decryptString($param);

            return $decoded;
        }
    }

    if (!function_exists('generateId')) {
        function generateId($prefix, $table, $field) {
            $id = \Illuminate\Support\Facades\DB::select("SELECT concat(concat('".$prefix."',date_format(curdate(),'%Y%m%d')),right(concat('00000',cast((right(IFNULL(max(".$field."),0),5) + 1) as char)),5)) as 'ID'
                                FROM `".$table."`
                                WHERE substring(".$field.", 3 ,8) = date_format(curdate(),'%Y%m%d')
                                AND ".$field." != '';");

            return $id[0]->ID;
        }
    }

    if (!function_exists('auditTrail')) {
        function auditTrail($action, $module, $component, $id, $name, $user_id, $forAuth = '') {
            $audittrail = new \App\Models\AuditTrail();
             // fallback: kalau $user_id tak diberi, ambil dari session
            if (is_null($user_id)) {
                $user_id = session('enakmen_user_id');   // ikut nama key session awak
            }

            $audittrail->audit_trail_id = generateId('AT','audit_trails','audit_trail_id');
            $audittrail->audit_trail_module = $module;
            $audittrail->audit_trail_component = $component;
            $audittrail->audit_trail_action = $action;
            $audittrail->audit_trail_data_id = $id;
            $audittrail->audit_trail_data_name = $name;
            $audittrail->audit_trail_desc = $action.' row '.$name.' with id '.$id.' on '.$component.' ('.$module.')';

            if (!empty($forAuth) && $forAuth == 'y') {
                $audittrail->audit_trail_desc = $name . ' with id ' .  $user_id . ' did ' .  $component;
            }
            
            $audittrail->users_id = $user_id;
            $audittrail->save();
        }
    }

?>
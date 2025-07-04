<?php

namespace App\Http\Services;
use App\Models\Incident;
use App\Models\Complaint;
use App\Models\Sla;
use App\Http\Resources\IncidentResources;

class IncidentServices
{
    public static function create($data){

        if(!isset($data['complaint_id'])){

            $complaint = Complaint::create($data);

            $data['complaint_id'] =  $complaint->id;
        }

        $get_sla = Sla::where('category_id',$data['category_id'])->where('branch_id',$data['branch_id'])->first();


        $data['start_date'] = date('Y-m-d H:i:s');
        $data['incident_no'] = 'YMT-SDASDADA';
        $data['code_sla'] = $get_sla?->code;

        $create = Incident::create($data);

        $return = new IncidentResources($create);

        return $return;
    }

    public static function update(Incident $incident,$data){

        $create = $incident->update($data);

        $return = new IncidentResources($incident);

        return $return;
    }
} 
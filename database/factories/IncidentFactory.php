<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sla;
use App\Models\SlaVersion;
use App\Models\Complaint;
use App\Models\KnowledgeBase;
use App\Models\Group;
use App\Models\User;
use App\Models\Incident;
use App\Models\IncidentResolution;
use App\Models\Workbasket;
use App\Http\Services\IncidentServices;
use Carbon\Carbon;

class IncidentFactory extends Factory
{
    public function definition(): array{
        $sla = Sla::inRandomOrder()->first();
        $sla_version = SlaVersion::where('sla_template_id',$sla?->sla_template_id)->orderBy('version','desc')->first();
        $complaint = Complaint::inRandomOrder()->first();
        $knowledgebase = KnowledgeBase::inRandomOrder()->first();
        $group = Group::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        $branch_id = json_decode($sla?->branch_id, true); 
        $random_branch = $branch_id[array_rand($branch_id)];

        $data['code_sla'] = $sla?->code;
        $data['incident_date'] = Carbon::parse(fake()->dateTimeBetween('-2 month', 'now'));
        $data['barcode'] = fake()->numerify('############');
        $data['branch_id'] = $random_branch;
        $data['category_id'] = $sla?->category_id;
        $data['complaint_id'] = $complaint?->id;
        $data['information'] = $knowledgebase?->solution.' '.fake()->sentence(20);
        $data['knowledge_base_id'] = $knowledgebase?->id;
        $data['received_via'] = fake()->numberBetween(1,4);
        $data['report_no'] = fake()->numerify('############');
        $data['incident_asset_type'] = fake()->numberBetween(1,2);
        $data['date_asset_loss'] = fake()->dateTimeBetween('-1 month', 'now');
        $data['date_report_police'] = fake()->dateTimeBetween('-1 month', 'now');
        $data['report_police_no'] = fake()->numerify('############');
        $data['asset_siri_no'] = fake()->numerify('############');
        $data['group_id'] = $group?->id;
        $data['operation_user_id'] = $user?->id;
        $data['sla_version_id'] = $sla_version?->id;
        $data['expected_end_date'] = IncidentServices::calculateDueDateIncident($data);

        $data['service_recipient_id'] =  $user?->id;
        

        return $data;
    }

    public function configure(){
        return $this->afterCreating(function (Incident $incident) {
            $user = User::inRandomOrder()->first();

            IncidentServices::createResolution($incident->id);

            $data_reso['incident_id'] = $incident->id;
            $data_reso['action_codes'] = 'UPDT';

            IncidentResolution::create($data_reso);

            if($incident->status == Incident::OPEN){
                IncidentServices::createWorkbasket($incident->id);
            }
            elseif($incident->status == Incident::RESOLVED){
                $data_reso['action_codes'] = 'ACTR';
                $data_reso['notes'] = fake()->sentence(20);
                $data_reso['solution_notes'] = fake()->sentence(20);

                IncidentResolution::create($data_reso);
            }
            elseif($incident->status == Incident::CLOSED){
                $data_reso['action_codes'] = 'CLSD';
                $data_reso['notes'] = fake()->sentence(20);

                IncidentResolution::create($data_reso);
                $endDate      = (clone $incident->incident_date)->addMonth();

                $data_update['actual_end_date'] = fake()->dateTimeBetween($incident->incident_date, $endDate)->format('Y-m-d H:i:s');
                $data_update['resolved_user_id'] = $user?->id;

                $incident->update($data_update);

            }
            elseif($incident->status == Incident::CANCEL_DUPLICATE){
                
                $data_reso['action_codes'] = 'CLSD';
                $data_reso['notes'] = fake()->sentence(20);

                IncidentResolution::create($data_reso);
            }
            else{
                $data_reso['action_codes'] = 'ESCL';
                $data_reso['notes'] = fake()->sentence(20);

                IncidentResolution::create($data_reso);

                $data_workbasket['date'] = date('Y-m-d H:i:s');
                $data_workbasket['incident_id'] = $incident->id;
                $data_workbasket['handle_by'] = $incident->operation_user_id;
                
                Workbasket::create($data_workbasket);
            }
        });
    }
}

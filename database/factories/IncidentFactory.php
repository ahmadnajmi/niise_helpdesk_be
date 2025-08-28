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
use App\Http\Services\IncidentServices;

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
        $data['incident_date'] = fake()->dateTimeBetween('-1 year','now');
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
        $data['end_date'] = IncidentServices::calculateDueDateIncident($data);

        return $data;
    }

    public function configure(){
        return $this->afterCreating(function (Incident $incident) {
            IncidentServices::createResolution($incident->id);
        });
    }
}

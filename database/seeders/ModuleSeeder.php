<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Imports\ModuleImport;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE MODULE_ID_SEQ RESTART START WITH 1");
        } 

        Excel::import(new ModuleImport, 'database/seeders/excel/module.xlsx');
    }

    public function createPermission($module_id,$permissions){
        $faker = Faker::create(config('app.faker_locale'));

        $permissions_description = [
            'dashboard.index' => 'Access to the main dashboard overview.',
            'dashboard.card.total-incidents' => 'View the total number of incidents on the dashboard.',
            'dashboard.card.total-sla' => 'View total SLA statistics on the dashboard.',
            'dashboard.card.total-reports' => 'View total number of reports on the dashboard.',
            'dashboard.idle-incidents.index' => 'View list of idle or unresolved incidents.',
            'dashboard.total-incidents-created.grand-total-this-year' => 'View total incidents created for the current year.',
            'dashboard.total-incidents-created.monthly.all' => 'View all users’ incidents created monthly.',
            'dashboard.total-incidents-created.monthly.self' => 'View logged-in user’s incidents created monthly.',
            'dashboard.total-incidents-created.grand-total-this-month' => 'View total incidents created for the current month.',
            'dashboard.total-incidents-created.daily.all' => 'View all users’ incidents created daily.',
            'dashboard.total-incidents-created.daily.self' => 'View logged-in user’s incidents created daily.',
            'dashboard.total-incidents-created.grand-total-today' => 'View total incidents created today.',
            'dashboard.total-incidents-created.hourly.all' => 'View all users’ incidents created hourly.',
            'dashboard.total-incidents-created.hourly.self' => 'View logged-in user’s incidents created hourly.',

            'system_administration.index' => 'General index view permission.',
            'incident_management.index' => 'General index view permission.',
            'people.index' => 'General index view permission.',
            'operations.index' => 'General index view permission.',
            'sla_management.index' => 'General index view permission.',
            'system_configuration.index' => 'General index view permission.',

            'individual.index' => 'View the list of individuals.',
            'individual.create' => 'Create a new individual.',
            'individual.view' => 'View individual details.',
            'individual.update' => 'Update individual information.',
            'individual.delete' => 'Delete an individual record.',

            'group.index' => 'View the list of groups.',
            'group.create' => 'Create a new group.',
            'group.view' => 'View group details.',
            'group.update' => 'Update group information.',
            'group.delete' => 'Delete a group.',

            'role.index' => 'View the list of roles.',
            'role.view' => 'View details of a role.',

            'calendar.index' => 'View calendar events.',
            'calendar.create' => 'Create new calendar events.',
            'calendar.view' => 'View calendar event details.',
            'calendar.update' => 'Update existing calendar events.',
            'calendar.delete' => 'Delete calendar events.',

            'operating-time.index' => 'View list of operating time entries.',
            'operating-time.create' => 'Create new operating time.',
            'operating-time.view' => 'View operating time details.',
            'operating-time.update' => 'Update existing operating time.',
            'operating-time.delete' => 'Delete operating time entries.',

            'category.index' => 'View the list of categories.',
            'category.create' => 'Create a new category.',
            'category.view' => 'View category details.',
            'category.update' => 'Update existing category.',
            'category.delete' => 'Delete a category.',

            'email-format.index' => 'View list of email templates.',
            'email-format.create' => 'Create new email template.',
            'email-format.view' => 'View email template details.',
            'email-format.update' => 'Update existing email template.',
            'email-format.delete' => 'Delete email template.',

            'sla-template.index' => 'View SLA template list.',
            'sla-template.create' => 'Create new SLA template.',
            'sla-template.view' => 'View SLA template details.',
            'sla-template.update' => 'Update SLA template.',
            'sla-template.delete' => 'Delete SLA template.',
            'sla-template.replicate'=> 'Replicate SLA template.',

            'sla.index' => 'View SLA configurations.',
            'sla.create' => 'Create new SLA.',
            'sla.view' => 'View SLA details.',
            'sla.update' => 'Update SLA configuration.',
            'sla.delete' => 'Delete SLA.',

            'global-setting.index' => 'View global system settings.',
            'global-setting.create' => 'Add new global setting.',
            'global-setting.view' => 'View global setting details.',
            'global-setting.update' => 'Update global settings.',
            'global-setting.delete' => 'Delete global setting.',

            'action-code.index' => 'View list of action codes.',
            'action-code.create' => 'Create new action code.',
            'action-code.view' => 'View action code details.',
            'action-code.update' => 'Update action code.',
            'action-code.delete' => 'Delete action code.',

            'module.index' => 'View list of system modules.',
            'module.view' => 'View module details.',
            'module.update' => 'Update module settings.',

            'incident.index.all' => 'View all incidents in the system.',
            'incident.index.self' => 'View incidents reported by the logged-in user.',
            'incident.create' => 'Create a new incident report.',
            'incident.view' => 'View incident details.',
            'incident.update' => 'Update an existing incident.',

            'audit-trail.index' => 'View system audit trails.',
            'audit-trail.view' => 'View details of specific audit trail events.',

            'report.index' => 'View available reports.',
            'report.generate' => 'Generate a new report.',

            'knowledge-base.index' => 'View the knowledge base.',
            'knowledge-base.create' => 'Add new knowledge base article.',
            'knowledge-base.view' => 'View details of a knowledge base entry.',
            'knowledge-base.update' => 'Update a knowledge base article.',
            'knowledge-base.delete' => 'Delete a knowledge base article.',

            'email-notification.index' => 'View list of email notifications.',
            'email-notification.view' => 'View details of an email notification.',
            'email-notification.receive' => 'Receive and process email notifications.',

            'contractor.index' => 'View the list of contractors in the system.',
            'contractor.create' => 'Create or register a new contractor.',
            'contractor.view' => 'View detailed information about a specific contractor.',
            'contractor.update' => 'Edit or update existing contractor information.',
            'contractor.delete' => 'Delete or deactivate a contractor from the system.',

            'operation-time.index' => 'View the list of operation time entries.',
            'operation-time.create' => 'Create or add a new operation time entry.',
            'operation-time.view' => 'View detailed information about a specific operation time.',
            'operation-time.update' => 'Edit or update existing operation time information.',
            'operation-time.delete' => 'Delete an operation time entry from the system.',

            'resolution.index' => 'View the list of resolution time entries.',
            'resolution.create' => 'Create or add a new resolution time entry.',
            'resolution.view' => 'View detailed information about a specific resolution time.',
            'resolution.update' => 'Edit or update existing resolution time information.',
            'resolution.delete' => 'Delete a resolution time entry from the system.',

            'contract.index' => 'View the list of contracts.',
            'contract.create' => 'Create or add a new contract.',
            'contract.view' => 'View detailed information about a specific contract.',
            'contract.update' => 'Edit or update existing contract information.',
            'contract.delete' => 'Delete a contract from the system.',
        ];

        foreach($permissions as $permission){
            $data['module_id'] = $module_id;
            $data['name'] = $permission;
            $data['description'] = $permissions_description[$permission];

            $create_permission = Permission::create($data);
        }
    }
}

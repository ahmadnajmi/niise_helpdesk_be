<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Permission;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('module')->truncate();
        DB::table('permissions')->truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE MODULE_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE PERMISSIONS_ID_SEQ RESTART START WITH 1");
        } 

        $faker = Faker::create('ms_My');

        $modules = [
            [
                'name' => 'Halaman Utama',
                'name_en' =>'Dashboard',
                'code' => 'dashboard',
                'description' =>'A central overview screen displaying key metrics, statistics, and summaries for quick system insights.',
                'permission' =>[
                    'dashboard.index',

                    'dashboard.card.total-incidents',
                    'dashboard.card.total-sla',
                    'dashboard.card.total-reports',

                    'dashboard.idle-incidents.index',

                    'dashboard.total-incidents-created.grand-total-this-year',
                    'dashboard.total-incidents-created.monthly.all',
                    'dashboard.total-incidents-created.monthly.self',

                    'dashboard.total-incidents-created.grand-total-this-month',
                    'dashboard.total-incidents-created.daily.all',
                    'dashboard.total-incidents-created.daily.self',

                    'dashboard.total-incidents-created.grand-total-today',
                    'dashboard.total-incidents-created.hourly.all',
                    'dashboard.total-incidents-created.hourly.self',
                ],
                'svg_path' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',

            ],
            [
                'name' => 'Pentadbiran Sistem',
                'name_en' => 'System Administration',
                'code' => 'system_administration',
                'permission' => ['index'],
                'description' =>'Controls user access, system-level settings, and configurations for managing the application.',
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />',
                'sub_module' => [
                    [
                        'name' =>'Pengurusan Orang',
                        'name_en' => 'People Management',
                        'permission' => ['index'],
                        'code' => 'people',
                        'description' =>'Manages records and data for individuals within the organization or user base.',
                        'svg_path' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                        'lower_sub_module' => [
                            [
                                'code' => 'individuals',
                                'name' => 'Pengurusan Individu',
                                'name_en' => 'Individual Management',
                                'description' =>'Handles detailed information, profiles, and settings for single users or staff members.',
                                'permission' =>[
                                    'individual.index',
                                    'individual.create',
                                    'individual.view',
                                    'individual.update',
                                    'individual.delete',
                                ]
                            ],
                            [
                                'code' => 'groups',
                                'name' => 'Pengurusan Kumpulan',
                                'name_en' => 'Group Management',
                                'description' => 'Manages grouping of users into teams, departments, or roles for permission or workflow purposes.',
                                'permission' =>[
                                    'group.index',
                                    'group.create',
                                    'group.view',
                                    'group.update',
                                    'group.delete',
                                ]
                            ],
                            [
                                'code' => 'roles',
                                'name' => 'Pengurusan Peranan',
                                'name_en' => 'Role Management',
                                'description' => 'Defines access roles and permissions, controlling what actions each role can perform.',
                                'permission' =>[
                                    'role.index',
                                    // 'role.create',
                                    'role.view',
                                    // 'role.update',
                                    // 'role.delete',
                                ]
                            ],
                            [
                                'code' => 'contractors',
                                'name' => 'Pengurusan Kontraktor',
                                'name_en' => 'Contractor Management',
                                'description' => 'Manages external personnel, vendors, or third-party contractors associated with the system.',
                                'permission' =>[
                                    'contractor.index',
                                    'contractor.create',
                                    'contractor.view',
                                    'contractor.update',
                                    'contractor.delete',
                                ]
                            ],
                        ]
                    ],

                    [
                        'code' => 'operations',
                        'name' =>'Pengurusan Operasi',
                        'name_en' =>'Operations Management',
                        'permission' => ['index'],
                        'description' => 'Oversees and configures daily system operations and workflows.',
                        'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />',
                        'lower_sub_module' => [
                            [
                                'code' => 'calendar',
                                'name' => 'Pengurusan Kalendar',
                                'name_en' =>'Calendar Management',
                                'description' => 'Allows scheduling and viewing of events, tasks, and system activities on a calendar',
                                'permission' =>[
                                    'calendar.index',
                                    'calendar.create',
                                    'calendar.view',
                                    'calendar.update',
                                    'calendar.delete',
                                ]
                            ],
                            [
                                'code' => 'operation_times',
                                'name' => 'Pengurusan Masa Operasi',
                                'name_en' =>'Operation Time Management',
                                'description' => 'Manages operational hours, business timings, and service availability rules.',
                                'permission' =>[
                                    'operation-time.index',
                                    'operation-time.create',
                                    'operation-time.view',
                                    'operation-time.update',
                                    'operation-time.delete',
                                ]
                            ],
                            [
                                'code' => 'categories',
                                'name' => 'Pengurusan Kategori',
                                'name_en' =>'Category Management',
                                'description' => 'Creates and organizes categories used throughout the system (e.g., for tickets, articles, etc.)',
                                'permission' =>[
                                    'category.index',
                                    'category.create',
                                    'category.view',
                                    'category.update',
                                    'category.delete',
                                ]
                            ],
                            [
                                'code' => 'email_templates',
                                'name' => 'Pengurusan Format Email',
                                'name_en' =>'Email Format Management',
                                'description' => 'Defines and edits templates used for automated emails sent by the system.',
                                'permission' =>[
                                    'email-format.index',
                                    'email-format.create',
                                    'email-format.view',
                                    'email-format.update',
                                    'email-format.delete',
                                ]
                            ],
                            [
                                'code' => 'branch',
                                'name' => 'Pengurusan Cawangan',
                                'name_en' =>'Branch Management',
                                'description' => 'Manage branch information, including creating, updating, viewing, and deleting branch records within the system.',
                                'permission' =>[
                                    'branch.index',
                                    'branch.create',
                                    'branch.view',
                                    'branch.update',
                                    'branch.delete',
                                ]
                            ],
                            [
                                'code' => 'customer',
                                'name' => 'Pengurusan Pelanggan',
                                'name_en' =>'Customer Management',
                                'description' => 'Manage customer profiles and related details, allowing the creation, viewing, updating, and deletion of customer records.',
                                'permission' =>[
                                    'customer.index',
                                    'customer.create',
                                    'customer.view',
                                    'customer.update',
                                    'customer.delete',
                                ]
                            ],
                        ]
                    ],

                    [
                        'code' => 'sla',
                        'name' =>'Pengurusan SLA',
                        'name_en' =>'SLA management',
                        'permission' => ['index'],
                        'description' => 'Handles Service Level Agreements (SLAs), defining time-based response and resolution expectations.',
                        'svg_path' => '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
                        'lower_sub_module' => [
                            [
                                'code' => 'sla_templates',
                                'name' => 'Tetapan Templat',
                                'name_en' =>'Template Settings',
                                'description' => 'Manages pre-defined templates for forms, reports, or workflows used in the system.',
                                'permission' =>[
                                    'sla-template.index',
                                    'sla-template.create',
                                    'sla-template.view',
                                    'sla-template.update',
                                    'sla-template.delete',
                                    'sla-template.replicate',

                                ]
                            ],
                            [
                                'code' => 'sla_settings',
                                'name' => 'Tetapan SLA',
                                'name_en' =>'SLA Settings',
                                'description' => 'Configures specific rules and timing for SLA response/resolution, escalation, and monitoring.',
                                'permission' =>[
                                    'sla.index',
                                    'sla.create',
                                    'sla.view',
                                    'sla.update',
                                    'sla.delete',
                                ]
                            ],
                        ]
                    ],

                    [
                        'code' => 'system_configuration',
                        'name' =>'Konfigurasi Sistem',
                        'name_en' =>'System Configuration',
                        'permission' => ['index'],
                        'description' => 'Advanced system settings such as time zone, language, integration endpoints, etc.',
                        'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
                        'lower_sub_module' => [
                            [
                                'code' => 'global_settings',
                                'name' => 'Tetapan Global',
                                'name_en' =>'Global Settings',
                                'description' => 'System-wide default values and configurations that affect all users or modules.',
                                'permission' =>[
                                    'global-setting.index',
                                    'global-setting.create',
                                    'global-setting.view',
                                    'global-setting.update',
                                    'global-setting.delete',
                                ]
                            ],
                            [
                                'code' => 'action_codes',
                                'name' => 'Kod Tindakan',
                                'name_en' =>'Action Codes',
                                'description' => 'Defines codes or tags used to classify actions, outcomes, or statuses in processes.',
                                'permission' =>[
                                    'action-code.index',
                                    'action-code.create',
                                    'action-code.view',
                                    'action-code.update',
                                    'action-code.delete',
                                ]
                            ],
                            [
                                'code' => 'modules',
                                'name' => 'Modul',
                                'name_en' =>'Modules',
                                'description' => 'A registry or control panel for enabling/disabling system modules or features.',
                                'permission' =>[
                                    'module.index',
                                    // 'module.create',
                                    'module.view',
                                    'module.update',
                                    // 'module.delete',
                                ]
                            ],
                            [
                                'code' => 'schedule_report',
                                'name' => 'Schedule Report',
                                'name_en' =>'Schedule Report',
                                'description' => 'Manage customer profiles and related details, allowing the creation, viewing, updating, and deletion of customer records.',
                                'permission' =>[
                                    'schedule_report.index',
                                    'schedule_report.create',
                                    'schedule_report.view',
                                    'schedule_report.update',
                                    'schedule_report.delete',
                                ]
                            ],
                        ]
                    ],
                ]
            ],

            [
                'code' => 'incidents',
                'name' => 'Pengurusan Insiden',
                'name_en' =>'Incident Management',
                'permission' => ['index'],
                'description' => 'Tracks and manages incidents, tickets, or issues reported by users or detected by the system.',
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />',
                'sub_module' => [
                    [
                        'code' => 'listings',
                        'name' =>'Insiden',
                        'name_en' =>'Incidents',
                        'description' => 'The actual reported incidents with details like type, status, and resolution steps.',
                        'permission' =>[
                            'incident.index.all',
                            'incident.index.self',
                            'incident.create',
                            'incident.view',
                            'incident.update',
                            // 'incident.delete',
                            'resolution.index' ,
                            'resolution.create',
                            'resolution.view' ,
                            'resolution.update',
                            'resolution.delete',

                            'contract.index',
                            'contract.create',
                            'contract.view',
                            'contract.update',
                            'contract.delete',
                        ]
                    ],
                    
                    
                ]
            ],

            [
                'code' => 'audit_trails',
                'name' => 'Jejak Audit',
                'name_en' =>'Audit Trails',
                'description' => 'Logs of user activities, changes, and system events for monitoring and security.',
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />',
                'permission' =>[
                    'audit-trail.index',
                    // 'audit-trail.create',
                    'audit-trail.view',
                    // 'audit-trail.update',
                    // 'audit-trail.delete',
                ]
            ],
            [
                'code' => 'reports',
                'name' => 'Laporan',
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />',
                'name_en' =>'Reports',
                'description' => 'Generates and displays data summaries, charts, and analytics on system usage and performance.',
                'permission' =>[
                    'report.index',
                    'report.generate',
                    // -- more from report --
                ]
            ],

            [
                'code' => 'knowledge_base',
                'name' => 'Knowledge Base',
                'name_en' =>'Knowledge Base',
                'permission' => ['index'],
                'description' => 'A collection of helpful articles, FAQs, and documentation to guide users and reduce support load.',
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z" />',
                'sub_module' => [
                    [
                        'code' => 'knowledge_entries',
                        'name' =>'Knowledge Entries',
                        'name_en' =>'Knowledge Entries',
                        'description' => 'The individual articles or content items inside the knowledge base.',
                        'permission' =>[
                            'knowledge-base.index',
                            'knowledge-base.create',
                            'knowledge-base.view',
                            'knowledge-base.update',
                            'knowledge-base.delete',
                        ]
                    ],
                ]
            ],

            // [
            //     'name' => 'Notifikasi',
            //     'name_en' =>'Notifications',
            //     'permission' => ['index'],
            //     'description' => 'Alerts and messages sent to users about system events, tasks, or updates.',
            //     'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />',
            //     'sub_module' => [
            //         [
            //             'name' =>'Emel',
            //             'name_en' =>'Email',
            //             'description' => 'Manages incoming/outgoing email messages, configurations, and logs.',
            //             'permission' =>[
            //                 'email-notification.index',
            //                 'email-notification.view',
            //                 'email-notification.receive'
            //             ]
            //         ],
            //     ]
        // ],

            [
                'name' => 'Administration',
                'name_en' =>'Administration',
                'code' => 'admin',
                'description' =>'A central overview screen displaying key metrics, statistics, and summaries for quick system insights.',
                'permission' =>[
                    'index',
                ],
                'svg_path' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
                'sub_module' => [
                    [
                        'code' => 'configuration',
                        'name' =>'Configuration Management',
                        'name_en' =>'Configuration Management',
                        'description' => 'The individual articles or content items inside the knowledge base.',
                        'permission' =>[
                            'configuration.index',
                            'configuration.create',
                            'configuration.view',
                            'configuration.update',
                            'configuration.delete',
                        ]
                    ],
                ]
            ],

        ];

        foreach($modules as $module){
            
            $create = $this->createModule($module);

            if(isset($module['permission'])) $this->createPermission($create->id,$module['permission']);

            if(isset($module['sub_module'])){
                foreach($module['sub_module'] as $sub_module){

                    $create_sub_module = $this->createModule($sub_module,$create->id);

                    if(isset($sub_module['permission'])) $this->createPermission($create_sub_module->id,$sub_module['permission']);

                    if(isset($sub_module['lower_sub_module'])){
                        foreach($sub_module['lower_sub_module'] as $lower_sub_module){

                            $create_lower_sub_module = $this->createModule($lower_sub_module,$create_sub_module->id);

                            if(isset($lower_sub_module['permission'])) $this->createPermission($create_lower_sub_module->id,$lower_sub_module['permission']);
                        }
                    }
                }
            }
        }


    }

    public function createPermission($module_id,$permissions){
        $faker = Faker::create('ms_My');

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

            'index' => 'General index view permission.',

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

            'branch.index' => 'View the list of branches.',
            'branch.create' => 'Create or add a new branch.',
            'branch.view' => 'View detailed information about a specific branch.',
            'branch.update' => 'Edit or update existing branch information.',
            'branch.delete' => 'Delete a branch from the system.',

            'customer.index' => 'View the list of customers.',
            'customer.create' => 'Create or add a new customer.',
            'customer.view' => 'View detailed information about a specific customer.',
            'customer.update' => 'Edit or update existing customer information.',
            'customer.delete' => 'Delete a customer from the system.',

            'configuration.index' => 'View the list of customers.',
            'configuration.create' => 'Create or add a new configuration.',
            'configuration.view' => 'View detailed information about a specific configuration.',
            'configuration.update' => 'Edit or update existing configuration information.',
            'configuration.delete' => 'Delete a configuration from the system.',

            'schedule_report.index' => 'View the list of customers.',
            'schedule_report.create' => 'Create or add a new schedule_report.',
            'schedule_report.view' => 'View detailed information about a specific schedule_report.',
            'schedule_report.update' => 'Edit or update existing schedule_report information.',
            'schedule_report.delete' => 'Delete a schedule_report from the system.',
            

            
        ];

        foreach($permissions as $permission){
            $data['module_id'] = $module_id;
            $data['name'] = $permission;
            $data['description'] = $permissions_description[$permission];

            $create_permission = Permission::create($data);
        }
    }

    public function createModule($data,$module_id = null){

        $data_module['module_id'] = $module_id;
        $data_module['code'] = $data['code'];
        $data_module['name'] = $data['name'];
        $data_module['name_en'] = $data['name_en'];
        $data_module['description'] = $data['description'];
        $data_module['svg_path'] =  isset($data['svg_path']) ? $data['svg_path'] : null;
        $data_module['created_by'] = 1;
        $data_module['updated_by'] =  2;

        $create = Module::create($data_module);

        return $create;
    }
}

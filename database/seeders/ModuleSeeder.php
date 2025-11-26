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

        $faker = Faker::create(config('app.faker_locale'));

        $modules = [
            [
                'name' => 'Halaman Utama',
                'name_en' =>'Dashboard',
                'code' => 'dashboard',
                'description' =>'A central overview screen displaying key metrics, statistics, and summaries for quick system insights.',
                'permission' =>[
                    'dashboard.index'
                ],
                'svg_path' => "<path transform='translate(-1.4,-1.2) scale(1.12)' fill-rule='evenodd' clip-rule='evenodd' fill='currentColor' opacity='0.5' d='M10.1259 2.21864C11.2216 1.34212 12.7784 1.34212 13.8741 2.21864L21.5041 8.32262C22.5088 9.12637 22.8891 10.4813 22.4494 11.6905L19.4185 20.0252C18.9874 21.2108 17.8607 22 16.5992 22H7.40087C6.13935 22 5.01261 21.2108 4.58149 20.0252L1.55067 11.6905C1.11097 10.4813 1.49127 9.12637 2.49596 8.32262L10.1259 2.21864Z'/><circle cy='12.7' cx='12' r='3' fill='currentColor'/>",
                'order_by' => 1,

            ],
            [
                'name' => 'Pentadbiran Sistem',
                'name_en' => 'System Administration',
                'code' => 'system_administration',
                'permission' => ['system_administration.index'],
                'description' =>'Controls user access, system-level settings, and configurations for managing the application.',
                'svg_path' => "<g transform='translate(-2.1,-2.2) scale(1.18)'><path opacity='0.4' d='M16.19 2H7.81C4.17 2 2 4.17 2 7.81V16.18C2 19.83 4.17 22 7.81 22H16.18C19.82 22 21.99 19.83 21.99 16.19V7.81C22 4.17 19.83 2 16.19 2Z' fill='currentColor'></path>
                                <path d='M15.5801 19.2501C15.1701 19.2501 14.8301 18.9101 14.8301 18.5001V14.6001C14.8301 14.1901 15.1701 13.8501 15.5801 13.8501C15.9901 13.8501 16.3301 14.1901 16.3301 14.6001V18.5001C16.3301 18.9101 15.9901 19.2501 15.5801 19.2501Z' fill='currentColor'></path>
                                <path d='M15.5801 8.2C15.1701 8.2 14.8301 7.86 14.8301 7.45V5.5C14.8301 5.09 15.1701 4.75 15.5801 4.75C15.9901 4.75 16.3301 5.09 16.3301 5.5V7.45C16.3301 7.86 15.9901 8.2 15.5801 8.2Z' fill='currentColor'></path>
                                <path d='M8.41992 19.2498C8.00992 19.2498 7.66992 18.9098 7.66992 18.4998V16.5498C7.66992 16.1398 8.00992 15.7998 8.41992 15.7998C8.82992 15.7998 9.16992 16.1398 9.16992 16.5498V18.4998C9.16992 18.9098 8.83992 19.2498 8.41992 19.2498Z' fill='currentColor'></path>
                                <path d='M8.41992 10.15C8.00992 10.15 7.66992 9.81 7.66992 9.4V5.5C7.66992 5.09 8.00992 4.75 8.41992 4.75C8.82992 4.75 9.16992 5.09 9.16992 5.5V9.4C9.16992 9.81 8.83992 10.15 8.41992 10.15Z' fill='currentColor'></path>
                                <path d='M15.5796 7.33008C14.0796 7.33008 12.8496 8.55008 12.8496 10.0501C12.8496 11.5501 14.0696 12.7801 15.5796 12.7801C17.0796 12.7801 18.2996 11.5601 18.2996 10.0501C18.2996 8.54008 17.0796 7.33008 15.5796 7.33008Z' fill='currentColor'></path>
                                <path d='M8.41922 11.23C6.91922 11.23 5.69922 12.45 5.69922 13.96C5.69922 15.47 6.91922 16.68 8.41922 16.68C9.91922 16.68 11.1492 15.46 11.1492 13.96C11.1492 12.46 9.92922 11.23 8.41922 11.23Z' fill='currentColor'></path>
                                </g>",
                'order_by' => 2,
                'sub_module' => [
                    [
                        'name' =>'Pengurusan Orang',
                        'name_en' => 'People Management',
                        'permission' => ['people.index'],
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
                        'permission' => ['operations.index'],
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
                        ]
                    ],

                    [
                        'code' => 'sla',
                        'name' =>'Pengurusan SLA',
                        'name_en' =>'SLA management',
                        'permission' => ['sla_management.index'],
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
                        'permission' => ['system_configuration.index'],
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
                        ]
                    ],
                ]
            ],

            [
                'code' => 'incidents',
                'name' => 'Pengurusan Insiden',
                'name_en' =>'Incident Management',
                'permission' => ['incident_management.index'],
                'description' => 'Tracks and manages incidents, tickets, or issues reported by users or detected by the system.',
                'svg_path' => "<g transform='translate(-1.2,-1.2) scale(1.10)'>
                                <path opacity='0.5' d='M4 16V21.25H20V16C20 11.5817 16.4183 8 12 8C7.58172 8 4 11.5817 4 16Z' fill='currentColor'></path>
                                <path d='M12.75 2C12.75 1.58579 12.4142 1.25 12 1.25C11.5858 1.25 11.25 1.58579 11.25 2V5C11.25 5.41421 11.5858 5.75 12 5.75C12.4142 5.75 12.75 5.41421 12.75 5V2Z' fill='currentColor'></path>
                                <path d='M21.5303 5.46967C21.8232 5.76256 21.8232 6.23744 21.5303 6.53033L20.0303 8.03033C19.7374 8.32322 19.2626 8.32322 18.9697 8.03033C18.6768 7.73744 18.6768 7.26256 18.9697 6.96967L20.4697 5.46967C20.7626 5.17678 21.2374 5.17678 21.5303 5.46967Z' fill='currentColor'></path>
                                <path d='M3.53033 5.46967C3.23744 5.17678 2.76256 5.17678 2.46967 5.46967C2.17678 5.76256 2.17678 6.23744 2.46967 6.53033L3.96967 8.03033C4.26256 8.32322 4.73744 8.32322 5.03033 8.03033C5.32322 7.73744 5.32322 7.26256 5.03033 6.96967L3.53033 5.46967Z' fill='currentColor'></path>
                                <path d='M14.5716 10.805C14.1877 10.6496 13.7505 10.8348 13.595 11.2188C13.4396 11.6027 13.6249 12.04 14.0088 12.1954C14.8233 12.5251 15.4746 13.1764 15.8043 13.9909C15.9597 14.3748 16.3969 14.5601 16.7809 14.4046C17.1648 14.2492 17.3501 13.812 17.1947 13.428C16.7126 12.2371 15.7626 11.2871 14.5716 10.805Z' fill='currentColor'></path>
                                <path d='M12.75 18.7993C13.1984 18.54 13.5 18.0552 13.5 17.5C13.5 16.6716 12.8284 16 12 16C11.1716 16 10.5 16.6716 10.5 17.5C10.5 18.0552 10.8016 18.54 11.25 18.7993V21.25H12.75V18.7993Z' fill='currentColor'></path>
                                <path d='M4 21.25H2C1.58579 21.25 1.25 21.5858 1.25 22C1.25 22.4142 1.58579 22.75 2 22.75H22C22.4142 22.75 22.75 22.4142 22.75 22C22.75 21.5858 22.4142 21.25 22 21.25H20H12.75H11.25H4Z' fill='currentColor'></path>
                                </g>",
                'order_by' => 3,
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
                'order_by' => 4,
                'svg_path' => "<g transform='translate(-1.2,-2.2) scale(1.18)'>
                                <path opacity='0.5' d='M12 20.0283V18H8L8 20.0283C8 20.3054 8 20.444 8.09485 20.5C8.18971 20.556 8.31943 20.494 8.57888 20.3701L9.82112 19.7766C9.9089 19.7347 9.95279 19.7138 10 19.7138C10.0472 19.7138 10.0911 19.7347 10.1789 19.7767L11.4211 20.3701C11.6806 20.494 11.8103 20.556 11.9051 20.5C12 20.444 12 20.3054 12 20.0283Z' fill='currentColor'></path>
                                <path d='M8 18H7.42598C6.34236 18 5.96352 18.0057 5.67321 18.0681C5.15982 18.1785 4.71351 18.4151 4.38811 18.7347C4.27837 18.8425 4.22351 18.8964 4.09696 19.2397C3.97041 19.5831 3.99045 19.7288 4.03053 20.02C4.03761 20.0714 4.04522 20.1216 4.05343 20.1706C4.16271 20.8228 4.36259 21.1682 4.66916 21.4142C4.97573 21.6602 5.40616 21.8206 6.21896 21.9083C7.05566 21.9986 8.1646 22 9.75461 22H14.1854C15.7754 22 16.8844 21.9986 17.7211 21.9083C18.5339 21.8206 18.9643 21.6602 19.2709 21.4142C19.5774 21.1682 19.7773 20.8228 19.8866 20.1706C19.9784 19.6228 19.9965 18.9296 20 18H12V20.0283C12 20.3054 12 20.444 11.9051 20.5C11.8103 20.556 11.6806 20.494 11.4211 20.3701L10.1789 19.7767C10.0911 19.7347 10.0472 19.7138 10 19.7138C9.95279 19.7138 9.9089 19.7347 9.82112 19.7766L8.57888 20.3701C8.31943 20.494 8.18971 20.556 8.09485 20.5C8 20.444 8 20.3054 8 20.0283V18Z' fill='currentColor'></path>
                                <path opacity='0.5' d='M4.72718 2.73332C5.03258 2.42535 5.46135 2.22456 6.27103 2.11478C7.10452 2.00177 8.2092 2 9.7931 2H14.2069C15.7908 2 16.8955 2.00177 17.729 2.11478C18.5387 2.22456 18.9674 2.42535 19.2728 2.73332C19.5782 3.0413 19.7773 3.47368 19.8862 4.2902C19.9982 5.13073 20 6.24474 20 7.84202L20 18H7.42598C6.34236 18 5.96352 18.0057 5.67321 18.0681C5.15982 18.1785 4.71351 18.4151 4.38811 18.7347C4.27837 18.8425 4.22351 18.8964 4.09696 19.2397C4.02435 19.4367 4 19.5687 4 19.7003V7.84202C4 6.24474 4.00176 5.13073 4.11382 4.2902C4.22268 3.47368 4.42179 3.0413 4.72718 2.73332Z' fill='currentColor'></path>
                                <path d='M7.25 7C7.25 6.58579 7.58579 6.25 8 6.25H16C16.4142 6.25 16.75 6.58579 16.75 7C16.75 7.41421 16.4142 7.75 16 7.75H8C7.58579 7.75 7.25 7.41421 7.25 7Z' fill='currentColor'></path>
                                <path d='M8 9.75C7.58579 9.75 7.25 10.0858 7.25 10.5C7.25 10.9142 7.58579 11.25 8 11.25H13C13.4142 11.25 13.75 10.9142 13.75 10.5C13.75 10.0858 13.4142 9.75 13 9.75H8Z' fill='currentColor'></path>
                                </g>",
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
                'svg_path' => "<g transform='translate(-2.2,-2.1) scale(1.18)'>
                                <path opacity='0.5' d='M21 15.9983V9.99826C21 7.16983 21 5.75562 20.1213 4.87694C19.3529 4.10856 18.175 4.01211 16 4H8C5.82497 4.01211 4.64706 4.10856 3.87868 4.87694C3 5.75562 3 7.16983 3 9.99826V15.9983C3 18.8267 3 20.2409 3.87868 21.1196C4.75736 21.9983 6.17157 21.9983 9 21.9983H15C17.8284 21.9983 19.2426 21.9983 20.1213 21.1196C21 20.2409 21 18.8267 21 15.9983Z' fill='currentColor'></path>
                                <path d='M8 3.5C8 2.67157 8.67157 2 9.5 2H14.5C15.3284 2 16 2.67157 16 3.5V4.5C16 5.32843 15.3284 6 14.5 6H9.5C8.67157 6 8 5.32843 8 4.5V3.5Z' fill='currentColor'></path>
                                <path fill-rule='evenodd' clip-rule='evenodd' d='M6.25 10.5C6.25 10.0858 6.58579 9.75 7 9.75H17C17.4142 9.75 17.75 10.0858 17.75 10.5C17.75 10.9142 17.4142 11.25 17 11.25H7C6.58579 11.25 6.25 10.9142 6.25 10.5ZM7.25 14C7.25 13.5858 7.58579 13.25 8 13.25H16C16.4142 13.25 16.75 13.5858 16.75 14C16.75 14.4142 16.4142 14.75 16 14.75H8C7.58579 14.75 7.25 14.4142 7.25 14ZM8.25 17.5C8.25 17.0858 8.58579 16.75 9 16.75H15C15.4142 16.75 15.75 17.0858 15.75 17.5C15.75 17.9142 15.4142 18.25 15 18.25H9C8.58579 18.25 8.25 17.9142 8.25 17.5Z' fill='currentColor'></path>
                                </g>",
                'order_by' => 5,
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
                // 'permission' => ['knowledge_base.index'],
                'permission' =>[
                    'knowledge-base.index',
                    'knowledge-base.create',
                    'knowledge-base.view',
                    'knowledge-base.update',
                    'knowledge-base.delete',
                ],
                'description' => 'A collection of helpful articles, FAQs, and documentation to guide users and reduce support load.',
                'svg_path' => "<g id='SVGRepo_iconCarrier' transform='translate(-2.2,-2.1) scale(1.18)'>
                                <path opacity='0.5' d='M16.5 3H7.5C6.39543 3 5.5 3.52941 5 5.11765L2.15737 14.9263C2.20285 14.7794 2.26148 14.6491 2.33706 14.5294C2.48298 14.2982 2.67048 14.0996 2.88886 13.9451C3.39331 13.5882 4.09554 13.5882 5.5 13.5882H18.5C19.9045 13.5882 20.6067 13.5882 21.1111 13.9451C21.3295 14.0996 21.517 14.2982 21.6629 14.5294C21.7435 14.6571 21.8049 14.7968 21.8515 14.9557L19 5.11765C18.5 3.52941 17.6046 3 16.5 3Z' fill='currentColor'></path>
                                <path fill-rule='evenodd' clip-rule='evenodd' d='M5.5 13.5879H18.5C19.9045 13.5879 20.6067 13.5879 21.1111 13.9448C21.3295 14.0993 21.517 14.2978 21.6629 14.529C21.7435 14.6567 21.8049 14.7965 21.8515 14.9554C22 15.4611 22 16.1618 22 17.2929C22 18.7799 22 19.5244 21.6629 20.0585C21.517 20.2897 21.3295 20.4883 21.1111 20.6428C20.6067 20.9997 19.9045 20.9997 18.5 20.9997H5.5C4.09554 20.9997 3.39331 20.9997 2.88886 20.6428C2.67048 20.4883 2.48298 20.2897 2.33706 20.0585C2 19.5244 2 18.7809 2 17.2938C2 16.1949 2 15.502 2.136 14.9997C2.14278 14.9746 2.1499 14.95 2.15737 14.9259C2.20285 14.7791 2.26148 14.6488 2.33706 14.529C2.48298 14.2978 2.67048 14.0993 2.88886 13.9448C3.39331 13.5879 4.09554 13.5879 5.5 13.5879ZM19 16.25C19.4142 16.25 19.75 16.5858 19.75 17V18C19.75 18.4142 19.4142 18.75 19 18.75C18.5858 18.75 18.25 18.4142 18.25 18V17C18.25 16.5858 18.5858 16.25 19 16.25ZM17.25 17C17.25 16.5858 16.9142 16.25 16.5 16.25C16.0858 16.25 15.75 16.5858 15.75 17V18C15.75 18.4142 16.0858 18.75 16.5 18.75C16.9142 18.75 17.25 18.4142 17.25 18V17ZM14 16.25C14.4142 16.25 14.75 16.5858 14.75 17V18C14.75 18.4142 14.4142 18.75 14 18.75C13.5858 18.75 13.25 18.4142 13.25 18V17C13.25 16.5858 13.5858 16.25 14 16.25ZM12.25 17C12.25 16.5858 11.9142 16.25 11.5 16.25C11.0858 16.25 10.75 16.5858 10.75 17V18C10.75 18.4142 11.0858 18.75 11.5 18.75C11.9142 18.75 12.25 18.4142 12.25 18V17Z' fill='currentColor'></path>
                                </g>
                                ",
                'order_by' => 6,
                // 'sub_module' => [
                //     [
                //         'code' => 'knowledge_entries',
                //         'name' =>'Knowledge Entries',
                //         'name_en' =>'Knowledge Entries',
                //         'description' => 'The individual articles or content items inside the knowledge base.',
                //         'permission' =>[
                //             'knowledge-base.index',
                //             'knowledge-base.create',
                //             'knowledge-base.view',
                //             'knowledge-base.update',
                //             'knowledge-base.delete',
                //         ]
                //     ],
                // ]
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

    public function createModule($data,$module_id = null){

        $data_module['module_id'] = $module_id;
        $data_module['code'] = $data['code'];
        $data_module['name'] = $data['name'];
        $data_module['name_en'] = $data['name_en'];
        $data_module['description'] = $data['description'];
        $data_module['svg_path'] =  isset($data['svg_path']) ? $data['svg_path'] : null;
        $data_module['order_by'] =  isset($data['order_by']) ? $data['order_by'] : null;
      
        $create = Module::create($data_module);

        return $create;
    }
}

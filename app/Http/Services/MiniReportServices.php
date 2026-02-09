<?php

namespace App\Http\Services;

use App\Exports\GeneralExport;
use App\Http\Resources\RefTableResources;
use App\Http\Resources\SlaTemplateResources;
use App\Http\Traits\ResponseTrait;
use App\Models\ActionCode;
use App\Models\AuditTrail;
use App\Models\Branch;
use App\Models\Calendar;
use App\Models\Category;
use App\Models\Company;
use App\Models\EmailTemplate;
use App\Models\Group;
use App\Models\Incident;
use App\Models\KnowledgeBase;
use App\Models\RefTable;
use App\Models\Sla;
use App\Models\SlaTemplate;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class MiniReportServices
{
    public static function export($request) {
        try {
            $items = self::fetchData($request);

            $format = $request->input('report_format') ?: 'excel';
            $module = str_replace('-', '_', $request->input('report_module') ?: '');
            $type = $request->input('report_type') ?? 'list';

            $title = $type == 'list'
                ? __("report.$module.list_title")
                : __("report.$module.item_title");

            $filename = $module . '_mini_report_' . date('Ymd_His') . '.' . ($format == 'excel' ? 'xlsx' : 'pdf');
            $template = '';

            switch ($format) {
                case 'excel':
                    switch ($module) {
                        case 'incidents':

                        default:
                            return Excel::download(
                                new GeneralExport($items, $title),
                                $filename,
                            );
                    }
                    break;

                case 'pdf':
                default:
                    if($module == 'incidents') {
                        if($type == 'list') {
                            $template = 'exports.pdf';
                        } else {
                            // $template = 'exports.incidents-pdf';
                            $template = 'exports.pdf';
                        }
                    } elseif($module == 'operation_times') {
                        if($type == 'list') {
                            $template = 'exports.pdf';
                        } else {
                            // $template = 'exports.operation-times-pdf';
                            $template = 'exports.pdf';
                        }
                    } elseif($module == 'sla' || $module == 'sla_settings') {
                        if($type == 'list') {
                            $template = 'exports.pdf';
                        } else {
                            // $template = 'exports.sla-pdf';
                            $template = 'exports.pdf';
                        }

                    } else {
                        $template = 'exports.pdf';
                    }

                    return response()->streamDownload(function () use ($items, $type, $title, $template) {
                        echo Pdf::loadView($template, [
                            'items' => $items,
                            'type' => $type,
                            'title' => $title,
                        ])
                        ->setPaper('a4', 'landscape')
                        ->output();
                    }, $filename, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                    ]);


            }
        } catch (\InvalidArgumentException $e) {
            return ResponseTrait::error($e->getMessage());
        }
    }

    public static function fetchData($request) {
        $lang = $request->header('Accept-Language') ?? config('app.locale');
        $type = $request->input('report_type') ?: 'list';
        $module = $request->input('report_module') ?: null;

        app()->setLocale($lang);

        if(!$module) {
            throw new \InvalidArgumentException('Module is required');
        }
        if($type == 'detail' && (!$request->input('id') || $request->input('id') == null)) {
            throw new \InvalidArgumentException('ID is required for detail type');
        }

        $items = collect();
        $columns = [];
        $rows = [];

        $extras = null; // For sub-tables, eg:
                        // $extras = [
                        //     'members' => [
                        //         'title' => '',
                        //         'orientation' => 'horizontal'
                        //         'columns' => [],
                        //         'rows' => [],
                        //     ],
                        //     'contracts' => [
                        //         'columns' => [],
                        //         'rows' => [],
                        //     ],
                        // ];

        switch($module) {
            // People
            // Passed (Phone No sometimes not formatted in Excel)
            case 'individuals': {
                $columnMap = [
                    'ic_no' => __('report.individuals.ic_no'),
                    'name' => __('report.individuals.name'),
                    'phone_no' => __('report.individuals.phone_no'),
                    'role' => __('report.individuals.role'),
                    'status' => __('report.individuals.status'),
                    'nickname' => __('report.individuals.nickname'),
                    'email' => __('report.individuals.email'),
                    'organization' => __('report.individuals.organization'),
                    'groups' => __('report.individuals.groups'),
                    'accessible_groups' => __('report.individuals.accessible_groups'),
                ];

                if($type == 'list') {
                    $columns = ['ic_no', 'name', 'phone_no', 'role', 'status'];
                    $relations = ['branch', 'company', 'roles'];
                    $data = User::with($relations)->hideSuperAdmin()->filter()->search(keyword: $request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'ic_no' => '*'.substr($item->ic_no, -4),
                            'name' => $item->name,
                            'phone_no' => $item->phone_no,
                            'role' => $lang == 'en'
                                ? $item?->roles?->first()?->name_en ?? '-'
                                : $item?->roles?->first()?->name ?? '-',
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = ['branch', 'company', 'roles', 'group.groupDetails', 'groupAccess.groupDetails'];
                    $data = User::with($relations)->hideSuperAdmin()->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'ic_no' => '*'.substr($item->ic_no, -4),
                            'name' => $item->name,
                            'nickname' => $item->nickname,
                            'phone_no' => $item->phone_no,
                            'email' => $item->email,
                            'role' => $lang == 'en'
                                ? $item?->roles?->first()?->name_en ?? '-'
                                : $item?->roles?->first()?->name ?? '-',
                            'organization' => ($item?->branch != null)
                                ? $item->branch?->name ?? '-'
                                : $item->company?->name ?? '-',
                            'groups' => $item->group?->pluck('groupDetails.name')->implode(', ') ?: '-',
                            'accessible_groups' => $item->groupAccess?->pluck('groupDetails.name')->implode(', ') ?: '-',
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');

                break;
            }

            // Index Only
            case 'group': {
                $columnMap = [
                    'name' => __('report.groups.name'),
                    'description' => __('report.groups.description'),
                    'status' => __('report.groups.status'),
                    // ..
                ];

                if($type == 'list') {
                    $columns = ['name', 'description', 'status'];
                    $relations = [];
                    $data = Group::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'name' => $item->name,
                            'description' => $item->description,
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = [];
                    $data = Group::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            '-' => '-',
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');


                break;
            }

            // Index Only
            case 'contractors': {
                $columnMap = [
                    'name' => __('report.contractors.name'),
                    'nickname' => __('report.contractors.nickname'),
                    'phone_no' => __('report.contractors.phone_no'),
                    'email' => __('report.contractors.email'),
                    'status' => __('report.contractors.status'),
                ];

                if($type == 'list') {
                    $columns = ['name', 'nickname', 'phone_no', 'email', 'status'];
                    $relations = ['stateDescription', 'contract', 'user'];
                    $data = Company::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'name' => $item->name,
                            'nickname' => $item->nickname,
                            'phone_no' => $item->phone_no,
                            'email' => $item->email,
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = ['stateDescription', 'contract', 'user'];
                    $data = Company::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            '-' => '-',
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');


                break;
            }

            // Operations
            // Passed
            case 'calendar': {
                $columnMap = [
                    'name' => __('report.calendar.name'),
                    'holiday_name' => __('report.calendar.holiday_name'),
                    'start_date' => __('report.calendar.start_date'),
                    'end_date' => __('report.calendar.end_date'),
                    'status' => __('report.calendar.status'),
                    'states' => __('report.calendar.states'),
                ];

                if($type == 'list') {
                    $columns = ['name', 'start_date', 'end_date', 'status'];
                    $relations = [];
                    $data = Calendar::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'name' => $item->name,
                            'start_date' => (new Carbon($item->start_date))->format('d-m-Y'),
                            'end_date' => (new Carbon($item->end_date))->format('d-m-Y'),
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                } else {
                    $columns = ['holiday_name', 'start_date', 'end_date', 'status', 'states'];
                    $relations = [];
                    $data = Calendar::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        $states = $item->getStateDesc($item->state_id);
                        return [
                            'holiday_name' => $item->name,
                            'start_date' => (new Carbon($item->start_date))->format('d-m-Y'),
                            'end_date' => (new Carbon($item->end_date))->format('d-m-Y'),
                            'states' => collect($states)->implode(', ') ?: $states,
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),

                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');


                break;
            }

            // Index Only
            case 'operation-times': {
                $columnMap = [
                    'branch' => __('report.operation_times.branch'),
                    'time' => __('report.operation_times.time'),
                    'day' => __('report.operation_times.day'),
                    'duration' => __('report.operation_times.duration'),
                    'status' => __('report.operation_times.status'),
                ];

                if($type == 'list') {
                    $columns = array_keys($columnMap);
                    $relations = [
                        'operatingTime.dayStartDescription',
                        'operatingTime.dayendDescription',
                        'operatingTime.durationDescription',
                    ];
                    $data = Branch::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang, $request) {
                        $operations = $item->operatingTime;
                        $times = $operations->map(function ($op) {
                            return (new Carbon($op->operation_start))->format('H:m:s').' - '.(new Carbon($op->operation_end))->format('H:m:s');
                        });
                        $days = $operations->map(function ($op) use ($lang) {
                            if($op->day_end == $op->day_start) {
                                return ($lang == 'en')
                                    ? $op->dayStartDescription->name_en
                                    : $op->dayStartDescription->name;
                            } else {
                                return ($lang == 'en')
                                    ? $op->dayStartDescription->name_en.' - '.$op->dayEndDescription->name_en
                                    : $op->dayStartDescription->name.' - '.$op->dayEndDescription->name;
                            }
                        });
                        $durations = $operations->map(function ($op) use ($lang) {
                            return ($lang == 'en')
                                ? $op->durationDescription->name_en
                                : $op->durationDescription->name;
                        });
                        $statuses = $operations->map(function ($op) use ($lang) {
                            return $op->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive');
                        });

                        if($request->input('report_format') == 'pdf') {
                            return [
                                'branch' => $item->name,
                                'time' => $times->implode('<br>'),
                                'day' => $days->implode('<br>'),
                                'duration' => $durations->implode('<br>'),
                                'status' => $statuses->implode('<br>'),
                            ];
                        } else {
                            return [
                                'branch' => $item->name,
                                'time' => $times->implode(PHP_EOL),
                                'day' => $days->implode(PHP_EOL),
                                'duration' => $durations->implode(PHP_EOL),
                                'status' => $statuses->implode(PHP_EOL),
                            ];
                        }

                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = [
                        'operatingTime.dayStartDescription',
                        'operatingTime.dayendDescription',
                        'operatingTime.durationDescription',
                    ];
                    $data = Branch::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            '-' => '-',
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');


                break;
            }

            // Passed
            case 'categories' : {
                $columnMap = [
                    'code' => __('report.categories.code'),
                    'parent_category' => __('report.categories.parent_category'),
                    'abbreviation' => __('report.categories.abbreviation'),
                    'description' => __('report.categories.description'),
                    'status' => __('report.categories.status'),
                ];

                if($type == 'list') {
                    $columns = array_keys($columnMap);
                    $relations = ['childCategoryRecursive', 'mainCategory'];
                    $data = Category::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'code' => $item->code,
                            'parent_category' => $item->mainCategory?->name ?? '-',
                            'abbreviation' => $item->name,
                            'description' => $item->description,
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = ['childCategoryRecursive', 'mainCategory'];
                    $data = Category::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'code' => $item->code,
                            'parent_category' => $item->mainCategory?->name ?? '-',
                            'abbreviation' => $item->name,
                            'description' => $item->description,
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');

                break;
            }

            // Passed
            case 'email-templates': {
                $columnMap = [
                    'name' => __('report.email_templates.name'),
                    'notes' => __('report.email_templates.notes'),
                    'sender_email' => __('report.email_templates.sender_email'),
                    'sender_name' => __('report.email_templates.sender_name'),
                    'status' => __('report.email_templates.status'),
                ];

                if($type == 'list') {
                    $columns = ['name', 'notes', 'sender_email', 'status'];
                    $relations = [];
                    $data = EmailTemplate::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'name' => $item->name,
                            'notes' => $item->notes,
                            'sender_email' => $item->sender_email,
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = [];
                    $data = EmailTemplate::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'name' => $item->name,
                            'notes' => $item->notes,
                            'sender_email' => $item->sender_email,
                            'sender_name' => $item->sender_name,
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');


                break;
            }

            // SLA Management
            // Final, Passed
            case 'sla-template': {
                $columnMap = [
                    'contractor' => __('report.sla_template.contractor'),
                    'contract' => __('report.sla_template.contract'),
                    'sla_code'=> __('report.sla_template.sla_code'),
                    'severity' => __('report.sla_template.severity'),
                    'contract_no' => __('report.sla_template.contract_no'),

                    'response_time' => __('report.sla_template.response_time'),
                    'response_time_type' => __('report.sla_template.response_time_type'),
                    'response_time_penalty' => __('report.sla_template.response_time_penalty'),
                    'response_time_penalty_type' => __('report.sla_template.response_time_penalty_type'),

                    'response_time_location' => __('report.sla_template.response_time_location'),
                    'response_time_location_type' => __('report.sla_template.response_time_location_type'),
                    'response_time_location_penalty' => __('report.sla_template.response_time_location_penalty'),
                    'response_time_location_penalty_type' => __('report.sla_template.response_time_location_penalty_type'),

                    'temporary_resolution_time' => __('report.sla_template.temporary_resolution_time'),
                    'temporary_resolution_time_type' => __('report.sla_template.temporary_resolution_time_type'),
                    'temporary_resolution_time_penalty' => __('report.sla_template.temporary_resolution_time_penalty'),
                    'temporary_resolution_time_penalty_type' => __('report.sla_template.temporary_resolution_time_penalty_type'),

                    'resolution_time' => __('report.sla_template.resolution_time'),
                    'resolution_time_type' => __('report.sla_template.resolution_time_type'),
                    'resolution_time_penalty' => __('report.sla_template.resolution_penalty'),
                    'resolution_time_penalty_type' => __('report.sla_template.resolution_penalty_type'),

                    'verify_resolution_time' => __('report.sla_template.verify_resolution_time'),
                    'verify_resolution_time_type' => __('report.sla_template.verify_resolution_time_type'),
                    'verify_resolution_time_penalty' => __('report.sla_template.verify_resolution_time_penalty'),
                    'verify_resolution_time_penalty_type' => __('report.sla_template.verify_resolution_time_penalty_type'),
                ];

                if($type == 'list') {
                    $columns = ['contractor', 'contract', 'sla_code', 'severity'];
                    $relations = ['severityDescription', 'company', 'companyContract'];
                    $data = SlaTemplate::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'contractor' => $item->company->name ?? null,
                            'contract' => $item->companyContract->name ?? null,
                            'sla_code' => $item->code,
                            'severity' => $lang === 'en'
                                ? $item->severityDescription?->name_en ?? null
                                : $item->severityDescription?->name ?? null,
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = [
                        'severityDescription',
                        'company',
                        'companyContract',
                        'responseTimeTypeDescription',
                        'responseTimePenaltyTypeDescription',
                        'resolutionTimeTypeDescription',
                        'resolutionTimePenaltyTypeDescription',
                        'responseTimeLocationTypeDescription',
                        'responseTimeLocationPenaltyTypeDescription',
                        'temporaryResolutionTimeTypeDescription',
                        'temporaryResolutionTimePenaltyTypeDescription',
                        'verifyResolutionTimeTypeDescription',
                        'verifyResolutionTimePenaltyTypeDescription',
                    ];
                    $data = SlaTemplate::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'sla_code' => $item->code,
                            'contractor' => $item->company?->name ?? null,
                            'contract' => $item->companyContract?->name ?? null,
                            'contract_no' => $item->companyContract?->contract_no ?? null,
                            'severity' => $lang === 'en'
                                ? $item->severityDescription?->name_en ?? null
                                : $item->severityDescription?->name ?? null,

                            'response_time' => $item->response_time,
                            'response_time_type' => $lang === 'en'
                                ? $item->responseTimeTypeDescription?->name_en ?? null
                                : $item->responseTimeTypeDescription?->name ?? null,
                            'response_time_penalty' => $item->response_time_penalty,
                            'response_time_penalty_type' => $lang === 'en'
                                ? $item->responseTimePenaltyTypeDescription?->name_en ?? null
                                : $item->responseTimePenaltyTypeDescription?->name ?? null,

                            'response_time_location' => $item->response_time_location,
                            'response_time_location_type' => $lang === 'en'
                                ? $item->responseTimeLocationTypeDescription?->name_en ?? null
                                : $item->responseTimeLocationTypeDescription?->name ?? null,
                            'response_time_location_penalty' => $item->response_time_location_penalty,
                            'response_time_location_penalty_type' => $lang === 'en'
                                ? $item->responseTimeLocationPenaltyTypeDescription?->name_en ?? null
                                : $item->responseTimeLocationPenaltyTypeDescription?->name ?? null,

                            'temporary_resolution_time' => $item->temporary_resolution_time,
                            'temporary_resolution_time_type' => $lang === 'en'
                                ? $item->temporaryResolutionTimeTypeDescription?->name_en ?? null
                                : $item->temporaryResolutionTimeTypeDescription?->name ?? null,
                            'temporary_resolution_time_penalty' => $item->temporary_resolution_time_penalty,
                            'temporary_resolution_time_penalty_type' => $lang === 'en'
                                ? $item->temporaryResolutionTimePenaltyTypeDescription?->name_en ?? null
                                : $item->temporaryResolutionTimePenaltyTypeDescription?->name ?? null,

                            'resolution_time' => $item->resolution_time,
                            'resolution_time_type' => $lang === 'en'
                                ? $item->resolutionTimeTypeDescription?->name_en ?? null
                                : $item->resolutionTimeTypeDescription?->name ?? null,
                            'resolution_time_penalty' => $item->resolution_time_penalty,
                            'resolution_time_penalty_type' => $lang === 'en'
                                ? $item->resolutionTimePenaltyTypeDescription?->name_en ?? null
                                : $item->resolutionTimePenaltyTypeDescription?->name ?? null,

                            'verify_resolution_time' => $item->verify_resolution_time,
                            'verify_resolution_time_type' => $lang === 'en'
                                ? $item->verifyResolutionTimeTypeDescription?->name_en ?? null
                                : $item->verifyResolutionTimeTypeDescription?->name ?? null,
                            'verify_resolution_time_penalty' => $item->verify_resolution_time_penalty,
                            'verify_resolution_time_penalty_type' => $lang === 'en'
                                ? $item->verifyResolutionTimePenaltyTypeDescription?->name_en ?? null
                                : $item->verifyResolutionTimePenaltyTypeDescription?->name ?? null,
                        ];
                    });
                }

                $items = $data->toArray();

                // change labelling using columnMap
                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');

                break;
            }

            // Index Only
            case 'sla': {
                $columnMap = [
                    'sla_code' => __('report.sla.sla_code'),
                    'category' => __('report.sla.category'),
                    'branch' => __('report.sla.branch'),
                    'severity' => __('report.sla.severity'),
                    'status' => __('report.sla.status'),
                    // ...
                ];

                if($type == 'list') {
                    $columns = ['sla_code', 'category', 'branch', 'severity', 'status'];
                    $relations = ['branch', 'slaTemplate.severityDescription', 'group', 'category'];
                    $data = Sla::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang, $request) {
                        $branches = ($item->getBranchDetails($item->branch_id))
                        ->map(function ($branch, $index) use ($lang) {
                            $state = $lang == 'en'
                                ? $branch->stateDescription->name_en
                                : $branch->stateDescription->name;

                            return ($index + 1) . '. ' . $branch->name . ' (' . $state . ')';
                        });

                        return [
                            'sla_code' => $item->slaTemplate?->code ?? '-',
                            'category' => $item->category?->name ?? '-',
                            'branch' => ($request->input('report_format') == 'pdf')
                                ? $branches->implode('<br>')
                                : $branches->implode(PHP_EOL),
                            'severity' => ($lang == 'en')
                                ? $item->slaTemplate?->severityDescription?->name_en ?? '-'
                                : $item->slaTemplate?->severityDescription?->name ?? '-',
                            'status' => $item->is_active == 1
                                ? __('report.general.active')
                                : __('report.general.inactive'),
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = ['branch', 'slaTemplate.severityDescription', 'group', 'category'];
                    $data = Sla::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            '-' => '-'
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');

                break;
            }

            // System Config
            // Final, Passed
            case 'global-settings': {
                $columns = ['code_category','ref_code','name','name_en'];
                $columnMap = [
                    'code_category' => __('report.global_settings.category'),
                    'ref_code' => __('report.global_settings.reference_code'),
                    'name' => __('report.global_settings.name_ms'),
                    'name_en' => __('report.global_settings.name_en'),
                ];
                $categoryMap = __('report.global_settings.categories');

                if($type == 'list') {
                    $data = RefTable::filter()->search($request->search)->sortByField($request)->get();
                } else {
                    $data = RefTable::where('id', $request->input('id'))->get();
                }

                $formatted = RefTableResources::collection($data)->resolve();
                $items = self::selectOnly($columns, $formatted);

                $columns = self::translate($columns, $columnMap, 'columns');

                // change each item's code_category's value to follow $categoryMap in $items
                $items = array_map(function ($item) use ($categoryMap) {
                    if (isset($item['code_category'])) {
                        $item['code_category'] = $categoryMap[$item['code_category']]
                            ?? $item['code_category']; // fallback
                    }

                    return $item;
                }, $items);

                // change labelling
                $rows = self::translate($items, $columnMap, 'rows');
                break;
            }

            // Final, Passed
            case 'action-codes': {
                $columnMap = [
                    'abbreviation' => __('report.action_codes.abbreviation'),
                    'name' => __('report.action_codes.name'),
                    'description' => __('report.action_codes.description'),
                    'roles_allowed' => __('report.action_codes.roles_allowed'),
                    'skip_penalty' => __('report.action_codes.skip_penalty'),
                    'send_email' => __('report.action_codes.send_email'),
                    'email_receiver' => __('report.action_codes.email_receiver'),
                    'status' => __('report.action_codes.status'),
                ];

                if($type == 'list') {
                    $columns = ['abbreviation', 'name', 'description', 'status'];
                    $relations = ['emailRecipientDescription'];
                    $data =  ActionCode::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function($item) use ($lang) {
                        return [
                            'abbreviation' => $item->nickname,
                            'name' => $item->name,
                            'description' => $item->description,
                            'status' => $item->is_active == 0 ? __('report.general.inactive') : __('report.general.active'),
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = ['emailRecipientDescription'];
                    $data =  ActionCode::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function($item) use ($lang) {
                        return [
                            'abbreviation' => $item->nickname,
                            'name' => $item->name,
                            'description' => $item->description,
                            'roles_allowed' => (new ActionCode)->getRoleDesc($item->role_id)->implode(', ') ?? __('report.general.none'),
                            'skip_penalty' => ($item->skip_penalty) == 0 ? __('report.general.no') : __('report.general.yes'),
                            'email_receiver' => ($lang == 'en')
                                ? $item->emailRecipientDescription?->name_en ?? __('report.general.none')
                                : $item->emailRecipientDescription?->name ?? __('report.general.none'),
                            'send_email' => ($item->send_email) == 0 ? __('report.general.no') : __('report.general.yes'),
                            'status' => $item->is_active == 0 ? __('report.general.inactive') : __('report.general.active'),
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');

                break;
            }

            // Incidents
            // Check
            case 'incidents': {
                $columnMap = [
                    'incident_no' => __('report.incidents.incident_no'),
                    'branch' => __('report.incidents.branch'),
                    'description' => __('report.incidents.description'),
                    'status' => __('report.incidents.status'),
                    'start_date' => __('report.incidents.start_date'),
                    'end_date' => __('report.incidents.end_date'),
                    'severity' => __('report.incidents.severity'),
                    'phone_no' => __('report.incidents.phone_no'),
                ];

                if($type == 'list') {
                    $columns = ['incident_no', 'branch', 'description', 'status', 'start_date', 'end_date', 'severity', 'phone_no'];
                    $relations = ['branch', 'statusDesc', 'sla.slaTemplate.severityDescription', 'complaintUser'];
                    $data = Incident::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'incident_no' => $item->incident_no,
                            'branch' => $item->branch->name,
                            'description' => $item->information,
                            'status' => ($lang == 'en')
                                ? $item->statusDesc?->name_en
                                : $item->statusDesc?->name,
                            'start_date' => $item->incident_date,
                            'end_date' => $item->actual_end_date,
                            'severity' => ($lang == 'en')
                                ? $item->sla?->slaTemplate?->severityDescription?->name_en
                                : $item->sla?->slaTemplate?->severityDescription?->name,
                            'phone_no' => $item->complaintUser?->phone_no,
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = [];
                    $data = Incident::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            '' => '',
                            '' => '',
                            '' => '',
                            '' => '',
                            '' => '',
                            '' => '',
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');


                break;
            }

            /// Audit Trail
            // Passed
            case 'audit-trails': {
                $columnMap = [
                    'date' => __('report.audit_trails.date'),
                    'time' => __('report.audit_trails.time'),
                    'event' => __('report.audit_trails.event'),
                    'old_values' => __('report.audit_trails.old_values'),
                    'new_values' => __('report.audit_trails.new_values'),
                    'user' => __('report.audit_trails.user'),
                    'user_id' => __('report.audit_trails.user_id'),
                ];

                if($type == 'list') {
                    $columns = ['date', 'time', 'user', 'event'];
                    $relations = ['user'];
                    $data = AuditTrail::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'date' => (new Carbon($item->created_at))->format('d-m-Y'),
                            'time' => (new Carbon($item->created_at))->format('H:m:s'),
                            'user' => $item->user?->name ?? '-',
                            'event' => $item->event,
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = ['user'];
                    $data = AuditTrail::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang) {
                        return [
                            'date' => (new Carbon($item->created_at))->format('d-m-Y'),
                            'time' => (new Carbon($item->created_at))->format('H:m:s'),
                            'event' => $item->event,
                            'old_values' => $item->old_values,
                            'new_values' => $item->new_values,
                            'user' => $item->user?->name ?? '-',
                            'user_id' => $item->user?->ic_no
                                ? '*'.substr($item->user->ic_no, -4)
                                : '-',

                        ];
                    });

                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');

                break;
            }

            // Knowledge Base
            // Passed
            case 'knowledge-base': {
                $columnMap = [
                    'keywords' => __('report.knowledge_base.keywords'),
                    'category' => __('report.knowledge_base.category'),
                    'problem' => __('report.knowledge_base.problem'),
                    'solution' => __('report.knowledge_base.solution'),
                ];

                if($type == 'list') {
                    $columns = array_keys($columnMap);
                    $relations = ['categoryDescription'];
                    $data = KnowledgeBase::with($relations)->filter()->search($request->search)->sortByField($request)->get()
                    ->map(function ($item) use ($lang, $request) {
                        return [
                            'keywords' => $item->keywords,
                            'category' => $item->categoryDescription->name,
                            'problem' => $item->explanation,
                            'solution' => ($request->input('report_format') == 'pdf')
                                ? str_replace(';', '<br>', $item->solution)
                                : str_replace(';', PHP_EOL, $item->solution)
                        ];
                    });
                } else {
                    $columns = array_keys($columnMap);
                    $relations = ['categoryDescription'];
                    $data = KnowledgeBase::with($relations)->where('id', $request->input('id'))->get()
                    ->map(function ($item) use ($lang, $request) {
                        return [
                            'keywords' => $item->keywords,
                            'category' => $item->categoryDescription->name,
                            'problem' => $item->explanation,
                            'solution' => ($request->input('report_format') == 'pdf')
                                ? str_replace(';', '<br>', $item->solution)
                                : str_replace(';', PHP_EOL, $item->solution)
                        ];
                    });
                }

                $items = $data->toArray();

                $rows = self::translate($items, $columnMap, 'rows');
                $columns = self::translate($columns, $columnMap, 'columns');

                break;
            }

            default:
                break;
        }

        if($type == 'list') {
            $items = [ $columns, ...$rows ];
        } else {
            $items = self::transpose($columns, $rows);

            if($extras != null) {

            }
        }
        return $items;
    }

    public static function selectOnly(array $fields, array $items) {
        return array_map(function($item) use ($fields) {
            return Arr::only($item, $fields);
        }, $items);
    }

    public static function transpose(array $columns, array $rows): array
    {
        $result = [];

        foreach ($columns as $col) {
            $row = [$col];
            foreach ($rows as $r) {
                $row[] = $r[$col] ?? null;
            }
            $result[] = $row;
        }

        return $result;
    }

    public static function translate($toBeTranslated, $columnMap, $type) {
        if($type == 'columns' || $type == 'column') {
            return array_map(fn($col) => $columnMap[$col] ?? $col, $toBeTranslated); // $toBeTranslated = $columns array
        } else {
            return array_map(function($row) use ($columnMap) {
                $newRow = [];
                foreach ($row as $key => $value) {
                    $newRow[$columnMap[$key] ?? $key] = $value;
                }
                return $newRow;
            }, $toBeTranslated); // $toBeTranslated = $items array of data items
        }
    }

}

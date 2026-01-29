<?php

namespace App\Http\Services;

use App\Exports\GeneralExport;
use App\Http\Resources\RefTableResources;
use App\Http\Traits\ResponseTrait;
use App\Models\RefTable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class MiniReportServices
{
    public static function export($request) {
        try {
            $items = self::fetchData($request);

            $format = $request->input('format') ?: 'excel';
            $module = str_replace('-', '_', $request->input('module') ?: '');
            $type = $request->input('type') ?? 'list';
            $title = $type == 'list'
                ? __("report.$module.list_title")
                : __("report.$module.item_title");

            $filename = $module . '_mini_report_' . date('Ymd_His') . '.' . ($format == 'excel' ? 'xlsx' : 'pdf');

            switch ($format) {
                case 'excel':
                    switch ($module) {
                        case 'incidents':
                            break;
                        default:
                            return Excel::download(new GeneralExport($items, $title), $filename);
                    }

                    break;

                case 'pdf':
                default:

                    // return ResponseTrait::success('Testing PDF', $items);
                    return Pdf::loadView("exports.pdf", [
                        'items' => $items,
                        'type' => $type,
                        'title' => $title,
                    ])->setPaper('a4', 'landscape')
                    ->download($filename);
            }
        } catch (\InvalidArgumentException $e) {
            return ResponseTrait::error($e->getMessage());
        }
    }

    public static function fetchData($request) {
        $lang = $request->header('Accept-Language') ?? config('app.locale');
        $type = $request->type ?: 'list';
        $module = $request->module ?: null;

        if(!$module) {
            throw new \InvalidArgumentException('Module is required');
        }
        if($type == 'detail' && (!$request->input('id') || $request->input('id') == null)) {
            throw new \InvalidArgumentException('ID is required for detail type');
        }

        $items = collect();
        $columns = [];

        switch($module) {
            case 'global-settings':
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
                    $formatted = RefTableResources::collection($data)->resolve();
                    $items = self::selectOnly($columns, $formatted);

                    $columns = array_map(fn ($col) => $columnMap[$col] ?? $col, $columns);

                    // change each item's code_category's value to follow $categoryMap in $items
                    $items = array_map(function ($item) use ($categoryMap) {
                        if (isset($item['code_category'])) {
                            $item['code_category'] = $categoryMap[$item['code_category']]
                                ?? $item['code_category']; // fallback
                        }

                        return $item;
                    }, $items);

                    // change labelling
                    $items = array_map(function ($item) use ($columnMap) {
                        return collect($item)->mapWithKeys(function ($value, $key) use ($columnMap) {
                            return [$columnMap[$key] ?? $key => $value];
                        })->toArray();
                    }, $items);

                } else {
                    $data = RefTable::where('id', $request->input('id'))->get();
                    $formatted = RefTableResources::collection($data)->resolve();
                    $rows = self::selectOnly($columns, $formatted);

                    $columns = array_map(fn ($col) => $columnMap[$col] ?? $col, $columns);

                    // change each item's code_category's value to follow $categoryMap in $items
                    $rows = array_map(function ($item) use ($categoryMap) {
                        if (isset($item['code_category'])) {
                            $item['code_category'] = $categoryMap[$item['code_category']]
                                ?? $item['code_category']; // fallback
                        }

                        return $item;
                    }, $rows);

                    // change columns labelling
                    $rows = array_map(function ($item) use ($columnMap) {
                        return collect($item)->mapWithKeys(function ($value, $key) use ($columnMap) {
                            return [$columnMap[$key] ?? $key => $value];
                        })->toArray();
                    }, $rows);

                }
                break;

            case 'sla':
                $columns = ['code','category_desc','branch','severity', 'is_active'];
                $columnMap = [
                    'code_category' => __('report.global_settings.category'),
                    'ref_code' => __('report.global_settings.reference_code'),
                    'name' => __('report.global_settings.name_ms'),
                    'name_en' => __('report.global_settings.name_en'),
                ];


                break;

            default:
                break;
        }

        if($type == 'list') {
            $items = [ $columns, ...$items ];
        } else {
            $items = self::transpose($columns, $rows);
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
}

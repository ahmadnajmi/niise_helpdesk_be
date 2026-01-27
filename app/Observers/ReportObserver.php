<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;
use App\Models\Report;

class ReportObserver
{
    public function created(Report $report)
    {
        Cache::forget('dynamic_option_report');
    }

    public function updated(Report $report)
    {
        Cache::forget('dynamic_option_report');
    }

    public function deleted(Report $report)
    {
        Cache::forget('dynamic_option_report');
    }
}

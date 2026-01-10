<?php

namespace App\Observers;

use App\Models\SlaTemplate;
use Illuminate\Support\Facades\Cache;

class SlaTemplateObserver
{
    public function created(SlaTemplate $slaTemplate)
    {
        Cache::forget('dynamic_option_sla_template');
    }

    public function updated(SlaTemplate $slaTemplate)
    {
        Cache::forget('dynamic_option_sla_template');
    }

    public function deleted(SlaTemplate $slaTemplate)
    {
        Cache::forget('dynamic_option_sla_template');
    }
}

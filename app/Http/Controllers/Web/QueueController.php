<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FailedJob;
use App\Models\Job;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $jobs = Job::latest('id')->get();

        $failed_jobs = FailedJob::latest('id')->get();

        return view('queue.index',compact('jobs','failed_jobs'));

    }

}

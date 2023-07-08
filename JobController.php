<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessUserJob;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function dispatchJobs(Request $request)
    {
        $user_id = $request->user_id;

            $queue_name = 'user_' . $user_id;
            dispatch((new ProcessUserJob())->onQueue($queue_name));

        return "Jobs dispatched for user: $user_id";
    }
}

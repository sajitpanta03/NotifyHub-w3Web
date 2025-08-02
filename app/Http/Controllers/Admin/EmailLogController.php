<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailLogController extends Controller
{
    public function show()
    {
        $logs = EmailLog::with(['sender', 'recipient'])
            ->latest()
            ->get();

        return Inertia::render('Admin/EmailLogs/show', [
            'logs' => $logs,
        ]);
    }
}

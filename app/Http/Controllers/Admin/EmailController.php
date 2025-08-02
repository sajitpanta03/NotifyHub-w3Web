<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminToUserMail;
use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Log;

class EmailController extends Controller
{
    public function create($id)
    {
        $selectedUser = User::select('id', 'name', 'email')
            ->findOrFail($id);

        return Inertia::render('Admin/Email/create', [
            'selectedUser' => $selectedUser,
            'preselectedUserId' => $id
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $sentCount = 0;
        $failedCount = 0;
        $total = count($request->recipients);
        $currentUserId = auth()->id();
        $errors = [];

        foreach ($request->recipients as $recipientId) {
            try {
                $user = User::findOrFail($recipientId);

                $log = EmailLog::create([
                    'sender_id' => $currentUserId,
                    'recipient_id' => $user->id,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'status' => 'success'
                ]);

                Mail::to($user->email)->queue(
                    (new AdminToUserMail(
                        $user,
                        $request->subject,
                        $request->message,
                        auth()->user()->name
                    ))->setLogId($log->id)
                );

                $sentCount++;

            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = "Failed to queue email to user ID {$recipientId}: " . $e->getMessage();

                EmailLog::create([
                    'sender_id' => $currentUserId,
                    'recipient_id' => $recipientId,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ]);

                Log::error(end($errors));
            }
        }

        $response = [
            'success' => "Queued {$sentCount} of {$total} emails successfully!"
        ];

        if ($failedCount > 0) {
            $response['warning'] = "{$failedCount} emails failed to queue";
        }

        return redirect()->route('admin.users.index')->with($response);
    }
}

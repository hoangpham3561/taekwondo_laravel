<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends BaseController
{
    public function index()
    {
        return view('pages.frontend.contact', [
            'userPrefix' => $this->userPrefix,
        ]);
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $ticket = Ticket::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        try {
            AdminNotifier::notifyContactFormSubmission($ticket, $validated);
        } catch (\Throwable $e) {
            Log::warning('Admin notification for contact form failed: ' . $e->getMessage(), [
                'ticket_id' => $ticket->getKey(),
            ]);
        }

        try {
            Mail::send('emails.contact-form-notification', ['payload' => $validated], function ($mail) {
                $mail->to(config('club.contact_email'))
                    ->subject('[Contact Form] New message from website');
            });
        } catch (\Exception $exception) {
            Log::error('Contact form email send failed: ' . $exception->getMessage(), [
                'contact_email' => $validated['email'],
                'subject' => $validated['subject'],
            ]);
        }

        return redirect()
            ->route($this->userPrefix . '.contact')
            ->with('success', 'Gửi liên hệ thành công. Chúng tôi sẽ phản hồi sớm.');
    }
}

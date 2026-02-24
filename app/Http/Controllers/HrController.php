<?php

namespace App\Http\Controllers;

use App\Models\HrRequest;
use Illuminate\Http\Request;

class HrController extends Controller
{
    // ثبت درخواست کارمند
    public function store(Request $request) 
    {
        $request->validate([
            'type' => 'required|in:leave,mission',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        HrRequest::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'manager_id' => 1, // بعداً می‌توانید به آی‌دی مدیر واقعی تغییر دهید
        ]);

        return back()->with('success', 'درخواست شما با موفقیت ثبت شد.');
    }

    // نمایش لیست درخواست‌ها برای مدیر
    public function managerInbox()
    {
        $hrRequests = HrRequest::with('user')->latest()->get();
        return view('hr.admin_index', compact('hrRequests'));
    }
    public function index()
    {
        // دریافت درخواست‌های کاربر فعلی برای نمایش در صفحه شخصی
        $hrRequests = \App\Models\HrRequest::where('user_id', auth()->id())->latest()->get();
        return view('hr.index', compact('hrRequests'));
    }
    // تغییر وضعیت درخواست (تایید یا رد)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $hrRequest = HrRequest::findOrFail($id);
        $hrRequest->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'وضعیت درخواست با موفقیت تغییر کرد.');
    }
}
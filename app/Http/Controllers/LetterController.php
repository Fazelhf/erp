<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\LetterAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LetterController extends Controller
{
    // ۱. نمایش فرم ثبت نامه
    public function create()
    {
        return view('letters.create');
    }

    // ۲. ذخیره نامه در دیتابیس
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'to_user_id' => 'required|exists:users,id',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,zip|max:2048',
        ]);

        return DB::transaction(function () use ($request) {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('letters', 'public');
            }

            $letter = Letter::create([
                'letter_no' => 'LTR-' . now()->timestamp, 
                'title' => $request->title,
                'content' => $request->content,
                'sender_id' => Auth::id(),
                'type' => 'internal',
                'attachment' => $attachmentPath,
            ]);

            LetterAction::create([
                'letter_id' => $letter->id,
                'from_user_id' => Auth::id(),
                'to_user_id' => $request->to_user_id,
                'description' => $request->description ?? 'جهت بررسی و اقدام',
                'status' => 'pending',
            ]);

            return redirect()->route('letters.inbox')->with('success', 'نامه با موفقیت ارسال شد.');
        });
    }

    // ۳. نمایش لیست نامه‌های دریافتی (کارتابل)
    public function inbox()
    {
        $letters = LetterAction::where('to_user_id', Auth::id())
                    ->with(['letter', 'sender'])
                    ->latest()
                    ->get();

        return view('letters.inbox', compact('letters'));
    }
    public function updateStatus(Request $request, $id)
    {
        // پیدا کردن ارجاع مورد نظر
        $action = LetterAction::where('id', $id)
                    ->where('to_user_id', Auth::id()) // امنیت: فقط گیرنده بتواند نظر بدهد
                    ->firstOrFail();

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // آپدیت وضعیت
        $action->update([
            'status' => $request->status,
            'is_read' => true // یعنی نامه خوانده شده
        ]);

        // آپدیت وضعیت در جدول اصلی نامه (اختیاری)
        $action->letter->update(['status' => $request->status]);

        return back()->with('success', 'وضعیت نامه با موفقیت تغییر کرد.');
    }
}

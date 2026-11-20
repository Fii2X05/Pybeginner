<?php

namespace App\Http\Controllers;

use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function create(): View
    {
        return view('support');
    }

    public function store(Request $request, AdminNotificationService $notifications): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:bug,curriculum,code_help'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $category = match ($validated['category']) {
            'bug' => 'Laporan bug',
            'curriculum' => 'Pertanyaan kurikulum',
            'code_help' => 'Permintaan bantuan kode',
        };
        $student = Auth::user();

        $notifications->notifyAdmins(
            'student_feedback',
            $category,
            "{$student->name} ({$student->email}): {$validated['message']}",
            route('admin.siswa.show', $student),
        );

        return back()->with('status', 'Pesan bantuan Anda telah dikirim kepada tim PyBeginner.');
    }
}

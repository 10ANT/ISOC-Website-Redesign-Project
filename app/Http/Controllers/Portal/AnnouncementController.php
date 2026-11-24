<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureApproved($request);

        return view('portal.announcements.index', [
            'announcements' => Announcement::active()->paginate(10),
        ]);
    }

    public function show(Request $request, Announcement $announcement): View
    {
        $this->ensureApproved($request);

        abort_unless(Announcement::active()->whereKey($announcement->id)->exists(), 404);

        return view('portal.announcements.show', ['announcement' => $announcement]);
    }

    private function ensureApproved(Request $request): void
    {
        abort_unless($request->user()->canViewMemberContent(), 403, 'Announcements are available once your membership is approved.');
    }
}

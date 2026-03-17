<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::active()->paginate(15);

        return view('pages.announcements.index', compact('announcements'));
    }
}

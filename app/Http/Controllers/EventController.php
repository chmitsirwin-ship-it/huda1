<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::published()->paginate(12);

        return view('pages.events.index', compact('events'));
    }

    public function show(Event $event): View
    {
        abort_unless($event->status->value === 'published', 404);

        return view('pages.events.show', compact('event'));
    }
}

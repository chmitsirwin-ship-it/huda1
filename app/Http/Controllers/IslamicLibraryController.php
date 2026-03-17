<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use App\Models\QuranVerse;
use Illuminate\View\View;

class IslamicLibraryController extends Controller
{
    public function index(): View
    {
        $verses = QuranVerse::paginate(12);
        $hadiths = Hadith::paginate(12);

        return view('pages.quran-hadith.index', compact('verses', 'hadiths'));
    }
}

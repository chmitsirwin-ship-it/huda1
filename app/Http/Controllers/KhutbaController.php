<?php

namespace App\Http\Controllers;

use App\Models\Khutba;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KhutbaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $query = Khutba::published();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title->en', 'like', "%{$search}%")
                    ->orWhere('speaker->en', 'like', "%{$search}%");
            });
        }

        $khutbas = $query->paginate(15);

        return view('pages.khutba.index', compact('khutbas', 'search'));
    }
}

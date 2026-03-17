<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staff = Staff::active()->get();

        return view('pages.staff.index', compact('staff'));
    }
}

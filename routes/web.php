<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\IslamicLibraryController;
use App\Http\Controllers\KhutbaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PrayerTimeController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/prayer-times', [PrayerTimeController::class, 'index'])->name('prayer-times.index');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/islamic-library', [IslamicLibraryController::class, 'index'])->name('islamic-library.index');
Route::get('/khutba', [KhutbaController::class, 'index'])->name('khutba.index');
Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

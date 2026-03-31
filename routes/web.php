<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventEditorController as AdminEventEditorController;
use App\Http\Controllers\Admin\ForumEditorController as AdminForumEditorController;
use App\Http\Controllers\Admin\GalleryEditorController as AdminGalleryEditorController;
use App\Http\Controllers\Admin\LetterEditorController as AdminLetterEditorController;
use App\Http\Controllers\Admin\NewsEditorController as AdminNewsEditorController;
use App\Http\Controllers\Admin\PageEditorController as AdminPageEditorController;
use App\Http\Controllers\Admin\ProjectEditorController as AdminProjectEditorController;
use App\Http\Controllers\Admin\ResourceController as AdminResourceController;
use App\Http\Controllers\Admin\SiteSettingsController as AdminSiteSettingsController;
use App\Http\Controllers\Admin\VideoEditorController as AdminVideoEditorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ForumsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LettersController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\WhatWeDoController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/', HomeController::class)->name('home');
Route::get('/about-ph-haiyan', AboutController::class)->name('about');
Route::get('/what-we-do', WhatWeDoController::class)->name('what-we-do');
Route::get('/projects', ProjectsController::class)->name('projects.index');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/forums', [ForumsController::class, 'index'])->name('forums.index');
Route::get('/letters', [LettersController::class, 'index'])->name('letters.index');
Route::get('/letters/{letter:slug}', [LettersController::class, 'show'])->name('letters.show');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{newsPost:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/events', [EventsController::class, 'index'])->name('events.index');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/support-the-mission', SupportController::class)->name('support');

Route::controller(ContactController::class)->group(function (): void {
    Route::get('/contact', 'index')->name('contact.index');
    Route::post('/contact', 'store')->name('contact.store');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/password', [AdminAuthController::class, 'editPassword'])->name('password.edit');
        Route::put('/password', [AdminAuthController::class, 'updatePassword'])->name('password.update');

        Route::get('/settings', [AdminSiteSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [AdminSiteSettingsController::class, 'update'])->name('settings.update');
        Route::get('/page-editors/{pageKey}', [AdminPageEditorController::class, 'edit'])
            ->whereIn('pageKey', ['home', 'about', 'what-we-do'])
            ->name('page-editors.edit');
        Route::put('/page-editors/{pageKey}', [AdminPageEditorController::class, 'update'])
            ->whereIn('pageKey', ['home', 'about', 'what-we-do'])
            ->name('page-editors.update');

        Route::get('/news-editor', [AdminNewsEditorController::class, 'index'])->name('news.index');
        Route::get('/news-editor/create', [AdminNewsEditorController::class, 'create'])->name('news.create');
        Route::post('/news-editor', [AdminNewsEditorController::class, 'store'])->name('news.store');
        Route::get('/news-editor/{newsPost}/edit', [AdminNewsEditorController::class, 'edit'])->name('news.edit');
        Route::put('/news-editor/{newsPost}', [AdminNewsEditorController::class, 'update'])->name('news.update');
        Route::delete('/news-editor/{newsPost}', [AdminNewsEditorController::class, 'destroy'])->name('news.destroy');

        Route::get('/letters-editor', [AdminLetterEditorController::class, 'index'])->name('letters.index');
        Route::get('/letters-editor/create', [AdminLetterEditorController::class, 'create'])->name('letters.create');
        Route::post('/letters-editor', [AdminLetterEditorController::class, 'store'])->name('letters.store');
        Route::get('/letters-editor/{letter}/edit', [AdminLetterEditorController::class, 'edit'])->name('letters.edit');
        Route::put('/letters-editor/{letter}', [AdminLetterEditorController::class, 'update'])->name('letters.update');
        Route::delete('/letters-editor/{letter}', [AdminLetterEditorController::class, 'destroy'])->name('letters.destroy');

        Route::get('/projects-editor', [AdminProjectEditorController::class, 'index'])->name('projects.index');
        Route::get('/projects-editor/create', [AdminProjectEditorController::class, 'create'])->name('projects.create');
        Route::post('/projects-editor', [AdminProjectEditorController::class, 'store'])->name('projects.store');
        Route::get('/projects-editor/{project}/edit', [AdminProjectEditorController::class, 'edit'])->name('projects.edit');
        Route::put('/projects-editor/{project}', [AdminProjectEditorController::class, 'update'])->name('projects.update');
        Route::delete('/projects-editor/{project}', [AdminProjectEditorController::class, 'destroy'])->name('projects.destroy');

        Route::get('/forums-editor', [AdminForumEditorController::class, 'index'])->name('forums.index');
        Route::get('/forums-editor/create', [AdminForumEditorController::class, 'create'])->name('forums.create');
        Route::post('/forums-editor', [AdminForumEditorController::class, 'store'])->name('forums.store');
        Route::get('/forums-editor/{forumTopic}/edit', [AdminForumEditorController::class, 'edit'])->name('forums.edit');
        Route::put('/forums-editor/{forumTopic}', [AdminForumEditorController::class, 'update'])->name('forums.update');
        Route::delete('/forums-editor/{forumTopic}', [AdminForumEditorController::class, 'destroy'])->name('forums.destroy');

        Route::get('/events-editor', [AdminEventEditorController::class, 'index'])->name('events.index');
        Route::get('/events-editor/create', [AdminEventEditorController::class, 'create'])->name('events.create');
        Route::post('/events-editor', [AdminEventEditorController::class, 'store'])->name('events.store');
        Route::get('/events-editor/{event}/edit', [AdminEventEditorController::class, 'edit'])->name('events.edit');
        Route::put('/events-editor/{event}', [AdminEventEditorController::class, 'update'])->name('events.update');
        Route::delete('/events-editor/{event}', [AdminEventEditorController::class, 'destroy'])->name('events.destroy');

        Route::get('/gallery-editor', [AdminGalleryEditorController::class, 'index'])->name('gallery.index');
        Route::get('/gallery-editor/create', [AdminGalleryEditorController::class, 'create'])->name('gallery.create');
        Route::post('/gallery-editor', [AdminGalleryEditorController::class, 'store'])->name('gallery.store');
        Route::get('/gallery-editor/{galleryItem}/edit', [AdminGalleryEditorController::class, 'edit'])->name('gallery.edit');
        Route::put('/gallery-editor/{galleryItem}', [AdminGalleryEditorController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery-editor/{galleryItem}', [AdminGalleryEditorController::class, 'destroy'])->name('gallery.destroy');

        Route::get('/videos-editor', [AdminVideoEditorController::class, 'index'])->name('videos.index');
        Route::get('/videos-editor/create', [AdminVideoEditorController::class, 'create'])->name('videos.create');
        Route::post('/videos-editor', [AdminVideoEditorController::class, 'store'])->name('videos.store');
        Route::get('/videos-editor/{video}/edit', [AdminVideoEditorController::class, 'edit'])->name('videos.edit');
        Route::put('/videos-editor/{video}', [AdminVideoEditorController::class, 'update'])->name('videos.update');
        Route::delete('/videos-editor/{video}', [AdminVideoEditorController::class, 'destroy'])->name('videos.destroy');

        Route::get('/messages', [AdminContactMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{contactMessage}', [AdminContactMessageController::class, 'show'])->name('messages.show');
        Route::patch('/messages/{contactMessage}', [AdminContactMessageController::class, 'update'])->name('messages.update');
        Route::delete('/messages/{contactMessage}', [AdminContactMessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('/resources/{resource}', [AdminResourceController::class, 'index'])->name('resources.index');
        Route::get('/resources/{resource}/create', [AdminResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources/{resource}', [AdminResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{resource}/{record}/edit', [AdminResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/resources/{resource}/{record}', [AdminResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{resource}/{record}', [AdminResourceController::class, 'destroy'])->name('resources.destroy');
    });
});

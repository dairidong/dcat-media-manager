<?php

use Illuminate\Support\Facades\Route;
use Jatdung\MediaManager\Http\Controllers\MediaManagerController;

$controller = config('admin.extension.media-manager.controller', MediaManagerController::class);

Route::get('media', [$controller, 'index'])->name('media.index');
// Route::get('media/download', [$controller, 'download'])->name('media-download');
Route::delete('media', [$controller, 'destroy'])->name('media.destroy');
Route::delete('media/batch', [$controller, 'batchDestroy'])->name('media.batch-destroy');

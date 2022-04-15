<?php

use Jatdung\MediaManager\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('media', [Controllers\MediaManagerController::class, 'index'])->name('media-index');
Route::get('media/download', [Controllers\MediaManagerController::class, 'download'])->name('media-download');
Route::delete('media/delete', [Controllers\MediaManagerController::class,'delete'])->name('media-delete');
Route::put('media/move', [Controllers\MediaManagerController::class,'move'])->name('media-move');
Route::post('media/upload', [Controllers\MediaManagerController::class,'upload'])->name('media-upload');
Route::post('media/folder', [Controllers\MediaManagerController::class,'newFolder'])->name('media-new-folder');

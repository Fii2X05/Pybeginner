<?php

use App\Http\Controllers\SubmissionController;
Route::post('/submissions', [SubmissionController::class, 'submit']);
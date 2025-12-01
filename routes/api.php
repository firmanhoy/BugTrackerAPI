<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BugController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| Public Routes (tanpa auth)
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/ping', function () {
  return response()->json([
    'message' => 'Bug Tracker API is running',
    'status' => 'OK',
    'timestamp' => now()->toIso8601String()
  ], 200);
});

// Auth public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (butuh Bearer Token / auth:sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

  // ===== AUTH =====
  Route::get('/me', [AuthController::class, 'me']);
  Route::post('/logout', [AuthController::class, 'logout']);

  // ===== USERS =====
  Route::get('/users', [UserController::class, 'index']);           // List users (paginated)
  Route::get('/users/{id}', [UserController::class, 'show']);       // Detail user

  // ===== BUGS =====
  Route::get('/bugs', [BugController::class, 'index']);             // List bugs (paginated + filter)
  Route::post('/bugs', [BugController::class, 'store']);            // Create bug
  Route::get('/bugs/{id}', [BugController::class, 'show']);         // Detail bug
  Route::put('/bugs/{id}', [BugController::class, 'update']);       // Update bug
  Route::delete('/bugs/{id}', [BugController::class, 'destroy']);   // Delete bug

  // Update status bug (plus create history)
  Route::put('/bugs/{id}/status', [BugController::class, 'updateStatus']);

  // History bug (opsional, kalo mau endpoint terpisah)
  // Route::get('/bugs/{id}/history', [BugController::class, 'history']);

  // ===== COMMENTS =====
  Route::get('/bugs/{bugId}/comments', [CommentController::class, 'index']);     // List comments
  Route::post('/bugs/{bugId}/comments', [CommentController::class, 'store']);    // Add comment
  Route::delete('/comments/{id}', [CommentController::class, 'destroy']);        // Delete comment

  // ===== ATTACHMENTS ===== (YANG KURANG INI)
  Route::get('/bugs/{bugId}/attachments', [AttachmentController::class, 'index']);      // List attachments
  Route::post('/bugs/{bugId}/attachments', [AttachmentController::class, 'store']);     // Upload attachment
  Route::get('/attachments/{id}', [AttachmentController::class, 'show']);               // Detail attachment (TAMBAH!)
  Route::get('/attachments/{id}/download', [AttachmentController::class, 'download']);  // Download attachment (TAMBAH!)
  Route::delete('/attachments/{id}', [AttachmentController::class, 'destroy']);         // Delete attachment

  // ===== REPORTS (PM/Admin) =====
  Route::get('/reports/bugs/summary', [ReportController::class, 'summary']);          // Summary report
  Route::get('/reports/bugs/by-status', [ReportController::class, 'byStatus']);       // Report by status
  Route::get('/reports/bugs/by-severity', [ReportController::class, 'bySeverity']);   // Report by severity
});

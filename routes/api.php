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

Route::get('/ping', function () {
  return response()->json(['message' => 'API OK'], 200);
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
  // List users (paginated)
  Route::get('/users', [UserController::class, 'index']);
  // Detail user
  Route::get('/users/{id}', [UserController::class, 'show']);

  // ===== BUGS =====
  Route::get('/bugs', [BugController::class, 'index']);
  Route::post('/bugs', [BugController::class, 'store']);
  Route::get('/bugs/{id}', [BugController::class, 'show']);
  Route::put('/bugs/{id}', [BugController::class, 'update']);
  Route::delete('/bugs/{id}', [BugController::class, 'destroy']);

  // Ubah status bug (plus history)
  Route::put('/bugs/{id}/status', [BugController::class, 'updateStatus']);

  // ===== COMMENTS =====
  Route::get('/bugs/{bugId}/comments', [CommentController::class, 'index']);
  Route::post('/bugs/{bugId}/comments', [CommentController::class, 'store']);
  Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

  // ===== ATTACHMENTS =====
  Route::get('/bugs/{bugId}/attachments', [AttachmentController::class, 'index']);
  Route::post('/bugs/{bugId}/attachments', [AttachmentController::class, 'store']);
  Route::delete('/attachments/{id}', [AttachmentController::class, 'destroy']);

  // ===== REPORTS (PM/Admin) =====
  Route::get('/reports/bugs/summary', [ReportController::class, 'summary']);
  Route::get('/reports/bugs/by-status', [ReportController::class, 'byStatus']);
  Route::get('/reports/bugs/by-severity', [ReportController::class, 'bySeverity']);
});

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBugRequest;
use App\Http\Requests\UpdateBugRequest;
use App\Http\Requests\UpdateBugStatusRequest;
use App\Models\Bug;
use App\Models\BugStatusHistory;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class BugController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bugs",
     *     tags={"Bugs"},
     *     summary="List bug (paginated)",
     *     description="Mengambil daftar bug dengan pagination dan optional filter status/severity.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Nomor halaman",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Jumlah item per halaman",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter status (OPEN, IN_PROGRESS, RESOLVED, CLOSED)",
     *         @OA\Schema(type="string", example="OPEN")
     *     ),
     *     @OA\Parameter(
     *         name="severity",
     *         in="query",
     *         required=false,
     *         description="Filter severity (LOW, MEDIUM, HIGH, CRITICAL)",
     *         @OA\Schema(type="string", example="HIGH")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar bug berhasil diambil"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function index()
    {
        try {
            $page      = request('page', 1);
            $perPage   = request('per_page', 10);
            $status    = request('status');
            $severity  = request('severity');
            $assigneeId = request('assignee_id');
            $startDate = request('start_date');
            $endDate   = request('end_date');

            // ENUM valid sesuai dokumen test
            $validStatuses  = ['OPEN', 'IN_PROGRESS', 'RESOLVED', 'CLOSED'];
            $validSeverities = ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'];

            // ❗ Invalid status filter → 400 (TC059)
            if ($status && !in_array($status, $validStatuses, true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status filter',
                ], 400);
            }

            // (Optional) Invalid severity filter → 400 juga, kalau di spec memang diminta
            if ($severity && !in_array($severity, $validSeverities, true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid severity filter',
                ], 400);
            }

            $query = Bug::with(['reporter', 'assignee', 'comments', 'statusHistories']);

            if ($status) {
                $query->byStatus($status);
            }
            if ($severity) {
                $query->bySeverity($severity);
            }
            if ($assigneeId) {
                $query->byAssignee($assigneeId);
            }
            if ($startDate && $endDate) {
                $query->byDateRange($startDate, $endDate);
            }

            $bugs = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success'    => true,
                'data'       => $bugs->items(),
                'pagination' => [
                    'total'         => $bugs->total(),
                    'per_page'      => $bugs->perPage(),
                    'current_page'  => $bugs->currentPage(),
                    'last_page'     => $bugs->lastPage(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * @OA\Post(
     *     path="/api/bugs",
     *     tags={"Bugs"},
     *     summary="Buat bug baru",
     *     description="QA melaporkan bug baru.",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","reproduction_steps","severity"},
     *             @OA\Property(property="title", type="string", example="Login button tidak berfungsi"),
     *             @OA\Property(property="description", type="string", example="Tombol login tidak merespon."),
     *             @OA\Property(property="reproduction_steps", type="string", example="1. Buka halaman login\n2. Klik tombol login"),
     *             @OA\Property(property="severity", type="string", example="HIGH")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Bug berhasil dibuat"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function store(StoreBugRequest $request)
    {
        try {
            $currentUserId = Auth::id();

            $bug = Bug::create([
                'title' => $request->title,
                'description' => $request->description,
                'reproduction_steps' => $request->reproduction_steps,
                'severity' => $request->severity,
                'status' => 'OPEN',
                'reporter_id' => $currentUserId,
                'assignee_id' => $request->assignee_id,
            ]);

            // Record status history untuk status pertama
            BugStatusHistory::create([
                'bug_id' => $bug->id,
                'user_id' => $currentUserId,
                'old_status' => null,
                'new_status' => 'OPEN',
                'notes' => 'Bug dilaporkan',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bug berhasil dibuat',
                'data' => $bug,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/bugs/{id}",
     *     tags={"Bugs"},
     *     summary="Detail bug",
     *     description="Mengambil detail satu bug lengkap dengan relasi.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID bug",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail bug berhasil diambil"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bug tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function show($id)
    {
        // 1) Validasi format ID (harus angka)
        if (!ctype_digit((string) $id)) {
            return response()->json([
                'success' => false,
                'message' => 'ID bug harus berupa angka yang valid',
            ], 400);
        }

        // 2) Cari bug, kalau tidak ada → 404
        $bug = Bug::with(['reporter', 'assignee', 'comments', 'attachments', 'statusHistories'])
            ->find($id);

        if (!$bug) {
            return response()->json([
                'success' => false,
                'message' => 'Bug tidak ditemukan',
            ], 404);
        }

        // 3) Sukses → 200
        return response()->json([
            'success' => true,
            'data' => $bug,
        ], 200);
    }


    /**
     * @OA\Put(
     *     path="/api/bugs/{id}",
     *     tags={"Bugs"},
     *     summary="Update data bug",
     *     description="Mengubah data bug (title, description, severity, assignee).",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID bug",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Judul baru"),
     *             @OA\Property(property="description", type="string", example="Deskripsi baru"),
     *             @OA\Property(property="reproduction_steps", type="string", example="Step baru"),
     *             @OA\Property(property="severity", type="string", example="MEDIUM"),
     *             @OA\Property(property="assignee_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bug berhasil diupdate"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bug tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function update(UpdateBugRequest $request, $id)
    {
        try {
            $bug = Bug::findOrFail($id);

            // Update fields yang ada
            $updateData = [];
            if ($request->has('title')) $updateData['title'] = $request->title;
            if ($request->has('description')) $updateData['description'] = $request->description;
            if ($request->has('reproduction_steps')) $updateData['reproduction_steps'] = $request->reproduction_steps;
            if ($request->has('severity')) $updateData['severity'] = $request->severity;
            if ($request->has('assignee_id')) $updateData['assignee_id'] = $request->assignee_id;

            $bug->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Bug berhasil diupdate',
                'data' => $bug,
            ], 200);
        } catch (ModelNotFoundException $e) {
            // ID valid tapi bug nggak ketemu → 404 (sesuai TC075)
            return response()->json([
                'success' => false,
                'message' => 'Bug tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            // Error lain tetap 500
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }




    /**
     * @OA\Put(
     *     path="/api/bugs/{id}/status",
     *     tags={"Bugs"},
     *     summary="Update status bug",
     *     description="Mengubah status bug dan mencatat history.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID bug",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="IN_PROGRESS"),
     *             @OA\Property(property="notes", type="string", example="Sedang dikerjakan oleh DEV")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status berhasil diupdate"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Status tidak valid / transition tidak diizinkan"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bug tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function updateStatus(UpdateBugStatusRequest $request, $id)
    {
        try {
            $bug = Bug::findOrFail($id);
            $newStatus = $request->status;
            $oldStatus = $bug->status;
            $currentUserId = Auth::id();

            // Validasi transisi status
            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak bisa transition dari $oldStatus ke $newStatus",
                ], 422);
            }

            $bug->update(['status' => $newStatus]);

            // Record status history
            BugStatusHistory::create([
                'bug_id' => $bug->id,
                'user_id' => $currentUserId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $request->notes ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status bug berhasil diubah',
                'data' => $bug,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/bugs/{id}",
     *     tags={"Bugs"},
     *     summary="Hapus bug",
     *     description="Menghapus bug dari sistem.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID bug",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bug berhasil dihapus"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bug tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $bug = Bug::findOrFail($id);
            $bug->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bug berhasil dihapus',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bug tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Helper: Validasi transisi status
     */
    private function isValidStatusTransition($from, $to)
    {
        $validTransitions = [
            'OPEN' => ['IN_PROGRESS'],
            'IN_PROGRESS' => ['RESOLVED', 'OPEN'],
            'RESOLVED' => ['CLOSED', 'IN_PROGRESS'],
            'CLOSED' => [],
        ];

        return in_array($to, $validTransitions[$from] ?? []);
    }
}

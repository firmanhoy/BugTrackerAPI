<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bug;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class ReportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reports/bugs/summary",
     *     tags={"Reports"},
     *     summary="Ringkasan bug per periode",
     *     description="Mengembalikan total bug dan breakdown status untuk rentang tanggal tertentu.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=true,
     *         description="Tanggal awal (YYYY-MM-DD)",
     *         @OA\Schema(type="string", example="2025-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=true,
     *         description="Tanggal akhir (YYYY-MM-DD)",
     *         @OA\Schema(type="string", example="2025-12-31")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil ringkasan bug"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function bugSummary()
    {
        try {
            $status = request('status');
            $severity = request('severity');
            $startDate = request('start_date');
            $endDate = request('end_date');

            $query = Bug::query();

            if ($status) {
                $query->where('status', $status);
            }
            if ($severity) {
                $query->where('severity', $severity);
            }
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $totalBugs = $query->count();

            // Agregasi per status
            $byStatus = Bug::query()
                ->when($severity, fn($q) => $q->where('severity', $severity))
                ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                ->groupBy('status')
                ->selectRaw('status, COUNT(*) as count')
                ->get();

            // Agregasi per severity
            $bySeverity = Bug::query()
                ->when($status, fn($q) => $q->where('status', $status))
                ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                ->groupBy('severity')
                ->selectRaw('severity, COUNT(*) as count')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_bugs' => $totalBugs,
                    'by_status' => $byStatus,
                    'by_severity' => $bySeverity,
                    'filters' => [
                        'status' => $status,
                        'severity' => $severity,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]
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
     * @OA\Get(
     *     path="/api/reports/bugs/by-status",
     *     tags={"Reports"},
     *     summary="Jumlah bug per status",
     *     description="Mengembalikan jumlah bug untuk setiap status (OPEN, IN_PROGRESS, RESOLVED, CLOSED).",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Data berhasil diambil"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function bugsByStatus()
    {
        try {
            $startDate = request('start_date');
            $endDate = request('end_date');

            $query = Bug::query();

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $bugs = $query->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $bugs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reports/bugs/by-severity",
     *     tags={"Reports"},
     *     summary="Jumlah bug per severity",
     *     description="Mengembalikan jumlah bug untuk setiap severity (LOW, MEDIUM, HIGH, CRITICAL).",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Data berhasil diambil"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function bugsBySeverity()
    {
        try {
            $startDate = request('start_date');
            $endDate = request('end_date');

            $query = Bug::query();

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $bugs = $query->selectRaw('severity, COUNT(*) as count')
                ->groupBy('severity')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $bugs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bug;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class AttachmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bugs/{bugId}/attachments",
     *     tags={"Attachments"},
     *     summary="List file attachment bug",
     *     description="Mengambil semua file yang ter-attach pada suatu bug.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="bugId",
     *         in="path",
     *         required=true,
     *         description="ID bug",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar attachment berhasil diambil"
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
    public function index($bugId)
    {
        try {
            $bug = Bug::findOrFail($bugId);
            $page = request('page', 1);
            $perPage = request('per_page', 10);

            $attachments = Attachment::where('bug_id', $bugId)
                ->with(['user'])
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $attachments->items(),
                'pagination' => [
                    'total' => $attachments->total(),
                    'per_page' => $attachments->perPage(),
                    'current_page' => $attachments->currentPage(),
                    'last_page' => $attachments->lastPage(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bug tidak ditemukan',
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/bugs/{bugId}/attachments",
     *     tags={"Attachments"},
     *     summary="Upload file untuk bug",
     *     description="Meng-upload file (misal screenshot) dan menghubungkannya dengan bug tertentu.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="bugId",
     *         in="path",
     *         required=true,
     *         description="ID bug",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="File yang akan di-upload (max 10MB)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="File berhasil di-upload"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal (ukuran/tipe file salah, dsb)"
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
    public function store($bugId)
    {
        try {
            $bug = Bug::findOrFail($bugId);
            $currentUserId = Auth::id();

            if (!request()->hasFile('file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'File wajib diunggah',
                ], 422);
            }

            $file = request()->file('file');

            // Validasi ukuran file (max 10MB)
            if ($file->getSize() > 10485760) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ukuran file maksimal 10MB',
                ], 422);
            }

            // Simpan file ke storage
            $path = $file->store('bug-attachments', 'local');

            // Buat record attachment
            $attachment = Attachment::create([
                'filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'bug_id' => $bugId,
                'user_id' => $currentUserId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diunggah',
                'data' => $attachment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/attachments/{id}",
     *     tags={"Attachments"},
     *     summary="Hapus attachment",
     *     description="Menghapus file attachment dari bug.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID attachment",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Attachment berhasil dihapus"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Attachment tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function destroy($attachmentId)
    {
        try {
            $attachment = Attachment::findOrFail($attachmentId);
            $currentUserId = Auth::id();

            // Hanya si uploader atau admin yang bisa hapus
            if ($currentUserId !== $attachment->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak authorized untuk menghapus file ini',
                ], 403);
            }

            // Hapus file dari storage
            if (Storage::disk('local')->exists($attachment->file_path)) {
                Storage::disk('local')->delete($attachment->file_path);
            }

            $attachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan',
            ], 404);
        }
    }
}

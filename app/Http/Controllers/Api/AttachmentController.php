<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Bug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Attachments",
 *     description="Endpoints untuk mengelola file lampiran pada bug"
 * )
 */
class AttachmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bugs/{bugId}/attachments",
     *     tags={"Attachments"},
     *     summary="List semua attachment di bug tertentu",
     *     description="Menampilkan daftar semua file yang dilampirkan pada bug, termasuk URL publik untuk diakses client app.",
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
     *         description="List attachments berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="filename", type="string", example="screenshot.png"),
     *                     @OA\Property(property="mime_type", type="string", example="image/png"),
     *                     @OA\Property(property="file_size", type="integer", example=204856),
     *                     @OA\Property(property="url", type="string", example="http://localhost:8000/storage/attachments/bug_1/screenshot.png"),
     *                     @OA\Property(property="uploaded_by", type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="QA User")
     *                     ),
     *                     @OA\Property(property="uploaded_at", type="string", format="date-time", example="2024-12-01T10:30:00Z")
     *                 )
     *             )
     *         )
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
        $bug = Bug::findOrFail($bugId);

        $attachments = Attachment::where('bug_id', $bugId)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'filename' => $attachment->filename,
                    'mime_type' => $attachment->mime_type,
                    'file_size' => $attachment->file_size,
                    'url' => url('storage/' . $attachment->file_path),
                    'uploaded_by' => [
                        'id' => $attachment->user->id,
                        'name' => $attachment->user->name,
                    ],
                    'uploaded_at' => $attachment->created_at,
                ];
            });

        return response()->json([
            'data' => $attachments
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/bugs/{bugId}/attachments",
     *     tags={"Attachments"},
     *     summary="Upload file attachment ke bug",
     *     description="Upload file (screenshot, log, dll) ke bug tertentu. File akan disimpan di storage dan URL publik akan dikembalikan.",
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
     *         description="File yang akan diupload",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="File attachment (max 10MB, format: jpg, jpeg, png, gif, pdf, txt, log, doc, docx)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="File berhasil diupload",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File uploaded successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="filename", type="string", example="screenshot.png"),
     *                 @OA\Property(property="mime_type", type="string", example="image/png"),
     *                 @OA\Property(property="file_size", type="integer", example=204856),
     *                 @OA\Property(property="url", type="string", example="http://localhost:8000/storage/attachments/bug_1/1733052840_screenshot.png"),
     *                 @OA\Property(property="uploaded_by", type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="QA User")
     *                 ),
     *                 @OA\Property(property="uploaded_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal (file terlalu besar atau format tidak didukung)"
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
    public function store(Request $request, $bugId)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,txt,log,doc,docx',
        ], [
            'file.required' => 'File wajib diupload',
            'file.max' => 'Ukuran file maksimal 10MB',
            'file.mimes' => 'Format file tidak didukung. Gunakan: jpg, jpeg, png, gif, pdf, txt, log, doc, docx'
        ]);

        $bug = Bug::findOrFail($bugId);
        $file = $request->file('file');

        // Generate unique filename
        $originalName = $file->getClientOriginalName();
        $timestamp = time();
        $uniqueName = $timestamp . '_' . $originalName;

        // Store file ke folder bug-nya
        $path = $file->storeAs(
            "attachments/bug_{$bugId}",
            $uniqueName,
            'public'
        );

        // Save metadata ke database
        $attachment = Attachment::create([
            'bug_id' => $bugId,
            'user_id' => auth()->id(),
            'filename' => $originalName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'data' => [
                'id' => $attachment->id,
                'filename' => $attachment->filename,
                'mime_type' => $attachment->mime_type,
                'file_size' => $attachment->file_size,
                'url' => url('storage/' . $attachment->file_path),
                'uploaded_by' => [
                    'id' => auth()->user()->id,
                    'name' => auth()->user()->name,
                ],
                'uploaded_at' => $attachment->created_at,
            ]
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/attachments/{id}",
     *     tags={"Attachments"},
     *     summary="Get detail attachment",
     *     description="Mendapatkan informasi lengkap attachment beserta URL publik untuk display di client app.",
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
     *         description="Detail attachment berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="bug_id", type="integer", example=1),
     *                 @OA\Property(property="bug_title", type="string", example="Login button tidak berfungsi"),
     *                 @OA\Property(property="filename", type="string", example="screenshot.png"),
     *                 @OA\Property(property="mime_type", type="string", example="image/png"),
     *                 @OA\Property(property="file_size", type="integer", example=204856),
     *                 @OA\Property(property="url", type="string", example="http://localhost:8000/storage/attachments/bug_1/screenshot.png"),
     *                 @OA\Property(property="uploaded_by", type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="QA User")
     *                 ),
     *                 @OA\Property(property="uploaded_at", type="string", format="date-time")
     *             )
     *         )
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
    public function show($id)
    {
        $attachment = Attachment::with('user:id,name', 'bug:id,title')
            ->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $attachment->id,
                'bug_id' => $attachment->bug_id,
                'bug_title' => $attachment->bug->title,
                'filename' => $attachment->filename,
                'mime_type' => $attachment->mime_type,
                'file_size' => $attachment->file_size,
                'url' => url('storage/' . $attachment->file_path),
                'uploaded_by' => [
                    'id' => $attachment->user->id,
                    'name' => $attachment->user->name,
                ],
                'uploaded_at' => $attachment->created_at,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/attachments/{id}/download",
     *     tags={"Attachments"},
     *     summary="Download attachment",
     *     description="Download file attachment dengan nama original. File akan langsung terdownload ke device client.",
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
     *         description="File berhasil didownload",
     *         @OA\MediaType(
     *             mediaType="application/octet-stream"
     *         )
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
    public function download($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Optional: Check authorization
        // Uncomment jika mau restrict download hanya untuk user yang terlibat di bug
        // if ($attachment->bug->reporter_id !== auth()->id() && 
        //     $attachment->bug->assignee_id !== auth()->id() &&
        //     auth()->user()->role !== 'PM') {
        //     abort(403, 'Unauthorized to download this file');
        // }

        // Check file exists
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return response()->json([
                'message' => 'File not found in storage'
            ], 404);
        }

        return Storage::disk('public')->download(
            $attachment->file_path,
            $attachment->filename
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/attachments/{id}",
     *     tags={"Attachments"},
     *     summary="Delete attachment",
     *     description="Menghapus attachment dari bug. File akan dihapus dari storage dan database.",
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
     *         description="Attachment berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Attachment deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Attachment tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Tidak memiliki akses untuk menghapus attachment ini"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function destroy($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Optional: Check authorization
        // Hanya uploader atau PM yang bisa hapus
        // if ($attachment->user_id !== auth()->id() && auth()->user()->role !== 'PM') {
        //     return response()->json([
        //         'message' => 'Unauthorized to delete this attachment'
        //     ], 403);
        // }

        // Delete file dari storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Delete record dari database
        $attachment->delete();

        return response()->json([
            'message' => 'Attachment deleted successfully'
        ]);
    }
}

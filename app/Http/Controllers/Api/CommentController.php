<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Bug;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class CommentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bugs/{bugId}/comments",
     *     tags={"Comments"},
     *     summary="List komentar untuk 1 bug",
     *     description="Mengambil semua komentar yang terkait dengan bug tertentu.",
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
     *         description="Daftar komentar berhasil diambil"
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

            $comments = Comment::where('bug_id', $bugId)
                ->with(['user'])
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $comments->items(),
                'pagination' => [
                    'total' => $comments->total(),
                    'per_page' => $comments->perPage(),
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bug tidak ditemukan',
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/bugs/{bugId}/comments",
     *     tags={"Comments"},
     *     summary="Tambah komentar ke bug",
     *     description="Menambahkan komentar baru pada bug tertentu.",
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
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Sudah di-test, masih muncul error di step 3.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Komentar berhasil ditambahkan"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validasi gagal - content wajib diisi"
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
    public function store(StoreCommentRequest $request, $bugId)
    {
        try {
            // Check bug exists FIRST (before creating comment)
            $bug = Bug::findOrFail($bugId);

            $currentUserId = Auth::id();

            $comment = Comment::create([
                'content' => $request->content,
                'bug_id' => $bugId,
                'user_id' => $currentUserId,
            ]);

            $comment->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan',
                'data' => $comment,
            ], 201);
        } catch (ModelNotFoundException $e) {
            // FIX TC100: Return 404 for nonexistent bug (instead of 500)
            return response()->json([
                'success' => false,
                'message' => 'Bug tidak ditemukan',
            ], 404);
        } catch (ValidationException $e) {
            //  Return 400 for validation errors (instead of 422)
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{id}",
     *     tags={"Comments"},
     *     summary="Hapus komentar",
     *     description="Menghapus komentar (hanya oleh pemilik komentar atau role tertentu).",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID komentar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Komentar berhasil dihapus"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Komentar tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function destroy($commentId)
    {
        try {
            $comment = Comment::findOrFail($commentId);
            $currentUserId = Auth::id();

            // Hanya si pembuat komentar atau admin yang bisa hapus
            if ($currentUserId !== $comment->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak authorized untuk menghapus komentar ini',
                ], 403);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan',
            ], 404);
        }
    }
}

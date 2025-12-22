<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;


class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register user baru",
     *     description="Membuat akun baru (QA, DEV, PM) dan langsung mengembalikan token Sanctum.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation","role"},
     *             @OA\Property(property="name", type="string", example="QA User"),
     *             @OA\Property(property="email", type="string", format="email", example="qa@test.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *             @OA\Property(property="role", type="string", example="QA", description="QA, DEV, atau PM")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registrasi berhasil"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal (misal email sudah digunakan)"
     *     )
     * )
     */
    public function register(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'QA',
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            // RESPONSE STRUCTURE YANG BENAR
            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'token' => $token,
                ]
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate email
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email sudah terdaftar',
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login & dapatkan token",
     *     description="Login menggunakan email & password, mengembalikan token Sanctum.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="qa@test.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil, token dikembalikan"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Kredensial salah / data tidak valid"
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah',
                ], 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout user",
     *     description="Menghapus token aktif sehingga tidak bisa digunakan lagi.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout berhasil"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function logout()
    {
        try {
            // Get current authenticated user
            $user = Auth::user();

            if ($user) {
                // Delete current token
                $user->currentAccessToken()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     tags={"Auth"},
     *     summary="Profil user yang sedang login",
     *     description="Mengembalikan data user berdasarkan token yang dikirim di header Authorization.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data user"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tidak terautentikasi"
     *     )
     * )
     */
    public function me()
    {
        try {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}

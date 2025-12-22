<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Bug Tracker API",
 *     version="1.0.0",
 *     description="Dokumentasi REST API Bug Tracker untuk QA, DEV, dan PM."
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local development server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token",
 *     description="Masukkan token dari /api/login atau /api/register."
 * )
 */
class SwaggerController extends Controller {}

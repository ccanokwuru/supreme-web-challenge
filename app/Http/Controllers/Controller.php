<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Supreme Wallet API Documentation",
 *     description="API documentation for Supreme Wallet application",
 *     @OA\Contact(
 *         email="cccanoks@gmail.com",
 *         name="API Support"
 *     )
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 * @OA\Security({"bearerAuth": "Bearer {}"})
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

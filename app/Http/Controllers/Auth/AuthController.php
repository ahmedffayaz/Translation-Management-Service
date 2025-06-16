<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\ApiJsonResponseHelper;
use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return ApiJsonResponseHelper::apiValidationFailResponse($validator);
        }
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $token = JWTAuth::fromUser($user);
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage(),
            ], 404);
        }
        return ApiJsonResponseHelper::successResponse($token, "User Registerd Successfully");
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User Login",
     *     description="Authenticate user and return a JWT token",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User Login Successfully"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi..."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Ahmad Fayyaz"),
     *                 @OA\Property(property="email", type="string", example="user@example.com")
     *             )
     *         )
     *     ),
     * )
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return ApiJsonResponseHelper::apiValidationFailResponse($validator);
        }
        try {
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return ApiJsonResponseHelper::apiUserNotFoundResponse('Invalid credentials');
            }
            $user = User::where('email', $request->email)->first();
            $message = "User Login Successfully";
            return ApiJsonResponseHelper::apiJsonAuthResponse($user, $token, $message);
        } catch (\Exception $e) {
            return ApiJsonResponseHelper::apiUserNotFoundResponse($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/auth-user",
     *     summary="Get authenticated user",
     *     description="Returns the currently authenticated user with related auth data",
     *     tags={"Auth"},
     *     security={{"bearer_token":{}}},
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Ahmad Fayyaz"),
     *                 @OA\Property(property="email", type="string", example="ahmad@example.com"),
     *                 @OA\Property(property="auth", type="object", description="Additional related data")
     *             )
     *         )
     *     ),
     * )
     */

    public function getAuthUser()
    {
        $user = auth()->user();
        return ApiJsonResponseHelper::successResponse($user, "Success");
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->header('Authorization');
            JWTAuth::setToken($token)->invalidate();
            return ApiJsonResponseHelper::successResponse([], "User logged out successfully");
        } catch (\Exception $e) {
            return ApiJsonResponseHelper::apiUserNotFoundResponse($e->getMessage());
        }
    }
}

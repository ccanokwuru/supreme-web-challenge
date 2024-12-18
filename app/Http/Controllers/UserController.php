<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for managing user resources including CRUD operations and authentication"
 * )
 */
class UserController extends Controller
{
    /**
     * Retrieve all users from the database.
     *
     * @OA\Get(
     *     path="/api/users",
     *     summary="Retrieve a list of all registered users",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved list of users",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse Returns JSON response containing paginated users
     */
    public function all_users(Request $request)
    {
        return response()->json(User::paginate($request->input('per_page', 15), ['*'], 'page', $request->input('page', 1)));
    }

    /**
     * Register a new user in the system.
     *
     * @OA\Post(
     *     path="/api/users/register",
     *     summary="Register a new user account",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", maxLength=255, description="User's full name"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, description="User's email address"),
     *             @OA\Property(property="password", type="string", minLength=8, description="User's password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully registered",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed due to invalid input"
     *     )
     * )
     * @param \Illuminate\Http\Request $request The request containing user registration data
     * @return \Illuminate\Http\JsonResponse Returns JSON response with newly created user data
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create($validated);

        return response()->json(['user' => $user], 201);
    }

    /**
     * Retrieve a specific user by ID.
     *
     * @OA\Get(
     *     path="/api/users/{user}",
     *     summary="Retrieve detailed information about a specific user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="Unique identifier of the user",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details successfully retrieved",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Specified user not found"
     *     )
     * )
     * @param \App\Models\User $user The user model instance to retrieve
     * @return \Illuminate\Http\JsonResponse Returns JSON response with user details
     */
    public function get_user(User $user)
    {
        return response()->json(['user' => $user]);
    }

    /**
     * Update user information.
     *
     * @OA\Put(
     *     path="/api/users/{user}",
     *     summary="Update an existing user's information",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="Unique identifier of the user to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, description="Updated user name"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, description="Updated email address"),
     *             @OA\Property(property="password", type="string", minLength=8, description="Updated password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User information successfully updated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Specified user not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed due to invalid input"
     *     )
     * )
     * @param \Illuminate\Http\Request $request The request containing update data
     * @param \App\Models\User $user The user model instance to update
     * @return \Illuminate\Http\JsonResponse Returns JSON response with updated user data
     */
    public function update_user(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
        ]);

        $user->update($validated);

        return response()->json(['user' => $user]);
    }

    /**
     * Delete a user account.
     *
     * @OA\Delete(
     *     path="/api/users/{user}",
     *     summary="Remove a user account from the system",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="Unique identifier of the user to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Specified user not found"
     *     )
     * )
     * @param \App\Models\User $user The user model instance to delete
     * @return \Illuminate\Http\JsonResponse Returns empty JSON response with 204 status
     */
    public function delete_account(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Authenticate user and generate access token.
     *
     * @OA\Post(
     *     path="/api/users/login",
     *     summary="Authenticate user and generate access token",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(
     *                  property="email", 
     *                  type="string", 
     *                  format="email",
     *                  description="User's registered email address"
     *             ),
     *             @OA\Property(
     *                  property="password", 
     *                  type="string", 
     *                  format="password",
     *                  description="User's password"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                  property="token", 
     *                  type="string",
     *                  description="JWT access token"
     *             ),
     *             @OA\Property(
     *                  property="user", 
     *                  ref="#/components/schemas/User"
     *             ),
     *             @OA\Property(
     *                  property="token_type", 
     *                  type="string", 
     *                  example="Bearer",
     *                  description="Type of authentication token"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication failed due to invalid credentials"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed due to invalid input"
     *     )
     * )
     * @param \Illuminate\Http\Request $request The request containing login credentials
     * @return \Illuminate\Http\JsonResponse Returns JSON response with authentication token and user data
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $request->user()?->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Search and filter users with pagination.
     *
     * @OA\Get(
     *     path="/api/users/search",
     *     summary="Search and filter users with pagination support",
     *     description="Search users by name, email, or role with customizable pagination",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search term for filtering users by name or email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filter users by their assigned role",
     *         required=false,
     *         @OA\Schema(type="string", enum={"admin", "user"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of users to return per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved filtered and paginated users",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", description="Current page number"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *             @OA\Property(property="total", type="integer", description="Total number of matching users"),
     *             @OA\Property(property="per_page", type="integer", description="Number of users per page"),
     *             @OA\Property(property="last_page", type="integer", description="Last page number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed due to invalid input"
     *     )
     * )
     * @param \Illuminate\Http\Request $request The request containing search and pagination parameters
     * @return \Illuminate\Http\JsonResponse Returns JSON response with paginated search results
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $role = $request->input('role');
        $perPage = $request->input('per_page', 10);

        $users = User::query();

        if ($query) {
            $users->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            });
        }

        if ($role) {
            $users->where('role', $role);
        }

        $users = $users->paginate($perPage, ['*'], 'page', $request->input('page', 1));
        return response()->json($users);
    }

    /**
     * @OA\Put(
     *     path="/api/users/change-password",
     *     summary="Change user password",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password", "new_password", "new_password_confirmation"},
     *             @OA\Property(property="current_password", type="string", description="Current password"),
     *             @OA\Property(property="new_password", type="string", description="New password"),
     *             @OA\Property(property="new_password_confirmation", type="string", description="Confirm new password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password changed successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized access"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Current password is incorrect"
     *     )
     * )
     * @param \Illuminate\Http\Request $request The request containing password change data
     * @return \Illuminate\Http\JsonResponse Returns JSON response with status message
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     tags={"Users"},
     *     summary="Send password reset link",
     *     description="Sends a password reset link to the user's email",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", description="User's email address")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset link sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset link sent to your email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     * @param \Illuminate\Http\Request $request The request containing user's email
     * @return \Illuminate\Http\JsonResponse Returns JSON response with status message
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link sent to your email'
            ]);
        }

        return response()->json([
            'message' => 'Unable to send reset link'
        ], 400);
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     tags={"Users"},
     *     summary="Reset password",
     *     description="Reset user's password using token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token", "email", "password", "password_confirmation"},
     *             @OA\Property(property="token", type="string", description="Password reset token"),
     *             @OA\Property(property="email", type="string", format="email", description="User's email address"),
     *             @OA\Property(property="password", type="string", description="New password"),
     *             @OA\Property(property="password_confirmation", type="string", description="Confirm new password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid token"
     *     )
     * )
     * @param \Illuminate\Http\Request $request The request containing reset password data
     * @return \Illuminate\Http\JsonResponse Returns JSON response with status message
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password reset successfully'
            ]);
        }

        return response()->json([
            'message' => 'Invalid token'
        ], 400);
    }
}

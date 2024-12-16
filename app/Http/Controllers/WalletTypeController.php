<?php

/**
 * Class WalletTypeController
 * 
 * Controller for managing wallet types in the application.
 * Provides CRUD operations and search functionality for wallet types.
 * 
 * @package App\Http\Controllers
 */

namespace App\Http\Controllers;

use App\Models\WalletType;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Wallet Types",
 *     description="API Endpoints for managing wallet types"
 * )
 */
class WalletTypeController extends Controller
{
    /**
     * Display a listing of all wallet types.
     * 
     * @OA\Get(
     *     path="/api/wallet-types",
     *     summary="Get all wallet types",
     *     description="Returns a paginated list of all wallet types",
     *     operationId="indexWalletTypes",
     *     tags={"Wallet Types"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/WalletType")),
     *             @OA\Property(property="first_page_url", type="string"),
     *             @OA\Property(property="from", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="last_page_url", type="string"),
     *             @OA\Property(property="next_page_url", type="string", nullable=true),
     *             @OA\Property(property="path", type="string"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="prev_page_url", type="string", nullable=true),
     *             @OA\Property(property="to", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(WalletType::paginate(10));
    }

    /**
     * Store a newly created wallet type in storage.
     *
     * @OA\Post(
     *     path="/api/wallet-types",
     *     summary="Create a new wallet type",
     *     description="Creates a new wallet type and stores it in the database",
     *     operationId="storeWalletType",
     *     tags={"Wallet Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Wallet type data",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Savings"),
     *             @OA\Property(property="description", type="string", nullable=true, example="For saving money")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Wallet type created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/WalletType")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $walletType = WalletType::create($validated);
        return response()->json($walletType, 201);
    }

    /**
     * Display the specified wallet type.
     *
     * @OA\Get(
     *     path="/api/wallet-types/{id}",
     *     summary="Get a specific wallet type",
     *     description="Returns details of a specific wallet type",
     *     operationId="showWalletType",
     *     tags={"Wallet Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of wallet type to return",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/WalletType")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wallet type not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found")
     *         )
     *     )
     * )
     *
     * @param  \App\Models\WalletType  $walletType
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(WalletType $walletType)
    {
        return response()->json($walletType);
    }

    /**
     * Update the specified wallet type in storage.
     *
     * @OA\Put(
     *     path="/api/wallet-types/{id}",
     *     summary="Update a wallet type",
     *     description="Updates an existing wallet type",
     *     operationId="updateWalletType",
     *     tags={"Wallet Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of wallet type to update",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated wallet type data",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Updated Savings"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Updated description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wallet type updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/WalletType")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wallet type not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WalletType  $walletType
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, WalletType $walletType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $walletType->update($validated);
        return response()->json($walletType);
    }

    /**
     * Remove the specified wallet type from storage.
     *
     * @OA\Delete(
     *     path="/api/wallet-types/{id}",
     *     summary="Delete a wallet type",
     *     description="Deletes an existing wallet type",
     *     operationId="destroyWalletType",
     *     tags={"Wallet Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of wallet type to delete",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Wallet type deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wallet type not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found")
     *         )
     *     )
     * )
     *
     * @param  \App\Models\WalletType  $walletType
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(WalletType $walletType)
    {
        $walletType->delete();
        return response()->json(null, 204);
    }

    /**
     * Search for wallet types based on given parameters.
     *
     * @OA\Get(
     *     path="/api/wallet-types/search",
     *     summary="Search wallet types",
     *     description="Search for wallet types based on name, description, or status",
     *     operationId="searchWalletTypes",
     *     tags={"Wallet Types"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search query string for name or description",
     *         required=false,
     *         @OA\Schema(type="string", example="savings")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"active", "inactive"})
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort field",
     *         required=false,
     *         @OA\Schema(type="string", enum={"name", "created_at"}, default="created_at")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Sort order",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/WalletType")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid parameters",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid parameters provided")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');

        $walletTypes = WalletType::query();

        if ($query) {
            $walletTypes->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            });
        }

        if ($status) {
            $walletTypes->where('status', $status);
        }

        $walletTypes->orderBy($sort, $order);

        return response()->json($walletTypes->get());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Wallets",
 *     description="API Endpoints for managing wallets"
 * )
 */
class WalletController extends Controller
{
    /**
     * Display a listing of all wallets
     * 
     * @OA\Get(
     *     path="/api/wallets",
     *     operationId="getWallets",
     *     summary="Display a listing of all wallets",
     *     tags={"Wallets"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of all wallets",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Wallet")
     *             ),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        return response()->json(
            Wallet::paginate(
                $perPage,
                ['*'],
                'page',
                $request->input('page', 1)
            )
        );
    }

    /**
     * Store a newly created wallet
     * 
     * @OA\Post(
     *     path="/api/wallets",
     *     operationId="storeWallet",
     *     summary="Store a newly created wallet",
     *     tags={"Wallets"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Wallet creation data",
     *         @OA\JsonContent(
     *             required={"name", "balance"},
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="balance", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Wallet created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="wallet", ref="#/components/schemas/Wallet")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric'
        ]);

        $wallet = Wallet::create($validated);

        // $wallet = $wallet->with('user');

        return response()->json([
            'message' => 'Wallet created successfully',
            'wallet' => $wallet
        ], 201);
    }

    /**
     * Display the specified wallet
     * 
     * @OA\Get(
     *     path="/api/wallets/{wallet}",
     *     operationId="showWallet",
     *     summary="Display the specified wallet",
     *     tags={"Wallets"},
     *     @OA\Parameter(
     *         name="wallet",
     *         in="path",
     *         required=true,
     *         description="ID of wallet",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wallet details",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/Wallet"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wallet not found"
     *     )
     * )
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $wallet = Wallet::find($id);
        return response()->json($wallet);
    }

    /**
     * Update the specified wallet
     * 
     * @OA\Put(
     *     path="/api/wallets/{wallet}",
     *     operationId="updateWallet",
     *     summary="Update the specified wallet",
     *     tags={"Wallets"},
     *     @OA\Parameter(
     *         name="wallet",
     *         in="path",
     *         required=true,
     *         description="ID of wallet",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Wallet update data",
     *         @OA\JsonContent(
     *             required={"name", "balance"},
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="balance", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wallet updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="wallet", ref="#/components/schemas/Wallet")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wallet not found"
     *     )
     * )
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric'
        ]);

        $wallet = Wallet::findOrFail($id);
        $wallet->update($validated);

        return response()->json([
            'message' => 'Wallet updated successfully',
            'wallet' => $wallet
        ]);
    }

    /**
     * Remove the specified wallet
     * 
     * @OA\Delete(
     *     path="/api/wallets/{wallet}",
     *     operationId="deleteWallet",
     *     summary="Remove the specified wallet",
     *     tags={"Wallets"},
     *     @OA\Parameter(
     *         name="wallet",
     *         in="path",
     *         required=true,
     *         description="ID of wallet",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wallet deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wallet not found"
     *     )
     * )
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $wallet = Wallet::findOrFail($id);
        $wallet->delete();

        return response()->json([
            'message' => 'Wallet deleted successfully'
        ]);
    }

    /**
     * Search wallets by name
     * 
     * @OA\Get(
     *     path="/api/wallets/search",
     *     operationId="searchWallets",
     *     summary="Search wallets by name",
     *     tags={"Wallets"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=true,
     *         description="Search query string",
     *         @OA\Schema(type="string", minLength=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of matching wallets",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Wallet")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:1'
        ]);

        $wallets = Wallet::where('name', 'like', '%' . $validated['query'] . '%')->get();

        return response()->json($wallets);
    }
}

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
     *     @OA\Response(
     *         response=200,
     *         description="List of all wallets",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  property="wallets", 
     *                  type="array", 
     *                  @OA\Items(ref="#/components/schemas/Wallet")
     *             )
     *         )
     *     )
     * )
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'wallets' => Wallet::all()
        ]);
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
     *             @OA\Property(property="wallet", ref="#/components/schemas/Wallet")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wallet not found"
     *     )
     * )
     * 
     * @param Wallet $wallet
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Wallet $wallet)
    {
        return response()->json([
            'wallet' => $wallet
        ]);
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
     * @param Wallet $wallet
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric'
        ]);

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
     * @param Wallet $wallet
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Wallet $wallet)
    {
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

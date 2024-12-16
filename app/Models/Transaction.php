<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Transaction",
 *     title="Transaction",
 *     description="Transaction model",
 *     required={"user_id", "amount", "type", "status", "wallet_id", "currency"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="Transaction ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         ref="#/components/schemas/User/properties/id",
 *         description="User ID"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User",
 *         description="User"
 *     ),
 *     @OA\Property(
 *         property="amount",
 *         type="number",
 *         format="float",
 *         description="Transaction amount",
 *         example=100.50
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Transaction type",
 *         example="deposit"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Transaction status",
 *         example="completed"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Transaction description",
 *         example="Deposit to wallet"
 *     ),
 *     @OA\Property(
 *         property="wallet_id",
 *         ref="#/components/schemas/Wallet/properties/id",
 *         description="Wallet ID"
 *     ),
 *     @OA\Property(
 *         property="wallet",
 *         ref="#/components/schemas/Wallet",
 *         description="Wallet"
 *     ),
 *     @OA\Property(
 *         property="currency",
 *         type="string",
 *         description="Transaction currency",
 *         example="USD"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T00:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T00:00:00Z"
 *     )
 * )
 */
class Transaction extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'status',
        'description',
        'wallet_id',
        'currency'
    ];

    /**
     * Get the user that owns the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet associated with the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}

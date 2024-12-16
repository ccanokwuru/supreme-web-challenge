<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      schema="Wallet",
 *      title="Wallet",
 *      description="Wallet model representing a user's financial account",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          format="int64",
 *          description="Unique identifier of the wallet"
 *      ),
 *      @OA\Property(
 *          property="user_id",
 *          ref="#/components/schemas/User/properties/id",
 *          description="ID of the user who owns this wallet"
 *      ),
 *      @OA\Property(
 *          property="user",
 *          ref="#/components/schemas/User",
 *          description="User who owns this wallet"
 *      ),
 *      @OA\Property(
 *          property="wallet_type_id",
 *          ref="#/components/schemas/WalletType/properties/id",
 *          description="ID of the wallet type"
 *      ),
 *      @OA\Property(
 *          property="wallet_type",
 *          ref="#/components/schemas/WalletType",
 *          description="Type of the wallet defining its characteristics"
 *      ),
 *      @OA\Property(
 *          property="balance",
 *          type="number",
 *          format="float",
 *          example=1000.50,
 *          description="Current balance in the wallet"
 *      ),
 *      @OA\Property(
 *          property="currency",
 *          type="string",
 *          example="USD",
 *          description="Currency code of the wallet (e.g., USD, EUR, GBP)"
 *      ),
 *      @OA\Property(
 *          property="transactions",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/Transaction"),
 *          description="List of transactions associated with this wallet"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          description="Timestamp when the wallet was created"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="date-time",
 *          description="Timestamp when the wallet was last updated"
 *      )
 * )
 */
class Wallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'balance',
        'currency',
        'wallet_type_id',
    ];

    /**
     * Get the user that owns the wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet type associated with the wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet_type()
    {
        return $this->belongsTo(WalletType::class);
    }

    /**
     * Get all transactions for the wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

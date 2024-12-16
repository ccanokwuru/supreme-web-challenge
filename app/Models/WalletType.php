<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="WalletType",
 *     title="WalletType",
 *     description="WalletType model",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        format="int64",
 *        example=1
 *    ),
 *    @OA\Property(
 *        property="name",
 *        type="string",
 *        example="Savings"
 *    ),
 *    @OA\Property(
 *        property="description",
 *        type="string",
 *        example="Savings wallet type"
 *    ),
 *    @OA\Property(
 *        property="status",
 *        type="string",
 *        example="active"
 *    ),
 *    @OA\Property(
 *        property="created_at",
 *        type="string",
 *        format="datetime"
 *    ),
 *    @OA\Property(
 *        property="updated_at",
 *        type="string",
 *        format="datetime"
 *    ),
 *    @OA\Property( 
 *         property="wallets",
 *         type="array",
 *         @OA\Items(
 *             ref="#/components/schemas/Wallet"
 *         )
 *     )
 * )
 */
class WalletType extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    /**
     * Get all wallets associated with this wallet type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }
}

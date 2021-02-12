<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount'
    ];

    public static function updateBalance($type, $amout, $userId)
    {
        $wallet = self::where('user_id', $userId);

        if($type == Transaction::TYPE_DEPOSIT)
        {
            $increment = $wallet->increment('amount', $amout);
            if($increment)
                return true;

        }elseif($type == Transaction::TYPE_WITHDRAW)
        {
            $decrement = $wallet->decrement('amount', $amout);
            if($decrement)
                return true;

        }
        return false;

    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}

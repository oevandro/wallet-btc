<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';

    protected $fillable = [
        'user_id',
        'amount',
        'type'
    ];

    public static function deposit($request, $userId)
    {
        $request['user_id'] = $userId;
        $request['type'] = Transaction::TYPE_DEPOSIT;
        $deposit = Transaction::create($request);
        Wallet::updateBalance(self::TYPE_DEPOSIT, $request['amount'], $userId);
        return $deposit;
    }

    public static function withdraw($request, $userId)
    {
        $request['user_id'] = $userId;
        $request['type'] = Transaction::TYPE_WITHDRAW;
        $withdraw = Transaction::create($request);
        Wallet::updateBalance(self::TYPE_WITHDRAW, $request['amount'], $userId);
        return $withdraw;
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}

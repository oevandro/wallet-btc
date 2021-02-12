<?php

namespace App\Models;
use App\Models\Wallet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionBTC extends Model
{
    use HasFactory;

    protected $table = 'transactions_btc';

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';

    protected $fillable = [
        'user_id',
        'amount',
        'amount_btc',
        'amount_btc_current',
        'type'
    ];

    public static function buy($data, $btcData, $userId)
    {
        $btcBuy = ($btcData->ticker->buy);
        $buyAmout = $btcBuy / $data['amount_btc'];

        $data['user_id'] = $userId;
        $data['amount'] = $buyAmout;
        $data['amount_btc_current'] = $btcBuy;
        $data['type'] = self::TYPE_DEPOSIT;
        $buy = self::insert($data);
        if($buy)
        {
            Wallet::updateBalance(self::TYPE_WITHDRAW, $buyAmout, $userId);
            return true;
        }
    }

    public static function sellVerify($data, $btcData, $userId)
    {
        $sellAmout = $data['amount_btc'];

        $allBTCTransaction = self::getAllBTCDepositedTransaction($userId);

        foreach ($allBTCTransaction as $transation) {

            if($sellAmout > $transation['amount_btc'])
            {   $sellAmout = $sellAmout - $transation['amount_btc'];
                self::sell($transation['id'], $btcData);
            }
            else
            {
                $dataBuy['amount_btc'] = $transation['amount_btc'] - $sellAmout;
                self::buy($dataBuy, $btcData, $userId);
            }
        }
        return true;
    }

    public static function sell($transactionId, $btcData)
    {
        $sell = self::find($transactionId);
        $sell =  $sell->update(['type' => self::TYPE_WITHDRAW]);
        $btcSell = ($btcData->ticker->sell);
        $sellAmout = $btcSell / $sell['amount_btc'];

        if($sell)
        {
            Wallet::updateBalance(self::TYPE_DEPOSIT, $sellAmout, $sell['user_id']);
            return true;
        }
    }

    public static function getBTCDepositedAmount($userId)
    {
        $sumBTCAmount = self::selectRaw('sum(amount_btc) as amount_btc')
            ->where('user_id', $userId)
            ->where('type', self::TYPE_DEPOSIT)
            ->value('amount_btc');

        return (int) $sumBTCAmount;

    }

    public static function getAllBTCDepositedTransaction($userId)
    {
        return self::where('user_id', $userId)
            ->where('type', self::TYPE_DEPOSIT)
            ->orderBy('id','asc')
            ->get();

    }

    public static function getAllBTCPositionTransaction($userId, $btcData)
    {
        $transations = self::where('user_id', $userId)
            ->where('type', self::TYPE_DEPOSIT)
            ->orderBy('id','asc')
            ->get();

        $positions = [];

        foreach ($transations as $transation) {
            $percentage_change = (100 -(100*($transation['amount_btc'] / $btcData->ticker->buy)));
            $yield = ($transation['amout'] / $btcData->ticker->buy);
            $transation['percentage_change'] = round($percentage_change, 2);
            $transation['yield'] = $yield;

            $positions[] = $transation;

        }
        return $positions;

    }

    public static function getAllBTCTransaction($userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('id','asc')
            ->get();

    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}

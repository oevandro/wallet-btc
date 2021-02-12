<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TransactionBTC;
use App\Http\Services\TickerBTCMercadoBitcoinServices;
use App\Http\Services\MailServices;
use Illuminate\Support\Facades\Validator;

class TransactionsBTCController extends Controller
{
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function getBTC()
    {
        $btcData = TickerBTCMercadoBitcoinServices::getData();
        return response()->json($btcData);
    }

    public function buy(Request $request)
    {
        $validatedData = self::validateFields($request->all());

        $btcData = TickerBTCMercadoBitcoinServices::getData();
        $buy = TransactionBTC::buy($validatedData, $btcData, $this->user->id);

        if($buy)
        {
            $subject = 'Buy BTC receipt - '.$this->user->name;
            $content = 'Hi ' . $this->user->name . ', ' . $validatedData['amount_btc'] . 'was purchased from BTC';
            MailServices::sendEmail($this->user->name, $this->user->email, $content, $subject);
            return response()->json(true, 200);
        }

        return response()->json(null, 422);
    }

    public function sell(Request $request)
    {
        $validatedData = self::validateFields($request->all());

        $btcData = TickerBTCMercadoBitcoinServices::getData();
        $buy = TransactionBTC::sellVerify($validatedData, $btcData, $this->user->id);

        if($buy)
        {
            $subject = 'Sell BTC receipt - '.$this->user->name;
            $content = 'Hi ' . $this->user->name . ', ' . $validatedData['amount_btc'] . 'was sold from BTC';
            MailServices::sendEmail($this->user->name, $this->user->email, $content, $subject);
            return response()->json(true, 200);
        }

        return response()->json(null, 422);
    }

    public function positions()
    {
        $btcData = TickerBTCMercadoBitcoinServices::getData();
        $response = TransactionBTC::getAllBTCPositionTransaction($this->user->id, $btcData);
        return response()->json($response, 200);
    }

    public function balance()
    {
        $response = TransactionBTC::getBTCDepositedAmount($this->user->id);
        return response()->json($response, 200);
    }

    public function volume()
    {
        return true;
    }

    public function history()
    {
        $response = TransactionBTC::getAllBTCTransaction($this->user->id);
        return response()->json($response, 200);
    }

    private static function validateFields($request)
    {
        $rules = ['amount_btc' => 'required|integer'];
        $validation = Validator::make($request, $rules);

        if ($validation->fails())
        {
            return response()->make((object) $validation->messages(), 400);
        }

        $validatedData = $validation->validated();
        return $validatedData;
    }
}

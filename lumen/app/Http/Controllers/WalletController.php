<?php

namespace App\Http\Controllers;
use App\Http\Services\MailServices;
use Illuminate\Support\Facades\Auth;
use  App\Models\Wallet;

use GuzzleHttp\Client;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->user = auth()->user();
    }


    public function balance()
    {
        $wallet = Wallet::where('user_id', $this->user->id)->value('amount');
        return response()->json((int)$wallet);
    }
}

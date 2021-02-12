<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\MailServices;

class TransactionsController extends Controller
{
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function deposit(Request $request)
    {
        $validatedData = self::validateFields($request->all());
        $deposit = Transaction::deposit($validatedData, $this->user->id);

        $subject = 'Deposit receipt - '.$this->user->name;
        $content = 'Hi ' . $this->user->name . ', ' . $validatedData['amount'] . 'was deposited from your account';
        MailServices::sendEmail($this->user->name, $this->user->email, $content, $subject);

        return $deposit;
    }

    public function withdraw(Request $request)
    {
        $validatedData = self::validateFields($request->all());
        $deposit = Transaction::withdraw($validatedData, $this->user->id);

        $subject = 'Withdrawal receipt - '.$this->user->name;
        $content = 'Hi ' . $this->user->name . ', ' . $validatedData['amount'] . 'was withdrawn from your account';
        MailServices::sendEmail($this->user->name, $this->user->email, $content, $subject);

        return $deposit;
    }

    private static function validateFields($request)
    {
        $rules = ['amount' => 'required|integer'];
        $validation = Validator::make($request, $rules);

        if ($validation->fails())
        {
            return response()->make((object) $validation->messages(), 400);
        }

        $validatedData = $validation->validated();
        return $validatedData;
    }

}

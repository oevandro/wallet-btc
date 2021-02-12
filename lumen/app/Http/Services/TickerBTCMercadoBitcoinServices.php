<?php

namespace App\Http\Services;

class TickerBTCMercadoBitcoinServices
{

    public static function getData()
    {
        $url_ticker = 'https://www.mercadobitcoin.net/api/BTC/ticker/';

        $response =  \json_decode(file_get_contents($url_ticker));

        return  $response;

    }
}

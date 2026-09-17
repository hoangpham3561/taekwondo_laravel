<?php

namespace App\Traits;
use Illuminate\Support\Arr;

trait Coinmarketcap
{
    public function getCoins($start = 1, $limit = 10) {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        try {
            $parameters = [
                'start' => intval($start),
                'limit' => intval($limit),
                'convert' => 'USD'
            ];
            $client = new \GuzzleHttp\Client();
            $request = $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-CMC_PRO_API_KEY' =>  '54822f06-2ad0-4299-9546-2dc74c637ad4',
                ],
                'query' => $parameters
            ]);
            $statusCode = $request->getStatusCode();
            if($statusCode === 200) {
                $data = json_decode($request->getBody(), TRUE);
                // Get response status error code api
                $statusApi = $data['status']['error_code'];
                // Set key data array getBody convert json to array
                $data = $data['data'];
                // Empty array
                $result = [];
                if($statusApi === 0) {
                    if(is_array($data)) {
                        // Mix array convert easy key array
                        foreach($data as $key => $value) {
                            $result[$key]['name'] = $value['name'];
                            $result[$key]['price'] = Arr::get($value, 'quote.USD.price');
                            $result[$key]['percent_change_24h'] = Arr::get($value, 'quote.USD.percent_change_24h');
                        }
                        return $result;
                    }
                }
            }
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
}

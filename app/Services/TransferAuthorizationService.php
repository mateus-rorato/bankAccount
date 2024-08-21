<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class TransferAuthorizationService
{
    public function authorizeTransfer($fromAccountId, $toAccountId, $amount)
    {
        $client = new Client(['verify' => false]);

        try {
            $response = $client->post('https://eo9ggxnfribmy6a.m.pipedream.net/authorizer', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer bXQucm9yYXRvQGdtYWlsLmNvbQ==',
                ],
                'json' => [
                    'sender' => $fromAccountId,
                    'receiver' => $toAccountId,
                    'amount' => $amount,
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if (!$responseBody['authorized']) {
                return false;
            }

            return true;
        } catch (Exception $e) {

            return false;
        }
    }
}

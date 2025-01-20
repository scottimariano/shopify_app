<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopifyWebhookController extends Controller
{
    public function createOrder(Request $request)
    {
        // $hmacHeader = $request->header('X-Shopify-Hmac-SHA256');
        // $calculatedHmac = base64_encode(hash_hmac('sha256', $request->getContent(), env('SHOPIFY_WEBHOOK_SECRET'), true));

        // if (!hash_equals($hmacHeader, $calculatedHmac)) {
        //     Log::warning('Webhook verification failed.');
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
        if ($this->isValidSignature($request)){
            $data = $request->all();
            Log::info('Webhook received:', $data);

            // TODO DISPATCH ORDEN PROCCESSING
            return response()->json(['message' => 'Webhook handled successfully'], 200);

        }

        Log::warning('Webhook verification failed.');
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    protected function isValidSignature (Request $request) :bool
    {
        $hmacHeader = $request->header('X-Shopify-Hmac-SHA256');
        $calculatedHmac = base64_encode(hash_hmac('sha256', $request->getContent(), env('SHOPIFY_WEBHOOK_SECRET'), true));

        if (hash_equals($hmacHeader, $calculatedHmac)) {
            return true;
        }

        Log::warning('Webhook verification failed.');
        return false;
    }
}
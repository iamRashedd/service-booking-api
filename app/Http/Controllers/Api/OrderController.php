<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function checkout(CheckoutRequest $request, OrderService $os){
        try{
            
            $data = $os->checkout(Auth::id(), $request);
            
            return response()->json($data['response'],$data['code']);

        } catch (\Throwable $th){
            Log::error($th);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function index(OrderService $os){
        try{
            $orders = $os->getHistory(Auth::id());
            return response()->json([
                'status' => true,
                'message' => 'Showing order history',
                'orders' => $orders,
            ], Response::HTTP_OK);

        } catch (\Throwable $th){
            Log::error($th);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
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

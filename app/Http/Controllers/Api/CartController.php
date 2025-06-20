<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    public function index(){
        try{

            $carts = Cart::with('cart_items')->where('user_id',Auth::id())->get();
            return response()->json([
                'status' => true,
                'message' => 'Showing all carts',
                'data' => $carts,
            ], Response::HTTP_OK);

        } catch (\Throwable $th){
            Log::error($th);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function show($id, CartService $cs){
        try{
            
            $cart = Cart::with('cart_items')->where('id',$id)->first();

            if(!$cart){
                return response()->json([
                    'status' => false,
                    'message' => 'Cart not found'
                ], Response::HTTP_NOT_FOUND);
            }
            if($cart->user_id != Auth::id()){
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized cart view'
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([
                'status' => true,
                'message' => 'Showing cart',
                'data' => $cart,
            ], Response::HTTP_OK);

        } catch (\Throwable $th){
            Log::error($th);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function addToCart(AddToCartRequest $request, CartService $cs) {
        try{
            
            $cartItem = $cs->addItem(Auth::id(), $request->validated());

            return response()->json([
                'status' => true,
                'data' => $cartItem
            ], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

<?php
namespace App\Services;
use App\Models\Cart;
use App\Models\CartItem;
use Symfony\Component\HttpFoundation\Response;

class CartService {

    public function addItem($user_id, $data) {
        $cart = Cart::with('cart_items')->firstOrCreate(['user_id' => $user_id, 'status' => Cart::ACTIVE]);
        $item = CartItem::where([
            'cart_id' => $cart->id,
            'service_id' => $data['service_id'],
            'schedule_time' => $data['schedule_time']
        ])->first();
        if($item){
            $item->quantity += $data['quantity'] ?? 1;
            $item->note = $data['note'] ?? $item->note;
            $item->save();
        }
        else{
            $cart->cart_items()->create($data);
        }
        return $cart->load('cart_items');
    }
}

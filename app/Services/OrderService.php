<?php
namespace App\Services;

use App\Jobs\SendOrderEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderService {

    protected $userId;

    public function checkout($user_id, $request){
        $this->userId = $user_id;
        return isset($request->cart_id) ? $this->checkoutByCart($request->cart_id) : $this->checkoutByItems($request->cart_items);
    }
    public function checkoutByCart($cart_id){

            $cart = Cart::with('cart_items','cart_items.service','cart_items.service.category')->where(['id'=> $cart_id, 'user_id' => $this->userId, 'status' => Cart::ACTIVE])->first();

            if(!$cart || !$cart->cart_items->count()){
                return [
                    'response' => [
                        'status' => false,
                        'message' => "Empty cart"
                    ],
                    'code' => 403,
                ];
            }
            
            $valid = $cart->cart_items->where('schedule_time', '>=', Carbon::now());
            if(!count($valid)){

                return [
                    'response' => [
                        'status' => false,
                        'message' => "No valid cart items with schedule time",
                    ],
                    'code' => 400,
                ];
            }
            return $this->createOrder($valid);
    }
    public function checkoutByItems($cart_items){
            $cart = Cart::with('cart_items')->where(['user_id' => $this->userId, 'status' => Cart::ACTIVE])->first();

            if(!$cart){
                return [
                    'response' => [
                        'status' => false,
                        'message' => "Empty cart"
                    ],
                    'code' => 403,
                ];
            }
            $invalid = array_diff( $cart_items, $cart->cart_items->pluck('id')->toArray());
            
            if(count($invalid)){
                return [
                    'response' => [
                        'status' => false,
                        'message' => "Invalid cart items! ID:[ ".implode(', ',$invalid)." ]",
                    ],
                    'code' => 400,
                ];
            }
            $items = CartItem::with('service','service.category')->whereIn('id',$cart_items)->get();
            
            $invalid_2 = $items->where('schedule_time', '<', Carbon::now())->pluck('id')->toArray();
            if(count($invalid_2)){

                return [
                    'response' => [
                        'status' => false,
                        'message' => "Invalid schedule time for ID:[ ".implode(', ',$invalid_2)." ]",
                    ],
                    'code' => 400,
                ];
            }
            return $this->createOrder($items);
        
    }
    public function createOrder($data){
        try{
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $this->userId,
                'status' => Order::IN_PROGRESS,
                'total_price' => $data->sum('sub_total_price'),
            ]);
            foreach($data as $i){
                $order->order_items()->create([
                    'service_id' => $i->service->id,
                    'service_name' => $i->service->name,
                    'service_category' => $i->service->category->name,
                    'service_price' => $i->service->price,
                    'schedule_time' => $i->schedule_time,
                    'quantity' => $i->quantity,
                    'note' => $i->note,
                    'status' => OrderItem::IN_PROGRESS,
                ]);
                $i->delete();
            }
            $order->load('order_items');
            DB::commit();
            // Mail::send(new OrderConfirmation($order->id));
            dispatch(new SendOrderEmail($order->id));
            return [
                'response' => [
                    'status' => true,
                    'message' => 'Order created successfully',
                    'order' => $order,
                ],
                'code' => 200,
            ];
        }catch(\Throwable $th){
            DB::rollBack();
            Log::error($th);
            return [
                'response' => [
                    'status' => false,
                    'message' => $th->getMessage(),
                ],
                'code' => 500,
            ];
        }
    }
    public function getHistory($user_id){
        $this->userId = $user_id;
        $orders = Order::with('order_items','user')->where('user_id',$user_id)->get();
        $orders = $orders->map(function ($item){
            return collect([
                'id' => $item->id,
                'ordered_at' => $item->created_at->format('Y-m-d H:i a'),
                'ordered_by' => $item->user->name,
                'order_statu' => $item->status,
                'total_items' => $item->order_items->count(),
                'total_price' => $item->total_price,
                'services' => $item->order_items->map(function ($i){
                    return [
                        'name' => $i->service_name,
                        'time' => $i->schedule_time,
                        'status' => $i->status,
                    ];
                }),
            ]);
        });
        return $orders;
    }
}

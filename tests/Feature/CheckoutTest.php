<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_authenticated_user_can_checkout() {
        Queue::fake();
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'status' => Cart::ACTIVE,
        ]);

        $category = Category::create([
            'name' => 'Car-Service',
        ]);

        $service = Service::create([
            'name' => 'Test Car Service 1',
            'category_id' => $category->id,
            'price' => 100,
        ]);

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'service_id' => $service->id,
            'schedule_time' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/checkout', [
                'cart_id' => $cart->id,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_price' => 200,
        ]);

        $this->assertSoftDeleted('cart_items', [
            'id' => $cartItem->id,
            'cart_id' => $cart->id,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * 🛒 មុខងារបង្ហាញទំព័រ Checkout
     */
    public function checkout()
    {
        // ១. ទាញទិន្នន័យទំនិញពីកន្ត្រក (Cart) ចេញពី Session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->to('/')->with('error', 'កន្ត្រកទំនិញរបស់អ្នកទទេស្អាត! សូមជ្រើសរើសគ្រាប់ពូជសិន។');
        }

        // ២. ចាប់យកព័ត៌មានរបស់ User ដែលកំពុង Login នាពេលបច្ចុប្បន្ន (ស្នូលនៃ Auto-fill)
        $user = Auth::user();

        // ៣. បោះទាំង Cart (បញ្ជីទំនិញ) និង User (ព័ត៌មានអ្នកទិញ) ទៅកាន់ទំព័រ checkout.blade.php
        return view('checkout', compact('cart', 'user'));
    }

    /**
     * 📦 បង្ហាញប្រវត្តិបញ្ជាទិញរបស់អតិថិជនម្នាក់ៗ (Customer Order History)
     */
    public function myOrders()
    {
        //  កែសម្រួល៖ ថែម with('items.seed') ដើម្បីទាញយកមុខទំនិញ និងរូបភាពគ្រាប់ពូជដែលគាត់បានទិញមកជាមួយតែម្តង
        $orders = Order::where('user_id', Auth::id())
                        ->with('items.seed')
                        ->latest()
                        ->get();

        return view('my_orders', compact('orders'));
    }

    /**
     * 💾 ទទួលទិន្នន័យពី Form កន្ត្រកទំនិញ រួចបង្កើតវិក្កយបត្រចូល Database
     */
    public function confirmOrder(Request $request)
    {
        // ១. ចាប់យកទិន្នន័យទំនិញពីកន្ត្រក (Cart) ក្នុង Session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->to('/cart')->with('error', 'កន្ត្រកទំនិញរបស់អ្នកទទេស្អាត មិនអាចទូទាត់បានឡើយ!');
        }

        // ២. ផ្ទៀងផ្ទាត់ទិន្នន័យពី Form
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:50',
            'delivery_address' => 'required|string',
        ]);

        // គណនាតម្លៃសរុបរួមក្នុងកន្ត្រក
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // ៣. ប្រកាសប្រើប្រាស់ DB Transaction ដើម្បីការពារសុវត្ថិភាពទិន្នន័យ
        DB::beginTransaction();

        try {
            // ៤. បង្កើតទិន្នន័យចូលក្នុងតារាង orders
            $order = Order::create([
                'user_id'          => Auth::id(),
                'total_price'      => $total,
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'delivery_address' => $request->delivery_address,
                'status'           => 'pending',
            ]);

            // ៥. រុញគ្រាប់ពូជម្នាក់ៗក្នុង Cart ចូលទៅក្នុងតារាង order_items និងកាត់ស្តុក
            foreach ($cart as $seedId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'seed_id'  => $seedId,
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                //  ទីតាំងត្រឹមត្រូវ៖ កូដកាត់ស្តុកគ្រាប់ពូជស្វ័យប្រវត្ត
                $seed = Seed::find($seedId);
                if ($seed) {
                    // ដកចំនួនស្តុកក្នុង Database ភ្លាមៗ ទៅតាមចំនួន (quantity) ដែលកសិករបានទិញ
                    $seed->decrement('stock', $item['quantity']);
                }
            }

            // ៦. បញ្ជាទិញជោគជ័យហើយ សម្អាតកន្ត្រកទំនិញចោល (Clear Cart Session)
            session()->forget('cart');

            DB::commit();

            // ៧. រុញកសិករទៅកាន់ទំព័រប្រវត្តិកម្មង់ ដើម្បីឱ្យគាត់ឃើញវិក្កយបត្រថ្មីភ្លាមៗ
            return redirect()->to('/my-orders')->with('success', 'ការបញ្ជាទិញគ្រាប់ពូជទទួលបានជោគជ័យ! 🌾');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'មានបញ្ហាបច្ទេកទេស៖ ' . $e->getMessage())->withInput();
        }
    }
}

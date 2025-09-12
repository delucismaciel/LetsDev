<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Service;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Create order
     * in: [service, provider_id, value, title, description]
     * out: [order, payment, status]
     */
    public function create(Request $request) {
        $service = Service::where('name', 'like', '%'.$request->service.'%')->firstOrFail();

        $order = new Order();
        $order->service_id = $service->id;
        $order->provider_id = $request->provider_id;
        $order->address_id = Address::inRandomOrder()->first()->id;
        $order->user_id = $request->user()->id;
        $order->final_price = $request->value;
        $order->title = $request->title;
        $order->description = $request->description;
        $order->status = OrderStatus::PENDING_PAYMENT;
        $order->save();

        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->client_id = $order->user_id;
        $payment->provider_id = $order->provider_id;
        $payment->amount = $order->final_price;
        $payment->getway = 'fakepay';
        $payment->getway_transaction_id = Str::random(20);
        $payment->status = PaymentStatus::PENDING;
        $payment->save();

        return response()->json([
            'order' => $order,
            'payment' => $payment,
            'status' => $order->status,
            'link' => "https://fakepay.com/pay/". $payment->getway_transaction_id
        ]);
    }

    /**
     * Retorna todos os pedidos do cliente logado
     * in: [page, limit, order(recent, value), status]
     * out: [orders]
     */
    public function getAll(Request $request) {
        $ordersQuery = Order::where('user_id', $request->user()->id)
                        ->select(['id','title','final_price','status','created_at'])
                        ->with('payment');


        if($request->filled('status')){
            $ordersQuery->where('status', $request->status);
        }

        if ($request->filled('order')) {
            switch ($request->order) {
                case 'recent':
                    $ordersQuery->orderBy('created_at', 'desc');
                    break;
                case 'value':
                    $ordersQuery->orderBy('final_price', 'desc');
                    break;
                case 'status':
                    $ordersQuery->orderBy('status', 'desc');
                    break;
                default:
                    $ordersQuery->orderBy('created_at', 'desc');
                    break;
            }
        }

        $orders = $ordersQuery->paginate($request->input('limit', 10));

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    /**
     * Retorna todos os dados de um pedido específico
     * in: id
     * out: [order]
     */
    public function get($id, Request $request) {
        //Verifica se o Token é do usuário logado
        if($request->user()->id != Order::findOrFail($id)->user_id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        $order = Order::with('service','provider','payment')->findOrFail($id);
        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }
}

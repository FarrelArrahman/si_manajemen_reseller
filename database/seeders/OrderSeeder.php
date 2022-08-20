<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Models\OrderShipping;
use App\Models\ProductVariant;
use App\Models\User;
use App\Traits\Rajaongkir;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    use Rajaongkir;

    private $couriers = [
        [
            'id' => 1,
            'code' => 'jne',
            'name' => 'JNE',
            'services' => [
                [
                    'service_name' => 'OKE',
                    'price' => 7000
                ],
            ]
        ],
        [
            'id' => 2,
            'code' => 'tiki',
            'name' => 'TIKI',
            'services' => [
                [
                    'service_name' => 'ECO',
                    'price' => 9000
                ],
            ]
        ],
        [
            'id' => 3,
            'code' => 'pos',
            'name' => 'POS',
            'services' => [
                [
                    'service_name' => 'Paket Kilat Khusus',
                    'price' => 13000
                ],
            ]
        ],
    ];

    private $beginDate = "2022-01-01";
    private $currentDate;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->currentDate = date('Y-m-d');
        // Ambil semua data user dengan role reseller
        $users = User::where('role', 'Reseller')->get();
        foreach($users as $user) {
            $reseller = $user->reseller;

            $orderRandom = rand(1,100);
            for($i = 0; $i < $orderRandom; $i++) {
                $dateRandom = rand(strtotime($this->beginDate), strtotime($this->currentDate));
                $totalWeight = 1000;
                $totalPrice = 0;

                $orderCount = Order::whereDate('created_at', today())->count();

                $order = Order::create([
                    'code' => date('Ymd') . sprintf('%04d', $orderCount + 1),
                    'ordered_by' => $reseller->id,
                    'handled_by' => 1,
                    'notes' => null,
                    'total_price' => 0,
                    'date' => date('Y-m-d h:i:s', $dateRandom),
                    'status' => Order::DONE,
                    'admin_notes' => null,
                ]);

                $productVariantRandom = rand(0,10);
                $productVariants = ProductVariant::where('product_variant_status', 1)->inRandomOrder()->take($productVariantRandom)->get();

                foreach($productVariants as $productVariant) {
                    $quantityRandom = rand(1,10);

                    $totalWeight += $quantityRandom * $productVariant->weight;
                    $totalPrice += $quantityRandom * $productVariant->reseller_price;

                    $productVariant->update([
                        'stock' => $productVariant->stock - $quantityRandom
                    ]);

                    $orderDetail = OrderDetail::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $productVariant->id,
                        'quantity' => $quantityRandom,
                        'price' => $productVariant->reseller_price,
                    ]);
                }

                $courierRand = rand(0,2);
                // $serviceDetail = $this->serviceDetailAPI(
                //     $this->configuration()->city, 
                //     $reseller->city,
                //     $totalWeight,
                //     $this->couriers[$courierRand]['code'],
                //     $this->couriers[$courierRand]['services'][0]['service_name']
                // );
                                
                $orderShipping = OrderShipping::create([
                    'order_id' => $order->id,
                    'address' => $reseller->shop_address,
                    'province' => $reseller->province,
                    'city' => $reseller->city,
                    'postal_code' => $reseller->postal_code,
                    'courier_id' => $this->couriers[$courierRand]['id'],
                    'service' => $this->couriers[$courierRand]['name'],
                    'total_weight' => $totalWeight,
                    'total_price' => $this->couriers[$courierRand]['services'][0]['price']
                ]);

                $order->total_price = $totalPrice + $orderShipping->total_price;
                $order->saveQuietly();

                $orderPayment = OrderPayment::create([
                    'order_id' => $order->id,
                    'amount' => $order->total_price,
                    'date' => now(),
                    'payment_status' => OrderPayment::APPROVED,
                    'proof_of_payment' => null,
                    'approved_by' => 1,
                    'admin_notes' => null,
                ]);
            }
        }
    }
}

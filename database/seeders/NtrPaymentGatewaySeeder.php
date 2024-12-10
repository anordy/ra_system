<?php

namespace Database\Seeders;

use App\Models\Ntr\NtrPaymentGateway;
use Illuminate\Database\Seeder;

class NtrPaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentGateways = [
            'PayPal',
            'Stripe',
            'Square',
            'Authorize.Net',
            'Amazon Pay',
            '2Checkout',
            'Adyen',
            'Worldpay',
            'Braintree',
            'Skrill',
            'Payoneer',
            'WePay',
            'Klarna',
            'Alipay',
            'Mollie',
            'BlueSnap',
            'Paysafe',
            'CyberSource',
            'PayU',
            'Razorpay'
        ];

        foreach ($paymentGateways as $gateway) {
            NtrPaymentGateway::updateOrCreate([
                'name' => $gateway
            ]);
        }

    }
}

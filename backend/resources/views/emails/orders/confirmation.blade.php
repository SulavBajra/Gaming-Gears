<x-mail::message>
Dear {{ $order->customer_name }},<br>
Thankyou for you purchase. Your order has been placed successfully.
If you have any questions, please contact us.
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

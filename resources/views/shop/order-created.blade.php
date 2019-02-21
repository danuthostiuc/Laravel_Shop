@component('mail::message')
#New Order  {{ $order->name }}
{{ $order->email }}
{{ $order->comment }}
@component('mail::button', ['url' => url('/order') . '/'.  $order->id])
View order
@endcomponent
Thanks, <br>
{{ config('app.name') }}
@endcomponent

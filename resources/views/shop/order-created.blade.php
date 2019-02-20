@component('mail::message')

    #New Order  {{ $order->name }}


    {{ $order->email }}

    {{ $order->comment }}

    @component('mail::button', ['url' => url('/orders/')])

        View product

    @endcomponent


    Thanks, <br>

    {{ config('app.name') }}

@endcomponent

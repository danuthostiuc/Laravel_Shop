#New Order<br>
{{ $order->name }}
{{ $order->email }}
{{ $order->comment }}
    View order<br>
    <table>
        @foreach ($products as $product)
            <tr>
                <td class="cp_img">
                    <img src="{{ \Storage::url($product->image) }}"/>
                </td>
                <td class="cp_img">
                    <ul>
                        <li>{{ $product->title }}</li>
                        <li>{{ $product->description }}</li>
                        <li>{{ $product->price }}</li>
                    </ul>
                </td>
            </tr>
        @endforeach
    </table>
Thanks,<br>
{{ config('app.name') }}

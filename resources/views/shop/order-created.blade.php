<html>
<head>
    <style>
        table {
            width: 600px;
            border: 1px solid #00086b;
        }

        .cp_img {
            height: 75px;
            width: 75px;
        }

        .cp_img > img {
            max-height: 75px;
            max-width: 75px;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
#New Order<br>
{{ $order->name }}
{{ $order->email }}
{{ $order->comment }}
<br>
<table>
    @foreach ($products as $product)
        <tr>
            <td class="cp_img">
                <img src="{{  asset('/storage/' . $product->image) }}"/>
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
</body>
</html>

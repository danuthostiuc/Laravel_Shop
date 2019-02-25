<html>
<head>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
<h1>
    {{ trans("Order") }}
</h1>
<table>
    <tr>
        <td rowspan="{{ count($order->products) + 1 }}" class="cp_img">
            {{ $order->name }}
        </td>
        <td rowspan="{{ count($order->products) + 1 }}" class="cp_img">
            {{ $order->email }}
        </td>
        <td rowspan="{{ count($order->products) + 1 }}" class="cp_img">
            {{ $order->comment }}
        </td>
    </tr>
    @foreach ($order->products as $row)
        <tr>
            <td class="cp_img">
                <img src="{{ \Storage::url($row->image) }}"/>
            </td>
            <td class="cp_img">
                {{  $row->title }}
            </td>
            <td class="cp_img">
                {{ $row->description }}
            </td>
            <td class="cp_img">
                {{  $row->price }}
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>

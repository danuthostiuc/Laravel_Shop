<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    {{ trans("Index") }}
</h1>
<table>
    @foreach ($products as $row)
        <tr>
            <td class="cp_img">
                <img src="{{ \Storage::url($row["image"]) }}"/>
            </td>
            <td class="cp_img">
                <ul>
                    <li>{{ $row["title"] }}</li>
                    <li>{{ $row["description"] }}</li>
                    <li>{{ $row["price"] }}</li>
                </ul>
            </td>
            <td class="cp_img">
                <a href="?id={{ $row["id"] }}">{{ trans("Add") }}</a>
            </td>
        </tr>
    @endforeach
</table>
<a href="/cart"> {{ trans("Go to cart") }}</a>
</body>
</html>

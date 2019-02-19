<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    {{ trans("Products") }}
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
                <a href="product.php?id={{ $row["id"] }}" class="">{{ trans("Edit") }}</a>
            </td>
            <td class="cp_img">
                <a href="products.php?id={{ $row["id"] }}" class="">{{ trans("Delete") }}</a>
            </td>
        </tr>
    @endforeach
</table>
<a href="product.php"> {{ trans("Add") }}</a>
<a href="products.php?logout"> {{ trans("Logout") }}</a>
</body>
</html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    {{ trans("Cart") }}
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
                <a href="?id={{ $row["id"] }}">{{ trans("Remove") }}</a>

            </td>
        </tr>
    @endforeach
</table>


<br>
<form method="post">
    @csrf
    <input type="text" name="name" value="{{ old("name") }}" placeholder="{{ trans("Name") }}" required>
    <br>
    <input type="text" name="contact" value="{{ old("contact") }}" placeholder="{{ trans("Contact details") }}" required>
    <br>
    <input type="text" name="comment" value="{{ old("comment") }}" placeholder="{{ trans("Comments") }}">
    <br>
    <input type="submit" name="checkout" value="{{ trans("Checkout") }}">
</form>
<a href="index.php">{{ trans("Go to index") }}</a>
</body>
</html>

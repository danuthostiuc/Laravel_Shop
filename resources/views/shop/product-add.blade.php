<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    {{ trans("Product") }}
</h1>

<form method="post" enctype="multipart/form-data" action="/products">
    @csrf
    <input type="text" name="title" value="{{ old('name') }}" placeholder="{{ trans("Title") }}" required>
    <br>
    <input type="text" name="description" value="{{ old('description') }}" placeholder="{{ trans("Description") }}" required>
    <br>
    <input type="number" name="price" value="{{ old('price') }}" placeholder="{{ trans("Price") }}" required>
    <br>
    <input type="file" name="image" accept=".png, .gif, .jpeg, .jpg" required>
    <br>
    <a href="/products">{{ trans("Products") }}</a>
    <input type="submit" name="save" value="{{ trans("Save") }}">
</form>

</body>
</html>

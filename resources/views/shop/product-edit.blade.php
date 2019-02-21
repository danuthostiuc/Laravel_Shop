<html>
<head>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">
</head>
<body>
<h1>
    {{ trans("Product") }}
</h1>

<form method="post" action="/products/{{ \request('id') }}">
    @csrf
    <input type="text" name="title" value="{{ old('name') }}" placeholder="{{ trans("Title") }}" required>
    <br>
    <input type="text" name="description" value="{{ old('description') }}" placeholder="{{ trans("Description") }}" required>
    <br>
    <input type="number" name="price" value="{{ old('price') }}" placeholder="{{ trans("Price") }}" required>
    <br>
    <a href="/products">{{ trans("Products") }}</a>
    <input type="submit" name="save" value="{{ trans("Save") }}">
</form>

</body>
</html>

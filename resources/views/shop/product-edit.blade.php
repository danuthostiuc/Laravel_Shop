<html>
<head>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">
</head>
<body>
<h1>
    {{ trans("Product") }}
</h1>

<form method="post" enctype="multipart/form-data" action="/products/{{ \request('id') }}">
    @csrf
    <input type="text" name="title" value="{{ old('name', $product->first()->title) }}" placeholder="{{ trans("Title") }}"
           required>
    <br>
    <input type="text" name="description" value="{{ old('description', $product->first()->description) }}"
           placeholder="{{ trans("Description") }}" required>
    <br>
    <input type="number" name="price" value="{{ old('price', $product->first()->price) }}" placeholder="{{ trans("Price") }}"
           required>
    <br>
    <input type="file" name="image" accept=".png, .gif, .jpeg, .jpg">
    <br>
    <a href="/products">{{ trans("Products") }}</a>
    <input type="submit" name="save" value="{{ trans("Save") }}">
    @include('shop.errors')
</form>

</body>
</html>

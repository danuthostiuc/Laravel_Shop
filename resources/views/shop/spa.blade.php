<html>
<head>

    <!-- Load stylesheet -->
    <link rel="stylesheet" type="text/css" href="/css/style.css">

    <!-- Load the jQuery JS library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Custom JS script -->
    <script type="text/javascript">
      $(document).ready(function () {

        /**
         * A function that takes a products array and renders it's html
         *
         * The products array must be in the form of
         * [{
         *     "title": "Product 1 title",
         *     "description": "Product 1 desc",
         *     "price": 1
         * },{
         *     "title": "Product 2 title",
         *     "description": "Product 2 desc",
         *     "price": 2
         * }]
         */

        function renderList(products) {
          html = [
            '<tr>',
            '<th>{{ __('Image') }}</th>',
            '<th>{{ __('Details') }}</th>',
            (window.location.hash.match(/#cart/) ? '<th>{{ __("Remove") }}</th>' : '<th>{{ __("Add") }}</th>'),
            '</tr>'
          ].join('');

          $.each(products, function (key, product) {
            html += [
              '<tr>',
              '<td class="cp_img">' + '<img src=storage/' + product.image + '></td>',
              '<td class="cp_img">' +
                '<ul>' +
                    '<li>' + product.title + '</li>' +
                    '<li>' + product.description + '</li>' +
                    '<li>' + product.price + '</li>' +
                '</ul>' +
              '</td>',

              '<td class="cp_img"><a href="' +
              (window.location.hash.match(/#cart(\/id=\d+)*/) ? '#cart/id=' : '#id=') +
              product.id +
              '">' +
              (window.location.hash.match(/#cart(\/id=\d+)*/) ? '{{ __("Remove") }}' : '{{ __("Add") }}') +
              '</a></td>',

              '</tr>'
            ].join('');

          });
          return html;
        }

        function renderAllProducts(products) {
          html = [
            '<tr>',
            '<th>{{ __('Image') }}</th>',
            '<th>{{ __('Details') }}</th>',
            '<th>{{ __('Action') }}',
            '</tr>'
          ].join('');

          $.each(products, function (key, product) {
            html += [
              '<tr>',
              '<td class="cp_img">' + '<img src=storage/' + product.image + '></td>',
              '<td class="cp_img">' +
                '<ul>' +
                    '<li>' + product.title + '</li>' +
                    '<li>' + product.description + '</li>' +
                    '<li>' + product.price + '</li>' +
                '</ul>' +
              '</td>',

              '<td class="cp_img"><a href="#product/id=' +
              product.id +
              '">' +
              '{{ __("Edit") }}' +
              '</a></td>',

              '<td class="cp_img"><a href="#products/id=' +
              product.id +
              '">' +
              '{{ __("Delete") }}' +
              '</a></td>',

              '</tr>'
            ].join('');

          });
          return html;
        }

        /**
         * URL hash change handler
         */
        window.onhashchange = function () {
          // First hide all the pages
          $('.page').hide();
          switch (window.location.hash) {
            //case '#cart':
            case '#cart':
              //alert('simple cart');
              // Show the cart page
              $('.cart').show();
              // Load the cart products from the server
              $.ajax('/cart', {
                dataType: 'json',
                success: function (response) {
                  // Render the products in the cart list
                  $('.cart .list').html(renderList(response));
                }
              });
              break;
            case (window.location.hash.match(/#cart\/id=\d+/) || {}).input:
              //alert('cart with ids');
              $.ajax({
                type: 'get',
                url: '/cart',
                data: {'id': window.location.hash.split('=')[1]},
                dataType: 'json',
                success: function () {
                  console.log('success');
                  $('.cart').show();
                  $.ajax('/cart', {
                    dataType: 'json',
                    success: function (response) {
                      // Render the products in the cart list
                      $('.cart .list').html(renderList(response));
                    }
                  });
                },
                error: function () {
                  console.log('error');
                }
              });
              break;
            case (window.location.hash.match(/#id=\d+/) || {}).input:
              //alert('index with ids');
              $.ajax({
                type: 'get',
                url: '/',
                data: {'id': window.location.hash.split('=')[1]},
                dataType: 'json',
                success: function () {
                  console.log('success');
                  $('.index').show();
                  $.ajax('/', {
                    dataType: 'json',
                    success: function (response) {
                      // Render the products in the index list
                      $('.index .list').html(renderList(response));
                    }
                  });
                },
                error: function () {
                  console.log('error');
                }
              });
              break;
            case '#login':
              $('.login').show();

              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

              $("btn-submit").click(function () {

                var username = $("input[name=username]").val();
                var password = $("input[name=password]").val();

                $.ajax({
                  type: 'post',
                  url: '/login',
                  data: {username: username, password: password},
                  success: function (data) {
                    alert(data.success());
                  },
                  error: function () {
                    alert("Login error");
                  }
                });
              });
              break;
            case '#products':
              $('.products').show();
              $.ajax('/products', {
                dataType: 'json',
                success: function (response) {
                  $('.products .list').html(renderAllProducts(response));
                }
              });
              break;
            case '#product':
              $('.product').show();

              break;
            default:
              //alert('simple index');
              // If all else fails, always default to index
              // Show the index page
              $('.index').show();
              // Load the index products from the server
              $.ajax('/', {
                dataType: 'json',
                success: function (response) {
                  // Render the products in the index list
                  $('.index .list').html(renderList(response));
                }
              });
              break;
          }
        };
        window.onhashchange();
      });
    </script>

</head>
<body>
<!-- The index page -->
<div class="page index">
    <h1>
        {{ __("Index") }}
    </h1>
    <!-- The index element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the cart by changing the hash -->
    <a href="#cart" class="button">Go to cart</a>
</div>

<!-- The cart page -->
<div class="page cart">
    <h1>
        {{ __("Cart") }}
    </h1>
    <!-- The cart element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the index by changing the hash -->
    <a href="#" class="button">Go to index</a>
</div>

<div class="page products">
    <h1>
        {{ __("Products") }}
    </h1>
    <table class="list"></table>

    <a href="#product" class="button">Add</a>

    <a href="#login" class="button">Logout</a>
</div>

<div class="page login">
    <h1>
        {{ __("Log In") }}
    </h1>
    <form method="post" action="#products">
        @csrf
        @include('shop.errors')
        <input type="text" name="username" value="{{ old('username') }}" placeholder="{{ __("Username") }}" required>
        <br>
        <input type="password" name="password" value="{{ old('password') }}" placeholder="{{ __("Password") }}"
               required>
        <br>
        <input class="btn-submit" type="submit" name="submit" value="{{ __("Login") }}">
    </form>
</div>

<div class="page product">
    <h1>
        {{ __("Product") }}
    </h1>
    <form method="post" enctype="multipart/form-data" action="/products/{{ \request('id') }}">
        @csrf
        @include('shop.errors')
        <input type="text"
               name="title"
               value="{{ old('title') }}"
               placeholder="{{ __("Title") }}" required>
        <br>
        <input type="text"
               name="description"
               value="{{ old('description') }}"
               placeholder="{{ __("Description") }}" required>
        <br>
        <input type="number"
               name="price"
               value="{{ old('price') }}"
               placeholder="{{ __("Price") }}" required>
        <br>
        <input
                type="file"
                name="image"
                accept=".png, .gif, .jpeg, .jpg"
                required>
        <br>
        <a href="/products">{{ __("Products") }}</a>
        <input type="submit" name="save" value="{{ __("Save") }}">
    </form>
</div>

</body>
</html>

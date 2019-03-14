<html>
<head>

    <!-- Load stylesheet -->
    <link rel="stylesheet" type="text/css" href="/css/style.css">

    <!-- Load the jQuery JS library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom JS script -->
    <script type="text/javascript">

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

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
            '<table>',
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
                (window.location.hash.match(/#cart(\/\d+)*/) ? '#cart/' : '#') +
                product.id +
                '">' +
                (window.location.hash.match(/#cart(\/\d+)*/) ? '{{ __("Remove") }}' : '{{ __("Add") }}') +
                '</a></td>',

                '</tr>'
            ].join('');
          });
          html += ['</table>'];

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

                '<td class="cp_img"><a href="#product/' +
                product.id +
                '">' +
                '{{ __("Edit") }}' +
                '</a></td>',

                '<td class="cp_img"><a href="#products/' +
                product.id +
                '">' +
                '{{ __("Delete") }}' +
                '</a></td>',

              '</tr>'
            ].join('');
          });
          return html;
        }

        function renderAllOrders(orders) {
          html = [];
          $.each(orders, function (key, order) {
            html += [
              '<tr>',
                '<td>',
                    '<a href="#order/' +
                    order.id +
                    '">' +
                    order.id +
                    '</a>',
                '</td>',
                '<td>' +
                    order.name +
                '</td>',
                '<td>' +
                    order.email +
                '</td>',
                '<td>' +
                    order.comment +
                '</td>',
                '<td>' +
                    order.total +
                '</td>',
              '</tr>'
            ].join('');
          });
          return html;
        }

        function renderSingleOrder(order) {
          html = [
            '<tr>',
                '<td rowspan="' +
                    order.products.length + 1 +
                    '" class="cp_img">' +
                    order.name +
                '</td>',
                '<td rowspan="' +
                    order.products.length + 1 +
                    '" class="cp_img">' +
                    order.email +
                '</td>',
                '<td rowspan="' +
                    order.products.length + 1 +
                    '" class="cp_img">' +
                    order.comment +
                '</td>',
            '</tr>'
          ].join('');

          $.each(order.products, function (key, order) {
            html += [
              '<tr>',
                '<td class="cp_img">',
                    '<img src="storage/' +
                    order.image +
                    '"/>',
                '</td>',
                '<td class="cp_img">' +
                    order.title +
                '</td>',
                '<td class="cp_img">' +
                    order.description +
                '</td>',
                '<td class="cp_img">' +
                    order.price +
                '</td>',
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
              // Show the cart page
              $('.cart').show();
              // Load the cart products from the server
              $.ajax({
                type: 'get',
                url: '/cart',
                dataType: 'json',
                success: function (response) {
                  // Render the products in the cart list
                  $('.cart .list').html(renderList(response));
                }
              });
              break;
            /**
             * case for removing products from cart
             */
            case (window.location.hash.match(/#cart\/\d+/) || {}).input:
              $.ajax({
                type: 'get',
                url: '/cart',
                data: {'id': window.location.hash.split('/')[1]},
                dataType: 'json',
                success: function () {
                  $('.cart').show();
                  $.ajax('/cart', {
                    dataType: 'json',
                    success: function (response) {
                      $('.cart .list').html(renderList(response));
                    }
                  });
                },
              });
              break;
            /**
             * case for adding products to cart
             */
            case (window.location.hash.match(/#\d+/) || {}).input:
              $.ajax({
                type: 'get',
                url: '/',
                data: {'id': window.location.hash.split('#')[1]},
                dataType: 'json',
                success: function () {
                  $('.index').show();
                  $.ajax('/', {
                    dataType: 'json',
                    success: function (response) {
                      $('.index .list').html(renderList(response));
                    }
                  });
                },
              });
              break;
            /**
             * case for log in
             */
            case '#login':
              $.ajax({
                type: 'get',
                url: '/orders',   //the request is sent at this url just to check if admin is set in laravel session
                dataType: 'json',
                success: function () {
                  window.location.hash = '#products';
                  window.onhashchange();
                },
                error: function () {
                  $('.login').show();
                }
              });
              break;
            /**
             * case for showing all products (admin restricted zone)
             */
            case '#products':
              $('.products').show();
              $.ajax({
                type: 'get',
                url: '/products',
                dataType: 'json',
                success: function (response) {
                  $('.products .list').html(renderAllProducts(response));
                },
                error: function () {
                  window.location.hash = '#login';
                  window.onhashchange();
                }
              });
              break;
            /**
             * case for displaying add form (admin)
             */
            case '#product':
              $.ajax({
                type: 'get',
                url: '/orders',   //the request is sent at this url just to check if admin is set in laravel session
                dataType: 'json',
                success: function () {
                  $('input[name=title]').val('');
                  $('input[name=description]').val('');
                  $('input[name=price]').val('');
                  $('input[name=image]').val('');
                  document.getElementById("image").required = true;
                  $('.product').show();
                },
                error: function () {
                  window.location.hash = '#login';
                  window.onhashchange();
                }
              });
              break;
            /**
             * case for displaying edit form (admin)
             */
            case (window.location.hash.match(/#product\/\d+/) || {}).input:
              $.ajax({
                type: 'get',
                url: '/product/' + window.location.hash.split('/')[1],
                dataType: 'json',
                success: function (product) {
                  $('input[name=title]').val(product.title);
                  $('input[name=description]').val(product.description);
                  $('input[name=price]').val(product.price);
                  document.getElementById("image").required = false;
                  $('.product').show();
                },
                error: function () {
                  window.location.hash = '#login';
                  window.onhashchange();
                }
              });
              break;
            /**
             * case for deleting a product (admin)
             */
            case (window.location.hash.match(/#products\/\d+/) || {}).input:
              $.ajax({
                type: 'get',
                url: '/products/' + window.location.hash.split('/')[1],
                dataType: 'json',
                success: function (response) {
                  $('.products').show();
                  $('.products .list').html(renderAllProducts(response));
                }
              });
              break;
            /**
             * case for displaying all orders (admin)
             */
            case '#orders':
              $.ajax({
                type: 'get',
                url: '/orders',
                dataType: 'json',
                success: function (response) {
                  $('.orders').show();
                  $('.orders .list').html(renderAllOrders(response));
                },
                error: function () {
                  window.location.hash = '#login';
                  window.onhashchange();
                }
              });
              break;
            /**
             * case of displaying a single order (admin)
             */
            case (window.location.hash.match(/#order\/\d+/) || {}).input:
              $.ajax({
                type: 'get',
                url: '/order/' + window.location.hash.split('/')[1],
                dataType: 'json',
                success: function (response) {
                  $('.order').show();
                  $('.order .list').html(renderSingleOrder(response));
                },
                error: function () {
                  window.location.hash = '#login';
                  window.onhashchange();
                }
              });
              break;
            default:
              // If all else fails, always default to index
              // Show the index page
              $('.index').show();
              // Load the index products from the server
              $.ajax({
                type: 'get',
                url: '/',
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

        /**
         *  Checkout
         */
        $('#formCart').submit(function (event) {
          event.preventDefault();
          $.ajax({
            type: 'post',
            url: "checkout",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function () {
              $.ajax('/', {
                dataType: 'json',
                success: function (response) {
                  $('.index .list').html(renderList(response));
                }
              });
              window.location.hash = '#';
            },
            error: function (data) {
              if (data.status === 422) {
                var errors = $.parseJSON(data.responseText);
                $.each(errors, function (key, value) {
                  $('.cart .list').addClass("alert alert-danger");
                  if ($.isPlainObject(value)) {
                    $.each(value, function (key, value) {
                      console.log(key + " " + value);
                      $('.cart .list').show().append(value + "<br/>");
                    });
                  } else {
                    $('.cart .list').show().append(value + "<br/>");
                  }
                });
              }
            }
          });
        });

        /**
         *  Log in
         */
        $('#formAdmin').submit(function (event) {
          event.preventDefault();
          $.ajax({
            type: 'post',
            url: 'login',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function () {
              window.location.hash = '#products';
              window.onhashchange();
            },
            error: function (data) {
              if (data.status === 422) {
                var errors = $.parseJSON(data.responseText);
                $.each(errors, function (key, value) {
                  $('.login').addClass("alert alert-danger");
                  if ($.isPlainObject(value)) {
                    $.each(value, function (key, value) {
                      console.log(key + " " + value);
                      $('.login').show().append(value + "<br/>");

                    });
                  } else {
                    $('.login').show().append(value + "<br/>");
                  }
                });
              }
            }
          });
        });

        /**
         * Log out
         */
        $('#logout').on("click", function (event) {
          event.preventDefault();
          $.ajax('/logout', {
            dataType: 'json',
          });
          window.location.hash = '#';
          window.onhashchange();
        });

        /**
         *  Adding/Editing a product
         */
        $('#form').submit(function (event) {
          event.preventDefault();
          $.ajax({
            type: 'post',
            url: 'products' + (window.location.hash.split('/')[1] ? '/' + window.location.hash.split('/')[1] : ''),
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function () {
              window.location.hash = '#products';
              window.onhashchange();
            },
            error: function (data) {
              if (data.status === 422) {
                var errors = $.parseJSON(data.responseText);
                $.each(errors, function (key, value) {
                  $('.product .form').addClass("alert alert-danger");
                  if ($.isPlainObject(value)) {
                    $.each(value, function (key, value) {
                      console.log(key + " " + value);
                      $('.product .form').show().append(value + "<br/> ");
                    });
                  } else {
                    $('.product .form').show().append(value + "<br/>");
                  }
                });
              }
            }
          });
        });

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
    <div class="list"></div>

    <form id="formCart" method="post">
        @csrf
        <input type="text"
               name="name"
               value="{{ old("name") }}"
               placeholder="{{ __("Name") }}"
               required>
        <br>
        <input type="text"
               name="email"
               value="{{ old("email") }}"
               placeholder="{{ __("Contact details") }}"
               required>
        <br>
        <input type="text"
               name="comment"
               value="{{ old("comment") }}"
               placeholder="{{ __("Comments") }}">
        <br>
        <input type="submit"
               name="checkout"
               value="{{ __("Checkout") }}">
    </form>

    <!-- A link to go to the index by changing the hash -->
    <a href="#" class="button">Go to index</a>
</div>

{{--The products page--}}
<div class="page products">
    <h1>
        {{ __("Products") }}
    </h1>
    <table class="list"></table>

    <a href="#product" class="button">Add</a>

    <a id="logout" href="#logout" class="button">Logout</a>
</div>

{{--The login page--}}
<div class="page login">
    <h1>
        {{ __("Log In") }}
    </h1>
    <form id="formAdmin" method="post">
        @csrf
        <input type="text"
               name="username"
               value="{{ old('username') }}"
               placeholder="{{ __("Username") }}"
               required>
        <br>
        <input type="password"
               name="password"
               value="{{ old('password') }}"
               placeholder="{{ __("Password") }}"
               required>
        <br>
        <input type="submit"
               name="login"
               value="{{ __("Login") }}">
    </form>
</div>

{{--The form for adding/editing products--}}
<div class="page product">
    <h1>
        {{ __("Product") }}
    </h1>
    <form id="form" class="form" method="post" enctype="multipart/form-data" target="frame">
        @csrf
        <input type="text"
               name="title"
               value="{{ old('title') }}"
               placeholder="{{ __("Title") }}"
               required>
        <br>
        <input type="text"
               name="description"
               value="{{ old('description') }}"
               placeholder="{{ __("Description") }}"
               required>
        <br>
        <input
                type="number"
                name="price"
                value="{{ old('price') }}"
                placeholder="{{ __("Price") }}"
                required>
        <br>
        <input
                type="file"
                id="image"
                name="image"
                accept=".png, .gif, .jpeg, .jpg">
        <br>
        <a href="#products">{{ __("Products") }}</a>
        <input
                type="submit"
                id="submit"
                name="save"
                value="{{ __("Save") }}">
    </form>
</div>

{{--The orders page--}}
<div class="page orders">
    <h1>
        {{ __("Orders") }}
    </h1>
    <table border="1" class="list"></table>
</div>

{{--The order page--}}
<div class="page order">
    <h1>
        {{ __("Order") }}
    </h1>
    <table border="1" class="list"></table>
</div>

</body>
</html>

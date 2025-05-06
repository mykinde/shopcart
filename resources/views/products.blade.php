<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css">
    <style>
        body{
  padding: 2rem 0rem;
}
.image-parent {
  max-width: 60px;
  border: #F0F0F0 1px solid;
}
        </style>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <h3><a href="/" class="text-decoration-none text-dark">Laravel Cart</a></h3>
                <div class="d-flex w-50">
                    <div class="position-relative" style="width:80%"> <!-- Add a parent div -->
                        <div>
                            <input class="form-control me-2" id="product-search" type="text" autocomplete="off" placeholder="Enter product code, e.g. h123,c456,w789">
                        </div>
                        <div id="product-details-container" class="position-absolute top-100 start-0 bg-light z-2 w-100"></div>
                    </div>
                    <button class="btn btn-outline-success bulk-upload mx-2" type="button">Add to cart</button>
                </div>

                <a href="/cart" class="text-decoration-none"><img class="mx-2" src="{{ asset('images/shopping-cart.svg') }}">View Cart</a>
            </div>

        </nav>

        <div class="row mt-5">
            @foreach ($products as $index => $product)
            <div class="col-md-4 mb-4">
                <div class="card" style="width:23rem">
                    <a href="{{ route('products.view', ['product_code' => $product['code']]) }}"><img src="{{ asset($product['image_path']) }}" class="card-img-top" alt="{{ $product['name'] }}"></a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product['name'] }}</h5>
                        <p class="card-text">Code# {{ $product['code'] }}</p>
                        <p class="card-text">${{ $product['price'] }}</p>
                        <span class="">
                            <span class="btn btn-primary add-text add-to-cart" data-product-code="{{ $product['code'] }}">Add to cart</span> <span class="btn btn-primary d-none adding-text">Adding</span>
                            <span class="btn btn-primary d-none added-text">Added. <a href="/cart" class="text-white text-decoration-none">Go to cart</a></span></span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).click(function(event) {
                if (!$(event.target).closest('#product-details-container').length && !$(event.target).is('#product-search')) {
                    $('#product-details-container').hide();
                }
            });
            $('.add-to-cart').click(function() {
                var addButton = $(this); // Store reference to the clicked button
                var productCode = addButton.data('product-code');
                addButton.closest('div').find('.add-text').hide();
                addButton.closest('div').find('.adding-text').removeClass('d-none');
                $.ajax({
                    type: 'POST',
                    url: '/add-to-cart',
                    data: {
                        product_code: productCode,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        addButton.closest('div').find('.adding-text').addClass('d-none'); // Use addButton here
                        addButton.closest('div').find('.added-text').removeClass('d-none'); // Use addButton here
                    }
                });
            });

            $('.bulk-upload').click(function() {
                var productCodes = $('#product-search').val();
                $.ajax({
                    type: 'POST',
                    url: '/bulk-add-to-cart',
                    data: {
                        product_code: productCodes,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.href = '/cart';
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + xhr.responseText);
                    }
                });

            });

            $('#product-search').keyup(function() {
                var productCode = $(this).val();
                if (productCode.includes(',')) {

                    var productCodes = productCode.split(',');
                }
                // if (productCode.length >= 3) { // Perform search when at least 3 characters are entered
                $.ajax({
                    type: 'GET',
                    url: '/search', // Modify the URL according to your route
                    data: {
                        product_code: productCode
                    },
                    success: function(response) {
                        $('#product-details-container').html(response);
                        $('#product-details-container').show();
                    }
                });
                // }
            });

        });
    </script>
</body>

</html>
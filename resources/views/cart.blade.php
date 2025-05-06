<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-light bg-light mb-5">
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
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <a href="{{ url('clear-cart') }}" id="clear-cart-btn"><button class="btn btn-danger mb-2 float-end">Clear Cart</button></a>
        <table id="cart" class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(session('cart'))

                @foreach(session('cart') as $id => $details)

                <tr rowId="{{ $id }}">
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"><a href="{{ route('products.view', ['product_code' => $details['code']]) }}"><img src="{{ asset($details['image']) }}" width="100" height="80" class="img-responsive" /></a>
                            </div>
                            <div class="col-sm-9">
                                <h5 class="my-auto">{{ $details['name'] }}</h5>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">{{ $details['quantity'] }}</td>
                    <td class="text-end">${{ $details['price'] }}</td>
                    <td class="text-end">${{ $details['quantity'] * $details['price'] }}</td>
                    <td class="text-center">
                        <span>
                            <span class="btn btn-outline-danger remove-text delete-item" data-product-code="{{ $details['code'] }}">Remove Item</span>
                            <span class="btn btn-outline-danger d-none removing-text">Removing</span>
                            <span class="btn btn-outline-danger d-none removed-text">Removed.</span></span>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>

        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).click(function(event) {
                if (!$(event.target).closest('#product-details-container').length && !$(event.target).is('#product-search')) {
                    $('#product-details-container').hide();
                }
            });
            $(".delete-item").click(function() {
                var removeButton = $(this); // Store reference to the clicked button
                var productCode = removeButton.data('product-code');
                if (confirm("Are you sure you want to remove product from the cart?")) {
                    removeButton.closest('div').find('.remove-text').hide();
                    removeButton.closest('div').find('.removing-text').removeClass('d-none');
                    $.ajax({
                        type: 'POST',
                        url: '{{ url("remove-from-cart") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_code: productCode
                        },
                        success: function(response) {
                            removeButton.closest('div').find('.removing-text').addClass('d-none'); // Use addButton here
                            removeButton.closest('div').find('.removed-text').removeClass('d-none'); // Use addButton here
                            window.location.reload();
                        }
                    });
                }
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

            $('#clear-cart-btn').click(function(e) {
                // Prevent the default action of the button (navigating to the clear-cart URL)
                e.preventDefault();

                // Show confirmation dialog
                if (confirm('Are you sure you want to clear your cart?')) {
                    // If user confirms, proceed with navigating to the clear-cart URL
                    window.location.href = $(this).attr('href');
                } else {
                    // If user cancels, do nothing
                    return false;
                }
            });
        });
    </script>
</body>

</html>
@if (!empty($searchResults))

        <ul class="list-group">
        @foreach ($searchResults as $product)
        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center product" data-code="{{ $product['code'] }}" style="cursor: pointer;">
          <div class="flex-column">
          {{ $product['name'] }}
            <p><small>${{ $product['price'] }}</small></p>
          </div>
          <div class="image-parent">
              <img src="{{ asset($product['image_path']) }}" class="img-fluid" alt="quixote">
          </div>
        </a>
        @endforeach
      </ul>

@else
<p class="mx-3 mt-3">No products found.</p>
@endif

<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
   $(document).ready(function() {
        // Initialize an empty array to store product codes
        var productCodes = [];

        // Add click event listener to each product to append its code to the search input box
        $('.product').click(function() {
            var productCode = $(this).data('code');

            // Check if the product code is already in the array, if not, add it
            if (!productCodes.includes(productCode)) {
                productCodes.push(productCode);
            }

            // Set the value of the search input to the joined product codes
            $('#product-search').val(productCodes.join(','));
        });
    });
</script>

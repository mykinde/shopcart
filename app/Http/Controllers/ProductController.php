<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    public function addToCart(Request $request)
    {
        $productCode = $request->input('product_code');

        $product = config('products.' . $productCode);

        $cart = session()->get('cart', []);
        if (isset($cart[$productCode])) {
            $cart[$productCode]['quantity']++;
        } else {
            $cart[$productCode] = [
                "name" => $product['name'],
                "code" => $product['code'],
                "quantity" => 1,
                "price" => $product['price'],
                "image" => $product['image_path']
            ];
        }
        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    public function bulkAddToCart(Request $request)
    {
        $products = $request->input('product_code');

        // Assuming each line in the textarea contains only the product code
        $productCodes = explode(",", $products);

        foreach ($productCodes as $productCode) {
            $product = config('products.' . trim($productCode));
            // If the product exists in the configuration
            if ($product) {
                $cart = session()->get('cart', []);

                if (isset($cart[$productCode])) {
                    // If the product already exists in the cart, increment quantity
                    $cart[$productCode]['quantity']++;
                } else {
                    // If the product doesn't exist in the cart, add it
                    $cart[$productCode] = [
                        'name' => $product['name'],
                        'code' => $product['code'],
                        'quantity' => 1,
                        'price' => $product['price'],
                        'image' => $product['image_path']
                    ];
                }

                session()->put('cart', $cart);
            }
        }

        return response()->json(['success' => true]);
    }

    public function index()
    {
        $products = Config::get('products');
        return view('products', compact('products'));
    }

    public function viewProduct($productCode)
    {
        $productDetails = config('products.' . $productCode);

        return view('view', compact('productDetails'));
    }

    public function productSearch(Request $request)
    {
        $searchTerms = $request->input('product_code');
        $products = Config::get('products');

        $searchTermsArray = explode(",", $searchTerms);

        // Check if the product code exists in the configuration
            $searchResults = [];
            foreach ($searchTermsArray as $searchTerm) {

            foreach ($products as $product) {
                if (stripos($product['code'], $searchTerm) !== false || stripos($product['name'], $searchTerm) !== false) {
                    $searchResults[] = $product;
                }
            }
        }
        $searchResults = array_unique($searchResults, SORT_REGULAR);

            if (!empty($searchResults)) {
                return view('search_results', compact('searchResults'));
            } else {
                // Product not found, return a view indicating no product found
                return view('search_results')->with('productDetails', null);
            }
        
    }
}

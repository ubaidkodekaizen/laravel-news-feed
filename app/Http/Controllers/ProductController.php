<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->get();
        return view('user.user-products', compact('products'));
    }

    public function addEditProduct($id = null)
    {
        $product = $id ? Product::findOrFail($id) : new Product();
        return view('user.add-product', compact('product'));
    }

    public function storeProduct(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'unit_of_quantity' => 'required|string|max:50',
            'product_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $product = $id ? Product::findOrFail($id) : new Product();

        if ($request->hasFile('product_image')) {
            if ($product->product_image && Storage::exists('public/' . $product->product_image)) {
                Storage::delete('public/' . $product->product_image);
            }
            $imagePath = $request->file('product_image')->store('products', 'public');
        } else {
            $imagePath = $product->product_image;
        }

        $product->user_id = Auth::id();
        $product->title = $request->title;
        $product->short_description = $request->short_description;
        $product->original_price = $request->original_price;
        $product->discounted_price = $request->discounted_price;
        $product->quantity = $request->quantity;
        $product->unit_of_quantity = $request->unit_of_quantity;
        $product->product_image = $imagePath;

        $product->save();

        $message = $id ? 'Product updated successfully!' : 'Product created successfully!';
        return redirect()->route('user.products')->with('success', $message);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        if ($product->product_image && Storage::exists('public/' . $product->product_image)) {
            Storage::delete('public/' . $product->product_image);
        }
        $product->delete();
        return redirect()->route('user.products')->with('success', 'Product deleted successfully!');
    }
}

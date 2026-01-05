<?php

namespace App\Http\Controllers\API;

use App\Models\Business\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function apiIndex()
    {
        $products = Product::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $products]);
    }

    public function apiShow($id)
    {
        $product = Product::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }



    public function apiStore(Request $request, $id = null)
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

        $product = $id ? Product::where('user_id', Auth::id())->findOrFail($id) : new Product();

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
        $product->discounted_price = $request->discounted_price ?? null;
        $product->quantity = $request->quantity;
        $product->unit_of_quantity = $request->unit_of_quantity;
        $product->product_image = $imagePath;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => $id ? 'Product updated successfully!' : 'Product created successfully!',
            'data' => $product
        ]);
    }

    public function apiDelete($id)
    {
        $product = Product::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        if ($product->product_image && Storage::exists('public/' . $product->product_image)) {
            Storage::delete('public/' . $product->product_image);
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
    }


}

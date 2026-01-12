<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Business\Product;
use App\Services\S3Service;
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
            'category' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'unit_of_quantity' => 'required|string|max:50',
            'product_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $product = $id ? Product::findOrFail($id) : new Product();

        if ($request->hasFile('product_image')) {
            $s3Service = app(S3Service::class);
            
            // Delete old image from S3 if exists
            if ($product->product_image) {
                $oldPath = $s3Service->extractPathFromUrl($product->product_image);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('product_image'), 'product');
            $imagePath = $uploadResult['url']; // Store full S3 URL
        } else {
            $imagePath = $product->product_image;
        }

        $product->user_id = Auth::id();
        $product->title = $request->title;
        $product->category = $request->category ?? null;
        $product->short_description = $request->short_description;
        $product->original_price = $request->original_price;
        $product->discounted_price = $request->discounted_price ?? null;
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
        // Delete image from S3 if exists
        if ($product->product_image) {
            $s3Service = app(S3Service::class);
            $oldPath = $s3Service->extractPathFromUrl($product->product_image);
            if ($oldPath && str_starts_with($oldPath, 'media/')) {
                $s3Service->deleteMedia($oldPath);
            }
        }
        $product->delete();
        return redirect()->route('user.products')->with('success', 'Product deleted successfully!');
    }
}

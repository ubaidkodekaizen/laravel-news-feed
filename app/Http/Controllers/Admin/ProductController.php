<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business\Product;
use App\Services\S3Service;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * View product details
     */
    public function view($id)
    {
        $product = Product::with('user')->findOrFail($id);
        return view('admin.products-services.view-product', compact('product'));
    }

    /**
     * Show edit product form
     */
    public function edit($id)
    {
        $product = Product::with('user')->findOrFail($id);
        return view('admin.products-services.edit-product', compact('product'));
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit_of_quantity' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $product->update($request->only([
            'title',
            'short_description',
            'original_price',
            'discounted_price',
            'quantity',
            'unit_of_quantity',
        ]));
        
        if ($request->hasFile('product_image')) {
            $s3Service = app(S3Service::class);
            
            // Delete old image if exists
            if ($product->product_image) {
                $oldPath = $s3Service->extractPathFromUrl($product->product_image);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('product_image'), 'products');
            $product->product_image = $uploadResult['url'];
            $product->save();
        }
        
        return redirect()->route('admin.products-services', ['filter' => 'products'])
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product (soft delete)
     */
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        
        // Soft delete - don't delete image, just mark as deleted
        $product->delete();
        
        return redirect()->route('admin.products-services', ['filter' => 'products'])
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Restore product
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        
        return redirect()->route('admin.products-services', ['filter' => 'deleted'])
            ->with('success', 'Product restored successfully!');
    }
}

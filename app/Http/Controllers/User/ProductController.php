<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Business\Product;
use App\Services\S3Service;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $products = Product::where('user_id', Auth::id())->get();
        return view('user.user-products', compact('products'));
    }

    public function addEditProduct($id = null)
    {
        $product = $id ? Product::where('user_id', Auth::id())->findOrFail($id) : new Product();
        return view('user.add-product', compact('product'));
    }

    public function storeProduct(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'unit_of_quantity' => 'required|string|max:50',
            'product_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240', // 10MB max
        ]);

        $product = $id ? Product::where('user_id', Auth::id())->findOrFail($id) : new Product();

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
        $product->quantity = $request->quantity;
        $product->unit_of_quantity = $request->unit_of_quantity;
        $product->product_image = $imagePath;

        $product->save();

        // Send notification if this is a new product (not an update)
        if (!$id) {
            try {
                $productOwner = Auth::user();
                $this->notificationService->sendNewProductNotification($productOwner, $product);
            } catch (\Exception $e) {
                Log::error('Failed to send new product notification', [
                    'error' => $e->getMessage()
                ]);
                // Don't fail the request if notification fails
            }
        }

        $message = $id ? 'Product updated successfully!' : 'Product created successfully!';
        return redirect()->route('user.products')->with('success', $message);
    }

    public function deleteProduct($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
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

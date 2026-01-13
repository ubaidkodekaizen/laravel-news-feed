<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Business\Service;
use App\Services\S3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{


    public function index()
    {
        $services = Service::where('user_id', Auth::id())->get();
        return view('user.user-services', compact('services'));
    }
    public function addEditService($id = null)
    {
        $service = $id ? Service::findOrFail($id) : new Service();
        return view('user.add-services', compact('service'));
    }


    public function storeService(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'duration' => 'required|string|in:Starting,One time,Monthly,Yearly,Quarterly',
            'service_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $service = $id ? Service::findOrFail($id) : new Service();


        if ($request->hasFile('service_image')) {
            $s3Service = app(S3Service::class);
            
            // Delete old image from S3 if exists
            if ($service->service_image) {
                $oldPath = $s3Service->extractPathFromUrl($service->service_image);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('service_image'), 'service');
            $imagePath = $uploadResult['url']; // Store full S3 URL
        } else {
            $imagePath = $service->service_image;
        }

        $service->user_id = Auth::id();
        $service->title = $request->title;
        $service->category = $request->category ?? null;
        $service->short_description = $request->short_description;
        $service->original_price = $request->original_price;
        $service->duration = $request->duration;
        $service->service_image = $imagePath;

        $service->save();

        $message = $id ? 'Service updated successfully!' : 'Service created successfully!';
        return redirect()->route('user.services')->with('success', $message);
    }


    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        // Delete image from S3 if exists
        if ($service->service_image) {
            $s3Service = app(S3Service::class);
            $oldPath = $s3Service->extractPathFromUrl($service->service_image);
            if ($oldPath && str_starts_with($oldPath, 'media/')) {
                $s3Service->deleteMedia($oldPath);
            }
        }
        $service->delete();
        return redirect()->route('user.services')->with('success', 'Service deleted successfully!');
    }


}

<?php
namespace App\Http\Controllers\API;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    // API: List all services
    public function apiIndex()
    {
        $services = Service::where('user_id', Auth::id())
                           ->orderByDesc('id')
                           ->get();

        return response()->json(['success' => true, 'data' => $services]);
    }

    // API: Show a specific service
    public function apiShow($id)
    {
        $service = Service::where('user_id', Auth::id())
                          ->where('id', $id)
                          ->first();

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Service not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => $service]);
    }

    // API: Store or update service
    public function apiStore(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'duration' => 'required|string|in:Starting,One time,Monthly,Yearly,Quarterly',
            'service_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $service = $id ? Service::where('user_id', Auth::id())->find($id) : new Service();

        if ($id && !$service) {
            return response()->json(['success' => false, 'message' => 'Service not found.'], 404);
        }

        if ($request->hasFile('service_image')) {
            if ($service->service_image && Storage::exists('public/' . $service->service_image)) {
                Storage::delete('public/' . $service->service_image);
            }
            $imagePath = $request->file('service_image')->store('services', 'public');
        } else {
            $imagePath = $service->service_image;
        }

        $service->user_id = Auth::id();
        $service->title = $request->title;
        $service->short_description = $request->short_description;
        $service->original_price = $request->original_price;
        $service->discounted_price = $request->discounted_price ?? null;
        $service->duration = $request->duration;
        $service->service_image = $imagePath;

        $service->save();

        return response()->json([
            'success' => true,
            'message' => $id ? 'Service updated successfully!' : 'Service created successfully!',
            'data' => $service
        ]);
    }

    // API: Delete a service
    public function apiDelete($id)
    {
        $service = Service::where('user_id', Auth::id())
                          ->where('id', $id)
                          ->first();

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Service not found.'], 404);
        }

        if ($service->service_image && Storage::exists('public/' . $service->service_image)) {
            Storage::delete('public/' . $service->service_image);
        }

        $service->delete();

        return response()->json(['success' => true, 'message' => 'Service deleted successfully!']);
    }
}

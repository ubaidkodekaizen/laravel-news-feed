<?php


namespace App\Http\Controllers;

use App\Models\Service;
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
            'short_description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'duration' => 'required|string|in:one time,Monthly,Yearly,Quarterly',
            'service_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $service = $id ? Service::findOrFail($id) : new Service();


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
        $service->discounted_price = $request->discounted_price;
        $service->duration = $request->duration;
        $service->service_image = $imagePath;

        $service->save();

        $message = $id ? 'Service updated successfully!' : 'Service created successfully!';
        return redirect()->route('user.services')->with('success', $message);
    }


    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        if ($service->service_image && Storage::exists('public/' . $service->service_image)) {
            Storage::delete('public/' . $service->service_image);
        }
        $service->delete();
        return redirect()->route('user.services')->with('success', 'Service deleted successfully!');
    }


}

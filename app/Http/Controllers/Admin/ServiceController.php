<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business\Service;
use App\Services\S3Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * View service details
     */
    public function view($id)
    {
        $service = Service::with('user')->findOrFail($id);
        return view('admin.products-services.view-service', compact('service'));
    }

    /**
     * Show edit service form
     */
    public function edit($id)
    {
        $service = Service::with('user')->findOrFail($id);
        return view('admin.products-services.edit-service', compact('service'));
    }

    /**
     * Update service
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:255',
            'service_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $service->update($request->only([
            'title',
            'category',
            'short_description',
            'original_price',
            'duration',
        ]));
        
        if ($request->hasFile('service_image')) {
            $s3Service = app(S3Service::class);
            
            // Delete old image if exists
            if ($service->service_image) {
                $oldPath = $s3Service->extractPathFromUrl($service->service_image);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('service_image'), 'services');
            $service->service_image = $uploadResult['url'];
            $service->save();
        }
        
        return redirect()->route('admin.products-services', ['filter' => 'services'])
            ->with('success', 'Service updated successfully!');
    }

    /**
     * Delete service (soft delete)
     */
    public function delete($id)
    {
        $service = Service::findOrFail($id);
        
        // Soft delete - don't delete image, just mark as deleted
        $service->delete();
        
        return redirect()->route('admin.products-services', ['filter' => 'services'])
            ->with('success', 'Service deleted successfully!');
    }

    /**
     * Restore service
     */
    public function restore($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();
        
        return redirect()->route('admin.products-services', ['filter' => 'deleted'])
            ->with('success', 'Service restored successfully!');
    }
}

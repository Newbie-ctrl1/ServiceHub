<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class AdminServiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware auth dan admin sudah diterapkan di grup rute admin
    }
    
    /**
     * Display a listing of the services for admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Hanya tampilkan layanan yang dibuat oleh admin yang sedang login
        $services = Service::where('user_id', Auth::id())->get();
        $service = $services->first() ?? new Service(); // Mengambil layanan pertama atau membuat instance kosong
        
        return view('Store.admin.product', compact('services', 'service'));
    }
    
    /**
     * Show the form for creating a new service.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('Store.admin.create-service');
    }
    
    /**
     * Store a newly created service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'full_description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'badge' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:255',
            'main_image' => 'required|image|max:2048',
            'additional_images.*' => 'nullable|image|max:2048',
        ]);
        
        $service = new Service();
        $service->user_id = Auth::id(); // Tambahkan user_id dari admin yang sedang login
        $service->title = $request->title;
        $service->description = $request->description;
        $service->full_description = $request->full_description;
        $service->price = $request->price;
        $service->location = $request->location;
        $service->badge = $request->badge;
        $service->category = $request->category;
        
        // Process highlights from textarea to array
        if ($request->has('highlights')) {
            $highlightsText = $request->highlights;
            $highlightsArray = array_filter(explode("\n", $highlightsText), 'trim');
            $service->highlights = $highlightsArray;
        }
        
        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $service->main_image = file_get_contents($request->file('main_image')->getRealPath());
        }
        
        $service->save();
        
        // Handle additional images if any
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $serviceImage = new \App\Models\ServiceImage();
                $serviceImage->service_id = $service->id;
                $serviceImage->image = file_get_contents($image->getRealPath());
                $serviceImage->save();
            }
        }
        
        // Create notification for service creation
        NotificationService::createServiceNotification($service, 'created');
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }
    
    /**
     * Show the form for editing the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Pastikan admin hanya bisa mengedit layanan mereka sendiri
        $service = Service::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('Store.admin.edit-service', compact('service'));
    }
    
    /**
     * Update the specified service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'full_description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'badge' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:255',
            'main_image' => 'nullable|image|max:2048',
            'additional_images.*' => 'nullable|image|max:2048',
        ]);
        
        // Pastikan admin hanya bisa mengupdate layanan mereka sendiri
        $service = Service::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $service->title = $request->title;
        $service->description = $request->description;
        $service->full_description = $request->full_description;
        $service->price = $request->price;
        $service->location = $request->location;
        $service->badge = $request->badge;
        $service->category = $request->category;
        
        // Process highlights from textarea to array
        if ($request->has('highlights')) {
            $highlightsText = $request->highlights;
            $highlightsArray = array_filter(explode("\n", $highlightsText), 'trim');
            $service->highlights = $highlightsArray;
        }
        
        // Handle main image upload if a new one is provided
        if ($request->hasFile('main_image')) {
            $service->main_image = file_get_contents($request->file('main_image')->getRealPath());
        }
        
        $service->save();
        
        // Handle additional images if any
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $serviceImage = new \App\Models\ServiceImage();
                $serviceImage->service_id = $service->id;
                $serviceImage->image = file_get_contents($image->getRealPath());
                $serviceImage->save();
            }
        }
        
        // Create notification for service update
        NotificationService::createServiceNotification($service, 'updated');
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui!');
    }
    
    /**
     * Remove the specified service from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Pastikan admin hanya bisa menghapus layanan mereka sendiri
        $service = Service::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        // Create notification before deleting
        NotificationService::createServiceNotification($service, 'deleted');
        
        // Delete related service images first
        $service->additionalImages()->delete();
        
        // Then delete the service
        $service->delete();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }
    
    /**
     * Display the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Pastikan admin hanya bisa melihat layanan mereka sendiri
        $service = Service::with('additionalImages')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        return view('Store.admin.show-service', compact('service'));
    }
}
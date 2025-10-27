<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use File;

class ServiceController extends Controller
{
    // عرض كل الخدمات
    public function index()
    {
        $services = Service::latest()->get();
        return view('backend.service.index', compact('services'));
    }

    // API عرض الخدمات النشطة
    public function apiIndex(): JsonResponse
    {
        $services = Service::where('status', 1)->latest()->get();
        return response()->json($services);
    }

    // نموذج إضافة خدمة جديدة
    public function create()
    {
        $categories = Category::whereStatus(1)->get();
        return view('backend.service.create', compact('categories'));
    }

    // حفظ خدمة جديدة
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:200',
            'slug' => 'required|unique:services,slug',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            'excerpt' => 'nullable',
            'body' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'featured' => 'nullable',
            'status' => 'nullable',
            'other' => 'nullable',
        ]);

        $data['featured'] = $request->featured ?? 0;
        $data['status'] = $request->status ?? 0;
        $data['excerpt'] = $request->excerpt ?? '';

        if ($request->file('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/images/service/'), $imageName);
            $data['image'] = $imageName;
        }

        Service::create($data);
        return redirect()->route('service.index')->with('success', 'Service has been added successfully.');
    }

    // نموذج تعديل خدمة موجودة
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $categories = Category::whereStatus(1)->get();
        return view('backend.service.edit', compact('service', 'categories'));
    }

    // تحديث خدمة موجودة
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $data = $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:200',
            'slug' => ['required', Rule::unique('services')->ignore($service->id)],
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            'excerpt' => 'nullable',
            'body' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'featured' => 'nullable',
            'status' => 'nullable',
            'other' => 'nullable',
        ]);

        $data['featured'] = $request->featured ?? 0;
        $data['status'] = $request->status ?? 0;
        $data['excerpt'] = $request->excerpt ?? '';

        if ($request->file('image')) {
            // حذف الصورة القديمة إذا موجودة
            if ($service->image && File::exists(public_path('uploads/images/service/' . $service->image))) {
                File::delete(public_path('uploads/images/service/' . $service->image));
            }
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/images/service/'), $imageName);
            $data['image'] = $imageName;
        }

        $service->update($data);
        return redirect()->route('service.index')->with('success', 'Service updated successfully.');
    }

    // حذف خدمة
    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        // حذف الصورة إذا موجودة
        if ($service->image && File::exists(public_path('uploads/images/service/' . $service->image))) {
            File::delete(public_path('uploads/images/service/' . $service->image));
        }

        $service->delete();
        return redirect()->route('service.index')->with('success', 'Service deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pages = Page::with('menu')->latest()->get();

            return DataTables::of($pages)
                ->addIndexColumn()
                ->addColumn('menu', fn($data) => $data->menu?->name ?? 'N/A')
                ->addColumn('slug', fn($data) => $data->slug)
                ->addColumn('banner_image', function ($data) {
                    $imageUrl = $data->banner_image
                        ? asset('storage/pages/' . $data->banner_image) // ✅ Correct public path
                        : asset('images/no-image.png'); // fallback image

                    return '<img src="' . $imageUrl . '" width="70" height="40" class="rounded shadow-sm" alt="Banner">';
                })
                ->addColumn('serial_no', fn($data) => $data->serial_no ?? '-')
                ->addColumn('action', function ($data) {
                    $editUrl = route('backend.admin.pages.edit', $data->id);
                    $deleteUrl = route('backend.admin.pages.destroy', $data->id);

                    return '
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm bg-gradient-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . $editUrl . '">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="dropdown-item" onclick="return confirm(\'Are you sure?\')">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['banner_image', 'action'])
                ->make(true);
        }

        return view('backend.pages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::where('status', true)->get();
        return view('backend.pages.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'banner_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_heading' => 'required|string|max:255',
            'banner_description' => 'required|string|max:1000',
            'serial_no' => 'nullable|numeric',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'focus_keywords' => 'nullable|string|max:255',
            'schema' => 'nullable|json',
            'redirect_301' => 'nullable|url|max:255',
            'redirect_302' => 'nullable|url|max:255',
        ]);

        // Handle Banner Image Upload
        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/pages', $filename);
            $validated['banner_image'] = $filename;
        }

        Page::create($validated);

        return redirect()->route('backend.admin.pages.index')
            ->with('success', 'Page created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $menus = Menu::where('status', true)->get();
        return view('backend.pages.edit', compact('page', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_heading' => 'required|string|max:255',
            'banner_description' => 'required|string|max:1000',
            'serial_no' => 'nullable|numeric',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'focus_keywords' => 'nullable|string|max:255',
            'schema' => 'nullable|json',
            'redirect_301' => 'nullable|url|max:255',
            'redirect_302' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($page->banner_image && Storage::exists('public/pages/' . $page->banner_image)) {
                Storage::delete('public/pages/' . $page->banner_image);
            }

            $file = $request->file('banner_image');
            $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/pages', $filename);
            $validated['banner_image'] = $filename;
        }

        $page->update($validated);

        return redirect()->route('backend.admin.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        if ($page->banner_image && Storage::exists('public/pages/' . $page->banner_image)) {
            Storage::delete('public/pages/' . $page->banner_image);
        }

        $page->delete();
        return redirect()->back()->with('success', 'Page deleted successfully.');
    }

    /**
     * Set a page as the default.
     */
    public function setDefault($id)
    {
        Page::where('is_active', true)->update(['is_active' => false]);

        $page = Page::findOrFail($id);
        $page->update(['is_active' => true]);

        Cache::put('default_page', $page, 60 * 24);

        return redirect()->back()->with('success', 'Page set as default successfully.');
    }
}

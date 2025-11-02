<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Models\Category;
use App\Trait\FileHandler;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
class CategoryController extends Controller
{
    public $fileHandler;

    public function __construct(FileHandler $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('category_view'), 403);
        if ($request->ajax()) {
            $categories = Category::latest()->get();
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    if ($data->image) {
                        $url = asset('storage/' . $data->image);
                        return '<div style="width:60px; height:60px; overflow:hidden; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <img src="' . $url . '" alt="' . $data->name . '" style="width:100%; height:100%; object-fit:cover; transition: transform 0.3s;" class="img-hover-zoom"/>
                </div>';
                    } else {
                        return '<div style="width:60px; height:60px; overflow:hidden; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <img src="' . asset('assets/images/no-image.png') . '" style="width:100%; height:100%; object-fit:cover;" />
                </div>';
                    }
                })
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('status', function ($data) {
                    $toggleUrl = route('backend.admin.categories.toggle', $data->id); 
                    $status = $data->status == 1
                        ? '<span class="badge bg-primary">Active</span>'
                        : ($data->status == 2
                            ? '<span class="badge bg-danger">Inactive</span>'
                            : '<span class="badge bg-secondary">Unknown</span>');
                    return '<button class="btn btn-sm btn-light toggle-status" data-url="' . $toggleUrl . '">' . $status . '</button>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                    <button type="button" class="btn bg-gradient-primary btn-flat">Action</button>
                    <button type="button" class="btn bg-gradient-primary btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" href="' . route('backend.admin.categories.edit', $data->id) . '" ' . ' >
                    <i class="fas fa-edit"></i> Edit
               
                  </form>
                  </div>';
                })
                ->rawColumns(['image', 'name', 'status', 'action'])
                ->toJson();
        }
        return view('backend.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->can('category_create'), 403);
        return view('backend.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    abort_if(!auth()->user()->can('category_create'), 403);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|boolean',
    ]);

    $category = Category::create($request->except('category_image'));

    // Handle image upload & WebP conversion
    if ($request->hasFile("category_image")) {
        $file = $request->file("category_image");

        // Unique filename with .webp
        $filename = time() . '_' . uniqid() . '.webp';
        $path = storage_path('app/public/media/categories/' . $filename);

        // Convert to WebP and save
        $img = Image::make($file)->encode('webp', 80); // 80% quality
        $img->save($path);

        // Save new path in DB
        $category->image = 'media/categories/' . $filename;
        $category->save();
    }

    return redirect()->route('backend.admin.categories.index')->with('success', 'Category created successfully!');
}
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort_if(!auth()->user()->can('category_update'), 403);

        $category = Category::findOrFail($id);
        return view('backend.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    abort_if(!auth()->user()->can('category_update'), 403);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|boolean',
    ]);

    $category = Category::findOrFail($id);
    $oldImage = $category->image;

    // Update other fields first
    $category->update($request->except('category_image'));

    // Handle image upload & WebP conversion
    if ($request->hasFile("category_image")) {
        $file = $request->file("category_image");

        // Unique filename with .webp
        $filename = time() . '_' . uniqid() . '.webp';
        $path = storage_path('app/public/media/categories/' . $filename);

        // Convert to WebP and save
        $img = Image::make($file)->encode('webp', 80); // 80% quality
        $img->save($path);

        // Delete old image if exists
        if ($oldImage) {
            $this->fileHandler->secureUnlink($oldImage);
        }

        // Save new path in DB
        $category->image = 'media/categories/' . $filename;
        $category->save();
    }

    return redirect()->route('backend.admin.categories.index')->with('success', 'Category updated successfully!');
}
    
      public function toggleStatus($id)
    {
        abort_if(!auth()->user()->can('category_update'), 403);

        $category = Category::findOrFail($id);

        // If status = 1 (Active), make it 2 (Inactive), else make it 1 (Active)
        $category->status = ($category->status == 1) ? 2 : 1;
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Category status updated successfully!',
            'new_status' => $category->status,
            'badge_html' => $category->status == 1
                ? '<span class="badge bg-primary">Active</span>'
                : '<span class="badge bg-danger">Inactive</span>',
        ]);
    }

}

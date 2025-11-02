@extends('backend.master')

@section('title', 'Create Page')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form action="{{ route('backend.admin.pages.store') }}" method="POST" class="accountForm" enctype="multipart/form-data">
      @csrf

      <!-- Basic Information -->
      <h5 class="mb-3 border-bottom pb-2 fw-semibold">Page Information</h5>
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="menu_id" class="form-label">
            Menu <span class="text-danger">*</span>
          </label>
          <select class="form-control select2" name="menu_id" required>
            <option value="">Select Menu</option>
            @foreach ($menus as $item)
              <option value="{{ $item->id }}" {{ old('menu_id') == $item->id ? 'selected' : '' }}>
                {{ $item->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3 col-md-6">
          <label for="banner_image" class="form-label">
            Banner Image <span class="text-danger">*</span>
          </label>
          <input type="file" class="form-control" name="banner_image" required>
        </div>

        <div class="mb-3 col-md-6">
          <label for="banner_heading" class="form-label">
            Banner Heading <span class="text-danger">*</span>
          </label>
          <input type="text" class="form-control" name="banner_heading" placeholder="Enter Banner Heading" value="{{ old('banner_heading') }}" required>
        </div>

        <div class="mb-3 col-md-6">
          <label for="banner_description" class="form-label">
            Banner Description <span class="text-danger">*</span>
          </label>
          <input type="text" class="form-control" name="banner_description" placeholder="Enter Banner Description" value="{{ old('banner_description') }}" required>
        </div>

        <div class="mb-3 col-md-6">
          <label for="serial_no" class="form-label">Serial No</label>
          <input type="text" class="form-control" name="serial_no" placeholder="Enter Serial No" value="{{ old('serial_no') }}">
        </div>

        <div class="mb-3 col-md-6">
          <label for="slug" class="form-label">
            Slug <span class="text-danger">*</span>
          </label>
          <input type="text" class="form-control" name="slug" placeholder="Enter Slug (auto or manual)" value="{{ old('slug') }}" required>
        </div>
      </div>

      <!-- SEO Fields -->
      <h5 class="mt-4 mb-3 border-bottom pb-2 fw-semibold text-primary">SEO Settings</h5>
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="meta_title" class="form-label">Meta Title</label>
          <input type="text" class="form-control" name="meta_title" placeholder="Enter Meta Title" value="{{ old('meta_title') }}">
        </div>

        <div class="mb-3 col-md-6">
          <label for="meta_description" class="form-label">Meta Description</label>
          <textarea class="form-control" name="meta_description" rows="2" placeholder="Enter Meta Description">{{ old('meta_description') }}</textarea>
        </div>

        <div class="mb-3 col-md-6">
          <label for="canonical_url" class="form-label">Canonical URL</label>
          <input type="url" class="form-control" name="canonical_url" placeholder="https://example.com/page" value="{{ old('canonical_url') }}">
        </div>

        <div class="mb-3 col-md-6">
          <label for="focus_keywords" class="form-label">Focus Keywords</label>
          <input type="text" class="form-control" name="focus_keywords" placeholder="Comma-separated keywords" value="{{ old('focus_keywords') }}">
        </div>

        <div class="mb-3 col-md-12">
          <label for="schema" class="form-label">Schema Markup (JSON-LD)</label>
          <textarea class="form-control" name="schema" rows="3" placeholder="Enter Schema JSON">{{ old('schema') }}</textarea>
        </div>

        <div class="mb-3 col-md-6">
          <label for="redirect_301" class="form-label">Redirect 301 (Permanent)</label>
          <input type="url" class="form-control" name="redirect_301" placeholder="Enter URL for 301 redirect" value="{{ old('redirect_301') }}">
        </div>

        <div class="mb-3 col-md-6">
          <label for="redirect_302" class="form-label">Redirect 302 (Temporary)</label>
          <input type="url" class="form-control" name="redirect_302" placeholder="Enter URL for 302 redirect" value="{{ old('redirect_302') }}">
        </div>
      </div>

      <!-- Submit -->
      <div class="text-end mt-3">
        <button type="submit" class="btn bg-gradient-primary px-4">
          <i class="fas fa-save me-1"></i> Create Page
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('script')
<script>
  $(document).ready(function () {
    $('.select2').select2({
      placeholder: "Select Menu",
      allowClear: true
    });
  });
</script>
@endpush

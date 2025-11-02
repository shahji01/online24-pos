@extends('backend.master')

@section('title', 'Create Menu')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.menus.update',$menu->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              Name
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="Enter title" name="name"
              value="{{ $menu->name }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              URL
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="Enter url" name="url"
              value="{{ $menu->url }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              Serial No
            </label>
            <input type="text" class="form-control" placeholder="Enter Serial No" name="serial_no"
              value="{{ $menu->serial_no }}">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">Update</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@push('script')
<script>
</script>
@endpush
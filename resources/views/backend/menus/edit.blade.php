@extends('backend.master')

@section('title', 'Edit Menu')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form action="{{ route('backend.admin.menus.update', $menu->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name', $menu->name) }}" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">URL <span class="text-danger">*</span></label>
          <input type="text" name="url" value="{{ old('url', $menu->url) }}" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Serial No</label>
          <input type="number" name="serial_no" value="{{ old('serial_no', $menu->serial_no) }}" class="form-control">
        </div>
      </div>
      <div class="text-end">
        <button type="submit" class="btn bg-gradient-primary px-4">Update</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
  Swal.fire('Success', '{{ session('success') }}', 'success');
</script>
@endif
@endpush

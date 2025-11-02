@extends('backend.master')

@section('title', 'Pages')

@section('content')
<div class="card">
  <div class="mt-n5 mb-3 d-flex justify-content-end">
    <a href="{{ route('backend.admin.pages.create') }}" class="btn bg-gradient-primary">
      <i class="fas fa-plus-circle me-1"></i>
      Add New
    </a>
  </div>

  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="table-responsive" id="table_data">
          <table id="datatables" class="table table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 5%;">#</th>
                <th>Menu</th>
                <th>Slug</th>
                <th>Banner Image</th>
                <th>Serial No</th>
                <th>Created At</th>
                <th style="width: 10%;">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
  $(function () {
    let table = $('#datatables').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      order: [[1, 'asc']],
      ajax: {
        url: "{{ route('backend.admin.pages.index') }}",
        type: "GET",
      },
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'menu', name: 'menu' },
        { data: 'slug', name: 'slug' },
        { data: 'banner_image', name: 'banner_image', orderable: false, searchable: false },
        { data: 'serial_no', name: 'serial_no' },
        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
      ],
      language: {
        processing: '<span class="text-primary">Loading...</span>'
      },
    });
  });
</script>
@endpush

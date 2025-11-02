@extends('backend.master')

@section('title', 'Menus')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Menus List</h5>
    <a href="{{ route('backend.admin.menus.create') }}" class="btn bg-gradient-primary">
      <i class="fas fa-plus-circle me-1"></i> Add New
    </a>
  </div>

  <div class="card-body table-responsive">
    <table id="datatables" class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th style="width: 40px;">#</th>
          <th>Name</th>
          <th>URL</th>
          <th>Serial No</th>
          <th>Status</th>
          <th style="width: 120px;">Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@push('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap JS (required for dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $(function () {
    const table = $('#datatables').DataTable({
      processing: true,
      serverSide: true,
   ajax: "{{ route('backend.admin.menus.index') }}",

      order: [[1, 'asc']],
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
        { data: 'name', name: 'name' },
        { data: 'url', name: 'url' },
        { data: 'serial_no', name: 'serial_no' },
        { data: 'status', name: 'status', orderable: false, searchable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false },
      ],
    });

    // Toggle Status with SweetAlert Confirmation
    $(document).on('click', '.toggle-status', function () {
      let url = $(this).data('url');

      Swal.fire({
        title: 'Are you sure?',
        text: "You want to change the menu status!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.get(url, function (res) {
            Swal.fire('Updated!', res.message, 'success');
            table.ajax.reload(null, false);
          });
        }
      });
    });

    // SweetAlert Success Toast
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
      });
    @endif
  });
</script>
@endpush

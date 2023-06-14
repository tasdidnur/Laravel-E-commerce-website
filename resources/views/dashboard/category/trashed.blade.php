@extends('layouts.admin')
@section('content')
{{--  push css files  --}}
@push('stylesheet')
        <link rel="stylesheet" href="{{ asset('admin/assets') }}/vendor/css/jquery.datatables.min.css" />
@endpush
{{--  push css files  --}}

{{-- alert  --}}
        @if(Session::has('success'))
          <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
          </div>  
        @endif
        @if(Session::has('error'))
          <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
          </div>  
        @endif
{{-- alert  --}}       
    <!-- Bordered Table -->
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <h5 class="card-header">Trashed Caregoryes</h5>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <form method="POST" action="{{ url('/dashboard/category/mark_force_delete') }}" enctype="multipart/form-data">
                        @csrf
                <table id="myTable" class="table table-bordered display" >
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                        <th>Category Name</th>
                        <th>Category Image</th>
                        <th>Category Creator</th>
                        <th>Category Editor</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                @foreach ($trashed as $category) 
                    <tr>
                        <td>{{ $sl++ }}</td>
                        <td><input class="check1" type="checkbox" name="check1[]" value="{{ $category->id }}" id="check1"></td>
                        <td>{{ $category->cat_name }}</td>
                        <td><img height="100" width="100" src="{{ $category->cat_image!='' ? URL::to($category->cat_image) : asset('uploads/avatar.png') }}"></td>
                        <td>{{ optional($category->creator_info)->name }}</td>
                        <td>{{ optional($category->editor_info)->name }}</td>
                        <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                            <a class="dropdown-item" id="restore" data-id={{ $category->id }} href="#" data-bs-toggle="modal" data-bs-target="#modalToggle"><i class="bx bxs-arrow-from-right me-1"></i> Restore</a>
                            <a class="dropdown-item" id="delete" data-id={{ $category->id }} href="#" data-bs-toggle="modal" data-bs-target="#modalDeleteToggle"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                        </div>
                        </td>
                    </tr>
                    @endforeach      
                    </tbody>
                </table>
                <button class="btn btn-danger" type="submit">Delete Selected Permanently</button>
                </form>
                </div>
            </div>
        </div>
    </div>
      <!--/ Bordered Table -->

      <!--  Resote Modal-->
      <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalToggleLabel">Confirmation</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <form method="POST" action="{{ url('/dashboard/category/restore') }}">
              @csrf      
            <div class="modal-body">Are you sure restore this?</div>
            <div class="modal-footer modal_body">
              <input type="hidden" name="modal_id" id='modal_ids' value="">
              <button type="submit" class="btn btn-dark"  >
                CONFIRM
              </button>
            </div>
          </form>
          </div>
        </div>
      </div>
      <!--  Resote Modal -->

      <!-- Soft Delete Modal-->
      <div class="modal fade" id="modalDeleteToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content bg-danger">
            <div class="modal-header">
              <h5 class="modal-title text-white" id="modalToggleLabel">Confirmation</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <form method="POST" action="{{ url('/dashboard/category/force_delete') }}">
              @csrf      
            <div class="modal-body text-white">You won't be able to restore. Are you sure delete this?</div>
            <div class="modal-footer modal_body">
              <input type="hidden" name="modal_id" id='modal_id' value="">
              <button type="submit" class="btn btn-dark"  >
                CONFIRM
              </button>
            </div>
          </form>
          </div>
        </div>
      </div>
      <!-- Soft Delete Modal -->

{{--  push js files  --}}
@push('script')
<script>
    $("#checkAll").click(function() {
        $('.check1:checkbox').not(this).prop('checked', this.checked);
    })
</script>
<script src="{{ asset('admin/assets') }}/vendor/js/jquery.datatables.min.js"></script>
@endPush
{{--  push js files  --}}    
@endsection
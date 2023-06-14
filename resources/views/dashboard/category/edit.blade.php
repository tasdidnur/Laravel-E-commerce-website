@extends('layouts.admin')
@section('content')
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
  <!-- Basic Layout -->
      <div class="col-xl-1"></div>
      <div class="col-xl-10">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Category</h5>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ url('/dashboard/category/update') }}" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <input type="hidden" name="id" value="{{ $category->id }}">
                <label class="form-label" for="name">Category Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" id="name" value="{{ $category->cat_name }}" />
                  @error('name')
                      <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
              <div class="mb-3">
                <label class="form-label" for="image">Category Image</label>
                <input type="file" accept="image/*" name="image" class="form-control" id="image" />
                  @error('image')
                      <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-xl-1"></div> 
@endsection
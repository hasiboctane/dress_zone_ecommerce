@extends('admin.layouts.app')
@section('main-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub-Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST" id="subCategoryForm" name="subCategoryForm" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Sub-Category Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="Slug" readonly>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" selected>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="show_on_home">Show on Home</label>
                                    <select name="show_on_home" id="show_on_home" class="form-control">
                                        <option value="yes" selected>Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select Category</option>
                                        @if ($categories->count() > 0)
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('custom-script')
    <script>
        $(function() {
            $('#subCategoryForm').submit(function(event) {
                event.preventDefault();
                var element = $(this);
                $("button[type=submit]").prop('disabled', true);
                $.ajax({
                    url: "{{ route('sub-category.store') }}",
                    type: "POST",
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        $("button[type=submit]").prop('disabled', false);
                        var errors = response.message;
                        if (response.status == true) {
                            window.location.href = "{{ route('sub-categories.index') }}";
                            $('#name').removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html('');
                            $('#slug').removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html('');
                            $('#category').removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html('');
                        } else {
                            if (errors.name) {
                                $('#name').addClass('is-invalid')
                                    .siblings('p').addClass('invalid-feedback')
                                    .html(errors.name);
                            }
                            if (errors.slug) {
                                $('#slug').addClass('is-invalid')
                                    .siblings('p').addClass('invalid-feedback')
                                    .html(errors.slug);
                            }
                            if (errors['category']) {
                                $('#category').addClass('is-invalid')
                                    .siblings('p').addClass('invalid-feedback')
                                    .html(errors['category']);
                            }
                        }

                    },
                    error: function(xhr, status, error) {
                        console.log(status);
                    }
                })
            });
            $('#name').change(function() {
                $("button[type=submit]").prop('disabled', true);
                $.ajax({
                    url: "{{ route('getSlug') }}",
                    type: "GET",
                    data: {
                        title: $(this).val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        $("button[type=submit]").prop('disabled', false);
                        $('#slug').val(response.slug);
                    }
                })
            });
        })
    </script>
@endsection

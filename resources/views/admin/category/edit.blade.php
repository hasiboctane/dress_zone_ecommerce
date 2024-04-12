@extends('admin.layouts.app')
@section('main-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST" id="categoryForm" name="categoryForm" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Category Name</label>
                                    <input type="text" value="{{ $category->name }}" name="name" id="name"
                                        class="form-control" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" value="{{ $category->slug }}" name="slug" id="slug"
                                        class="form-control" placeholder="Slug" readonly>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" id="image_id" name="image_id" class="form-control">
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick ">
                                            <br> Drop files here or click to upload <br> <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image">Previous Image</label>
                                    <div class="">
                                        <img src="{{ !empty($category->image && file_exists('uploads/category/thumb/' . $category->image)) ? asset('uploads/category/thumb/' . $category->image) : asset('assets/admin_asset/img/no_image.jpg') }}"
                                            alt="{{ 'Image: ' . $category->id . ' not found' }}" width="100px">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" @if ($category->status == 1) selected @endif>Active
                                        </option>
                                        <option value="0" @if ($category->status == 0) selected @endif>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="show_on_home">Show on Home</label>
                                    <select name="show_on_home" id="show_on_home" class="form-control">
                                        <option value="yes" @if ($category->show_on_home == 'yes') selected @endif>Yes
                                        </option>
                                        <option value="no" @if ($category->show_on_home == 'no') selected @endif>No
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('custom-script')
    <script>
        Dropzone.autoDiscover = false;
        $(function() {
            $('#categoryForm').submit(function(event) {
                event.preventDefault();
                var element = $(this);
                $("button[type=submit]").prop('disabled', true);
                $.ajax({
                    url: "{{ route('category.update', $category->id) }}",
                    type: "PUT",
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        $("button[type=submit]").prop('disabled', false);
                        var errors = response.message;
                        if (response.status == true) {
                            window.location.href = "{{ route('categories.index') }}";
                            $('#name').removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html('');
                            $('#slug').removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html('');
                        } else {
                            if (response.notFound) {
                                window.location.href = "{{ route('categories.index') }}";
                            }
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

            const dropzone = $('#image').dropzone({
                init: function() {
                    this.on("addedfile", function(file) {
                        if (this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }
                    });
                },
                url: "{{ route('temp-image.create') }}",
                maxFiles: 1,
                paramName: "image",
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(file, response) {
                    $('#image_id').val(response.image_id);
                }
            });

        });
    </script>
@endsection

@extends('adminlte::page')

@section('title', 'Add Category')

@section('content_header')

    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-6">
                <h1 class="m-0">Add Category</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Category</li>
                </ol>
            </div>
        </div>
    </div>

@stop

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-dismissable alert-danger mt-3">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Whoops!</strong> There were some problems with your input.<br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container-fluid">
        <div class=" justify-content-between pb-5">

            <form role="form" method="post" action="{{ route('category.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-light">
                            <div class="card-header">
                                <h3 class="card-title">Add Category
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus" aria-hidden="true">
                                        </i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="inputStatus">Title
                                    </label>
                                    <input class="form-control @error('title') is-invalid @enderror" type="text"
                                        id="title" name="title" placeholder="Title here.." value="{{ old('title') }}">
                                        <small class="text-muted">The name is how it appears on your site.</small>
                                    @error('title')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="mb-0" for="inputStatus">Slug
                                    </label>

                                    <input style="background-color: rgb(220, 220, 220);" class="form-control @error('slug') is-invalid @enderror" type="text"
                                        id="slug" name="slug" placeholder="slug here.." value="{{ old('slug') }}">
                                        <small class="text-muted">&nbsp;&nbsp;The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.
                                        </small>

                                    @error('slug')
                                        <p class="text-danger mb-0">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card card-light">
                            <div class="card-header">
                                <h3 class="card-title">Description
                                </h3>
                                <small>&nbsp;&nbsp;The description is not prominent by default; however, some themes may show it.
                                </small>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus" aria-hidden="true">
                                        </i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <textarea class="form-control" name="body" id="" value="{{ old('body') }}" cols="30" rows="5">{{ old('excerpt') }}</textarea>
                                    @error('excerpt')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        {{-- seo --}}
                        {{-- <div class="card card-light">
                            <div class="card-header">
                                <h3 class="card-title">SEO
                                </h3>
                                <small>&nbsp;&nbsp;Search engine details
                                </small>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus" aria-hidden="true">
                                        </i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-3 pb-0">
                                <div class="form-group">
                                    <label for="">SEO Title
                                    </label>
                                    <input placeholder="Post title here for seo..." type="text"
                                        class="form-control @error('meta_title') is-invalid @enderror" name="meta_title"
                                        id="" value="{{ old('meta_title') }}">
                                    @error('meta_title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-body  pt-0 pb-0">
                                <div class="form-group">
                                    <label for="">SEO Description
                                    </label>
                                    <textarea placeholder="Post description here for seo..."
                                        class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" id=""
                                        cols="0" rows="4" value="{{ old('meta_description') }}">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-body pt-0 pb-0">
                                <div class="form-group">
                                    <label for="">SEO Keywords
                                    </label>
                                    <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                        placeholder="keyword1, keyword2, keyword3" name="meta_keywords" id=""
                                        value="{{ old('meta_keywords') }}">
                                    @error('meta_keywords')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div> --}}
                    </div>

                    <div class="col-md-4">
                        <div class="sticky-top">
                            <div class="card card-primary sticky-bottom">
                                <div class="card-header">
                                    <h3 class="card-title">Category Details</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body pb-0">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select required="required" name="status" id="inputStatus"
                                            class="form-control custom-select">
                                            <option disabled value="">Select Option</option>
                                            <option value="1" >PUBLISHED
                                            </option>
                                            <option value="0" >DRAFT
                                            </option>
                                        </select>
                                    </div>

                                    {{-- <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="featured"
                                                name="featured" value="1" {{ old('featured') == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="featured">Featured</label>
                                            <small>categories prioritize content on the homepage.</small>
                                        </div>
                                    </div> --}}

                                    <div class="form-group pt-0 pb-0 text-right">
                                        <button type="submit" class="btn btn-primary">Publish
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Featured Image</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body pt-0 pb-0">
                                    <div class="form-group">
                                        <small class="text-red">Note: size: Width-1280px Height: 720px</small>
                                        <input class="form-control" name="image" accept="image/*" type="file" id="imgInp">
                                        <img style="width: 150px; margin-top:10px; border:1px solid black;" id="blah"
                                            src="{{ asset('uploads/images/no-image.jpg') }}" alt="your image">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
@stop

@section('css')

@stop

@section('js')
<script>
    $('#title').on("change keyup paste click", function() {
        var Text = $(this).val().trim();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
        $('#slug').val(Text);
    });
</script>

{{-- show image --}}
<script>
    imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
            blah.src = URL.createObjectURL(file)
        }
    }
</script>

<script>
    $(document).ready(function() {
        $(".alert").delay(6000).slideUp(300);
    });
</script>

@stop

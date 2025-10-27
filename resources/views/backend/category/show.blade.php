@extends('adminlte::page')

@section('title', 'Add Category')

@section('content_header')

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Show Category</h1>
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
    <div class="rowjustify-content-between pb-5">
        <div class="col-md-6 ">
            <h5><a href="{{ route('category.index') }}" class="btn btn-primary">Back</a>
            </h5>
            <form role="form" method="post" action="#" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Category name*</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Category Name" value="{{ $category->title }}" / disabled>
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="slug" class="form-label bg-light">Slug <small>Unique url of the
                                        category</small></label>
                                <input type="text" name="slug" value="{{ $category->slug }}" class="form-control  @error('slug') is-invalid @enderror" id="slug" disabled>
                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Select parent category*</label>
                                <select type="text" name="parent_id" class="form-control" disabled>
                                    <option value="">None</option>
                                    @if($categories)
                                        @foreach($categories as $item)
                                            <?php $dash=''; ?>
                                            <option value="{{$item->id}}" @if($category->parent_id == $item->id ) selected @endif>{{$item->title}}</option>
                                            @if(count($item->subcategory))
                                                @include('backend.category.sub-category-list-option-for-update',['subcategories' => $item->subcategory])
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>



                        <div class="col-sm-12 mb">
                           <div class="form-group">
                            <label for="">Short Description</label>
                            <textarea name="description" id="" cols="30" class="form-control" rows="2" value="{{ $category->description }}" disabled>{{ $category->description }}</textarea>
                           </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="featured"
                                        name="featured" value="1" {{ $category->featured ? 'checked' : '' }} disabled>
                                    <label class="custom-control-label" for="featured">Featured
                                    </label><br>
                                    <small>Featured will be shown on home page on priorty</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="text-red">&nbsp;&nbsp;Note: Webp Image |  size: Width-1200px Height: 800px
                                        </small><br>
                                        <input name="image" accept="image/*" type="file" id="imgInp" disabled>
                                    </div>
                                    <div>
                                        @if($category->image)
                                        <img style="width: 100px; margin-top:10px; border:1px solid black;" id="blah"
                                    src="{{ asset('public/uploads/images/category/'.$category->image) }}" alt="your image">
                                    @else
                                    <img style="width: 100px; margin-top:10px; border:1px solid black;" id="blah"
                                    src="{{ asset('public/uploads/images/no-image.jpg') }}" alt="your image">
                                    @endif
                                    </div>
                                </div>

                            </div>
                        </div>



                    </div>
                </div>

            </form>


        </div>

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

@php
    $dash.='--';
@endphp

@foreach ($subcategories as $subcategory)
    <option {{ in_array($subcategory->id, old('category', []) )  ? 'selected' : '' }} value="{{ $subcategory->id }}" > {{$dash}}{{$subcategory->title}}</option>
    @if (count($subcategory->subcategory))
        @include('backend.category.subcategoryList-option',['subcategories'=>$subcategory->subcategory])
    @endif
@endforeach

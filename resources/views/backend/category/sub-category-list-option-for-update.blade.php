@php
    $dash .= '--';
@endphp

@foreach ($subcategories as $subcategory)
    <option
        {{ in_array($subcategory->id, old('category', $selectedCategories)) ? 'selected' : '' }}
        value="{{ $subcategory->id }}"
    >
        {{$dash}}{{$subcategory->title}}
    </option>
    @if (count($subcategory->subcategory))
        @include('backend.category.sub-category-list-option-for-update', [
            'subcategories' => $subcategory->subcategory,
            'selectedCategories' => $selectedCategories,
            'dash' => $dash  // pass updated dash for further indentation
        ])
    @endif
@endforeach

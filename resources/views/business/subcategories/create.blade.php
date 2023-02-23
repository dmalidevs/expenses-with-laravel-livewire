<x-app-layout>
    <x-slot name="header">
        <div class="flex align-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Business Subcategories') }}
            </h2>
        </div>
    </x-slot>

    <section>
        <form method="post" action="{{ route('business.subcategories.store') }}" class="w-1/3 mx-auto space-y-3 mt-6" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="category_id" class="font-bold">Category Name</label>
                <select name="category_id" class="w-full rounded py-2 px-2" id="category_id">
                    <option>Select Categroy Name</option>
                    @foreach ($categories as $catId )
                        <option value="{{$catId->code}}">{{$catId->name}}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="subcategory_name" class="font-bold">Subcategory Name</label>
                <input type="text" name="subcategory_name" id="subcategory_name" class="w-full rounded"
                    placeholder="add expense type" value="{{ old('subcategory_name') }}">

                @error('subcategory_name')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="subcategory_image" class="font-bold">Category Image</label>
                <input type="file" name="subcategory_image" id="subcategory_image" class="w-full rounded"
                    placeholder="add expense type" value="{{ old('subcategory_image') }}">

                @error('subcategory_image')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
           
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-white w-full rounded">{{ __('Add') }}</button>
        </form>
    </section>
    <x-slot name="footer">
        <p class="text-center"> {{ __('All Rights Reserved') }} </p>
    </x-slot>
</x-app-layout>

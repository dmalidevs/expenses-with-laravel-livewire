<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Business Categories') }}
        </h2>
    </x-slot>
    <section>
        <form method="post" class="w-1/3 mx-auto space-y-3 mt-6" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="category_name" class="font-bold">Category Name</label>
                
                <input type="text" id="category_name" name="category_name" class="w-full rounded"  value="{{$categoriesTypes->name}}" placeholder="Type Business Category Name">
                @error('category_name')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="category_image" class="font-bold">Category Image</label>
                <input type="file" name="category_image" id="category_image" class="w-full rounded"
                    placeholder="add expense type" value="{{ old('category_image')}}">

                @if ($categoriesTypes->image)
                    <img class="mt-4" src="{{asset('storage/'.$categoriesTypes->image)}}">
                @else
                    <span class="text-xs text-red-500">{{__('No Category Image Added Yet')}}</span>
                @endif

                @error('category_image')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
           
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-white w-full rounded">
                {{ __('Update Category') }}
            </button>
        </form>
    </section>

    <x-slot name="footer">
        <p class="text-center"> {{ __('All Rights Reserved') }} </p>
    </x-slot>
</x-app-layout>

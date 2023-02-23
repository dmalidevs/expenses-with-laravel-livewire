<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Business Categories') }}
        </h2>
    </x-slot>

    <!-- Modal toggle -->
    <x-button wire:click="dataAddModal" class="block text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center mx-auto mt-6">
    Add Category
    </x-button>
    <x-flash/>
    
    <x-jet-dialog-modal wire:model="dataAddModalOpen">
        <x-slot name="title">
            {{__('Add')}}
        </x-slot>
        <x-slot name="content">
            <div wire:submit.prevent="create" class=" space-y-3 mt-6">
                <label for="category_name" class="font-bold">Category Name</label>
                <input type="text" wire:model="category_name" id="category_name" class="w-full rounded" placeholder="add expense type" value="{{ old('category_name') }}">

                @error('category_name')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
                <label for="category_image" class="font-bold">Category Name</label>
                <input type="file" wire:model="category_image" id="category_image" class="w-full rounded" value="{{ old('category_image') }}">

                @error('category_image')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-button wire:click.prevent="create" class="bg-indigo-500 text-white px-4 py-2 ">
                {{__('Add') }}
            </x-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="dataEditModalOpen">
        <x-slot name="title">
            {{__('Edit')}}
        </x-slot>
        <x-slot name="content">
            <div wire:submit.prevent="edit" class=" space-y-3 mt-6">
                <label for="edit_category_name" class="font-bold">Category Name</label>
                <input type="text" wire:model="edit_category_name" id="edit_category_name" class="w-full rounded" placeholder="add expense type" value="{{ old('edit_category_name') }}">

                @error('edit_category_name')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
                
                <label for="edit_category_image" class="font-bold">Category Name</label>
                <input type="file" wire:model="edit_category_image" id="edit_category_image" class="w-full rounded" value="{{ old('edit_category_image') }}">

                @error('edit_category_image')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-button wire:click.prevent="edit" class="bg-red-500 text-white p-2 ">
                {{__('Update') }}
            </x-button>
        </x-slot>
    </x-jet-dialog-modal>

    <section>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-4/5 mx-auto mt-16">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Sl
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Slug
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Policy
                        </th>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Assigned By
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Updated by
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($businessCategories as $category)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $loop->iteration }}
                            </td>
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $category->slug }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($category->image)
                                    <img class="w-32" src="{{asset('storage/'.$category->image) }}">
                                @else
                                    <span class="text-xs text-red-500">{{__('No Image Found')}}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ Str::title($category->assignedUser->name) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $category->updatedUser ? Str::title($category->updatedUser->name) : '' }}
                            </td>
                            <td>
                                <div class="flex items-center justify-center px-6 py-4 space-x-3">
                                    <x-button wire:click="status({{$category->code}})"
                                        class="text-{{ $category->status == 1 ? 'green' : 'gray' }}-500 p-2">
                                        @if ($category->status == 1)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                            </svg>
                                        @endif
                                    </x-button>

                                    <x-button wire:click="dataEditModal({{$category->code}})" class="font-medium text-blue-600 dark:text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </x-button>

                                    <x-button wire:click="delete({{$category->code}})"
                                        class="font-medium text-red-600 dark:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <x-slot name="footer">
        <p class="text-center"> {{ __('All Rights Reserved') }} </p>
    </x-slot>
</div>

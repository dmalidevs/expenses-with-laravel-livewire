<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Expense Types Edit') }}
        </h2>
        <span class="text-red-500">{{ $editType->name }}</span>
    </x-slot>
    
    <section>
        <form method="post" action="{{route('expenses.type.update',$editType->code)}}" class="w-1/3 mx-auto space-y-3 mt-6">
            @csrf
            <label for="expense_type" class="font-bold">Type Name</label>
            <input type="text" name="expense_type" id="expense_type" class="w-full rounded"
                placeholder="add expense type" value="{{ old('expense_type') ?? $editType->name }}">

            @error('expense_type')
                <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror 
            <select name="policy" class="w-full rounded p-2">
                <option value="">Select Policy</option>
                <option value="credit" @selected($editType->policy == 'credit') >Credit</option>
                <option value="debit" @selected($editType->policy == 'debit')>Debit</option>
            </select>
            @error('policy')
                <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror

            <x-button class="bg-red-500 text-white w-full py-2 justify-center">
                 {{__('Update') }}
            </x-button>
        </form>
    </section>

    <x-slot name="footer">
        <p class="text-center"> {{ __('All Rights Reserved') }} </p>
    </x-slot>
</x-app-layout>

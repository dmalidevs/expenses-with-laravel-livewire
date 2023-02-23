<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Expense Types Edit') }}
        </h2>
    </x-slot>
    <section>
        <form method="post" action="{{ route('expenses.bill.update', $editBill->code) }}"
            class="w-1/3 mx-auto space-y-3 mt-6" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="expensesType" class="font-bold">Expenses Type</label>
                <select id="expensesType" class="w-fulls rounded py-2 px-2" name="expensesType">
                    <option value="">Select Expenses Type</option>
                    @foreach ($expensesTypes as $type)
                        <option value="{{ $type->code }}" @selected(old('expensesType') == $type->code || $editBill->expensesTypeId->code == $type->code)>{{ $type->name }}</option>
                    @endforeach
                </select>

                @error('expensesType')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="font-bold">Expens Billing Policy</label>
                <input type="text" class="w-full rounded" value="{{ __('How To Update Policy Here') }}" readonly>
            </div>
            <div>
                <label for="purpose" class="font-bold">Bill Purpose</label>
                <input type="text" name="purpose" id="purpose" class="w-full rounded"
                    placeholder="Add Bill Purpose" value="{{ old('purpose') ?? $editBill->purpose }}">
                @error('purpose')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="details" class="font-bold">Bill Details</label>
                <textarea name="details" id="details" class="w-full rounded h-36" placeholder="Add Bill Details"> {{ old('details') ?? $editBill->details }} </textarea>
                @error('details')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="amount" class="font-bold">Bill Amount</label>
                <input type="number" name="amount" id="amount" class="w-full rounded" placeholder="Add Bill Amount" value="{{ old('amount') ?? $editBill->amount }}" min="0" step=".01">
                @error('amount')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="invoice_image" class="font-bold">Bill Invoice Image</label>
                <input type="file" name="invoice_image" id="invoice_image" class="w-full rounded mb-2">
                
                @if ($editBill->invoice_image)
                    <img class="w-1/2 py-2" src="{{ asset('storage/' . $editBill->invoice_image) }}">
                @else
                    <span class="text-xs text-red-500">{{__('Invoice Not Attached Yet')}} </span>
                @endif

                @error('invoice_image')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="billing_date" class="font-bold">Billing Date</label>
                <input type="date" name="billing_date" id="billing_date" class="w-full rounded"
                    value="{{ old('billing_date') ?? ($editBill->billing_date->format('Y-m-d') ?? today()->format('Y-m-d')) }}"
                    placeholder="Select Billing Date">
                @error('billing_date')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-white w-full rounded my-6">
                {{ __('Update Bill') }}
            </button>
        </form>
    </section>

    <x-slot name="footer">
        <p class="text-center"> {{ __('All Rights Reserved') }} </p>
    </x-slot>
</x-app-layout>

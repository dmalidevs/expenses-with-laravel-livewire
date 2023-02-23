<x-app-layout>
    <x-slot name="header" class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Expense Bill') }}
        </h2>
        <x-button href="{{route('expenses.bills')}}">
            {{__('Back')}}
        </x-button>
    </x-slot>

    <section>
        @if($expensesTypes->count() == 0)
            <div class="py-2 px-4 bg-indigo-500 text-white w-full rounded mx-auto w-1/5 text-center mt-4"> 
                <x-button href="{{route('expenses.type.all')}}">
                    {{ __("Add Expenses Type First")}}
                </x-button>
            </div>
        @else
            <form method="post" action="{{ route('expenses.bill.store') }}" class="w-1/3 mx-auto space-y-3 mt-6" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="expensesType" class="font-bold">Expenses Type</label>
                    <select id="expensesType"class="w-full rounded py-2 px-2" name="expensesType">
                        <option value="">Select Expenses Type</option>
                        @foreach ( $expensesTypes as $type)
                        <option value="{{$type->code}}">{{$type->name .' [ '.Str::title($type->policy).' ] '}}</option>
                        @endforeach
                    </select>
                    @error('expensesType')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="font-bold">Expens Bill Policy</label>
                    <input type="text" class="w-full rounded" value="{{ __('How To Add Policy Here')}}" readonly>
                </div>
                
                <div>
                    <label for="purpose" class="font-bold">Bill Purpose</label>
                    <input type="text" name="purpose" id="purpose" class="w-full rounded" placeholder="Add Bill Purpose" value="{{ old('purpose') }}">
                    @error('purpose')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="details" class="font-bold">Bill Details</label>
                    <textarea name="details" id="details" class="w-full rounded h-36" placeholder="Add Bill Details"> {{ old('details') }} </textarea>
                    @error('details')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="amount" class="font-bold">Bill Amount</label>
                    <input type="number" name="amount" id="amount" class="w-full rounded" placeholder="Add Bill Amount" value="{{ old('amount') }}" min="0" step=".01">
                    @error('amount')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="invoice_image" class="font-bold">Bill Invoice Image</label>
                    <input type="file" name="invoice_image" id="invoice_image" class="w-full rounded">
                    @error('invoice_image')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="billing_date" class="font-bold">Billing Date</label>
                    <input type="date" name="billing_date" id="billing_date" class="w-full rounded" value="{{old('billing_date')??today()->format('Y-m-d')}}" selected placeholder="Select Billing Date">
                    @error('billing_date')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="py-2 px-4 bg-indigo-500 text-white w-full rounded">{{ __('Add') }}</button>
            </form>
        @endif
    </section>

    <x-slot name="footer">
        <p class="text-center"> {{ __('All Rights Reserved') }} </p>
    </x-slot>
</x-app-layout>
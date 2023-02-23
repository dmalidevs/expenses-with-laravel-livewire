<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Expense Bills') }}
            </h2>
            <x-button href="{{route('expenses.bill.create')}}">
                {{__('Add Bill')}}
            </x-button>
        </div>
    </x-slot>

    <section>
        <div class="relative overflow-x-auto sm:rounded-lg mx-auto mt-16 px-28">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Sl
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Purpose
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Details
                        </th>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Amount
                        </th>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Invoice Image
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Billing Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Expenses Type
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Policy
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
                    @forelse ($expensesbill as $bill)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $loop->iteration }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $bill->purpose }}
                            </td>
                            <td class="px-6 py-4"> {{ $bill->details }} </td>
                            
                            <td class="px-6 py-4"> {{ $bill->amount }} </td>

                            <td class="px-6 py-4">
                                @if($bill->invoice_image)
                                    <img src="{{asset('storage/'.$bill->invoice_image)}}" alt="">
                                @else
                                    <span class="text-xs text-red-500">{{'No Invoice Found!!!'}}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <time>{{ $bill->billing_date->format('Y-m-d') }} </time>
                            </td>
                            <td class="px-6 py-4">
                               {{ Str::title($bill->expensesTypeId->name)}}
                            </td>
                            <td class="px-6 py-4">
                               {{ Str::title($bill->expensesTypeId->policy)}}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ Str::title($bill->assignedUser->name) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $bill->updatedUser ? Str::title($bill->updatedUser->name) : '' }}
                            </td>
                            <td>
                                <div class="flex items-center justify-center px-6 py-4 space-x-3">
                                    <x-button :href="route('expenses.bill.edit', $bill->code)"
                                        class="font-medium text-blue-600 dark:text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </x-button>
                                    <form method="post" action="{{ route('expenses.bill.delete', $bill->code) }}">
                                        @csrf
                                        <x-button name="deleteCode" value="{{ $bill->code }}"
                                            class="font-medium text-red-600 dark:text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                            <td scope="row text-center" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" colspan="9">
                                {{ __('No Data Found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $expensesbill->links() }}
            </div>
        </div>
    </section>

    <x-slot name="footer">
        <p class="text-center"> {{ __('All Rights Reserved') }} </p>
    </x-slot>
</x-app-layout>

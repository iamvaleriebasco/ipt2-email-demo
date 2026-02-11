<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message with Auto-Dismiss -->
            @if(session('success'))
                <div id="success-message" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="document.getElementById('success-message').remove()" class="text-green-700 hover:text-green-900 font-bold ml-4">
                        ✕
                    </button>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Transaction Information</h3>
                        <div class="space-x-2">
                            <a href="{{ route('transactions.edit', $transaction) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                            <a href="{{ route('transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Transaction Number</p>
                            <p class="font-semibold">{{ $transaction->transaction_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Type</p>
                            <p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $transaction->type === 'payment' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Amount</p>
                            <p class="font-semibold text-lg">₱{{ number_format($transaction->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Transaction Date</p>
                            <p class="font-semibold">{{ $transaction->transaction_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Balance After</p>
                            <p class="font-semibold">₱{{ number_format($transaction->balance_after, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Processed By</p>
                            <p class="font-semibold">{{ $transaction->processedBy->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-6">
                        <h4 class="font-semibold mb-3">Account Information</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Account Number</p>
                                <p class="font-semibold">{{ $transaction->account->account_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Customer Name</p>
                                <p class="font-semibold">{{ $transaction->account->customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Current Balance</p>
                                <p class="font-semibold">₱{{ number_format($transaction->account->balance, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Account Status</p>
                                <p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $transaction->account->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($transaction->account->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($transaction->type === 'payment')
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-6">
                            <h4 class="font-semibold mb-3">Payment Details</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Payment Method</p>
                                    <p class="font-semibold">{{ $transaction->payment_method ? ucfirst($transaction->payment_method) : 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Reference Number</p>
                                    <p class="font-semibold">{{ $transaction->reference_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($transaction->notes)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h4 class="font-semibold mb-2">Notes</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $transaction->notes }}</p>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end space-x-2">
                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Transaction
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .transaction-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }
        .disbursement {
            background-color: #ddd6fe;
            color: #6b21a8;
        }
        .payment {
            background-color: #d1fae5;
            color: #065f46;
        }
        .info-section {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #4F46E5;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
        }
        .info-value {
            color: #111827;
            font-weight: 500;
        }
        .amount {
            font-size: 28px;
            font-weight: bold;
            margin: 15px 0;
        }
        .amount.disbursement {
            color: #dc2626;
        }
        .amount.payment {
            color: #059669;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .notes-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Transaction Notification</h1>
    </div>

    <div class="content">
        <p>Hello!</p>

        <p>A new transaction has been successfully created.</p>

        <div style="text-align: center;">
            <span class="transaction-badge {{ $transaction->type }}">
                {{ ucfirst($transaction->type) }}
            </span>
            <div class="amount {{ $transaction->type }}">
                {{ $transaction->type === 'disbursement' ? '+' : '-' }}₱{{ number_format($transaction->amount, 2) }}
            </div>
        </div>

        <div class="info-section">
            <h3 style="margin-top: 0; color: #4F46E5;">Transaction Details</h3>

            <div class="info-row">
                <span class="info-label">Transaction Number:</span>
                <span class="info-value">{{ $transaction->transaction_number }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ $transaction->transaction_date->format('F d, Y') }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Balance After:</span>
                <span class="info-value">₱{{ number_format($transaction->balance_after, 2) }}</span>
            </div>
        </div>

        <div class="info-section">
            <h3 style="margin-top: 0; color: #4F46E5;">Customer Information</h3>

            <div class="info-row">
                <span class="info-label">Customer Name:</span>
                <span class="info-value">{{ $customer->name }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Account Number:</span>
                <span class="info-value">{{ $account->account_number }}</span>
            </div>

            @if($customer->contact_number)
            <div class="info-row">
                <span class="info-label">Contact:</span>
                <span class="info-value">{{ $customer->contact_number }}</span>
            </div>
            @endif
        </div>

        @if($transaction->type === 'payment')
        <div class="info-section">
            <h3 style="margin-top: 0; color: #4F46E5;">Payment Information</h3>

            <div class="info-row">
                <span class="info-label">Payment Method:</span>
                <span class="info-value">{{ $transaction->payment_method ? ucfirst($transaction->payment_method) : 'N/A' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Reference Number:</span>
                <span class="info-value">{{ $transaction->reference_number ?? 'N/A' }}</span>
            </div>
        </div>
        @endif

        @if($transaction->notes)
        <div class="notes-box">
            <strong>Notes:</strong><br>
            {{ $transaction->notes }}
        </div>
        @endif

        @if($transaction->processedBy)
        <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
            <strong>Processed by:</strong> {{ $transaction->processedBy->name }}
        </p>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('transactions.show', $transaction) }}" class="button">
                View Transaction Details
            </a>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated notification from your transaction management system.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>

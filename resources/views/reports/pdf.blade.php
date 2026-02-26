<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Report</title>
</head>
<body style="margin: 0; padding: 20px; font-family: Arial, Helvetica, sans-serif; background-color: #ffffff;">

    <!-- Header -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td style="background-color: #667eea; padding: 25px 30px; text-align: center;">
                <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: bold;">Transaction Report</h1>
                <p style="margin: 8px 0 0 0; color: #ffffff; font-size: 13px;">{{ $summary['month'] }}</p>
            </td>
        </tr>
    </table>

    <!-- Summary -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td style="width: 25%; padding: 12px 15px; background-color: #f3f4f6; border: 1px solid #e5e7eb; text-align: center;">
                <p style="margin: 0; font-size: 11px; color: #6b7280;">Total Transactions</p>
                <p style="margin: 4px 0 0 0; font-size: 20px; font-weight: bold; color: #111827;">{{ $summary['total'] }}</p>
            </td>
            <td style="width: 25%; padding: 12px 15px; background-color: #f0fdf4; border: 1px solid #e5e7eb; text-align: center;">
                <p style="margin: 0; font-size: 11px; color: #6b7280;">Total Payments</p>
                <p style="margin: 4px 0 0 0; font-size: 18px; font-weight: bold; color: #16a34a;">&#8369;{{ number_format($summary['payments'], 2) }}</p>
            </td>
            <td style="width: 25%; padding: 12px 15px; background-color: #eff6ff; border: 1px solid #e5e7eb; text-align: center;">
                <p style="margin: 0; font-size: 11px; color: #6b7280;">Total Disbursements</p>
                <p style="margin: 4px 0 0 0; font-size: 18px; font-weight: bold; color: #2563eb;">&#8369;{{ number_format($summary['disbursements'], 2) }}</p>
            </td>
            <td style="width: 25%; padding: 12px 15px; background-color: #fef2f2; border: 1px solid #e5e7eb; text-align: center;">
                <p style="margin: 0; font-size: 11px; color: #6b7280;">Total Charges</p>
                <p style="margin: 4px 0 0 0; font-size: 18px; font-weight: bold; color: #dc2626;">&#8369;{{ number_format($summary['charges'], 2) }}</p>
            </td>
        </tr>
    </table>

    <!-- Transactions Table -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #667eea;">
                <th style="padding: 10px 12px; text-align: left; color: #ffffff; font-size: 12px; font-weight: bold; border: 1px solid #5a67d8;">Date</th>
                <th style="padding: 10px 12px; text-align: left; color: #ffffff; font-size: 12px; font-weight: bold; border: 1px solid #5a67d8;">Account #</th>
                <th style="padding: 10px 12px; text-align: left; color: #ffffff; font-size: 12px; font-weight: bold; border: 1px solid #5a67d8;">Customer</th>
                <th style="padding: 10px 12px; text-align: right; color: #ffffff; font-size: 12px; font-weight: bold; border: 1px solid #5a67d8;">Payment</th>
                <th style="padding: 10px 12px; text-align: right; color: #ffffff; font-size: 12px; font-weight: bold; border: 1px solid #5a67d8;">Disbursement</th>
                <th style="padding: 10px 12px; text-align: right; color: #ffffff; font-size: 12px; font-weight: bold; border: 1px solid #5a67d8;">Balance After</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $i => $transaction)
            <tr style="background-color: {{ $i % 2 === 0 ? '#ffffff' : '#f9fafb' }};">
                <td style="padding: 9px 12px; font-size: 12px; color: #374151; border: 1px solid #e5e7eb;">
                    {{ $transaction->transaction_date->format('M d, Y') }}
                </td>
                <td style="padding: 9px 12px; font-size: 12px; color: #374151; border: 1px solid #e5e7eb;">
                    {{ $transaction->account->account_number }}
                </td>
                <td style="padding: 9px 12px; font-size: 12px; color: #374151; border: 1px solid #e5e7eb;">
                    {{ $transaction->account->customer->name }}
                </td>
                <td style="padding: 9px 12px; font-size: 12px; text-align: right; border: 1px solid #e5e7eb;">
                    @if($transaction->type === 'payment')
                        <span style="color: #16a34a; font-weight: bold;">&#8369;{{ number_format($transaction->amount, 2) }}</span>
                    @else
                        <span style="color: #9ca3af;">—</span>
                    @endif
                </td>
                <td style="padding: 9px 12px; font-size: 12px; text-align: right; border: 1px solid #e5e7eb;">
                    @if($transaction->type === 'disbursement')
                        <span style="color: #2563eb; font-weight: bold;">&#8369;{{ number_format($transaction->amount, 2) }}</span>
                    @else
                        <span style="color: #9ca3af;">—</span>
                    @endif
                </td>
                <td style="padding: 9px 12px; font-size: 12px; text-align: right; color: #374151; border: 1px solid #e5e7eb;">
                    &#8369;{{ number_format($transaction->balance_after, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f3f4f6;">
                <td colspan="3" style="padding: 10px 12px; font-size: 12px; font-weight: bold; color: #111827; border: 1px solid #e5e7eb; text-align: right;">
                    Total
                </td>
                <td style="padding: 10px 12px; font-size: 12px; font-weight: bold; color: #16a34a; text-align: right; border: 1px solid #e5e7eb;">
                    &#8369;{{ number_format($summary['payments'], 2) }}
                </td>
                <td style="padding: 10px 12px; font-size: 12px; font-weight: bold; color: #2563eb; text-align: right; border: 1px solid #e5e7eb;">
                    &#8369;{{ number_format($summary['disbursements'], 2) }}
                </td>
                <td style="padding: 10px 12px; font-size: 12px; border: 1px solid #e5e7eb;"></td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="background-color: #f8f9fa; padding: 15px; border-top: 1px solid #e0e0e0; text-align: center;">
                <p style="margin: 0; color: #666666; font-size: 11px;">
                    Generated on {{ now()->format('F d, Y h:i A') }} &mdash; This is a system-generated report.
                </p>
            </td>
        </tr>
    </table>

</body>
</html>

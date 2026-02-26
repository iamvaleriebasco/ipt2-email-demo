<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Spatie\LaravelPdf\Facades\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month        = $request->get('month', now()->format('Y-m'));
        $transactions = collect();
        $summary      = [];

        if ($month) {
            $date  = Carbon::parse($month . '-01');
            $start = $date->copy()->startOfMonth();
            $end   = $date->copy()->endOfMonth();

            $transactions = Transaction::with('account.customer')
                ->whereBetween('transaction_date', [$start, $end])
                ->orderBy('transaction_date', 'desc')
                ->get();

            $summary = [
                'month'       => $date->format('F Y'),
                'total'       => $transactions->count(),
                'payments'    => $transactions->where('type', 'payment')->sum('amount'),
                'charges'     => $transactions->where('type', 'charge')->sum('amount'),
                'disbursements' => $transactions->where('type', 'disbursement')->sum('amount'),
            ];
        }

        return view('reports.index', compact('transactions', 'month', 'summary'));
    }

    public function generatePdf(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $date  = Carbon::parse($month . '-01');
        $start = $date->copy()->startOfMonth();
        $end   = $date->copy()->endOfMonth();

        $transactions = Transaction::with('account.customer')
            ->whereBetween('transaction_date', [$start, $end])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $summary = [
            'month'         => $date->format('F Y'),
            'total'         => $transactions->count(),
            'payments'      => $transactions->where('type', 'payment')->sum('amount'),
            'charges'       => $transactions->where('type', 'charge')->sum('amount'),
            'disbursements' => $transactions->where('type', 'disbursement')->sum('amount'),
        ];

        $filename = 'Report-' . $date->format('Y-m') . '.pdf';

        return Pdf::view('reports.pdf', compact('transactions', 'summary', 'month'))
            ->format('letter')
            ->name($filename)
            ->download();
    }
}

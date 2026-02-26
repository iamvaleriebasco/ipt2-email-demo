<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

class ManagementController extends Controller
{
    public function soaGeneration()
    {
        $accounts = $this->getAccountsForSOA();

        return view('soa.index', [
            'accounts' => $accounts,
        ]);
    }

    public function generateAllSOAs()
    {
        $accounts = $this->getAccountsForSOA();

        foreach ($accounts as $account) {
            Log::info("Generating SOA for Account ID: {$account->id}, Account Number: {$account->account_number}");
            $this->generateAndSend($account);
        }

        return redirect()->route('soa.index')->with('status', 'All SOAs have been generated and emailed successfully.');
    }

    /**
     * Generate PDF + send email for a single account (triggered from the table row).
     */
    public function generateSOA(Account $account)
    {
        Log::info("Generating SOA for Account ID: {$account->id}, Account Number: {$account->account_number}");
        $this->generateAndSend($account);

        return redirect()->route('soa.index')->with('status', "SOA for account {$account->account_number} has been generated and emailed.");
    }

    /**
     * Generate PDF + send email, then download the PDF to the browser.
     */
    public function generateSOAPdf(Account $account)
    {
        $filename = 'SOA-' . $account->account_number . '-' . now()->format('Ymd') . '.pdf';
        $storagePath = 'soa/' . $filename;

        Storage::makeDirectory('soa');

        // Save PDF to storage/app/soa/
        Pdf::view('emails.soa', ['account' => $account])
            ->format('letter')
            ->save(Storage::path($storagePath));

        // Send email with PDF attached
        Mail::send('emails.soa', ['account' => $account], function ($message) use ($account, $storagePath, $filename) {
            $message->to($account->customer->email, $account->customer->name)
                    ->subject('Statement of Account - ' . $account->account_number)
                    ->attach(Storage::path($storagePath), [
                        'as'   => $filename,
                        'mime' => 'application/pdf',
                    ]);
        });

        Log::info("SOA PDF generated and emailed for Account: {$account->account_number}");

        // Download the PDF to the browser
        return response()->download(Storage::path($storagePath), $filename);
    }

    /**
     * Core logic: save PDF to storage and send email with attachment.
     */
    private function generateAndSend(Account $account): void
    {
        $filename = 'SOA-' . $account->account_number . '-' . now()->format('Ymd') . '.pdf';
        $storagePath = 'soa/' . $filename;

        Storage::makeDirectory('soa');

        // Generate and save PDF
        Pdf::view('emails.soa', ['account' => $account])
            ->format('letter')
            ->save(Storage::path($storagePath));

        // Send email with PDF attached
        Mail::send('emails.soa', ['account' => $account], function ($message) use ($account, $storagePath, $filename) {
            $message->to($account->customer->email, $account->customer->name)
                    ->subject('Statement of Account - ' . $account->account_number)
                    ->attach(Storage::path($storagePath), [
                        'as'   => $filename,
                        'mime' => 'application/pdf',
                    ]);
        });
    }

    private function getAccountsForSOA()
    {
        // return Account::whereDay('start_date', 23)->get();
        return Account::whereDay('start_date', \Carbon\Carbon::now()->addDays(10)->day)->get();
    }
}

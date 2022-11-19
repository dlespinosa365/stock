<?php

namespace App\Jobs;

use App\Mail\ProductMaintenanceNotification;
use App\Models\ProductMaintenance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessProductMaintenance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productMaintenances = ProductMaintenance::getReadyToSend();
        foreach ($productMaintenances as $productMaintenance) {
            $this->processProductMaintenance($productMaintenance);
        }
    }

    private function processProductMaintenance(ProductMaintenance $productMaintenance) {
        if (!$productMaintenance->location->email) {
            $productMaintenance->reason = 'NO EMAIL';
            $productMaintenance->save();
            return null;
        }
        $this->sendMailToNotifyMaintenance($productMaintenance);
    }

    private function sendMailToNotifyMaintenance(ProductMaintenance $productMaintenance) {
        Mail::to($productMaintenance->location->email)
            ->send(new ProductMaintenanceNotification($productMaintenance))
            ->bcc(['dlespinosa365@gmail.com','benjamalo02@gmail.com']);
    }
}

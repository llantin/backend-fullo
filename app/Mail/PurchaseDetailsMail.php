<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchaseDetails;

    /**
     * Create a new message instance.
     *
     * @param $purchaseDetails
     */
    public function __construct($purchaseDetails)
    {
        $this->purchaseDetails = $purchaseDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.purchase_details')
                    ->subject('Detalles de tu Compra')
                    ->with([
                        'purchaseDetails' => $this->purchaseDetails
                    ]);
    }
}

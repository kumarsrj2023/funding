<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SopSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $directorInfo;
    public $businessInfo;
    public $pdfPath;

    public function __construct($directorInfo, $pdfPath, $businessInfo)
    {
        $this->directorInfo = $directorInfo;
        $this->pdfPath = $pdfPath;
        $this->businessInfo = $businessInfo;
    }

    public function build()
    {
        return $this->view('emails.sop_submitted')
            ->subject("{$this->directorInfo->first_name} {$this->directorInfo->surname} – SOP")
            ->attach($this->pdfPath, [
                'as' => "{$this->directorInfo->first_name} {$this->directorInfo->surname} - SOP.pdf",
                'mime' => 'application/pdf',
            ])
            ->with([
                'name' => "{$this->directorInfo->first_name} {$this->directorInfo->surname}",
                'businessName' => $this->businessInfo->business_name,
            ]);
    }
}

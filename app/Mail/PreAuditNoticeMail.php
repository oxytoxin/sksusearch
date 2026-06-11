<?php

namespace App\Mail;

use App\Models\DisbursementVoucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * ICU Pre-Audit Notice email — mirrors the on-screen ICU Verification Report
 * (resources/views/livewire/icu/icu-manage-verified-documents.blade.php).
 *
 * READY-TO-USE TEMPLATE, NOT YET WIRED. When a wiring point is chosen (e.g. the
 * ICU verify/return action), dispatch it — directly:
 *     Mail::to($user->email)->send(new PreAuditNoticeMail($dv, $user->name, $reviewerName, $reviewerPosition));
 * or via the logged email channel by rendering it to HTML and passing the body to SendEmailJob.
 *
 * All findings are read from the DisbursementVoucher model at render time, so the
 * notice always reflects the current verification state.
 */
class PreAuditNoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    public DisbursementVoucher $disbursementVoucher;
    public ?string $recipientName;
    public ?string $reviewerName;
    public ?string $reviewerPosition;
    public ?string $senderEmail;

    public function __construct(
        DisbursementVoucher $disbursementVoucher,
        ?string $recipientName = null,
        ?string $reviewerName = null,
        ?string $reviewerPosition = null,
        ?string $senderEmail = null
    ) {
        $this->disbursementVoucher = $disbursementVoucher;
        $this->recipientName = $recipientName;
        $this->reviewerName = $reviewerName;
        $this->reviewerPosition = $reviewerPosition;
        $this->senderEmail = $senderEmail;
    }

    public function envelope()
    {
        $result = $this->isForCompliance() ? 'For Compliance' : 'Verified';

        return new Envelope(
            subject: "Pre-Audit Notice: {$this->disbursementVoucher->tracking_number} - {$result}",
        );
    }

    public function content()
    {
        $dv = $this->disbursementVoucher;

        $purposes = $dv->disbursement_voucher_particulars
            ? $dv->disbursement_voucher_particulars->pluck('purpose')->filter()->implode('; ')
            : '';

        return new Content(
            view: 'emails.pre-audit-notice',
            with: [
                'dv' => $dv,
                'recipientName' => $this->recipientName ?? optional($dv->user)->name,
                'items' => $dv->getRelatedDocumentItems(),
                'generalRemarks' => $dv->getRelatedDocumentsGeneralRemarks(),
                'isForCompliance' => $this->isForCompliance(),
                'purposes' => $purposes,
                'senderEmail' => $this->senderEmail ?? config('mail.from.address'),
            ],
        );
    }

    /**
     * "For Compliance" = at least one requirement is deficient (status not_required).
     */
    protected function isForCompliance(): bool
    {
        return $this->disbursementVoucher->getRelatedDocumentItems()
            ->contains(fn ($item) => ($item['status'] ?? null) === 'not_required');
    }
}

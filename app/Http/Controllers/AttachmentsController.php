<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Storage;

class AttachmentsController extends Controller
{
    public function download(Attachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->path)) {
            Log::warning('Attachment file missing on download', [
                'attachment_id' => $attachment->id,
                'path'          => $attachment->path,
                'file_name'     => $attachment->file_name,
                'user_id'       => auth()->id(),
            ]);
            abort(404, 'Attachment file is missing from storage.');
        }

        return Storage::disk('public')->download($attachment->path, $attachment->file_name);
    }
}

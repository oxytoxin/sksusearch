<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Storage;

class AttachmentsController extends Controller
{
    public function download(Attachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404, 'Attachment file is missing from storage.');
        }

        return Storage::disk('public')->download($attachment->path, $attachment->file_name);
    }
}

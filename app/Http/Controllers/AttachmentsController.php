<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Storage;

class AttachmentsController extends Controller
{
    public function download(Attachment $attachment)
    {
        return Storage::download($attachment->path, $attachment->file_name);
    }
}

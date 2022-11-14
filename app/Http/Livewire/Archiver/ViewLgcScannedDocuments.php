<?php

namespace App\Http\Livewire\Archiver;

use App\Models\LegacyDocument;
use App\Models\ScannedDocument;
use Livewire\Component;
use WireUi\Traits\Actions;

class ViewLgcScannedDocuments extends Component
{
    use Actions;
    public $dv;
    public $scanned_documents;
    public $editable=0;
    public $currentAttachment;
    public function mount(LegacyDocument $legacy_document, $edit)
    {
        $this->dv = $legacy_document;
        $this->editable = $edit;
    }
    
    public function deleteAttachment($curAtt)
    {
        $this->currentAttachment = $curAtt;
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete attachement? This action cannot be undone',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'deleteAttachmentFinal',
                'params' => 'Saved',
            ],
            'reject' => [
                'label'  => 'No, cancel',
            ],
        ]);
    }
    
    public function deleteAttachmentFinal()
    {
        $deleted = false;
        if (isset($this->currentAttachment)) {
            $deleted = ScannedDocument::where('id',$this->currentAttachment['id'])->first()->delete();
        }
       if ($deleted) {
        $this->dialog()->success(
            $title = 'Operation Success',
            $description = 'Attachment has been removed from the database and server/s!'
        );   
       } else {
        $this->dialog()->error(
            $title = 'An error occured!',
            $description = 'Reload the page and try again!'
        );
       }
       
    }
    public function render()
    {
        return view('livewire.archiver.view-lgc-scanned-documents');
    }
}

<?php

namespace App\Http\Livewire\Archiver;

use App\Models\ArchivedCheque;
use App\Models\ScannedDocument;
use Livewire\Component;
use WireUi\Traits\Actions;

class ViewChqScannedDocuments extends Component
{
    use Actions;
    public $dv;
    public $scanned_documents;
    public $editable=0;
    public $currentAttachment;
    public function mount(ArchivedCheque $archived_cheque, $edit)
    {
        $this->dv = $archived_cheque;
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
        return view('livewire.archiver.view-chq-scanned-documents');
    }
}

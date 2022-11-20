<?php

namespace App\Http\Livewire;
use Livewire\WithPagination;
use Livewire\Component;

class CustomMasterComponent extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected function sendSuccessMessageToSession($message) {
        session()->flash('type', 'success');
        session()->flash('message', $message);
    }

    protected function sendErrorMessageToSeparateSession($message) {
        session()->flash('error_message', $message);
    }

    protected function sendErrorMessageToSession($message) {
        session()->flash('type', 'error');
        session()->flash('message', $message);
    }

    protected function sendInfoMessageToSession($message) {
        session()->flash('type', 'info');
        session()->flash('message', $message);
    }

    protected function log($title, $message) {
        $this->dispatchBrowserEvent('log', ['title' => $title, 'message' => $message ]);
    }

    protected function closeModal($modalId) {
        $this->dispatchBrowserEvent('close-modal', ['id' => $modalId]);
    }


}

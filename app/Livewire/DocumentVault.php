<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentVault extends Component
{
    use WithFileUploads;

    public $title, $category, $file;
    public $isModalOpen = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'category' => 'required|string',
        'file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx', // Max 10MB
    ];

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['title', 'category', 'file']);
    }

    public function save()
    {
        $this->validate();

        $path = $this->file->store('documents/' . Auth::id(), 'public');

        Document::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'category' => $this->category,
            'file_path' => $path,
            'mime_type' => $this->file->getMimeType(),
        ]);

        $this->dispatch('flash-message', text: 'Dokumen berhasil diunggah ke brankas digital.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $doc = Document::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        
        if (Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }
        
        $doc->delete();
        $this->dispatch('flash-message', type: 'info', text: 'Dokumen dihapus.');
    }

    public function download($id)
    {
        $doc = Document::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        return response()->download(storage_path('app/public/' . $doc->file_path), $doc->title . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION));
    }

    public function render()
    {
        $documents = Document::where('user_id', Auth::id())->latest()->get();
        return view('livewire.document-vault', ['documents' => $documents])
            ->layout('components.layouts.app');
    }
}

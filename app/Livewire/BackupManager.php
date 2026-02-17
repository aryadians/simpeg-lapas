<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupManager extends Component
{
    public function generateBackup()
    {
        if (Auth::user()->role !== 'admin') return;

        $zip = new ZipArchive;
        $fileName = 'backup-' . now()->format('Y-m-d-His') . '.zip';
        $tempPath = storage_path('app/' . $fileName);

        if ($zip->open($tempPath, ZipArchive::CREATE) === TRUE) {
            
            // 1. Backup Database (Jika SQLite)
            $dbPath = database_path('database.sqlite');
            if (file_exists($dbPath)) {
                $zip->addFile($dbPath, 'database.sqlite');
            }

            // 2. Backup Selfies (Storage Public)
            $files = Storage::disk('public')->allFiles('selfies');
            foreach ($files as $file) {
                $zip->addFile(storage_path('app/public/' . $file), 'storage/' . $file);
            }

            $zip->close();

            $this->dispatch('flash-message', text: 'Backup Archive Generated Successfully.');
            return response()->download($tempPath)->deleteFileAfterSend(true);
        } else {
            $this->dispatch('flash-message', type: 'error', text: 'Failed to create backup archive.');
        }
    }

    public function render()
    {
        return view('livewire.backup-manager');
    }
}

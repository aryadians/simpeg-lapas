<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class InventoryManager extends Component
{
    use WithPagination;

    public $name, $description, $serial_number;
    public $inventory_id;
    public $selectedItem;

    public $users;
    public $selectedUser;
    public $due_at;
    public $notes;

    public $isModalOpen = false;
    public $isCheckoutModalOpen = false;
    public $isHistoryModalOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'serial_number' => 'nullable|string|max:255|unique:inventories,serial_number',
    ];

    public function render()
    {
        return view('livewire.inventory-manager', [
            'inventories' => Inventory::with('holder')->paginate(10),
        ])->layout('components.layouts.app');
    }

    public function openModal()
    {
        $this->resetCreateForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm()
    {
        $this->name = '';
        $this->description = '';
        $this->serial_number = '';
    }

    public function store()
    {
        $this->validate();

        Inventory::create([
            'name' => $this->name,
            'description' => $this->description,
            'serial_number' => $this->serial_number,
        ]);

        session()->flash('message', 'Item inventaris berhasil ditambahkan.');

        $this->closeModal();
        $this->resetCreateForm();
    }

    public function openCheckoutModal(Inventory $inventory)
    {
        $this->selectedItem = $inventory;
        $this->users = User::orderBy('name')->get();
        $this->selectedUser = null;
        $this->due_at = null;
        $this->notes = '';
        $this->isCheckoutModalOpen = true;
    }

    public function closeCheckoutModal()
    {
        $this->isCheckoutModalOpen = false;
    }

    public function checkout()
    {
        $this->validate([
            'selectedUser' => 'required|exists:users,id',
            'due_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $this->selectedItem->update([
            'status' => 'checked_out',
            'current_holder_id' => $this->selectedUser,
            'checked_out_at' => now(),
            'due_at' => $this->due_at,
        ]);

        InventoryLog::create([
            'inventory_id' => $this->selectedItem->id,
            'user_id' => $this->selectedUser,
            'action' => 'checked_out',
            'action_at' => now(),
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Item berhasil dipinjamkan.');
        $this->closeCheckoutModal();
    }

    public function checkin(Inventory $inventory)
    {
        InventoryLog::create([
            'inventory_id' => $inventory->id,
            'user_id' => $inventory->current_holder_id,
            'action' => 'checked_in',
            'action_at' => now(),
            'notes' => 'Dikembalikan oleh pemegang terakhir.',
        ]);

        $inventory->update([
            'status' => 'available',
            'current_holder_id' => null,
            'checked_out_at' => null,
            'due_at' => null,
        ]);

        session()->flash('message', 'Item telah dikembalikan.');
    }

    public function openHistoryModal(Inventory $inventory)
    {
        $this->selectedItem = $inventory->load('logs.user');
        $this->isHistoryModalOpen = true;
    }

    public function closeHistoryModal()
    {
        $this->isHistoryModalOpen = false;
    }
}

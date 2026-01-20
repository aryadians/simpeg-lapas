<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class EmployeeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $employeeId = null;

    // Properti Form
    public $name, $email, $nip, $jabatan, $grade;

    protected $listeners = ['deleteConfirmed' => 'deleteConfirmed', 'resetPasswordConfirmed' => 'resetPasswordConfirmed'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $employees = User::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('nip', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.employee-manager', [
            'employees' => $employees
        ])->layout('components.layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->employeeId = null;
        $this->name = '';
        $this->email = '';
        $this->nip = '';
        $this->jabatan = '';
        $this->grade = '';
    }

    public function store()
    {
        if (Auth::user()->role !== 'admin') {
            $this->dispatch('flash-message', type: 'error', title: 'Akses Ditolak!', text: 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            return;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->employeeId,
            'nip' => 'required|string|max:20',
            'jabatan' => 'required|string',
            'grade' => 'required|integer',
        ]);

        User::updateOrCreate(['id' => $this->employeeId], [
            'name' => $this->name,
            'email' => $this->email,
            'nip' => $this->nip,
            'jabatan' => $this->jabatan,
            'grade' => $this->grade,
            'password' => $this->employeeId ? User::find($this->employeeId)->password : Hash::make('password'),
            'role' => 'staff'
        ]);

        $this->closeModal();
        $this->dispatch('flash-message', text: $this->employeeId ? 'Data Pegawai berhasil diperbarui!' : 'Pegawai baru berhasil ditambahkan!');
        $this->resetInputFields();
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') return;

        $employee = User::findOrFail($id);
        $this->employeeId = $id;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->nip = $employee->nip;
        $this->jabatan = $employee->jabatan;
        $this->grade = $employee->grade;

        $this->openModal();
    }

    public function delete($id)
    {
        if (Auth::user()->role !== 'admin') {
             $this->dispatch('flash-message', type: 'error', title: 'Akses Ditolak!', text: 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            return;
        }
        $this->dispatch('confirm-dialog', title: 'Hapus Pegawai?', text: 'Anda yakin ingin menghapus data pegawai ini?', confirm_event: 'deleteConfirmed', confirm_params: $id);
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed($id)
    {
        User::find($id)->delete();
        $this->dispatch('flash-message', text: 'Data Pegawai telah dihapus.');
    }

    public function resetPassword($id)
    {
        if (auth()->user()->role !== 'admin') {
            $this->dispatch('flash-message', type: 'error', title: 'Akses Ditolak!', text: 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            return;
        }
        $user = User::find($id);
        $this->dispatch('confirm-dialog', title: 'Reset Password?', text: 'Reset password untuk ' . $user->name . ' menjadi "password"?', confirm_event: 'resetPasswordConfirmed', confirm_params: $id);
    }

    #[On('resetPasswordConfirmed')]
    public function resetPasswordConfirmed($id)
    {
        $user = User::find($id);
        $user->update([
            'password' => Hash::make('password')
        ]);
        $this->dispatch('flash-message', text: 'Password untuk ' . $user->name . ' telah direset.');
    }
}

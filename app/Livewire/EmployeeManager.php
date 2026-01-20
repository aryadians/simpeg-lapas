<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeManager extends Component
{
    public $employees;
    public $isModalOpen = false;
    public $employeeId = null;

    // Form Fields
    public $name, $email, $nip, $jabatan, $grade;

    public function render()
    {
        // Ambil semua user kecuali diri sendiri (Admin) agar tidak terhapus tidak sengaja
        $this->employees = User::orderBy('name')->get();

        return view('livewire.employee-manager')
            ->layout('components.layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $employee = User::findOrFail($id);
        $this->employeeId = $id;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->nip = $employee->nip;
        $this->jabatan = $employee->jabatan;
        $this->grade = $employee->grade;

        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'nip' => 'required|unique:users,nip,' . $this->employeeId,
            'jabatan' => 'required',
            'grade' => 'required|numeric',
        ]);

        User::updateOrCreate(['id' => $this->employeeId], [
            'name' => $this->name,
            'email' => $this->email,
            'nip' => $this->nip,
            'jabatan' => $this->jabatan,
            'grade' => $this->grade,
            // Jika user baru, set password default: 'password'
            'password' => $this->employeeId ? User::find($this->employeeId)->password : Hash::make('password')
        ]);

        $this->closeModal();
        $this->dispatch('roster-updated', message: $this->employeeId ? 'Data diperbarui' : 'Pegawai ditambahkan');
    }

    public function delete($id)
    {
        User::find($id)->delete();
        $this->dispatch('roster-updated', message: 'Pegawai dihapus');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->nip = '';
        $this->jabatan = '';
        $this->grade = '';
        $this->employeeId = null;
    }
}

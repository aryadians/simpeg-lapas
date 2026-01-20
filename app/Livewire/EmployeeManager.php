<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination; // 1. Import Trait Pagination
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeeManager extends Component
{
    use WithPagination; // 2. Gunakan Trait ini

    public $search = '';
    public $isModalOpen = false;
    public $employeeId = null;

    // Properti Form
    public $name, $email, $nip, $jabatan, $grade;

    // Reset ke halaman 1 jika user mengetik di search bar
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // 3. Query Data (Ganti get() menjadi paginate())
        $employees = User::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('nip', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10); // <--- PERBAIKAN UTAMA: Gunakan paginate, bukan get

        return view('livewire.employee-manager', [
            'employees' => $employees
        ])->layout('components.layouts.app');
    }

    // ==========================================
    // LOGIKA CRUD (TAMBAH, EDIT, HAPUS)
    // ==========================================

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
        // KEAMANAN: Cek Role Admin
        if (Auth::user()->role !== 'admin') {
            $this->dispatch('roster-updated', message: 'AKSES DITOLAK: Anda bukan Admin!');
            return;
        }

        // Validasi
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->employeeId,
            'nip' => 'required|string|max:20',
            'jabatan' => 'required|string',
            'grade' => 'required|integer',
        ]);

        // Logic Simpan / Update
        User::updateOrCreate(['id' => $this->employeeId], [
            'name' => $this->name,
            'email' => $this->email,
            'nip' => $this->nip,
            'jabatan' => $this->jabatan,
            'grade' => $this->grade,
            // Jika user baru, kasih password default 'password'. Jika edit, jangan ubah password.
            'password' => $this->employeeId ? User::find($this->employeeId)->password : Hash::make('password'),
            'role' => 'staff' // Default role
        ]);

        $this->closeModal();
        $this->resetInputFields();
        $this->dispatch('roster-updated', message: $this->employeeId ? 'Data Pegawai Diperbarui!' : 'Pegawai Baru Ditambahkan!');
    }

    public function edit($id)
    {
        // KEAMANAN
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
        // KEAMANAN
        if (Auth::user()->role !== 'admin') {
            $this->dispatch('roster-updated', message: 'AKSES DITOLAK: Anda bukan Admin!');
            return;
        }

        User::find($id)->delete();
        $this->dispatch('roster-updated', message: 'Data Pegawai Dihapus.');
    }
}

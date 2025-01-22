<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Dashboard extends Component
{
    public $jumlahKaryawan;

    public function mount()
    {
        // Panggil API untuk mendapatkan jumlah karyawan
        $response = Http::get(url('/api/karyawan'));
        if ($response->successful()) {
            $this->jumlahKaryawan = count($response->json());
        } else {
            $this->jumlahKaryawan = 'Error fetching data';
        }

        $this->jumlahKaryawan = 50;
    }

    public function render()
    {
        return view('dashboard');
    }
}

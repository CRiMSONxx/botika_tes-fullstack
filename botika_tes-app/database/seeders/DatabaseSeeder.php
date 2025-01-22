<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com', //password=password
        ]);
        User::factory()->create([
            'name' => 'SU User',
            'email' => 'su@example.com',
        ]);

        // Seed Divisi
        $divisiIds = [];
        $divisiIds[] = DB::table('divisis')->insertGetId([
            'nama_divisi' => 'IT',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $divisiIds[] = DB::table('divisis')->insertGetId([
            'nama_divisi' => 'HRD',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Seed Pekerjaan
        $pekerjaanIds = [];
        $pekerjaanIds[] = DB::table('pekerjaans')->insertGetId([
            'nama_pekerjaan' => 'Developer',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $pekerjaanIds[] = DB::table('pekerjaans')->insertGetId([
            'nama_pekerjaan' => 'Manager',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Seed Karyawan
        DB::table('karyawans')->insert([
            [
                'nama' => 'John Doe',
                'email' => 'john.doe@example.com',
                'no_telepon' => '551523123',
                'tanggal_lahir' => '1990-01-01',
                'divisi_id' => $divisiIds[0],
                'pekerjaan_id' => $pekerjaanIds[0],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'nama' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'no_telepon' => '1231566',
                'tanggal_lahir' => '1992-02-02',
                'divisi_id' => $divisiIds[1],
                'pekerjaan_id' => $pekerjaanIds[1],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'nama' => 'Alex Johnson',
                'email' => 'alex.johnson@example.com',
                'no_telepon' => '12356735',
                'tanggal_lahir' => '1995-03-03',
                'divisi_id' => $divisiIds[0],
                'pekerjaan_id' => $pekerjaanIds[0],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'nama' => '123 Johnson',
                'email' => '123.johnson@example.com',
                'no_telepon' => '123412453',
                'tanggal_lahir' => '1995-03-03',
                'divisi_id' => $divisiIds[0],
                'pekerjaan_id' => $pekerjaanIds[0],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => now(),
            ],
            [
                'nama' => '555 Johnson',
                'email' => '555.johnson@example.com',
                'no_telepon' => '081123892',
                'tanggal_lahir' => '1995-03-03',
                'divisi_id' => $divisiIds[0],
                'pekerjaan_id' => $pekerjaanIds[0],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ]
        ]);
    }
}

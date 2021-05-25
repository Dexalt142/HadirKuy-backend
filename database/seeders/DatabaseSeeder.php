<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {

        /* Setup guru */
        Guru::create([
            'nip' => '1234567890',
            'nama' => 'Fatur Rahman S',
            'email' => 'fatur@hadirkuy.test',
            'password' => Hash::make('fatur12345'),
            'mata_pelajaran' => 'Pemrograman Web dan Perangkat Bergerak',
        ]);

        /* Setup siswa */
        Siswa::create([
            'nis' => '1800001',
            'kode' => '2dbadb45-6cc2-406a-b0b8-32a217b22bdf',
            'nama' => 'Muhammad Cahya',
            'jenis_kelamin' => 'L',
            'alamat' => 'Garut',
            'tempat_lahir' => 'Garut',
            'tanggal_lahir' => '2000-04-20',
            'foto' => '2dbadb45-6cc2-406a-b0b8-32a217b22bdf.jpg'
        ]);

        Siswa::create([
            'nis' => '1800002',
            'kode' => 'b07af1cc-147d-4d24-9e5f-421d508643dd',
            'nama' => 'Fatur Rahman',
            'jenis_kelamin' => 'L',
            'alamat' => 'Sukarajin',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2000-02-14',
            'foto' => 'b07af1cc-147d-4d24-9e5f-421d508643dd.jpg'
        ]);
    }
}

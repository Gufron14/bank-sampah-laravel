<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Pastikan role sudah ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // $nasabahRole = Role::firstOrCreate(['name' => 'nasabah']);

        // Buat 1 Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@banksampah.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'dusun' => 'Tanggulangin',
            'rt' => '001',
            'rw' => '001',
            'balance' => 0,
            'age' => 24,
            'no_rek' => '1234567890123456',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Data untuk 10 Nasabah
        $nasabahData = [
            [
                'name' => 'Dian Aldera Herdiansyah',
                'email' => 'herdi@gmail.com',
                'phone' => '081234567891',
                'dusun' => 'Tanggulangin',
                'rt' => '001',
                'rw' => '001',
                'balance' => 0,
                'age' => 24,
                'no_rek' => '1234567890123456',
                'next_pickup_date' => '2025-01-15',
            ],
            // [
            //     'name' => 'Siti Nurhaliza',
            //     'email' => 'siti@gmail.com',
            //     'phone' => '081234567892',
            //     'dusun' => 'Tanggulangin',
            //     'rt' => '001',
            //     'rw' => '002',
            //     'balance' => 32000,
            //     'next_pickup_date' => '2025-01-16',
            // ],
            // [
            //     'name' => 'Ahmad Fauzi',
            //     'email' => 'ahmad@gmail.com',
            //     'phone' => '081234567893',
            //     'dusun' => 'Tanggulangin',
            //     'rt' => '002',
            //     'rw' => '001',
            //     'balance' => 28000,
            //     'next_pickup_date' => '2025-01-17',
            // ],
            // [
            //     'name' => 'Dewi Sartika',
            //     'email' => 'dewi@gmail.com',
            //     'phone' => '081234567894',
            //     'dusun' => 'Tanggulangin',
            //     'rt' => '002',
            //     'rw' => '002',
            //     'balance' => 51000,
            //     'next_pickup_date' => '2025-01-18',
            // ],
            // [
            //     'name' => 'Rudi Hermawan',
            //     'email' => 'rudi@gmail.com',
            //     'phone' => '081234567895',
            //     'dusun' => 'Tanggulangin',
            //     'rt' => '003',
            //     'rw' => '001',
            //     'balance' => 19000,
            //     'next_pickup_date' => '2025-01-19',
            // ],
            // [
            //     'name' => 'Rina Wati',
            //     'email' => 'rina@gmail.com',
            //     'phone' => '081234567896',
            //     'dusun' => 'Tanggulangin',
            //     'rt' => '003',
            //     'rw' => '002',
            //     'balance' => 37000,
            //     'next_pickup_date' => '2025-01-20',
            // ],
            // [
            //     'name' => 'Joko Widodo',
            //     'email' => 'joko@gmail.com',
            //     'phone' => '081234567897',
            //     'dusun' => 'Tanggulangin',
            //     'rt' => '004',
            //     'rw' => '001',
            //     'balance' => 42000,
            //     'next_pickup_date' => '2025-01-21',
            // ],
            // [
            //     'name' => 'Sri Mulyani',
            //     'email' => 'sri@gmail.com',
            //     'phone' => '081234567898',
            //     'dusun' => 'Tanggulangin',
            //     'rt' => '004',
            //     'rw' => '002',
            //     'balance' => 25000,
            //     'next_pickup_date' => '2025-01-22',
            // ],
            // [
            //     'name' => 'Bambang Sutrisno',
            //     'email' => 'bambang@gmail.com',
            //     'phone' => '081234567899',
            //     'dusun' => 'Tanggulangin',
            //     'rt' => '005',
            //     'rw' => '001',
            //     'balance' => 33000,
            //     'next_pickup_date' => '2025-01-23',
            // ],
            // [
            //     'name' => 'Ani Yudhoyono',
            //     'email' => 'ani@gmail.com',
            //     'phone' => '081234567800',
            //     'dusun' => 'Tanggulangin',
            //     'rt' => '005',
            //     'rw' => '002',
            //     'balance' => 48000,
            //     'next_pickup_date' => '2025-01-24',
            // ],
        ];

        // Buat 10 Nasabah
        foreach ($nasabahData as $data) {
            $nasabah = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'), // Password default: password
                'phone' => $data['phone'],
                'dusun' => $data['dusun'],
                'rt' => $data['rt'],
                'rw' => $data['rw'],
                'balance' => $data['balance'],
                // 'next_pickup_date' => $data['next_pickup_date'],
                'age' => $data['age'],
                'no_rek' => $data['no_rek'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $nasabah->assignRole('nasabah');
        }

        // $this->command->info('✅ Berhasil membuat 1 Admin dan 10 Nasabah');
        $this->command->info('📧 Email Admin: admin@banksampah.com');
        $this->command->info('🔑 Password untuk semua user: password');
    }
}

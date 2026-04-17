<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ============================================
        // USERS
        // ============================================
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        User::create([
            'name' => 'Admin Yaksa',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Operator',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // ============================================
        // ITEMS (Sample dari Google Sheets)
        // ============================================
        $items = [
            ['nama_perangkat' => 'FortiADC-200F', 'serial_number' => 'FAD2HFTA19000224', 'status' => 'Barang Keluar', 'status_barang' => 'Demo', 'os_version' => null, 'lokasi_device' => 'Delivered', 'description' => 'Pickup Expedisi Cibitung'],
            ['nama_perangkat' => 'FortiADC-300F', 'serial_number' => 'FAD3HFTA20000090', 'status' => 'Ready', 'status_barang' => 'Demo', 'os_version' => null, 'lokasi_device' => 'Gudang ATS', 'description' => null],
            ['nama_perangkat' => 'FortiAnalyzer-1000G', 'serial_number' => 'FAZ1KGT924000248', 'status' => 'Barang RMA', 'status_barang' => 'DRMA Bank IBK', 'os_version' => null, 'lokasi_device' => 'Gudang ATS', 'description' => null],
            ['nama_perangkat' => 'FortiAnalyzer-150G', 'serial_number' => 'FAZ15GT222000116', 'status' => 'Barang Keluar', 'status_barang' => null, 'os_version' => '7.2.4', 'lokasi_device' => 'Delivered', 'description' => 'Di pickup expedisi SMI'],
            ['nama_perangkat' => 'FortiAnalyzer-200F', 'serial_number' => 'FL-2HFTB18000189', 'status' => 'Barang Keluar', 'status_barang' => 'Demo', 'os_version' => '6.4.9', 'lokasi_device' => 'Delivered', 'description' => 'Pickup Expedisi ke gudang Cibitung'],
            ['nama_perangkat' => 'FortiAnalyzer-200F', 'serial_number' => 'FL-2HFTB20000929', 'status' => 'Ready', 'status_barang' => 'Demo', 'os_version' => '7.0.15', 'lokasi_device' => 'Gudang ATS', 'description' => null],
            ['nama_perangkat' => 'FortiAP-231F', 'serial_number' => 'FP231FTF2309DZTL', 'status' => 'Ready', 'status_barang' => 'Demo', 'os_version' => null, 'lokasi_device' => 'Gudang ATS', 'description' => null],
            ['nama_perangkat' => 'FortiGate-100F', 'serial_number' => 'FG100FTK23000055', 'status' => 'Barang Keluar', 'status_barang' => null, 'os_version' => null, 'lokasi_device' => 'Delivered', 'description' => null],
            ['nama_perangkat' => 'FortiGate-101F', 'serial_number' => 'FG101FTK21023811', 'status' => 'Barang Keluar', 'status_barang' => null, 'os_version' => '7.2.7', 'lokasi_device' => 'Delivered', 'description' => null],
            ['nama_perangkat' => 'FortiGate-101F', 'serial_number' => 'FG101FTK21029291', 'status' => 'Ready', 'status_barang' => 'Forsale', 'os_version' => null, 'lokasi_device' => 'Gudang ATS', 'description' => null],
            ['nama_perangkat' => 'FortiGate-1800F', 'serial_number' => 'FG180FTK23900832', 'status' => 'Barang RMA', 'status_barang' => 'RMA', 'os_version' => null, 'lokasi_device' => 'Gudang ATS', 'description' => null],
            ['nama_perangkat' => 'FortiGate-200F-NFR', 'serial_number' => 'FG200FT922937371', 'status' => 'Barang Keluar', 'status_barang' => null, 'os_version' => null, 'lokasi_device' => 'Delivered', 'description' => null],
            ['nama_perangkat' => 'FortiGate-400F', 'serial_number' => 'FG4H0FT922901935', 'status' => 'Barang Keluar', 'status_barang' => 'Demo', 'os_version' => '7.2.10', 'lokasi_device' => 'Delivered', 'description' => 'Pickup Expedisi SMI Cibitung'],
            ['nama_perangkat' => 'FortiGate-40F-NFR', 'serial_number' => 'FGT40FTK2309E689', 'status' => 'Barang Keluar', 'status_barang' => 'Demo', 'os_version' => '7.4.8', 'lokasi_device' => 'Delivered', 'description' => 'Pickup SMI Cibitung'],
            ['nama_perangkat' => 'FortiWeb-600F', 'serial_number' => 'FV600FT924000026', 'status' => 'Ready', 'status_barang' => 'Demo', 'os_version' => '7.4.7', 'lokasi_device' => 'Gudang ATS', 'description' => null],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

        // ============================================
        // DUMMY NOTIFICATIONS
        // ============================================
        Notification::create([
            'type' => 'item_created', 'title' => 'Barang Baru Ditambahkan',
            'message' => 'FortiADC-200F (FAD2HFTA19000224) telah ditambahkan ke inventaris.',
            'link' => '/items',
        ]);
        Notification::create([
            'type' => 'transaction_out', 'title' => 'Barang Keluar',
            'message' => 'FortiGate-100F dikirim ke customer.',
            'link' => '/transactions',
        ]);
        Notification::create([
            'type' => 'user_created', 'title' => 'User Baru',
            'message' => 'Akun Operator (user1@gmail.com) telah dibuat.',
            'link' => '/users',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Users
        $superadmin = User::firstOrCreate(
            ['email' => 'roy.kamto@yaksaersadasolusindo.com'],
            [
                'name' => 'Super Admin Roy Kamto',
                'password' => Hash::make('Yaksaersadasolusindo2023$'),
                'role' => 'superadmin',
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@yaksa.com'],
            [
                'name' => 'Admin Gudang',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@yaksa.com'],
            [
                'name' => 'Staff Operasional',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Clear existing tables
        Schema::disableForeignKeyConstraints();
        Transaction::truncate();
        Item::truncate();
        Schema::enableForeignKeyConstraints();

        $excelPath = base_path('List Perangkat Fortinet ATS - YES.xlsx');
        if (!file_exists($excelPath)) {
            $this->command->error('File Excel tidak ditemukan di root project!');
            return;
        }

        try {
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($excelPath);
        } catch (\Exception $e) {
            $this->command->error('Error loading file: ' . $e->getMessage());
            return;
        }

        // --- Process ATS x YES Jkt -> Jakarta ---
        $this->command->info('Seeding Gudang Jakarta...');
        $sheetJkt = $spreadsheet->getSheetByName('ATS x YES Jkt');
        if ($sheetJkt) {
            $highestRow = $sheetJkt->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $nama_perangkat = trim($sheetJkt->getCell('A' . $row)->getValue() ?? '');
                if (empty($nama_perangkat)) continue;

                $serial_number = trim($sheetJkt->getCell('B' . $row)->getValue() ?? '');
                $status = trim($sheetJkt->getCell('C' . $row)->getValue() ?? '');
                $status_barang = trim($sheetJkt->getCell('D' . $row)->getValue() ?? '');
                $masuk_pengirim = trim($sheetJkt->getCell('E' . $row)->getValue() ?? '');
                $keluar_penerima = trim($sheetJkt->getCell('F' . $row)->getValue() ?? '');
                $tgl_menerima = trim($sheetJkt->getCell('G' . $row)->getFormattedValue() ?? '');
                $tgl_pengiriman = trim($sheetJkt->getCell('H' . $row)->getFormattedValue() ?? '');
                $os_version = trim($sheetJkt->getCell('I' . $row)->getValue() ?? '');
                $lokasi_device = trim($sheetJkt->getCell('J' . $row)->getValue() ?? '');
                $description = trim($sheetJkt->getCell('K' . $row)->getValue() ?? '');

                if (empty($status)) $status = 'Ready';

                // Extract category name dynamically
                $categoryName = 'Uncategorized';
                $parts = preg_split('/[-\s]+/', $nama_perangkat);
                if (count($parts) > 0 && !empty($parts[0])) {
                    $categoryName = $parts[0];
                    if (strtolower($categoryName) === 'fortigate') $categoryName = 'FortiGate';
                }

                $category = Category::firstOrCreate([
                    'name' => $categoryName,
                    'gudang' => 'jakarta'
                ]);

                $item = Item::create([
                    'nama_perangkat' => $nama_perangkat,
                    'gudang' => 'jakarta',
                    'serial_number' => $serial_number,
                    'status' => $status,
                    'status_barang' => $status_barang === '-' ? null : $status_barang,
                    'os_version' => $os_version === '-' ? null : $os_version,
                    'lokasi_device' => $lokasi_device === '-' ? null : $lokasi_device,
                    'description' => $description === '-' ? null : $description,
                    'category_id' => $category->id,
                ]);

                // Transactions
                if (!empty($tgl_menerima) && $tgl_menerima !== '-') {
                    try {
                        $dateIn = Carbon::createFromFormat('d/m/Y', $tgl_menerima)->format('Y-m-d');
                    } catch (\Exception $e) {
                        try {
                            $dateIn = Carbon::parse($tgl_menerima)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $dateIn = now(); // fallback
                        }
                    }
                    Transaction::create([
                        'item_id' => $item->id,
                        'user_id' => $admin->id,
                        'tipe_transaksi' => 'in',
                        'tanggal_transaksi' => $dateIn,
                        'pengirim' => $masuk_pengirim === '-' ? null : $masuk_pengirim,
                        'penerima' => 'Warehouse SMI', 
                    ]);
                }

                if (!empty($tgl_pengiriman) && $tgl_pengiriman !== '-') {
                    try {
                        $dateOut = Carbon::createFromFormat('d/m/Y', $tgl_pengiriman)->format('Y-m-d');
                    } catch (\Exception $e) {
                        try {
                            $dateOut = Carbon::parse($tgl_pengiriman)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $dateOut = now(); // fallback
                        }
                    }
                    Transaction::create([
                        'item_id' => $item->id,
                        'user_id' => $admin->id,
                        'tipe_transaksi' => 'out',
                        'tanggal_transaksi' => $dateOut,
                        'pengirim' => 'Warehouse SMI', 
                        'penerima' => $keluar_penerima === '-' ? null : $keluar_penerima,
                    ]);
                }
            }
        }

        // --- Process SFP -> SFP ---
        $this->command->info('Seeding Gudang SFP...');
        $sheetSfp = $spreadsheet->getSheetByName('SFP');
        if ($sheetSfp) {
            $highestRow = $sheetSfp->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                $nama_perangkat = trim($sheetSfp->getCell('A' . $row)->getValue() ?? '');
                if (empty($nama_perangkat)) continue;

                $serial_number = trim($sheetSfp->getCell('B' . $row)->getValue() ?? '');
                $status = trim($sheetSfp->getCell('C' . $row)->getValue() ?? '');
                $description = trim($sheetSfp->getCell('D' . $row)->getValue() ?? '');
                $keterangan = trim($sheetSfp->getCell('E' . $row)->getValue() ?? '');
                $description2 = trim($sheetSfp->getCell('F' . $row)->getValue() ?? '');

                if (empty($status)) $status = 'Ready';

                // Category
                $catName = explode(' ', $nama_perangkat)[0];
                $catName = str_replace(['(', ')'], '', $catName);
                if (empty($catName)) $catName = 'Uncategorized';
                
                $category = Category::firstOrCreate([
                    'name' => $catName,
                    'gudang' => 'sfp'
                ]);

                $descFull = $description;
                if (!empty($description2) && $description2 !== '-') {
                    $descFull .= (empty($descFull) ? '' : ' | ') . $description2;
                }

                $item = Item::create([
                    'nama_perangkat' => $nama_perangkat,
                    'gudang' => 'sfp',
                    'serial_number' => $serial_number,
                    'status' => $status,
                    'status_barang' => $keterangan === '-' ? null : $keterangan,
                    'description' => empty($descFull) ? null : $descFull,
                    'category_id' => $category->id,
                ]);

                if ($status == 'Barang Keluar') {
                    Transaction::create([
                        'item_id' => $item->id,
                        'user_id' => $admin->id,
                        'tipe_transaksi' => 'out',
                        'tanggal_transaksi' => now(),
                        'penerima' => (empty($description2) || $description2 === '-') ? 'Tidak Diketahui' : $description2,
                    ]);
                } else {
                    Transaction::create([
                        'item_id' => $item->id,
                        'user_id' => $admin->id,
                        'tipe_transaksi' => 'in',
                        'tanggal_transaksi' => now(),
                        'penerima' => 'Warehouse SMI',
                    ]);
                }
            }
        }
        
        // --- Process YES Bali is skipped based on user's instruction ---
        $this->command->info('Gudang Bali dikosongkan (skip YES Bali sheet).');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $jabatanList = ['Admin', 'Manager', 'Staff', 'Finance'];

        for ($i = 1; $i <= 5; $i++) {
            Karyawan::create([
                'nama' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'telepon' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'jabatan' => $faker->randomElement($jabatanList),
                'tanggal_masuk' => $faker->date(),
                'salary' => $faker->numberBetween(3000000, 10000000),
            ]);
        }
    }
}

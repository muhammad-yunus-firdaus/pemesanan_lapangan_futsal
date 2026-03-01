<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Field;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    Field::create([
        'name' => 'Lapangan A',
        'description' => 'Lapangan Futsal Semen.',
        'price_per_hour' => 90000,
    ]);
    Field::create([
        'name' => 'Lapangan B',
        'description' => 'Lapangan Futsal Rumput Sintesis.',
        'price_per_hour' => 120000,
    ]);
    Field::create([
        'name' => 'Lapangan C',
        'description' => 'Lapangan Futsal Vinyl.',
        'price_per_hour' => 150000,
    ]);
}
}

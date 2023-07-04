<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFiles = File::files(public_path('provinsi'));

        foreach ($jsonFiles as $file) {
            $json = file_get_contents($file->getPathname());
            $data = json_decode($json, true);

            $provinsi = $data['properties']['provinsi'];

            if ($provinsi) {
                Provinsi::firstOrCreate([
                    'nama' => $provinsi,
                    'geojson' => 'provinsi/'.$file->getFilename(),
                ]);
            }
        }
    }
}

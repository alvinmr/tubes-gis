<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // list all indonesia kabupaten from json
        $jsonFiles = File::files(public_path('kabupaten'));

        foreach ($jsonFiles as $file) {
            $json = file_get_contents($file->getPathname());
            $data = json_decode($json, true);

            $kabupaten = $data['properties']['kabupaten'];

            if ($kabupaten) {
                Kabupaten::firstOrCreate([
                    'nama' => $kabupaten,
                    'provinsi_id' => Provinsi::where('nama', $data['properties']['provinsi'])->first()->id,
                    'geojson' => 'kabupaten/'.$file->getFilename(),
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFiles = File::files(public_path('kecamatan'));

        foreach ($jsonFiles as $file) {
            $json = file_get_contents($file->getPathname());
            $data = json_decode($json, true);

            $kecamatan = $data['properties']['kecamatan'];

            if ($kecamatan) {
                Kecamatan::firstOrCreate([
                    'nama' => $kecamatan,
                    'kabupaten_id' => Kabupaten::where('nama', $data['properties']['kabupaten'])->first()->id,
                    'geojson' => 'kecamatan/'.$file->getFilename(),
                ]);
            }
        }
    }
}

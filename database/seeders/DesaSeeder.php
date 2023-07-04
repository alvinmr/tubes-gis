<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFiles = File::files(public_path('desa'));

        foreach ($jsonFiles as $file) {
            $json = file_get_contents($file->getPathname());
            $data = json_decode($json, true);

            $desa = $data['properties']['desa'];

            if ($desa) {
                Desa::firstOrCreate([
                    'nama' => $desa,
                    'penduduk' => rand(1000, 10000),
                    'call_center' => '08' . rand(100000000, 999999999),
                    'kecamatan_id' => Kecamatan::where('nama', $data['properties']['kecamatan'])->first()->id,
                    'geojson' => 'desa/'.$file->getFilename(),
                ]);
            }
        }
    }
}

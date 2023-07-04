<?php

namespace Database\Seeders;

use App\Models\KasusCovid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KasusKovidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // list all desa and give random kasus
        $desas = \App\Models\Desa::all();
        KasusCovid::truncate();

        foreach ($desas as $desa) {
            $geojson = json_decode(file_get_contents(public_path($desa->geojson)), true);

            // Get the polygon coordinates
            $coordinates = $geojson['geometry']['coordinates'];

            // Generate a random coordinate within the polygon
            $randomCoordinate = $this->generateRandomCoordinate($coordinates);

            \App\Models\KasusCovid::firstOrCreate([
                'desa_id' => $desa->id,
                'suspek' => rand(0, 100),
                'dirawat' => rand(0, 100),
                'sembuh' => rand(0, 100),
                'meninggal' => rand(0, 100),
                'coordinate' => $randomCoordinate, // Convert array to JSON string
                'keterangan' => 'Kasus Covid-19 di desa ' . $desa->nama,
            ]);
        }
    }

    /**
     * Generate a random coordinate within the given polygon coordinates.
     *
     * @param array $coordinates
     * @return array
     */
    private function generateRandomCoordinate(array $coordinates): array
    {
        // Helper function to flatten the GeoJSON coordinates
        $flattenCoordinates = function ($coordinates) use (&$flattenCoordinates) {
            $result = [];
            foreach ($coordinates as $coord) {
                if (is_array($coord[0])) {
                    $result = array_merge($result, $flattenCoordinates($coord));
                } else {
                    $result[] = $coord;
                }
            }
            return $result;
        };

        // Assuming the polygon is the first element in the coordinates array
        $polygon = $flattenCoordinates($coordinates);

        // Generate random point within the polygon bounds
        $minLongitude = $maxLongitude = $polygon[0][0];
        $minLatitude = $maxLatitude = $polygon[0][1];

        foreach ($polygon as $point) {
            $minLongitude = min($minLongitude, $point[0]);
            $maxLongitude = max($maxLongitude, $point[0]);
            $minLatitude = min($minLatitude, $point[1]);
            $maxLatitude = max($maxLatitude, $point[1]);
        }

        $randomLongitude = mt_rand($minLongitude * 1000000, $maxLongitude * 1000000) / 1000000;
        $randomLatitude = mt_rand($minLatitude * 1000000, $maxLatitude * 1000000) / 1000000;

        return [$randomLatitude, $randomLongitude];
    }
}

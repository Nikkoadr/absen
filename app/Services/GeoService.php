<?php

namespace App\Services;

class GeoService
{
    public function jarakMeter(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $theta = $lon1 - $lon2;
        $miles = sin(deg2rad($lat1)) * sin(deg2rad($lat2))
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $miles = max(-1, min(1, $miles));
        $miles = acos($miles);
        $miles = rad2deg($miles) * 60 * 1.1515;

        return $miles * 1.609344 * 1000;
    }
}

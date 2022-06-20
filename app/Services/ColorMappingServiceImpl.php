<?php 

namespace App\Services;

use App\Models\Color;

class ColorMappingServiceImpl implements ColorMappingServiceInterface {
    public function getColorId(string $colorName = '') {
        $colorName = strtolower($colorName);
        return Color::where('name', '=', ucfirst($colorName))->first();
    }
}
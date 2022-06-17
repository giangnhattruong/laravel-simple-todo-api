<?php 

namespace App\Services;

use App\Models\Color;

class ColorMappingServiceImpl implements ColorMappingServiceInterface {
    public function getColorId(string $colorName = '') {
        return Color::where('name', 'ilike', $colorName)->first();
    }
}
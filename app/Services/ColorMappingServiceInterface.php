<?php 

namespace App\Services;

interface ColorMappingServiceInterface {
    public function getColorId(string $colorName);
}
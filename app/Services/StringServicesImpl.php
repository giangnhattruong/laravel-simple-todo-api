<?php 

namespace App\Services;

use App\Models\Color;
use InvalidArgumentException;

class StringServicesImpl implements StringServicesInterface {
    public function toArrayOfNumber($numberArrayInString = '') {
        $arrayResult = explode(",", str_replace(['[', ']'], '', $numberArrayInString));

        foreach ($arrayResult as $item) {
            if (!is_numeric($item)) {
                return [];
            }
        }

        return $arrayResult;
    }

    public function toArrayOfColorId($colorArrayInString = '') {
        $colorMappingService = app()->make(ColorMappingServiceInterface::class);
        $arrayResult = explode(",", str_replace(['[', ']'], '', $colorArrayInString));
        $colorIds = [];

        foreach ($arrayResult as $item) {
            $color_id = $colorMappingService->getColorId($item);

            if ($color_id !== null) {
                array_push($colorIds, $color_id);
            }
        }

        return array_map(fn($color) => $color->id, $colorIds);
    }
}
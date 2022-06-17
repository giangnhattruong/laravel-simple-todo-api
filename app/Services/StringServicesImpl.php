<?php 

namespace App\Services;

use InvalidArgumentException;

class StringServicesImpl implements StringServicesInterface {
    public function toArray($arrayString = '') {
        $arrayResult = explode(",", str_replace(['[', ']'], '', $arrayString));

        foreach ($arrayResult as $item) {
            if (!is_numeric($item)) {
                return [];
            }
        }

        return $arrayResult;
    }
}
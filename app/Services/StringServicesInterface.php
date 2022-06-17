<?php 

namespace App\Services;

interface StringServicesInterface {
    public function toArrayOfNumber(string $arrayString);
    public function toArrayOfColorId($colorArrayInString = '');
}
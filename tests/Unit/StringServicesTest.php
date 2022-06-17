<?php

namespace Tests\Unit;

use App\Services\StringServicesInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class StringServicesTest extends TestCase
{
    use RefreshDatabase;

    private $stringServices;

    public function setUp() : void {
        parent::setUp();
        $this->stringServices = $this->app->make(StringServicesInterface::class);
    }

    public function test_convert_string_to_array_of_number() {
        $testStr = '[1,2,3]';
        $result = $this->stringServices->toArrayOfNumber($testStr);
        $this->assertTrue(in_array(1, $result));
        $this->assertTrue(in_array(2, $result));
        $this->assertTrue(in_array(3, $result));
    }

    public function test_convert_string_to_array_of_number_then_return_empty_array_if_invalid_input() {
        $testStr = '[sad,asdsad]';
        $result = $this->stringServices->toArrayOfNumber($testStr);
        $this->assertSame([], $result, "Return value is not an empty array as expected.");
    }

    public function test_convert_string_to_array_of_number_then_return_empty_array_if_input_is_null() {
        $result = $this->stringServices->toArrayOfNumber(null);
        $this->assertSame([], $result, "Return value is not an empty array as expected.");
    }
}

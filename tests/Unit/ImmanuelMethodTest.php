<?php

use Illuminate\Support\Facades\Http;
use Sunlight\Immanuel\Tests\TestCase;
use Sunlight\Immanuel\Facades\Immanuel;

class ImmanuelMethodTest extends TestCase
{
    public function testCreate()
    {
        $immanuel = Immanuel::create($this->options);
        $this->assertEquals($immanuel->latitude, $this->options['latitude']);
    }

    public function testGetSet()
    {
        $immanuel = Immanuel::create($this->options);
        $immanuel->latitude = '123456';
        $this->assertEquals($immanuel->latitude, '123456');
    }

    public function testNatalChart()
    {
        $response = Immanuel::natalChart($this->options)->response();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal' &&
                   $request['latitude'] == $this->options['latitude'] &&
                   $request['longitude'] == $this->options['longitude'] &&
                   $request['birth_date'] == $this->options['birth_date'] &&
                   $request['birth_time'] == $this->options['birth_time'] &&
                   $request['house_system'] == $this->options['house_system'];
        });
    }

    public function testSolarChart()
    {
        $response = Immanuel::solarReturnChart($this->options)->response();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/solar' &&
                   $request['latitude'] == $this->options['latitude'] &&
                   $request['longitude'] == $this->options['longitude'] &&
                   $request['birth_date'] == $this->options['birth_date'] &&
                   $request['birth_time'] == $this->options['birth_time'] &&
                   $request['house_system'] == $this->options['house_system'] &&
                   $request['solar_return_year'] == $this->options['solar_return_year'];
        });
    }

    public function testProgressedChart()
    {
        $response = Immanuel::progressedChart($this->options)->response();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/progressed' &&
                   $request['latitude'] == $this->options['latitude'] &&
                   $request['longitude'] == $this->options['longitude'] &&
                   $request['birth_date'] == $this->options['birth_date'] &&
                   $request['birth_time'] == $this->options['birth_time'] &&
                   $request['house_system'] == $this->options['house_system'] &&
                   $request['progression_date'] == $this->options['progression_date'];
        });
    }
}
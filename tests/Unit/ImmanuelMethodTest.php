<?php

use Illuminate\Support\Facades\Http;
use Sunlight\Immanuel\Tests\TestCase;
use Sunlight\Immanuel\Facades\Immanuel;

class ImmanuelMethodTest extends TestCase
{
    public function testNatalChart()
    {
        Immanuel::natalChart($this->options);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal' &&
                   $this->checkRequestAgainstBasicOptions($request);
        });
    }

    public function testSolarChart()
    {
        Immanuel::solarReturnChart($this->options);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/solar' &&
                   $this->checkRequestAgainstBasicOptions($request) &&
                   $request['solar_return_year'] == $this->options['solar_return_year'];
        });
    }

    public function testProgressedChart()
    {
        Immanuel::progressedChart($this->options);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/progressed' &&
                   $this->checkRequestAgainstBasicOptions($request) &&
                   $request['progression_date'] == $this->options['progression_date'];
        });
    }

    public function testCreate()
    {
        Immanuel::create($this->options)->natalChart();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal' &&
                   $this->checkRequestAgainstBasicOptions($request);
        });
    }

    public function testGet()
    {
        $immanuel = Immanuel::create($this->options);
        $this->assertEquals($immanuel->latitude, $this->options['latitude']);
    }

    public function testSet()
    {
        $immanuel = Immanuel::create($this->options);
        $immanuel->latitude = '123456';
        $this->assertEquals($immanuel->latitude, '123456');
    }
}
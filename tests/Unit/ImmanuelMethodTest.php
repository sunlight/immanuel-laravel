<?php

/**
 * Testing in this file is a bit half-assed since we only rally need
 * to test the correct data is being sent off, and that some of the
 * exceptions are thrown when expected. Most of the chaining logic is
 * taken from immanuel-chart and tests of the actual data returned are
 * included in immanuel-api.
 */

use Illuminate\Support\Facades\Http;
use RiftLab\Immanuel\Tests\TestCase;
use RiftLab\Immanuel\Facades\Immanuel;

class ImmanuelMethodTest extends TestCase
{
    public function testNatalChart()
    {
        Immanuel::create($this->options)->addNatalChart()->get();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal' &&
                   $this->checkRequestAgainstBasicOptions($request);
        });
    }

    public function testSolarChart()
    {
        Immanuel::create($this->options)->addSolarReturnChart()->get();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/solar' &&
                   $this->checkRequestAgainstBasicOptions($request) &&
                   $request['solar_return_year'] == $this->options['solar_return_year'];
        });
    }

    public function testProgressedChart()
    {
        Immanuel::create($this->options)->addProgressedChart()->get();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/progressed' &&
                   $this->checkRequestAgainstBasicOptions($request) &&
                   $request['progression_date'] == $this->options['progression_date'];
        });
    }

    public function testNatalChartAndTransits()
    {
        Immanuel::create($this->options)->addNatalChart()->addTransits()->get();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal/transits' &&
                   $this->checkRequestAgainstBasicOptions($request) &&
                   $request['transit_date'] === $this->options['transit_date'];
        });
    }

    public function testNatalChartAspectsToTransits()
    {
        Immanuel::create($this->options)->addNatalChart()->addTransits()->aspectsToTransits()->get();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal/transits' &&
                   $this->checkRequestAgainstBasicOptions($request) &&
                   $request['aspects'] === 'transits';
        });
    }

    public function testSynastryWithNoPrimaryChartException()
    {
        $this->expectException(\Exception::class);
        $chartData = Immanuel::create($this->options)->addSynastryChart();
    }

    public function testGetter()
    {
        $immanuel = Immanuel::create($this->options);
        $this->assertEquals($immanuel->latitude, $this->options['latitude']);
    }

    public function testSetter()
    {
        $immanuel = Immanuel::create($this->options);
        $immanuel->latitude = '123456';
        $this->assertEquals($immanuel->latitude, '123456');
    }
}
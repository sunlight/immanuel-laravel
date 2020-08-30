<?php

use Illuminate\Support\Facades\Http;
use Sunlight\Immanuel\Tests\TestCase;

class ImmanuelInstantiationTest extends TestCase
{
    public function testTraditionalInstantiation()
    {
        $immanuel = new Sunlight\Immanuel\Immanuel();
        $immanuel->create($this->options)->natalChart();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal' &&
                   $this->checkRequestAgainstBasicOptions($request);
        });
    }

    public function testTraditionalInstantiationWithOptionProperties()
    {
        $immanuel = new Sunlight\Immanuel\Immanuel();

        $immanuel->latitude = $this->options['latitude'];
        $immanuel->longitude = $this->options['longitude'];
        $immanuel->birth_date = $this->options['birth_date'];
        $immanuel->birth_time = $this->options['birth_time'];
        $immanuel->house_system = $this->options['house_system'];

        $immanuel->natalChart();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal' &&
                   $this->checkRequestAgainstBasicOptions($request);
        });
    }

    public function testMethodOptionsDoNotAffectOptionsProperty()
    {
        $immanuel = Sunlight\Immanuel\Facades\Immanuel::create($this->options);
        $immanuel->latitude = '123456';
        $immanuel->natalChart($this->options);

        Http::assertSent(function ($request) {
            return $request['latitude'] == $this->options['latitude'];
        });

        $this->assertEquals($immanuel->latitude, '123456');
    }
}
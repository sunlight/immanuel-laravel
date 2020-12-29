<?php

use Illuminate\Support\Facades\Http;
use RiftLab\Immanuel\Tests\TestCase;

class ImmanuelInstantiationTest extends TestCase
{
    public function testTraditionalInstantiation()
    {
        $immanuel = new RiftLab\Immanuel\Immanuel();
        $immanuel->create($this->options)->addNatalChart()->get();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal' &&
                   $this->checkRequestAgainstBasicOptions($request);
        });
    }

    public function testTraditionalInstantiationWithOptionProperties()
    {
        $immanuel = new RiftLab\Immanuel\Immanuel();

        $immanuel->latitude = $this->options['latitude'];
        $immanuel->longitude = $this->options['longitude'];
        $immanuel->birth_date = $this->options['birth_date'];
        $immanuel->birth_time = $this->options['birth_time'];
        $immanuel->house_system = $this->options['house_system'];

        $immanuel->addNatalChart()->get();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization') &&
                   $request->url() == config('immanuel.api_url').'/chart/natal' &&
                   $this->checkRequestAgainstBasicOptions($request);
        });
    }
}
<?php

namespace RiftLab\Immanuel\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use RiftLab\Immanuel\ImmanuelServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $options = [
        'latitude' => '38.5616505',
        'longitude' => '-121.5829968',
        'birth_date' => '2000-10-30',
        'birth_time' => '05:00',
        'house_system' => 'Polich Page',
        'solar_return_year' => '2025',
        'progression_date' => '2020-07-01',
        'synastry_date' => '2001-02-16',
        'synastry_time' => '06:00',
        'synastry_latitude' => '38.5616505',
        'synastry_longitude' => '-121.5829968',
        'transit_date' => '2021-07-01',
        'transit_time' => '13:00',
    ];

    public function setUp(): void
    {
        parent::setUp();
        Http::fake();
    }

    protected function getPackageProviders($app)
    {
        return [
            ImmanuelServiceProvider::class,
        ];
    }

    protected function checkRequestAgainstBasicOptions(Request $request)
    {
        return $request['latitude'] == $this->options['latitude'] &&
               $request['longitude'] == $this->options['longitude'] &&
               $request['birth_date'] == $this->options['birth_date'] &&
               $request['birth_time'] == $this->options['birth_time'] &&
               $request['house_system'] == $this->options['house_system'];
    }
}
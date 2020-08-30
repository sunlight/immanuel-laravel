<?php

namespace Sunlight\Immanuel\Tests;

use Illuminate\Support\Facades\Http;
use Sunlight\Immanuel\ImmanuelServiceProvider;

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
}
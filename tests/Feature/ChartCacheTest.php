<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RiftLab\Immanuel\Tests\TestCase;
use RiftLab\Immanuel\Facades\Immanuel;

class ChartCacheTest extends TestCase
{
    public function testRequestGetsCached()
    {
        Cache::spy();
        Immanuel::create($this->options)->addNatalChart()->get();
        Cache::shouldHaveReceived('has')->once();
        Cache::shouldHaveReceived('put')->once();
    }
}
<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RiftLab\Immanuel\Tests\TestCase;

class ChartCacheTest extends TestCase
{
    public function testRequestGetsCached()
    {
        Cache::spy();

        $immanuel = new RiftLab\Immanuel\Immanuel();
        $immanuel->create($this->options)->addNatalChart()->get();

        Cache::shouldHaveReceived('has')->once();
        Cache::shouldHaveReceived('put')->once();

        $this->assertFalse($immanuel->cached());
        $this->assertNotNull($immanuel->response());
    }

    public function testRequestDoesNotGetsCached()
    {
        Cache::spy();

        Config::set('immanuel.cache', 0);

        $immanuel = new RiftLab\Immanuel\Immanuel();
        $immanuel->create($this->options)->addNatalChart()->get();

        Cache::shouldNotHaveReceived('has');
        Cache::shouldNotHaveReceived('put');

        $this->assertFalse($immanuel->cached());
        $this->assertNotNull($immanuel->response());
    }

    public function testRequestGetsCachedForever()
    {
        Cache::spy();

        Config::set('immanuel.cache_lifetime', 0);

        $immanuel = new RiftLab\Immanuel\Immanuel();
        $immanuel->create($this->options)->addNatalChart()->get();

        Cache::shouldHaveReceived('has')->once();
        Cache::shouldHaveReceived('forever')->once();

        $this->assertFalse($immanuel->cached());
        $this->assertNotNull($immanuel->response());
    }
}
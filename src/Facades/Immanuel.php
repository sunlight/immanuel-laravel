<?php

namespace RiftLab\Immanuel\Facades;

use Illuminate\Support\Facades\Facade;

class Immanuel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'immanuel';
    }
}

<?php

namespace Sunlight\Immanuel;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Immanuel
{
    protected $apiKey;
    protected $apiSecret;
    protected $apiUrl;
    protected $options;
    protected $chartData;
    protected $response;

    /**
     * Set API details on instantiation.
     *
     */
    public function __construct()
    {
        $this->apiKey = config('immanuel.api_key');
        $this->apiSecret = config('immanuel.api_secret');
        $this->apiUrl = config('immanuel.api_url');

        $this->options = [
            'latitude'=> null,
            'longitude'=> null,
            'birth_date'=> null,
            'birth_time'=> null,
            'house_system'=> null,
            'solar_return_year'=> null,
            'progression_date'=> null,
        ];
    }

    /**
     * Basic getter for options.
     *
     */
    public function __get($key)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }

        return null;
    }

    /**
     * Basic setter for options.
     *
     */
    public function __set($key, $value) : void
    {
        if (Arr::has($this->options, $key)) {
            $this->options[$key] = $value;
        }
    }

    /**
     * Create() method for setting all options at once.
     *
     */
    public function create(array $options)
    {
        $options = Arr::only($options, array_keys($this->options));
        $this->options = array_replace($this->options, $options);
        return $this;
    }

    /**
     * Methods for various supported chart types.
     *
     */
    public function natalChart($options = null)
    {
        $this->getChart($options, 'natal');
        return $this;
    }

    public function solarReturnChart($options = null)
    {
        $this->getChart($options, 'solar');
        return $this;
    }

    public function progressedChart($options = null)
    {
        $this->getChart($options, 'progressed');
        return $this;
    }

    /**
     * Return chart data.
     *
     */
    public function get()
    {
        return $this->chartData;
    }

    /**
     * Preserve Laravel's HTTP Response object.
     *
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Here's where the API magic happens via Laravel's Http facade.
     * Upon success, the API's JSON response is stored as a standard Laravel collection.
     *
     */
    protected function getChart($options, $type)
    {
        $options = $options ?? $this->options;

        if (empty($options)) {
            return;
        }

        $endpointUrl = Str::of($this->apiUrl)->finish('/').'chart/'.$type;
        $this->response = Http::withBasicAuth($this->apiKey, $this->apiSecret)->post($endpointUrl, $options);
        $this->chartData = $this->response->ok() ? collect($this->response->json()) : null;
    }
}
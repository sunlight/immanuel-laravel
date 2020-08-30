<?php

namespace Sunlight\Immanuel;

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
        if (isset($this->options[$key])) {
            $this->options[$key] = $value;
        }
    }

    /**
     * Create() method for setting all options at once.
     *
     */
    public function create(array $options)
    {
        $this->options = array_replace([
            'latitude' => '',
            'longitude' => '',
            'birth_date' => '',
            'birth_time' => '',
            'house_system' => '',
            'solar_return_year' => '',
            'progression_date' => '',
        ], $options);

        return $this;
    }

    /**
     * Methods for various supported chart types.
     *
     */
    public function natalChart()
    {
        $this->getChart('natal');
        return $this;
    }

    public function solarReturnChart()
    {
        $this->getChart('solar');
        return $this;
    }

    public function progressedChart()
    {
        $this->getChart('progressed');
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
    protected function getChart($type)
    {
        $endpointUrl = Str::of($this->apiUrl)->finish('/').'chart/'.$type;
        $this->response = Http::withBasicAuth($this->apiKey, $this->apiSecret)->post($endpointUrl, $this->options);
        $this->chartData = $this->response->ok() ? collect($this->response->json()) : null;
    }
}
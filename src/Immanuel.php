<?php

namespace RiftLab\Immanuel;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Immanuel
{
    protected $apiUrl;
    protected $apiToken;
    protected $chartMethods;
    protected $options;
    protected $arguments;
    protected $chartData;
    protected $response;

    /**
     * Set API details on instantiation.
     *
     */
    public function __construct()
    {
        $this->apiUrl = config('immanuel.api_url');
        $this->apiToken = config('immanuel.api_token');
        $this->chartMethods = [];
        $this->arguments = [];

        $this->options = [
            'latitude'=> null,
            'longitude'=> null,
            'birth_date'=> null,
            'birth_time'=> null,
            'house_system'=> null,
            'solar_return_year'=> null,
            'progression_date'=> null,
            'synastry_date' => null,
            'synastry_time' => null,
            'synastry_latitude' => null,
            'synastry_longitude' => null,
            'solar_return_latitude' => null,
            'solar_return_longitude' => null,
            'progression_latitude' => null,
            'progression_longitude' => null,
            'transit_latitude' => null,
            'transit_longitude' => null,
            'transit_date' => null,
            'transit_time' => null,
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
     * Add relevant data to arguments for a natal chart.
     *
     */
    public function addNatalChart()
    {
        $this->addChart('natal');
        return $this;
    }

    /**
     * Add relevant data to arguments for a solar return chart.
     *
     */
    public function addSolarReturnChart()
    {
        $this->addChart('solar');
        return $this;
    }

    /**
     * Add relevant data to arguments for a progressed chart.
     *
     */
    public function addProgressedChart()
    {
        $this->addChart('progressed');
        return $this;
    }

    /**
     * Add relevant data to arguments for a synastry chart.
     * This will always be a secondary chart.
     *
     */
    public function addSynastryChart()
    {
        $this->addChart('synastry');
        return $this;
    }

    /**
     * Add relevant data to arguments to append a transit chart.
     *
     */
    public function addTransits()
    {
        $this->addChart('transits');
        return $this;
    }

    /**
     * Main chart aspects to solar return chart.
     *
     */
    public function aspectsToSolarReturn()
    {
        if (!in_array('solar', $this->chartMethods)) {
            throw new \Exception('No solar return chart to aspect to.');
        }

        $this->arguments['aspects'] = 'secondary';
        return $this;
    }

    /**
     * Main chart aspects to progressed chart.
     *
     */
    public function aspectsToProgressed()
    {
        if (!in_array('progressed', $this->chartMethods)) {
            throw new \Exception('No progressed chart to aspect to.');
        }

        $this->arguments['aspects'] = 'secondary';
        return $this;
    }

    /**
     * Main chart aspects to synastry chart.
     *
     */
    public function aspectsToSynastry()
    {
        if ($this->chartMethods['secondary_type'] !== 'synastry') {
            throw new \Exception('No synastry chart to aspect to.');
        }

        $this->arguments['aspects'] = 'secondary';
        return $this;
    }

    /**
     * Main chart aspects to transit chart.
     *
     */
    public function aspectsToTransits()
    {
        if (!in_array('transits', $this->chartMethods)) {
            throw new \Exception('No transits to aspect to.');
        }

        $this->arguments['aspects'] = 'transits';
        return $this;
    }

    /**
     * Send off arguents & options.
     *
     */
    public function get($forcePrimaryOnSingleChart = false)
    {
        if (empty($this->options)) {
            throw new \Exception('No base chart options specified.');
        }

        if (empty($this->chartMethods)) {
            throw new \Exception('No chart type(s) specified.');
        }

        if ($forcePrimaryOnSingleChart) {
            $this->arguments['force_primary_chart_key'] = 'true';
        }

        $this->getChartData($this->options + $this->arguments);
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
     * Add a chart to the arguments - either as a primary if one doesn't exist,
     * or as a secondary if a primary does already exist.
     *
     */
    protected function addChart(string $type)
    {
        if (isset($this->chartMethods['secondary_type'])) {
            throw new \Exception('Only two non-transit charts may be returned.');
        }
        elseif (!isset($this->chartMethods['type'])) {
            if ($type === 'synastry' || $type === 'transits') {
                throw new \Exception('No primary chart defined.');
            }
            $this->chartMethods['type'] = $type;
        }
        else {
            $this->chartMethods['secondary_type'] = $type;
        }
    }

    /**
     * Here's where the API magic happens via Laravel's Http facade.
     * Upon success, the API's JSON response is stored as a standard Laravel collection.
     *
     */
    protected function getChartData(array $postData)
    {
        // Get URL & data
        $postData = array_filter($postData);
        $endpointUrl = Str::of($this->apiUrl)->finish('/').'chart/'.implode('/', $this->chartMethods);

        // Generate cache key unique to URL & data
        ksort($postData);
        $cacheKey = base64_encode(json_encode($postData).$endpointUrl);

        // If it exists already, return it
        if (Cache::has($cacheKey)) {
            $this->response = null;
            $this->chartData = Cache::get($cacheKey);
        }
        // Otherwise, store it
        else {
            $this->response = Http::withToken($this->apiToken)->post($endpointUrl, $postData);
            $this->chartData = $this->response->ok() ? collect($this->response->json()) : null;
            Cache::put($cacheKey, $this->chartData, config('immanuel.cache_lifetime'));
        }
    }
}
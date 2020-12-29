# Immanuel

[Immanuel](https://immanuel.app/) is an astrology API that allows signed-up users to make requests for natal, solar return, and progressed chart data. This package provides a Laravel facade to wrap requests into simple methods that are easy to use in any Laravel application.

## Installation

```bash
composer require theriftlab/immanuel-laravel
```

## Config

By default, the Immanuel package reads the following keys from your `.env` file:

* `API_KEY`: the API key you received when signing up at https://immanuel.app/
* `API_SECRET`: the API secret you received.
* `API_URL`: the URL of the API, defaulting to `https://api.immanuel.app` - you should only need to change this if you host your own version of the project.

If you need to store any extra data related to this package, or store any of the above details directly in your application, you can publish the config file using the following:

```bash
php artisan vendor:publish --provider="RiftLab\Immanuel\ImmanuelServiceProvider" --tag="config"
```

This will give you the `config/immanuel.php` file with the above three settings added in by default.

## Usage

Check the [Immanuel API](https://github.com/theriftlab/immanuel-api/) README for more detailed usage, limitations, and a specific list of all available options that are POSTed to the API methods. Apart from `get()` and `response()`, the following methods all return `$this` to allow chaining.

* `create()` takes an array of birth data, and any any other required or optional data for the following methods.
* `addNatalChart()` adds a natal chart to the list of returned charts.
* `addSolarReturnChart()` adds a solar return chart to the list of returned charts.
* `addProgressedChart()` adds a progressed chart to the list of returned charts.
* `addSynastryChart()` adds a synastry chart to the list of returned charts.
* `addTransits()` adds a transit chart to the list of returned charts.
* `aspectsToSolarReturn()` will ensure the first chart's aspects point to the second chart's planets, if the second chart is a solar return.
* `aspectsToProgressed()` will ensure the first chart's aspects point to the second chart's planets, if the second chart is a progressed chart.
* `aspectsToSynastry()` will ensure the first chart's aspects point to the second chart's planets, if the second chart is a synastry chart.
* `aspectsToTransits()` will ensure the first chart's aspects point to the transit chart's planets, if transits were requested.
* `get()` can be called after the above methods to query the API and return a standard Laravel collection containing the requested chart data.
* `response()` can be called after `get()` to return Laravel's Http `Response` object.

### Example

```php
use RiftLab\Immanuel\Facades\Immanuel;


// Basic natal chart
$birthData = [
    'latitude' => '38.5616505',
    'longitude' => '-121.5829968',
    'birth_date' => '2000-10-30',
    'birth_time' => '05:00',
    'house_system' => 'Polich Page',
];

$chartData = Immanuel::create($birthData)->addNatalChart()->get();
dd($chartData);

// Check response
$transitChart = Immanuel::create($birthData)->addNatalChart()->addTransits()->aspectsToTransits();
$chartData = $transitChart->get();
dd($transitChart->response());
```
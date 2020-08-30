# Immanuel

[Immanuel](https://immanuel.app/) is an astrology API that allows signed-up users to make requests for natal, solar return, and progressed chart data. This package provides a Laravel facade to wrap requests into simple methods that are easy to use in any Laravel application.

## Installation

```bash
composer require sunlight/immanuel-laravel
```

## Config

By default, the Immanuel package reads the following keys from your `.env` file:

* `API_KEY`: the API key you received when signing up at https://immanuel.app/
* `API_SECRET`: the API secret you received.
* `API_URL`: the URL of the API, defaulting to `https://api.immanuel.app` - you should only need to change this if you host your own version of the project.

If you need to store any extra data related to this package, or store any of the above details directly in your application, you can publish the config file using the following:

```bash
php artisan vendor:publish --provider="Sunlight\Immanuel\ImmanuelServiceProvider" --tag="config"
```

This will give you the `config/immanuel.php` file with the above three settings added in by default.

## Usage

* `create()` takes an array of birth data - date, time, location, house system, and optionally a solar return year or a progression date - and returns an instance of itself.
* `natalChart()` requests natal chart data, either based on the array passed to `create()` or taking an identical array as an argument. The array passed directly to this method will not be stored in the object itself.
* `solarChart()` requests solar return chart data based on the array passed to `create()` or to itself, as above.
* `progressedChart()` requests progression chart data based on the array passed to `create()` or to itself, as above.
* `get()` can be called after one of the chart methods to return a standard Laravel collection containing data for planets and points, including placements and aspects.
* `response()` can be called after one of the chart methods to return Laravel's Http `Response` object.

### Example

```php
use Sunlight\Immanuel\Facades\Immanuel;

...

// Basic natal chart
$birthData = [
    'latitude' => '38.5616505',
    'longitude' => '-121.5829968',
    'birth_date' => '2000-10-30',
    'birth_time' => '05:00',
    'house_system' => 'Polich Page',
];

$natalChart = Immanuel::create($birthData)->natalChart();

if ($natalChart->response()->Ok()) {
    // Options passed to create() get stored in the object
    echo 'Your birth date is ' . $natalChart->birth_date;   // '2000-10-30'
    dd($natalChart->get());
}

// Or...

$natalChart = Immanuel::natalChart($birthData);

if ($natalChart->response()->Ok()) {
    // Options passed directly to chart methods won't get stored
    echo 'Your birth date is ' . $natalChart->birth_date;   // null
    dd($natalChart->get());
}
```
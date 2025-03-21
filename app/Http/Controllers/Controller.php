<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
  protected $urlSearch = 'https://geocoding-api.open-meteo.com/v1/search?';

  protected $urlSearchTime = 'https://api.open-meteo.com/v1/forecast?';
}

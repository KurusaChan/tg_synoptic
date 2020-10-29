<?php

namespace App\Services\WeatherService;
/**
 * Class responsible for making requests to OpenWeatherMap API
 */
class OwmApiService {

    const API_URL = 'https://api.openweathermap.org/data/2.5/';

    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzle;

    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
    }

    public function call(string $method, array $params): array
    {
        $response = $this->guzzle->get($this->getApiUrl(strtolower($method)), [
            'query' => $this->getParams($params),
        ]);
        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    protected function getApiUrl(string $method): string
    {
        return self::API_URL . $method;
    }

    protected function getParams(array $params): array
    {
        return array_merge([
            'APPID' => '93d42ddaf258b216da34c067bd913310', 'units' => 'metric',
        ], $params);
    }
}

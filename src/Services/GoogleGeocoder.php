<?php

namespace Ivacuum\Generic\Services;

use GuzzleHttp\Client;

class GoogleGeocoder
{
    const ENDPOINT = 'https://maps.googleapis.com/maps/api/geocode/json';

    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => static::ENDPOINT]);
    }

    public function geocode(string $address): array
    {
        return $this->query($address);
    }

    public function reverse(string $lat, string $lon): array
    {
        // Можно добавить преобразование , в .
        return $this->query("{$lat} {$lon}");
    }

    protected function query(string $query): array
    {
        $response = $this->client->get('', [
            'query' => [
                'address' => $query,
            ],
        ]);

        $json = json_decode($response->getBody());

        if (!isset($json->results) || !count($json->results) || 'OK' !== $json->status) {
            throw new \Exception('Запрос геоданных не удался');
        }

        $result = [];

        foreach ($json->results as $item) {
            $result[] = [
                'lat' => $item->geometry->location->lat,
                'lon' => $item->geometry->location->lng,
                'address' => $item->formatted_address,
                'lower_corner_lat' => $item->geometry->bounds->southwest->lat,
                'lower_corner_lon' => $item->geometry->bounds->southwest->lng,
                'upper_corner_lat' => $item->geometry->bounds->northeast->lat,
                'upper_corner_lon' => $item->geometry->bounds->northeast->lng,
            ];
        }

        return $result;
    }
}

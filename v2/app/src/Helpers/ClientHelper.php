<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use JsonException;

class ClientHelper
{
    private $request_options = [];

    public function __construct(array $options = [])
    {
        $this->request_options = $options;
    }

    public function invokeUri(string $resourceUri)
    {
        $client = new Client();
        $data = $client->get($resourceUri, $this->getOptions());
        $decoded_data = json_decode($data->getBody(), true);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $decoded_data;
        } else {
            throw new JsonException(json_last_error_msg());
        }
    }

    public function setOptions(array $options)
    {
        $this->request_options = $options;
    }

    public function getOptions()
    {
        return $this->request_options;
    }
}

<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

class DistanceController extends BaseController
{
    private string $invalid_body_msg = "Body must have the following fields: x1, x2, y1, and y2. Fields z1 and z2 are optional.";
    private string $not_numeric_msg = "Each value must be a number or a numeric string.";
    private array $mandatory_fields = ['x1', 'x2', 'y1', 'y2'];

    public function handleCalculateDistance(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        $this->validateBody($request, $body);

        // convert each value to a number if it's passed as a string ex: "32.2" -> 32.2
        foreach ($body as $key => $value) {
            if (in_array($key, $this->mandatory_fields) || $key !== 'z1' || $key !== 'z2') {
                $body[$key] = floatval($value);
            }
        }

        // distance
        $delta_x = pow($body['x2'] - $body['x1'], 2);
        $delta_y = pow($body['y2'] - $body['y1'], 2);

        $distance = sqrt($delta_x + $delta_y);

        // midpoint
        $xm = ($body['x1'] + $body['x2']) / 2;
        $ym = ($body['y1'] + $body['y2']) / 2;

        $midpoint = [
            "x" => $xm,
            "y" => $ym
        ];

        // 3D
        if (isset($body['z1'])) {
            // distance
            $delta_z = pow($body['z2'] - $body['z1'], 2);
            $distance = sqrt($delta_x + $delta_y + $delta_z);

            // midpoint
            $zm = ($body['z1'] + $body['z2']) / 2;
            $midpoint["z"] = $zm;
        }

        return $this->renderJson(
            $response,
            [
                "distance" => round($distance, 2),
                "midpoint" => $midpoint
            ]
        );
    }

    private function validateBody($request, $body)
    {
        foreach ($this->mandatory_fields as $field) {
            if (!isset($body[$field]))
                throw new HttpBadRequestException($request, $this->invalid_body_msg);
            else if (!is_numeric($body[$field])) {
                throw new HttpBadRequestException($request, $this->not_numeric_msg);
            }
        }

        // (optional fields) can't have z1 without z2 and vice-versa
        if (isset($body['z1']) ^ isset($body['z2'])) {
            throw new HttpBadRequestException($request, $this->invalid_body_msg);
        }

        // validate z1 and z2 if set
        if (isset($body['z1'])) {
            if (!is_numeric($body['z1']) || !is_numeric($body['z2'])) {
                throw new HttpBadRequestException($request, $this->not_numeric_msg);
            }
        }
    }
}

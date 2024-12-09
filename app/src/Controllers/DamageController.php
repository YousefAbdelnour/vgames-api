<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DamageController extends BaseController
{
    public function handleCalculateDamage(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody(), true); // fixing bug related to arrays

        //required fields
        $requiredFields = [
            'baseWeaponDamage',
            'damageMultiplier',
            'fireDamage',
            'armor',
            'criticalMultiplier',
            'attackSpeedPerSecond',
            'dotDuration',
            'dotDamagePerSecond'
        ];
        //array for notifying user of missing fields
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $missingFields[] = $field;
            }
        }

        //if there are missing fields, return an error response
        if (!empty($missingFields)) {
            //manually concatenate missing fields
            $errorMessage = 'Missing required fields: ';
            foreach ($missingFields as $index => $field) {
                if ($index > 0) {
                    $errorMessage .= ', ';
                }
                $errorMessage .= $field;
            }

            return $this->renderJson($response, [
                'error' => $errorMessage
            ], 400);
        }

        //setting variables as data pulled from body
        $baseWeaponDamage = $data['baseWeaponDamage'];
        $damageMultiplier = $data['damageMultiplier'];
        $fireDamage = $data['fireDamage'];
        $armor = $data['armor']; // percent reduction not damage reduction
        $criticalMultiplier = $data['criticalMultiplier'];
        $attackSpeed = $data['attackSpeedPerSecond'];  //attacks per second (1 = one attack per second)
        $dotDuration = $data['dotDuration']; //damage over time duration (seconds)
        $dotDamage = $data['dotDamagePerSecond']; // damage per second over time

        //if armor exceeds 100%, cap it at 100%
        if ($armor > 100) {
            $armor = 100;
        }

        //calculating effective weapon damage after damage multiplier
        $effectiveWeaponDamage = $baseWeaponDamage * $damageMultiplier;

        //armor reduces the incoming damage based on percentage (1 - x/100)
        $damageAfterArmor = $effectiveWeaponDamage * (1 - ($armor / 100));

        //fire damage is added as damage over time
        $totalFireDamage = $fireDamage * $dotDuration;

        //critical hit damage calculation
        $totalDamage = $damageAfterArmor * $criticalMultiplier + $totalFireDamage + $dotDamage * $dotDuration;

        //calculating DPS -> total times the attack speed
        $dps = ($totalDamage + $totalFireDamage) * $attackSpeed;

        return $this->renderJson($response, [
            'dps' => $dps,
            'totalDamage' => $totalDamage,
            'fireDamage' => $totalFireDamage,
        ]);
    }
}

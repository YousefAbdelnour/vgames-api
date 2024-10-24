<?php

namespace App\Services;

use App\Validation\Validator;

use App\Core\Result;
use App\Models\CountryModel;
use App\Models\DeveloperModel;
use App\Models\GameModel;
use App\Models\GenreModel;

class GamesService
{
    private $valid_esrb_ratings = ['E', 'E10+', 'T', 'M', 'AO', 'RP'];

    //! ['min', 0] doesn't work will ask teacher
    private $rules = array(
        'Developer_Id' => [
            'required',
            'integer',
        ],
        'Genre_Name' => [
            'required',
        ],
        'Name' => [
            'required',
        ],
        'Founder' => [
            'required',
        ],
        'Release_Date' => [
            'required',
            ['dateFormat', 'Y-m-d']
        ],
        'Country_Name' => [
            'required',
        ],
        'ESRB' => [
            'required',
            array('lengthBetween', 1, 4)
        ],
        'Price' => [
            'required',
            'numeric',
        ],
        'Number_Of_Players' => [
            'required',
            'integer',
        ],
    );

    public function __construct(
        private GameModel $game_model,
        private DeveloperModel $developer_model,
        private GenreModel $genre_model,
        private CountryModel $country_model
    ) {}

    public function createGame($game): Result
    {
        $errors = [];

        $validator = new Validator($game);

        $validator->mapFieldsRules($this->rules);

        if (!$validator->validate()) {
            $errors = $validator->errors();
        }

        // Validating FKs
        if (!$this->developer_model->isValidDevId($game['Developer_Id'])) {
            $errors['Developer_Id'][] = "Could not find developer with id [{$game['Developer_Id']}]";
        };

        if (!$this->genre_model->isValidGenreName($game['Genre_Name'])) {
            $errors['Genre_Name'][] = "Could not find genre titled: [{$game['Genre_Name']}]";
        }

        if (!$this->country_model->isValidCountry($game['Country_Name'])) {
            $errors['Country_Name'][] = "Could not find country named [{$game['Country_Name']}]";
        }

        // For regex, Valitron supports ['regex', '/^[1-9]\d*/'], but we want to have specific error messages.

        if (!preg_match('/^[1-9]\d*/', $game['Number_Of_Players'])) {
            $errors['Number_Of_Players'][] = "Number of players must be at least 1";
        }

        if (!preg_match('/^\d/', $game['Price'])) {
            $errors['Price'][] = "Price must be at least 0.00";
        }

        // Validate ESRB
        $game['ESRB'] = strtoupper($game['ESRB']);
        if (!in_array($game['ESRB'], $this->valid_esrb_ratings)) {
            $esrb_join = implode(", ", $this->valid_esrb_ratings);
            $errors['ESRB'][] = "Invalid ESRB rating. Accepted ratings: {$esrb_join}";
        }

        // Return fail if any errors
        if ($errors) return Result::fail("Invalid game object", $errors);

        $game_id = $this->game_model->insertGame($game);

        return Result::success('Game created successfully.', $game_id);
    }
    public function updateGame(): Result
    {
        return Result::success('');
    }

    public function deleteGame(): Result
    {
        return Result::success('');
    }
}

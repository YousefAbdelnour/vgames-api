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
            array('lengthBetween', 2, 50)
        ],
        'Founder' => [
            'required',
            array('lengthBetween', 2, 100)
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
            ['min', 0]
        ],
        'Number_Of_Players' => [
            'required',
            'integer',
            ['min', 1]
        ],
    );

    private $delete_rules = array(
        'id' => [
            'required',
            'integer',
        ]
    );

    public function __construct(
        private GameModel $game_model,
        private DeveloperModel $developer_model,
        private GenreModel $genre_model,
        private CountryModel $country_model
    ) {}

    public function createGame($game): Result
    {
        $validator = new Validator($game);

        $validator->mapFieldsRules($this->rules);

        // checks for required fields and their types
        if (!$validator->validate()) {
            $errors = $validator->errors();
        }

        // passing errors array
        $this->validateGameBody($game, $errors);

        // Return fail if any errors
        if ($errors) return Result::fail("Invalid game object", $errors);

        $game_id = $this->game_model->insertGame($game);
        $inserted_game = $this->game_model->getGameById($game_id);

        return Result::success('Game created successfully.', $inserted_game);
    }

    public function updateGame($game): Result
    {
        $put_rules = array_merge(
            array('Game_Id' => [
                'required',
                'integer',
            ]),
            $this->rules
        );

        $validator = new Validator($game);

        $validator->mapFieldsRules($put_rules);

        if (!$validator->validate()) {
            $errors = $validator->errors();
        } else {
            // Validate PK (game_id)
            if (isset($game['Game_Id']) && !$this->game_model->isValidGameId($game['Game_Id'])) {
                $errors['Developer_Id'][] = "Could not find developer with id [{$game['Developer_Id']}]";
            };

            $this->validateGameBody($game, $errors);
        }

        // Return fail if any errors
        if ($errors) return Result::fail("Invalid game object", $errors);


        $this->game_model->updateGame($game);
        $update_game = $this->game_model->getGameById($game['Game_Id']);

        return Result::success('Game updated successfully.', $update_game);
    }

    public function deleteGame($body): Result
    {
        $errors = [];

        $validator = new Validator($body);

        $validator->mapFieldsRules($this->delete_rules);

        if (!$validator->validate()) {
            // if id isn't an integer it won't check if it exists
            $errors = $validator->errors();
        } else if (!$this->game_model->isValidGameId($body['id'])) {
            // if id is valid (integer), check if it exists
            $errors['id'][] = "Could not find game with id [{$body['id']}]";
        }

        // Return fail if any errors
        if ($errors) return Result::fail("Invalid game ID", $errors);

        $game_id = $body['id'];
        // return the deleted game object
        $deleted_game = $this->game_model->getGameById($game_id);

        $this->game_model->deleteGame($game_id);

        return Result::success('Game deleted successfully.', $deleted_game);
    }

    private function validateGameBody($game, &$errors)
    {
        if (isset($game['Developer_Id']) && !$this->developer_model->isValidDevId($game['Developer_Id'])) {
            $errors['Developer_Id'][] = "Could not find developer with id [{$game['Developer_Id']}]";
        };

        if (isset($game['Genre_Name']) && !$this->genre_model->isValidGenreName($game['Genre_Name'])) {
            $errors['Genre_Name'][] = "Could not find genre titled: [{$game['Genre_Name']}]";
        }

        if (isset($game['Country_Name']) && !$this->country_model->isValidCountry($game['Country_Name'])) {
            $errors['Country_Name'][] = "Could not find country named [{$game['Country_Name']}]";
        }

        // Validate ESRB
        if (isset($game['ESRB'])) {
            $game['ESRB'] = strtoupper($game['ESRB']);
            if (!in_array($game['ESRB'], $this->valid_esrb_ratings)) {
                $esrb_join = implode(", ", $this->valid_esrb_ratings);
                $errors['ESRB'][] = "Invalid ESRB rating. Accepted ratings: {$esrb_join}";
            }
        }
    }
}

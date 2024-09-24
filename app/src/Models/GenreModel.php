<?php

namespace App\Models;

use App\Core\PDOService;

class GenreModel extends BaseModel
{
    private string $table_name = "genre";

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getGenres(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name}";

        return $this->paginate($sql, $query_args);
    }

    public function getGenreByName($name)
    {
        // Selecting specific name
        $sql = "SELECT * FROM {$this->table_name} WHERE Genre_Name = :genre_name";

        // Returning the single instance of the name
        $genre = $this->fetchSingle($sql, ["genre_name" => $name]);
        return $genre;
    }
}

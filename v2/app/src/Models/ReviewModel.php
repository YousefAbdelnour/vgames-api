<?php

namespace App\Models;

use App\Core\PDOService;

class ReviewModel extends BaseModel
{
    private string $table_name = "review";
    public array $fields = ['review_id', 'game_id', 'rating', 'date', 'likes', 'platform_name'];
    public string $default_sort_field = 'review_id';

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getReviews(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";
        $this->filter($params, $sql, $query_args);
        $this->sort($params, $sql, $this);

        return (array) $this->paginate($sql, $query_args);
    }

    public function getReviewById($review_id): mixed
    {
        $sql = "SELECT * FROM {$this->table_name} where review_id = :review_id";
        return $this->fetchSingle($sql, ["review_id" => $review_id]);
    }

    private function filter(array $params, string &$sql, array &$query_args)
    {
        // RATING
        if (isset($params["min_rating"]) && isset($params["max_rating"])) {
            $sql .= " AND rating BETWEEN :min_rating AND :max_rating ";
            $query_args['min_rating'] = $params['min_rating'];
            $query_args['max_rating'] = $params['max_rating'];
        } else if (isset($params["min_rating"])) {
            $sql .= " AND rating >= :min_rating";
            $query_args['min_rating'] = $params['min_rating'];
        } else if (isset($params["max_rating"])) {
            $sql .= " AND rating <= :max_rating";
            $query_args['max_rating'] = $params['max_rating'];
        } else if (isset($params['rating'])) {
            $sql .= " AND rating = :rating";
            $query_args['rating'] = $params['rating'];
        }

        // LIKES
        if (isset($params["min_likes"]) && isset($params["max_likes"])) {
            $sql .= " AND likes BETWEEN :min_likes AND :max_likes ";
            $query_args['min_likes'] = $params['min_likes'];
            $query_args['max_likes'] = $params['max_likes'];
        } else if (isset($params["min_likes"])) {
            $sql .= " AND likes >= :min_likes";
            $query_args['min_likes'] = $params['min_likes'];
        } else if (isset($params["max_likes"])) {
            $sql .= " AND likes <= :max_likes";
            $query_args['max_likes'] = $params['max_likes'];
        } else if (isset($params['likes'])) {
            $sql .= " AND likes = :likes";
            $query_args['likes'] = $params['likes'];
        }

        // PLATFORM_NAME
        if (isset($params['platform_name'])) {
            $sql .= ' AND platform_name LIKE :platform_name';
            $query_args['platform_name'] = '%' . $params['platform_name'] . '%';
        }

        // DATE
        if (isset($params['from_date']) && isset($params['to_date'])) {
            $sql .= ' AND date BETWEEN :from_date AND :to_date ';
            $query_args['from_date'] = $params['from_date'];
            $query_args['to_date'] = $params['to_date'];
        } else if (isset($params['to_date'])) {
            $sql .= ' AND date <= :to_date';
            $query_args['to_date'] = $params['to_date'];
        } else if (isset($params['from_date'])) {
            $sql .= ' AND date >= :from_date';
            $query_args['from_date'] = $params['from_date'];
        } else if (isset($params['date'])) {
            $sql .= ' AND date = :date';
            $query_args['date'] = $params['date'];
        }

        // GAME_ID
        if (isset($params['game_id'])) {
            $sql .= ' AND game_id = :game_id';
            $query_args['game_id'] = $params['game_id'];
        }
    }
}

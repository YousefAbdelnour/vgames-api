<?php

namespace App\Models;

use App\Core\PDOService;

class DLCModel extends BaseModel
{
    private string $table_name = "dlc";

    public array $fields = ['dlc_id', 'game_id', 'name', 'release_date', 'price', 'description', 'total_sales', 'sales_id', 'revenue', 'hard_copies_sold', 'digital_copies_sold', 'highest_revenue_region'];

    public string $default_sort_field = 'release_date';


    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function getDLCs(array $params): array
    {
        $query_args = [];

        $sql = "SELECT * FROM {$this->table_name} WHERE 1 ";
        $this->filter($params, $sql, $query_args);
        $this->sort($params, $sql, $this);
        return (array) $this->paginate($sql, $query_args);
    }

    public function getDLCById($dlc_id): mixed
    {
        $sql = "SELECT * FROM {$this->table_name} where DLC_id = :dlc_id";
        return $this->fetchSingle($sql, ["dlc_id" => $dlc_id]);
    }

    private function filter(array $params, string &$sql, array &$query_args)
    {
        //* dlc_name
        if (isset($params['dlc_name'])) {
            $sql .= ' AND `Name` LIKE :dlc_name';
            $query_args['dlc_name'] = '%' . $params['dlc_name'] . '%';
        }

        //* Revenue Region
        if (isset($params['highest_rev_region'])) {
            $sql .= ' AND Highest_reveue_region LIKE :highest_rev_region';
            $query_args['highest_rev_region'] = '%' . $params['highest_rev_region'] . '%';
        }

        //* game_id
        if (isset($params['game_id'])) {
            $sql .= ' AND game_id = :game_id';
            $query_args['game_id'] = $params['game_id'];
        }

        //* price
        if (isset($params['min_price']) && isset($params['max_price'])) {
            $sql .= ' AND price BETWEEN :min_price AND :max_price ';
            $query_args['min_price'] = $params['min_price'];
            $query_args['max_price'] = $params['max_price'];
        } else if (isset($params['max_price'])) {
            $sql .= ' AND price <= :max_price';
            $query_args['max_price'] = $params['max_price'];
        } else if (isset($params['min_price'])) {
            $sql .= ' AND price >= :min_price';
            $query_args['min_price'] = $params['min_price'];
        } else if (isset($params['price'])) {
            $sql .= ' AND price = :price';
            $query_args['price'] = $params['price'];
        }

        //* Num of languages
        if (isset($params['min_revenue']) && isset($params['max_revenue'])) {
            $sql .= ' AND revenue BETWEEN :min_revenue AND :max_revenue ';
            $query_args['min_revenue'] = $params['min_revenue'];
            $query_args['max_revenue'] = $params['max_revenue'];
        } else if (isset($params['max_revenue'])) {
            $sql .= ' AND revenue <= :max_revenue';
            $query_args['max_revenue'] = $params['max_revenue'];
        } else if (isset($params['min_revenue'])) {
            $sql .= ' AND revenue >= :min_revenue';
            $query_args['min_revenue'] = $params['min_revenue'];
        } else if (isset($params['revenue'])) {
            $sql .= ' AND revenue = :revenue';
            $query_args['revenue'] = $params['revenue'];
        }
    }
}

<?php


namespace App\Repositories\Product;


interface ProductRepositoryInterface
{
    /**
     * Returns the attributes of all products as an array.
     *
     * @return array
     */
    public function getSortAttribute();

    /**
     * Searches according to the specified rules and sorting.
     *
     * @param $condition
     * @param $column
     * @param $direction
     * @return array
     */
    public function searchWithCondition($column = 'name', $direction = 'ASC', $condition = '1 = 1');
}

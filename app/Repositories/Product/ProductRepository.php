<?php


namespace App\Repositories\Product;


use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Returns the attributes of all products as an array.
     *
     * @return array
     */
    public function getSortAttribute()
    {
        return $this->product->query()->get(['data'])->toArray();
    }

    /**
     * Searches according to the specified rules and sorting.
     *
     * @param $condition
     * @param $column
     * @param $direction
     * @return array
     */
    public function searchWithCondition($column = 'name', $direction = 'ASC', $condition = '1 = 1')
    {
        return $this->product->query()
            ->whereRaw($condition)
            ->orderBy($column, $direction)
            ->get()
            ->toArray();
    }
}

<?php


namespace App\Services\Search;


use App\Repositories\Product\ProductRepository;
use App\Services\Attribute\AttributeService;
use Illuminate\Support\Facades\DB;


class SearchService implements SearchServiceInterface
{
    private $productRepository;
    private $attributeService;

    public function __construct(ProductRepository $product, AttributeService $attribute)
    {
        $this->productRepository = $product;
        $this->attributeService = $attribute;
    }

    /**
     * Searches and prepares products from the products table.
     *
     * @param $request
     * @return array
     */
    public function search($request)
    {
        $data = json_decode($request->input('options'), true);
        $sortRaw = [
            'column' => $request->input('sort')[0] == 'price' ? 'price' : 'name',
            'direction' => $request->input('sort')[1] == 'asc' ? 'ASC' : 'DESC'
        ];
        if (empty($data)) {
            return $this->prepareAfterSearch(
                $this->productRepository->searchWithCondition($sortRaw['column'], $sortRaw['direction'])
            );
        }

        $conditions = [];
        $attrs = [];
        $rawCondition = '';
        $dbAttrs = $this->attributeService->get();
        foreach ($dbAttrs as $type => $values) {
            if (in_array($type, $dbAttrs)) {
                $attrs[] = $type;
            }
        }

        foreach ($data as $type => $values) {
            foreach ($values as $value) {
                $conditions [] = ["data->>" . "'" . $type . "'" , ' = ' , DB::connection()->getPdo()->quote($value), count($values) > 1 ? 'OR' : 'AND'];
            }
        }

        usort($conditions, function ($item1, $item2) {
            return $item1[3] <=> $item2[3];
        });

        for ($i = 0; $i < count($conditions); $i++) {
            if ($i == count($conditions)-1) {
                $rawCondition .= $conditions[$i][0] . $conditions[$i][1] . $conditions[$i][2];
            } else {
                $rawCondition .= $conditions[$i][0] . $conditions[$i][1] . $conditions[$i][2] . ' ' . $conditions[$i][3] . ' ';
            }
        }

        return $this->prepareAfterSearch(
            $this->productRepository->searchWithCondition($sortRaw['column'], $sortRaw['direction'], $rawCondition)
        );
    }

    /**
     * Prepares an array after a request from the database for re-wrapping in a json.
     *
     * @param $in
     * @return mixed
     */
    public function prepareAfterSearch($in) {
        foreach ($in as $key => &$val) {
            $val['data'] = json_decode($val['data'], true);
        }
        return $in;
    }
}

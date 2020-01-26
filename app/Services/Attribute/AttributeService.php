<?php


namespace App\Services\Attribute;


use App\Repositories\Product\ProductRepository;

class AttributeService implements AttributeServiceInterface
{
    private $productRepository;

    public function __construct(ProductRepository $product)
    {
        $this->productRepository = $product;
    }

    /**
     * Sorts attributes by template option => [value, value..].
     *
     * @return array
     */
    public function get()
    {
        $attrStruct = [];
        $attrs = $this->productRepository->getSortAttribute();

        foreach ($attrs as $attribute) {
            $temp = json_decode($attribute['data'], true);
            foreach ($temp as $key => $value) {
                $attrStruct[$key][$value] = $value;
            }
        }

        return $attrStruct;
    }
}

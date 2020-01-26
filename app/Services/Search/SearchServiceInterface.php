<?php


namespace App\Services\Search;


interface SearchServiceInterface
{
    /**
     * Searches and prepares products from the products table.
     *
     * @param $request
     * @return array
     */
    public function search($request);

    /**
     * Prepares an array after a request from the database for re-wrapping in a json.
     *
     * @param $in
     * @return mixed
     */
    public function prepareAfterSearch($in);
}

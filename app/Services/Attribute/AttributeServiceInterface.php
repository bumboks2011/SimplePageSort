<?php


namespace App\Services\Attribute;


interface AttributeServiceInterface
{
    /**
     * Sorts attributes by template option => [value, value..].
     *
     * @return array
     */
    public function get();
}

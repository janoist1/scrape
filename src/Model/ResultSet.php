<?php

namespace Sainsburys\TechTest\Model;

/**
 * Class ResultSet - representation of a set of Result items
 *
 * @package Sainsburys\TechTest\Model
 */
class ResultSet
{
    /** @var Result[] */
    private $results;

    /**
     * constructor - optionally take an initial array of results as a param
     *
     * @param array $results
     */
    function __construct(array $results = [])
    {
        $this->results = $results;
    }

    /**
     * return the items as array
     *
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * set result items
     *
     * @param Result[] $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

    /**
     * add an item to the set
     *
     * @param Result $result
     */
    public function addResult(Result $result)
    {
        $this->results[] = $result;
    }

    /**
     * calculate and return the total of unit price
     *
     * @return float
     */
    public function getTotal()
    {
        $total = .0;
        foreach ($this->results as $result) {
            $total += (float)$result->getUnitPrice();
        }

        return $total;
    }
}

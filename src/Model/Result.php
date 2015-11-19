<?php

namespace Sainsburys\TechTest\Model;

/**
 * Class Result - representation of a Result item
 *
 * @package Sainsburys\TechTest\Model
 */
class Result
{
    /** @var string */
    private $title;

    /** @var string */
    private $size;

    /** @var string */
    private $unitPrice;

    /** @var string */
    private $description;

    /**
     * @param $title
     * @param $size
     * @param $unitPrice
     * @param $description
     */
    function __construct($title, $size, $unitPrice, $description)
    {
        $this->title = $title;
        $this->size = $size;
        $this->unitPrice = $unitPrice;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param string $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }
}

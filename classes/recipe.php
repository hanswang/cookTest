<?php

class Ingredient {

    public $name;
    public $amount;
    public $unit;

    function __construct() {
    }

    public function loadFromArray($spec) {
        if (is_array($spec) && isset($spec['item']) && isset($spec['amount']) && isset($spec['unit'])) {
            $this->name = trim($spec['item']);
            $this->amount = intval($spec['amount']);
            $this->unit = $spec['unit'];

            return true;
        }

        return false;
    }

    public function validate() {
        if (!in_array($this->unit, array('of', 'grams', 'ml', 'slices'))) {
            echo 'Malform of unit, invalid Ingredient found : ' .json_encode($this);
            echo "\n";
            return false;
        }
        if (!is_int($this->amount)) {
            echo 'Malform of amount, invalid Ingredient found : ' .json_encode($this);
            echo "\n";
            return false;
        }

        return true;
    }
}

class Recipe {
    public $name;
    public $ingredients = array();

    function __construct() {
    }

    public function loadFromArray($course) {
        if (is_array($course) && isset($course['name']) && isset($course['ingredients'])) {
            $this->name = $course['name'];

            $ingreLoad = $course['ingredients'];
            if(is_array($ingreLoad) && count($ingreLoad) > 0) {
                foreach ($ingreLoad as $ingre) {
                    $ingreObj = new Ingredient();
                    if ($ingreObj->loadFromArray($ingre)) {
                        $this->ingredients[] = $ingreObj;
                    } else {
                        return false;
                    }
                }

                return true;
            }
        }

        return false;
    }
}

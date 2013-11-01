<?php

require_once 'classes/Item.php';
require_once 'classes/Recipe.php';

class ForgeHandler {
    public $useby;

    private $recipe;
    private $itemList;

    function __construct() {
        $this->useby = false;
    }

    public function getRecipe() {
        return $this->recipe;
    }
    public function setRecipe($newRecipe) {
        $this->recipe = $newRecipe;
    }

    public function getItemList() {
        return $this->itemList;
    }
    public function setItemList($newItemList) {
        $this->itemList = $newItemList;
    }

    public function forgeCourseByRecipe() {
        $ingreList = $this->recipe->ingredients;
        foreach($ingreList as $ingre) {
            $item = self::fetchItems($ingre);
            if (!$item) {
                // cant find items needed
                return false;
            }
            // update valid time if found one
            if (!$this->useby || $this->useby > $item->useby) {
                $this->useby = $item->useby;
            }
        }

        return true;
    }

    public function fetchItems($ingredient) {
        foreach($this->itemList as $item) {
            if ( ($item->name === $ingredient->name)
                && ($item->unit === $ingredient->unit)
                && ($item->amount > $ingredient->amount) ) {
                    return $item;
                }
        }
        return false;
    }
}

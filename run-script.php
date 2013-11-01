#!/usr/bin/php
<?php

require_once 'classes/Item.php';
require_once 'classes/Recipe.php';
require_once 'classes/ForgeHandler.php';

function latestExpireDay($a, $b) {
    if ($a->useby === $b->useby) {
        return 0;
    }
    return ($a->useby > $b->useby) ? 1 : -1;
}

function main() {
    global $argc, $argv;
    if ($argc < 3) {
        print "usage : php run-script.php <item> <recipe> \n";
        print "\t\t<item> as item list csv file\n";
        print "\t\t<recipe> as recipe json file\n";
        print "\ttake 2 input as in correct order\n";
    }

    $itemsInput = file($argv[1], FILE_IGNORE_NEW_LINES);
    $fridgeCollection = array();
    foreach($itemsInput as $itemInput) {
        $item = new Item($itemInput);
        if ($item->load() && $item->validate()) {
            $fridgeCollection[] = $item;
        }
    }

    $recipeInput = json_decode(file_get_contents($argv[2]), true);
    $recipeList = array();
    if (is_array($recipeInput) && count($recipeInput) > 0) {
        foreach($recipeInput as $recipeFeed) {
            $recipe = new Recipe();
            if ($recipe->loadFromArray($recipeFeed)) {
                $recipeList[] = $recipe;
            } else {
                print "Unable to fully load recipe list, quiting ... \n";
                return false;
            }
        }
    }

    usort($fridgeCollection, 'latestExpireDay');

    $possibleSolution = array();
    foreach($recipeList as $recipe) {
        $cook = new ForgeHandler();
        $cook->setRecipe($recipe);
        $cook->setItemList($fridgeCollection);
        if ($cook->forgeCourseByRecipe()) {
            $possibleSolution[] = $cook;
        }
    }

    if (count($possibleSolution) == 0) {
        print "Order Takeout\n";
    } else {
        if (count($possibleSolution) > 1) {
            usort($possibleSolution, 'latestExpireDay');
        }
        print $possibleSolution[0]->getRecipe()->name ."\n";
    }

    return true;
}

main();

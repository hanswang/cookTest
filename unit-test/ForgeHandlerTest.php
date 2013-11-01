<?php

require '../classes/Item.php';
require '../classes/Recipe.php';
require '../classes/ForgeHandler.php';

class ForgeHandlerTest extends PHPUnit_Framework_TestCase {

    public function testInitial() {
        $handler = new ForgeHandler();
        $this->assertFalse($handler->useby);
        $this->assertEquals($handler->getRecipe(), NULL);
        $this->assertEquals($handler->getItemList(), NULL);

        $handler->setRecipe('test Recipe');
        $this->assertEquals($handler->getRecipe(), 'test Recipe');

        $handler->setItemList(array('a' => 123, 'skd' => 'hello'));
        $this->assertEquals($handler->getItemList(), array('a' => 123, 'skd' => 'hello'));
    }

    public function testFetchSuccess() {
        $handler = new ForgeHandler();

        $recipe = new Recipe();
        $formatRecipe = json_decode('{ "name": "grilled cheese on toast", "ingredients": [ { "item":"bread", "amount":"2", "unit":"slices"}, { "item":"cheese", "amount":"2", "unit":"slices"} ] }', true);
        $this->assertTrue($recipe->loadFromArray($formatRecipe));

        $itemA = new Item('bread,10,slices,25/12/2014');
        $this->assertTrue($itemA->load() && $itemA->validate());
        $itemB = new Item('cheese,10,slices,25/12/2014');
        $this->assertTrue($itemB->load() && $itemB->validate());

        $handler->setRecipe($recipe);
        $handler->setItemList(array($itemA, $itemB));

        $this->assertTrue($handler->forgeCourseByRecipe());
    }

    public function testFetchMissingItem() {
        $handler = new ForgeHandler();

        $recipe = new Recipe();
        $formatRecipe = json_decode('{ "name": "grilled cheese on toast", "ingredients": [ { "item":"bread", "amount":"2", "unit":"slices"}, { "item":"cheese", "amount":"2", "unit":"slices"} ] }', true);
        $this->assertTrue($recipe->loadFromArray($formatRecipe));

        $itemA = new Item('bread,10,slices,25/12/2013');
        $this->assertTrue($itemA->load() && $itemA->validate());
        $itemB = new Item('grape,10,of,25/12/2014');
        $this->assertTrue($itemB->load() && $itemB->validate());

        $handler->setRecipe($recipe);
        $handler->setItemList(array($itemA, $itemB));

        $this->assertFalse($handler->forgeCourseByRecipe());
    }
}

<?php

require '../classes/Recipe.php';

class RecipeTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider provider
     */
    public function testValidRecipe($input) {
        $formated = json_decode($input, true);
        $this->assertTrue(is_array($formated));
        $this->assertEquals(count($formated), 2);
        $recipe = new Recipe();

        $this->assertTrue($recipe->loadFromArray($formated));
        $this->assertEquals($recipe->ingredients[0]->name, 'bread');
        $this->assertEquals($recipe->ingredients[1]->amount, '2');
    }

    public function provider() {
        return array(array('{ "name": "grilled cheese on toast", "ingredients": [ { "item":"bread", "amount":"2", "unit":"slices"}, { "item":"cheese", "amount":"2", "unit":"slices"} ] }'));
    }
}

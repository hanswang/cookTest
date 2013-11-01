<?php

require '../classes/Item.php';

class ItemTest extends PHPUnit_Framework_TestCase {

    public function testExpireItem() {
        $item = new Item('bread,10,slices,25/12/2011');

        $this->assertTrue($item->load());
        $this->assertFalse($item->validate());
        $this->assertEquals($item->getData(), 'bread,10,slices,25/12/2011');
    }

    /**
     * @dataProvider provider
     */
    public function testValidItem($input) {
        $item = new Item($input);

        $this->assertTrue($item->load());
        $this->assertTrue($item->validate());
        $this->assertEquals($item->getData(), 'bread,10,slices,25/12/2014');
    }

    public function provider() {
        return array(array('bread,10,slices,25/12/2014'));
    }
}

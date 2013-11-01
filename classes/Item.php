<?php

class Item {
    public $name;
    public $amount;
    public $unit;
    public $useby;

    protected $data;

    function __construct($line) {
        $this->data = trim($line);
    }

    public function load() {
        if (isset($this->data)) {
            $params = explode(',', $this->data);
            if (is_array($params) && count($params) > 0) {
                $this->name = trim($params[0]);
                $this->unit = trim($params[2]);

                $this->amount = intval(trim($params[1]));
                $useByDate = explode('/', trim($params[3]));
                if (is_array($useByDate) && count($useByDate) > 0) {
                    $this->useby = mktime(0, 0, 0, (int)$useByDate[1], (int)$useByDate[0], (int)$useByDate[2]);
                    return true;
                }
            }
        }
        return false;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($newData) {
        $this->data = $newData;
    }

    public function validate() {
        // format check
        if (!in_array($this->unit, array('of', 'grams', 'ml', 'slices'))) {
            echo 'Malform of unit, invalid Item found : ' .json_encode($this);
            echo "\n";
            return false;
        }
        if (!is_int($this->amount)) {
            echo 'Malform of amount, invalid Item found : ' .json_encode($this);
            echo "\n";
            return false;
        }

        // functionality check
        if ($this->useby < time()) {
            // expired item
            return false;
        }
        return true;
    }
}

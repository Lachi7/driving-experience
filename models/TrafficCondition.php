<?php
// models/TrafficCondition.php
class TrafficCondition {
    public $id;
    public $name;
    
    public function __construct($id = null, $name = null) {
        $this->id = $id;
        $this->name = $name;
    }
    
    public static function fromArray($array) {
        return new self($array['traffic_id'] ?? null, $array['traffic_condition'] ?? null);
    }
}
?>
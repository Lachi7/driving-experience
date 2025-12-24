<?php
// models/RoadType.php
class RoadType {
    public $id;
    public $name;
    
    public function __construct($id = null, $name = null) {
        $this->id = $id;
        $this->name = $name;
    }
    
    public static function fromArray($array) {
        return new self($array['road_type_id'] ?? null, $array['road_type'] ?? null);
    }
}
?>
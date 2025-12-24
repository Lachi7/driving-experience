<?php
// models/Maneuver.php
class Maneuver {
    public $id;
    public $name;
    
    public function __construct($id = null, $name = null) {
        $this->id = $id;
        $this->name = $name;
    }
    
    public static function fromArray($array) {
        return new self($array['maneuver_id'] ?? null, $array['maneuver'] ?? null);
    }
}
?>
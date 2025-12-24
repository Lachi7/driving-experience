<?php
// models/JourneyType.php
class JourneyType {
    public $id;
    public $name;
    
    public function __construct($id = null, $name = null) {
        $this->id = $id;
        $this->name = $name;
    }
    
    public static function fromArray($array) {
        return new self($array['journey_type_id'] ?? null, $array['journey_type'] ?? null);
    }
}
?>
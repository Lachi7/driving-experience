<?php
// models/WeatherCondition.php
class WeatherCondition {
    public $id;
    public $name;
    
    public function __construct($id = null, $name = null) {
        $this->id = $id;
        $this->name = $name;
    }
    
    public static function fromArray($array) {
        return new self($array['weather_id'] ?? null, $array['weather_condition'] ?? null);
    }
}
?>
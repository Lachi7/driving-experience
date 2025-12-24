<?php
// models/DrivingExperience.php
class DrivingExperience {
    public $id;
    public $date;
    public $departure_time;
    public $arrival_time;
    public $km_covered;
    public $weather_id;
    public $weather_condition;
    public $traffic_id;
    public $traffic_condition;
    public $road_type_id;
    public $road_type;
    public $journey_type_id;
    public $journey_type;
    public $maneuvers = [];
    
    public function __construct($data = []) {
        foreach($data as $key => $value) {
            if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    
    public static function fromArray($array) {
        return new self($array);
    }
    
    public function calculateDuration() {
        if (!$this->departure_time || !$this->arrival_time) {
            return '00:00';
        }
        $start = new DateTime($this->departure_time);
        $end = new DateTime($this->arrival_time);
        $interval = $start->diff($end);
        return $interval->format('%H:%I');
    }
    
    public function isValid() {
        return !empty($this->date) && 
               !empty($this->departure_time) && 
               !empty($this->arrival_time) &&
               $this->km_covered > 0 &&
               $this->weather_id > 0 &&
               $this->traffic_id > 0 &&
               $this->road_type_id > 0 &&
               $this->journey_type_id > 0;
    }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'departure_time' => $this->departure_time,
            'arrival_time' => $this->arrival_time,
            'km_covered' => $this->km_covered,
            'weather_id' => $this->weather_id,
            'weather_condition' => $this->weather_condition,
            'traffic_id' => $this->traffic_id,
            'traffic_condition' => $this->traffic_condition,
            'road_type_id' => $this->road_type_id,
            'road_type' => $this->road_type,
            'journey_type_id' => $this->journey_type_id,
            'journey_type' => $this->journey_type
        ];
    }
}
?>
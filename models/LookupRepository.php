<?php
// models/LookupRepository.php
require_once 'WeatherCondition.php';
require_once 'TrafficCondition.php';
require_once 'RoadType.php';
require_once 'JourneyType.php';
require_once 'Maneuver.php';

class LookupRepository {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getWeatherConditions() {
        $query = "SELECT * FROM weather_conditions ORDER BY weather_condition";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        
        $conditions = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $conditions[] = WeatherCondition::fromArray($row);
        }
        return $conditions;
    }
    
    public function getTrafficConditions() {
        $query = "SELECT * FROM traffic_conditions ORDER BY traffic_condition";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        
        $conditions = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $conditions[] = TrafficCondition::fromArray($row);
        }
        return $conditions;
    }
    
    public function getRoadTypes() {
        $query = "SELECT * FROM road_types ORDER BY road_type";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        
        $types = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $types[] = RoadType::fromArray($row);
        }
        return $types;
    }
    
    public function getJourneyTypes() {
        $query = "SELECT * FROM journey_types ORDER BY journey_type";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        
        $types = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $types[] = JourneyType::fromArray($row);
        }
        return $types;
    }
    
    public function getManeuvers() {
        $query = "SELECT * FROM maneuvers ORDER BY maneuver";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        
        $maneuvers = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $maneuvers[] = Maneuver::fromArray($row);
        }
        return $maneuvers;
    }
    
    public function getWeatherConditionById($id) {
        $query = "SELECT * FROM weather_conditions WHERE weather_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? WeatherCondition::fromArray($row) : null;
    }
    
    public function getTrafficConditionById($id) {
        $query = "SELECT * FROM traffic_conditions WHERE traffic_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? TrafficCondition::fromArray($row) : null;
    }
    
    public function getRoadTypeById($id) {
        $query = "SELECT * FROM road_types WHERE road_type_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? RoadType::fromArray($row) : null;
    }
    
    public function getJourneyTypeById($id) {
        $query = "SELECT * FROM journey_types WHERE journey_type_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? JourneyType::fromArray($row) : null;
    }
}
?>
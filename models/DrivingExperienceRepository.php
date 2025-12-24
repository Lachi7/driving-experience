<?php
// models/DrivingExperienceRepository.php
require_once 'DrivingExperience.php';

class DrivingExperienceRepository {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function save(DrivingExperience $experience, $maneuvers = []) {
        if ($experience->id == 0 || $experience->id === null) {
            // INSERT
            $query = "INSERT INTO driving_experience 
                     (date, departure_time, arrival_time, km_covered, 
                      weather_id, traffic_id, road_type_id, journey_type_id) 
                     VALUES (:date, :departure, :arrival, :km, 
                             :weather, :traffic, :road, :journey)";
            
            $stmt = $this->pdo->prepare($query);
            
            $stmt->bindValue(':date', $experience->date);
            $stmt->bindValue(':departure', $experience->departure_time);
            $stmt->bindValue(':arrival', $experience->arrival_time);
            $stmt->bindValue(':km', $experience->km_covered, PDO::PARAM_STR);
            $stmt->bindValue(':weather', $experience->weather_id, PDO::PARAM_INT);
            $stmt->bindValue(':traffic', $experience->traffic_id, PDO::PARAM_INT);
            $stmt->bindValue(':road', $experience->road_type_id, PDO::PARAM_INT);
            $stmt->bindValue(':journey', $experience->journey_type_id, PDO::PARAM_INT);
            
            $stmt->execute();
            $newId = $this->pdo->lastInsertId();
            
            // Save maneuvers
            $this->saveManeuvers($newId, $maneuvers);
            
            return $newId;
        } else {
            // UPDATE
            $query = "UPDATE driving_experience 
                     SET date = :date, departure_time = :departure, arrival_time = :arrival, 
                         km_covered = :km, weather_id = :weather, traffic_id = :traffic,
                         road_type_id = :road, journey_type_id = :journey
                     WHERE driving_experience_id = :id";
            
            $stmt = $this->pdo->prepare($query);
            
            $stmt->bindValue(':date', $experience->date);
            $stmt->bindValue(':departure', $experience->departure_time);
            $stmt->bindValue(':arrival', $experience->arrival_time);
            $stmt->bindValue(':km', $experience->km_covered, PDO::PARAM_STR);
            $stmt->bindValue(':weather', $experience->weather_id, PDO::PARAM_INT);
            $stmt->bindValue(':traffic', $experience->traffic_id, PDO::PARAM_INT);
            $stmt->bindValue(':road', $experience->road_type_id, PDO::PARAM_INT);
            $stmt->bindValue(':journey', $experience->journey_type_id, PDO::PARAM_INT);
            $stmt->bindValue(':id', $experience->id, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // Update maneuvers
            $this->saveManeuvers($experience->id, $maneuvers);
            
            return $experience->id;
        }
    }
    
    private function saveManeuvers($experienceId, $maneuvers) {
        // Delete existing maneuvers
        $query = "DELETE FROM driving_experiences_maneuvers WHERE driving_experience_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $experienceId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Insert new maneuvers
        if (!empty($maneuvers)) {
            $query = "INSERT INTO driving_experiences_maneuvers (driving_experience_id, maneuver_id) 
                     VALUES (:exp_id, :man_id)";
            $stmt = $this->pdo->prepare($query);
            
            foreach($maneuvers as $maneuverId) {
                $stmt->bindValue(':exp_id', $experienceId, PDO::PARAM_INT);
                $stmt->bindValue(':man_id', intval($maneuverId), PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
    
    public function findById($id) {
        $query = "SELECT de.*, wc.weather_condition, tc.traffic_condition, 
                         rt.road_type, jt.journey_type
                  FROM driving_experience de
                  JOIN weather_conditions wc ON de.weather_id = wc.weather_id
                  JOIN traffic_conditions tc ON de.traffic_id = tc.traffic_id
                  JOIN road_types rt ON de.road_type_id = rt.road_type_id
                  JOIN journey_types jt ON de.journey_type_id = jt.journey_type_id
                  WHERE de.driving_experience_id = :id";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $experience = DrivingExperience::fromArray($row);
            $experience->id = $row['driving_experience_id'];
            
            // Get maneuvers for this experience
            $experience->maneuvers = $this->getManeuversForExperience($id);
            
            return $experience;
        }
        
        return null;
    }
    
    public function findAll() {
        $query = "SELECT de.*, wc.weather_condition, tc.traffic_condition, 
                         rt.road_type, jt.journey_type
                  FROM driving_experience de
                  JOIN weather_conditions wc ON de.weather_id = wc.weather_id
                  JOIN traffic_conditions tc ON de.traffic_id = tc.traffic_id
                  JOIN road_types rt ON de.road_type_id = rt.road_type_id
                  JOIN journey_types jt ON de.journey_type_id = jt.journey_type_id
                  ORDER BY de.date DESC, de.departure_time DESC";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        
        $experiences = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $experience = DrivingExperience::fromArray($row);
            $experience->id = $row['driving_experience_id'];
            $experiences[] = $experience;
        }
        return $experiences;
    }
    
    public function delete($id) {
        try {
            // Delete maneuvers first
            $query = "DELETE FROM driving_experiences_maneuvers WHERE driving_experience_id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Delete experience
            $query = "DELETE FROM driving_experience WHERE driving_experience_id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
            
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getTotalKilometers() {
        $query = "SELECT SUM(km_covered) AS total FROM driving_experience";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($row['total'] ?? 0);
    }
    
    public function getCount() {
        $query = "SELECT COUNT(*) AS count FROM driving_experience";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($row['count'] ?? 0);
    }
    
    private function getManeuversForExperience($experienceId) {
        $query = "SELECT m.maneuver_id, m.maneuver 
                  FROM maneuvers m
                  JOIN driving_experiences_maneuvers dem ON m.maneuver_id = dem.maneuver_id
                  WHERE dem.driving_experience_id = :id";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $experienceId, PDO::PARAM_INT);
        $stmt->execute();
        
        $maneuvers = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $maneuvers[] = $row['maneuver_id'];
        }
        return $maneuvers;
    }
    
    public function getStatistics() {
        $stats = [];
        
        // Weather statistics
        $query = "SELECT wc.weather_condition, COUNT(*) as count, SUM(km_covered) as total_km
                  FROM driving_experience de
                  JOIN weather_conditions wc ON de.weather_id = wc.weather_id
                  GROUP BY wc.weather_id, wc.weather_condition
                  ORDER BY count DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $stats['weather'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Traffic statistics
        $query = "SELECT tc.traffic_condition, COUNT(*) as count, SUM(km_covered) as total_km
                  FROM driving_experience de
                  JOIN traffic_conditions tc ON de.traffic_id = tc.traffic_id
                  GROUP BY tc.traffic_id, tc.traffic_condition
                  ORDER BY count DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $stats['traffic'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Road type statistics
        $query = "SELECT rt.road_type, COUNT(*) as count, SUM(km_covered) as total_km
                  FROM driving_experience de
                  JOIN road_types rt ON de.road_type_id = rt.road_type_id
                  GROUP BY rt.road_type_id, rt.road_type
                  ORDER BY count DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $stats['road'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Journey type statistics
        $query = "SELECT jt.journey_type, COUNT(*) as count, SUM(km_covered) as total_km
                  FROM driving_experience de
                  JOIN journey_types jt ON de.journey_type_id = jt.journey_type_id
                  GROUP BY jt.journey_type_id, jt.journey_type
                  ORDER BY count DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $stats['journey'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Maneuver statistics
        $query = "SELECT m.maneuver, COUNT(*) as count
                  FROM maneuvers m
                  JOIN driving_experiences_maneuvers dem ON m.maneuver_id = dem.maneuver_id
                  GROUP BY m.maneuver_id, m.maneuver
                  ORDER BY count DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $stats['maneuvers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
}
?>
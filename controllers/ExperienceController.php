<?php
// controllers/ExperienceController.php

// Include the database connection FIRST
require_once __DIR__ . '/../includes/connectDB.inc.php';
require_once __DIR__ . '/../models/DrivingExperience.php';
require_once __DIR__ . '/../models/DrivingExperienceRepository.php';
require_once __DIR__ . '/../models/LookupRepository.php';

class ExperienceController {
    private $pdo;
    private $experienceRepo;
    private $lookupRepo;
    
    public function __construct() {
        $this->pdo = Database::getInstance();
        $this->experienceRepo = new DrivingExperienceRepository($this->pdo);
        $this->lookupRepo = new LookupRepository($this->pdo);
    }
    
    public function index() {
        $experiences = $this->experienceRepo->findAll();
        $totalKm = $this->experienceRepo->getTotalKilometers();
        $totalCount = $this->experienceRepo->getCount();
        
        // Initialize session array if not exists
        if (!isset($_SESSION['code'])) {
            $_SESSION['code'] = [];
        }
        
        $codes = [];
        foreach($experiences as $experience) {
            $code = random_code(10);
            $_SESSION['code'][$code] = $experience->id;
            $codes[$experience->id] = $code;
        }
        
        // Generate code for new experience
        $newExpCode = random_code(10);
        $_SESSION['code'][$newExpCode] = 0;
        
        require_once __DIR__ . '/../views/index.php';
    }
    
    public function showForm() {
        $code = $_GET['code'] ?? '';
        
        if (!$code || !isset($_SESSION['code'][$code])) {
            header("Location: index.php");
            exit();
        }
        
        $experienceId = intval($_SESSION['code'][$code]);
        $experience = null;
        
        if ($experienceId > 0) {
            $experience = $this->experienceRepo->findById($experienceId);
        }
        
        $lookupData = [
            'weather' => $this->lookupRepo->getWeatherConditions(),
            'traffic' => $this->lookupRepo->getTrafficConditions(),
            'road' => $this->lookupRepo->getRoadTypes(),
            'journey' => $this->lookupRepo->getJourneyTypes(),
            'maneuvers' => $this->lookupRepo->getManeuvers()
        ];
        
        require_once __DIR__ . '/../views/form.php';
    }
    
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php");
            exit();
        }
        
        $code = $_POST['code'] ?? '';
        
        if (!$code || !isset($_SESSION['code'][$code])) {
            $_SESSION['error'] = "Invalid or expired session";
            header("Location: index.php");
            exit();
        }
        
        $experienceId = intval($_SESSION['code'][$code]);
        
        // Create Experience object
        $experience = new DrivingExperience([
            'id' => $experienceId,
            'date' => $_POST['date'] ?? '',
            'departure_time' => $_POST['departure_time'] ?? '',
            'arrival_time' => $_POST['arrival_time'] ?? '',
            'km_covered' => floatval($_POST['km_covered'] ?? 0),
            'weather_id' => intval($_POST['weather_id'] ?? 0),
            'traffic_id' => intval($_POST['traffic_id'] ?? 0),
            'road_type_id' => intval($_POST['road_type_id'] ?? 0),
            'journey_type_id' => intval($_POST['journey_type_id'] ?? 0)
        ]);
        
        // Validation
        if (!$experience->isValid()) {
            $_SESSION['error'] = "Please fill all required fields correctly";
            header("Location: index.php?action=form&code=" . urlencode($code));
            exit();
        }
        
        // Validate time logic
        $dept_timestamp = strtotime($experience->date . ' ' . $experience->departure_time);
        $arrv_timestamp = strtotime($experience->date . ' ' . $experience->arrival_time);
        
        if ($arrv_timestamp <= $dept_timestamp) {
            $_SESSION['error'] = "Arrival time must be after departure time";
            header("Location: index.php?action=form&code=" . urlencode($code));
            exit();
        }
        
        // Validate maneuvers
        $maneuvers = $_POST['maneuvers'] ?? [];
        if (empty($maneuvers)) {
            $_SESSION['error'] = "Please select at least one maneuver";
            header("Location: index.php?action=form&code=" . urlencode($code));
            exit();
        }
        
        try {
            // Save experience
            $newId = $this->experienceRepo->save($experience, $maneuvers);
            
            $_SESSION['success'] = $experienceId == 0 ? 
                "Driving experience added successfully!" : 
                "Driving experience updated successfully!";
                
        } catch (Exception $e) {
            $_SESSION['error'] = "Error saving experience: " . $e->getMessage();
            header("Location: index.php?action=form&code=" . urlencode($code));
            exit();
        }
        
        header("Location: index.php");
        exit();
    }
    
    public function delete() {
        $code = $_GET['code'] ?? '';
        
        if (!$code || !isset($_SESSION['code'][$code])) {
            header("Location: index.php");
            exit();
        }
        
        $experienceId = intval($_SESSION['code'][$code]);
        
        if ($experienceId == 0) {
            header("Location: index.php");
            exit();
        }
        
        try {
            if ($this->experienceRepo->delete($experienceId)) {
                $_SESSION['success'] = "Driving experience deleted successfully!";
            } else {
                $_SESSION['error'] = "Error deleting experience";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        
        header("Location: index.php");
        exit();
    }
    
    public function statistics() {
        $totalKm = $this->experienceRepo->getTotalKilometers();
        $totalCount = $this->experienceRepo->getCount();
        $avgKm = $totalCount > 0 ? $totalKm / $totalCount : 0;
        
        // Get cumulative data for evolution chart
        $experiences = $this->experienceRepo->findAll();
        $evolutionData = [];
        $cumulativeKm = 0;
        
        foreach($experiences as $exp) {
            $cumulativeKm += $exp->km_covered;
            $evolutionData[] = [
                'date' => $exp->date,
                'cumulative' => round($cumulativeKm, 2)
            ];
        }
        
        // Get all statistics
        $stats = $this->experienceRepo->getStatistics();
        
        // Prepare chart data
        $chartData = [
            'weatherLabels' => array_column($stats['weather'], 'weather_condition'),
            'weatherData' => array_column($stats['weather'], 'count'),
            'trafficLabels' => array_column($stats['traffic'], 'traffic_condition'),
            'trafficData' => array_column($stats['traffic'], 'count'),
            'roadLabels' => array_column($stats['road'], 'road_type'),
            'roadData' => array_column($stats['road'], 'count'),
            'journeyLabels' => array_column($stats['journey'], 'journey_type'),
            'journeyData' => array_column($stats['journey'], 'count'),
            'evolutionData' => $evolutionData
        ];
        
        require_once __DIR__ . '/../views/statistics.php';
    }
}
?>
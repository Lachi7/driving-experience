<?php
// index.php - Main entry point

// Include necessary files
require_once 'includes/connectDB.inc.php';
require_once 'controllers/ExperienceController.php';

// Create controller instance
$controller = new ExperienceController();

// Get action from URL
$action = $_GET['action'] ?? 'index';

// Route to appropriate method
switch ($action) {
    case 'form':
        $controller->showForm();
        break;
        
    case 'save':
        $controller->save();
        break;
        
    case 'delete':
        $controller->delete();
        break;
        
    case 'statistics':
        $controller->statistics();
        break;
        
    case 'index':
    default:
        $controller->index();
        break;
}
?>
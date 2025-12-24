# 🚗 Supervised Driving Experience Tracker

A comprehensive web application for tracking and analyzing supervised driving sessions. Built with PHP, MySQL, and modern web technologies following MVC principles.

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-3.7-0769AD?logo=jquery&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-4.0-FF6384?logo=chartdotjs&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

## 📋 Table of Contents
- [✨ Features](#-features)
- [🌐 Live Demo](#-live-demo)
- [🛠️ Technology Stack](#️-technology-stack)
- [📁 Project Structure](#-project-structure)
- [🚀 Installation Guide](#-installation-guide)
- [🗄️ Database Setup](#️-database-setup)
- [⚙️ Configuration](#️-configuration)
- [📖 Usage Guide](#-usage-guide)
- [💻 Technical Highlights](#-technical-highlights)
- [🔒 Security Features](#-security-features)
- [⚡ Performance Optimizations](#-performance-optimizations)

## ✨ Features

### 📱 Responsive Design
- **Mobile-first approach** with adaptive layouts
- **Dual interface**: DataTables for desktop, cards for mobile
- **Touch-friendly** controls and navigation

### 📊 Data Management
- **Complete CRUD operations** (Create, Read, Update, Delete)
- **Dynamic form generation** with database-driven dropdowns
- **Real-time validation** (client-side + server-side)
- **Data anonymization** using session-based codes

### 📈 Analytics & Visualization
- **Interactive charts** with Chart.js (pie, bar, line, doughnut)
- **Comprehensive statistics** dashboard
- **Cumulative distance tracking** over time
- **Filterable data views** by weather, traffic, road type, etc.

### 🛡️ Security & Architecture
- **PDO prepared statements** with named parameters
- **Session-based security** with data anonymization
- **Repository pattern** for database abstraction

### 🔍 Enhanced UX
- **Smart search** with DataTables (desktop) and custom mobile search
- **Auto-complete** and date/time pickers with jQuery UI
- **Form validation** with instant feedback
- **Responsive alerts** and notifications

## 🛠️ Technology Stack

### Backend
- **PHP 7.4+** with PDO extensions
- **MySQL 8.0+** (or MariaDB 10.3+)
- **Object-Oriented Programming** with custom classes
- **MVC architecture** pattern

### Frontend
- **HTML5** with semantic markup
- **CSS3** with Flexbox and Grid layouts
- **JavaScript ES6+**
- **jQuery 3.7** with jQuery UI
- **DataTables 1.13** for advanced table features
- **Chart.js 4.0** for data visualization

### Design
- **Custom CSS** with gradient backgrounds
- **Oxanium Google Font** for typography
- **Responsive breakpoints** (mobile, tablet, desktop)
- **Print-friendly styles**

## 📁 Project Structure

```
driving_app/
├── index.php                 # Main entry point / router
├── README.md                 # This documentation
├── database_creation_query.sql   # Database schema
├── driving_experiences_queries.sql # Sample queries
│
├── controllers/
│   └── ExperienceController.php  # Main controller
│
├── models/                    # Data models
│   ├── DrivingExperience.php
│   ├── DrivingExperienceRepository.php
│   ├── LookupRepository.php
│   ├── WeatherCondition.php
│   ├── TrafficCondition.php
│   ├── RoadType.php
│   ├── JourneyType.php
│   └── Maneuver.php
│
├── views/                    # Presentation layer
│   ├── index.php            # Main listing view
│   ├── form.php             # Experience form
│   └── statistics.php       # Statistics dashboard
│
├── includes/                 # Core utilities
│   └── connectDB.inc.php    # Database connection
```

## 🚀 Installation Guide

### Prerequisites
- Web server (Apache, Nginx, or similar)
- PHP 7.4 or higher with PDO MySQL extension
- MySQL 8.0+ or MariaDB 10.3+
- Modern web browser

### Step-by-Step Installation

1. **Clone or download the project**
   ```bash
   git clone https://github.com/yourusername/driving-experience-tracker.git
   cd driving-experience-tracker
   ```

2. **Upload to your web server**
   - Upload all files to your server's web directory
   - Ensure proper file permissions (755 for directories, 644 for files)

3. **Configure database connection** (see [Configuration](#️-configuration))

4. **Import database schema** (see [Database Setup](#️-database-setup))

5. **Access the application**
   ```
   http://your-domain.com/driving_app/
   ```

## 🗄️ Database Setup

### 1. Create Database
```sql
CREATE DATABASE driving_experience_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

### 2. Import Schema
Run the SQL file included in the project:
```bash
mysql -u username -p driving_experience_db < database_creation_query.sql
```

### 3. Database Schema Overview
The application uses 8 related tables:

- **`driving_experience`** - Main experiences table
- **`weather_conditions`** - Weather options
- **`traffic_conditions`** - Traffic levels
- **`road_types`** - Road categories
- **`journey_types`** - Journey purposes
- **`maneuvers`** - Driving maneuvers
- **`driving_experiences_maneuvers`** - Many-to-many relationship
- **`users`** (optional) - For future authentication

### 4. Sample Data
Optional sample data is provided in `driving_experiences_queries.sql`

## ⚙️ Configuration

### Database Connection
Edit `includes/connectDB.inc.php`:

```php
class Database {
    private function __construct() {
        // Update these values with your database credentials
        $host = 'localhost';                 // Database host
        $dbname = 'driving_experience_db';   // Database name
        $user = 'your_username';            // Database username
        $pass = 'your_password';            // Database password
        
        // ... rest of the constructor
    }
}
```

## 📖 Usage Guide

### Adding a Driving Experience
1. Click **"Add New Driving Experience"**
2. Fill in the form with:
   - **Date** (auto-filled with today's date)
   - **Departure & Arrival times** (auto-filled with current time)
   - **Distance covered** in kilometers
   - **Weather conditions** (dropdown)
   - **Traffic conditions** (dropdown)
   - **Road type** (dropdown)
   - **Journey type** (dropdown)
   - **Maneuvers performed** (checkboxes - select at least one)
3. Click **"✓ Save Experience"**

### Viewing Experiences
- **Desktop**: Interactive table with sorting, filtering, and pagination
- **Mobile**: Card-based layout with search functionality
- **Actions**: Edit or delete any experience

### Statistics & Analytics
- **Overview**: Total sessions, distance, and averages
- **Charts**: Visual breakdown by weather, traffic, road, and journey types
- **Evolution**: Cumulative distance over time
- **Detailed tables**: Comprehensive statistics with counts and totals

### Mobile Features
- **Touch-optimized** form controls
- **Responsive cards** with all information
- **Integrated search** for mobile views
- **Collapsible sections** for better readability

## 💻 Technical Highlights

### PDO with Prepared Statements
```php
// Secure query execution with named parameters
$query = "INSERT INTO driving_experience VALUES (:date, :departure, ...)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':date', $date);
$stmt->bindValue(':departure', $departure_time);
$stmt->execute();
```

### Data Anonymization
```php
// Protect database IDs with session-based codes
$code = random_code(10);  // Generates: AbC123XyZ9
$_SESSION['code'][$code] = $experienceId;
// Use in URLs: experienceForm.php?code=AbC123XyZ9
```

### Responsive Design Pattern
```css
/* Mobile-first CSS with progressive enhancement */
.experience-card {
    display: block; /* Mobile default */
}

@media (min-width: 769px) {
    .desktop-table {
        display: block; /* Desktop enhancement */
    }
    .mobile-cards {
        display: none;
    }
}
```

## 🔒 Security Features

### 1. **SQL Injection Protection**
- PDO prepared statements with named parameters
- Explicit data type binding with `bindValue()`
- No direct variable interpolation in queries

### 2. **XSS Prevention**
- `htmlspecialchars()` on all user output
- Content Security Policy ready structure
- Sanitized user input before display

### 3. **Session Security**
- Data anonymization protects database IDs
- Session regeneration on sensitive actions
- Secure session configuration

### 4. **Form Security**
- CSRF protection through session tokens
- Client-side and server-side validation
- Input sanitization and type checking

### 5. **Database Security**
- Limited database user permissions
- Separate database user for application
- Regular parameterized queries

## ⚡ Performance Optimizations

### 1. **Database Optimization**
- Proper indexing on foreign keys
- Efficient JOIN queries
- Query result caching in repositories

### 2. **Frontend Optimization**
- Minified external libraries (CDN delivery)
- Efficient DOM manipulation with jQuery
- Lazy loading for chart data

### 3. **Caching Strategy**
- Browser caching for static resources
- Session-based data caching
- Optimized database queries

### 4. **Mobile Performance**
- Optimized images and icons
- Efficient CSS with minimal repaints
- JavaScript event delegation

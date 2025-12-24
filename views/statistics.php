<?php
require_once 'includes/connectDB.inc.php';
$pdo = Database::getInstance();


// Total kilometers
$query = "SELECT SUM(km_covered) AS total_km FROM driving_experience";
$result = $pdo->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);
$totalKm = floatval($row['total_km']);

// Total experiences
$query = "SELECT COUNT(*) AS total FROM driving_experience";
$result = $pdo->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);
$totalExperiences = intval($row['total']);

// Average km per experience
$avgKm = $totalExperiences > 0 ? $totalKm / $totalExperiences : 0;

// Cumulative KM over time for evolution graph
$query = "SELECT date, km_covered 
          FROM driving_experience 
          ORDER BY date ASC, departure_time ASC";
$evolutionResult = $pdo->query($query);
$evolutionData = array();
$cumulativeKm = 0;
while($row = $evolutionResult->fetch(PDO::FETCH_ASSOC)) {
    $cumulativeKm += floatval($row['km_covered']);
    $evolutionData[] = array(
        'date' => $row['date'],
        'cumulative' => round($cumulativeKm, 2)
    );
}

// Statistics by weather
$query = "SELECT wc.weather_condition, 
          COUNT(de.driving_experience_id) AS count,
          SUM(de.km_covered) AS total_km
          FROM weather_conditions wc
          LEFT JOIN driving_experience de ON wc.weather_id = de.weather_id
          GROUP BY wc.weather_id, wc.weather_condition
          ORDER BY count DESC";
$weatherStats = $pdo->query($query);

// Statistics by traffic
$query = "SELECT tc.traffic_condition, 
          COUNT(de.driving_experience_id) AS count,
          SUM(de.km_covered) AS total_km
          FROM traffic_conditions tc
          LEFT JOIN driving_experience de ON tc.traffic_id = de.traffic_id
          GROUP BY tc.traffic_id, tc.traffic_condition
          ORDER BY count DESC";
$trafficStats = $pdo->query($query);

// Statistics by road type
$query = "SELECT rt.road_type, 
          COUNT(de.driving_experience_id) AS count,
          SUM(de.km_covered) AS total_km
          FROM road_types rt
          LEFT JOIN driving_experience de ON rt.road_type_id = de.road_type_id
          GROUP BY rt.road_type_id, rt.road_type
          ORDER BY count DESC";
$roadStats = $pdo->query($query);

// Statistics by journey type
$query = "SELECT jt.journey_type, 
          COUNT(de.driving_experience_id) AS count,
          SUM(de.km_covered) AS total_km
          FROM journey_types jt
          LEFT JOIN driving_experience de ON jt.journey_type_id = de.journey_type_id
          GROUP BY jt.journey_type_id, jt.journey_type
          ORDER BY count DESC";
$journeyStats = $pdo->query($query);

// Most common maneuvers
$query = "SELECT m.maneuver, COUNT(*) AS count
          FROM maneuvers m
          JOIN driving_experiences_maneuvers dem ON m.maneuver_id = dem.maneuver_id
          GROUP BY m.maneuver_id, m.maneuver
          ORDER BY count DESC";
$maneuverStats = $pdo->query($query);

// Prepare data for charts
$weatherLabels = array();
$weatherData = array();
while($row = $weatherStats->fetch(PDO::FETCH_ASSOC)) {
    $weatherLabels[] = $row['weather_condition'];
    $weatherData[] = $row['count'];
}

// Reset pointer for PDO
$weatherStats = $pdo->query("SELECT wc.weather_condition, 
          COUNT(de.driving_experience_id) AS count,
          SUM(de.km_covered) AS total_km
          FROM weather_conditions wc
          LEFT JOIN driving_experience de ON wc.weather_id = de.weather_id
          GROUP BY wc.weather_id, wc.weather_condition
          ORDER BY count DESC");

$trafficLabels = array();
$trafficData = array();
while($row = $trafficStats->fetch(PDO::FETCH_ASSOC)) {
    $trafficLabels[] = $row['traffic_condition'];
    $trafficData[] = $row['count'];
}

$trafficStats = $pdo->query("SELECT tc.traffic_condition, 
          COUNT(de.driving_experience_id) AS count,
          SUM(de.km_covered) AS total_km
          FROM traffic_conditions tc
          LEFT JOIN driving_experience de ON tc.traffic_id = de.traffic_id
          GROUP BY tc.traffic_id, tc.traffic_condition
          ORDER BY count DESC");

$roadLabels = array();
$roadData = array();
while($row = $roadStats->fetch(PDO::FETCH_ASSOC)) {
    $roadLabels[] = $row['road_type'];
    $roadData[] = $row['count'];
}

$roadStats = $pdo->query("SELECT rt.road_type, 
          COUNT(de.driving_experience_id) AS count,
          SUM(de.km_covered) AS total_km
          FROM road_types rt
          LEFT JOIN driving_experience de ON rt.road_type_id = de.road_type_id
          GROUP BY rt.road_type_id, rt.road_type
          ORDER BY count DESC");

$journeyLabels = array();
$journeyData = array();
while($row = $journeyStats->fetch(PDO::FETCH_ASSOC)) {
    $journeyLabels[] = $row['journey_type'];
    $journeyData[] = $row['count'];
}

$journeyStats = $pdo->query("SELECT jt.journey_type, 
          COUNT(de.driving_experience_id) AS count,
          SUM(de.km_covered) AS total_km
          FROM journey_types jt
          LEFT JOIN driving_experience de ON jt.journey_type_id = de.journey_type_id
          GROUP BY jt.journey_type_id, jt.journey_type
          ORDER BY count DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driving Statistics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@200..800&display=swap" rel="stylesheet">
    
    <!-- jQuery and jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Oxanium", sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: linear-gradient(55deg, rgba(55,16,120,1) 0%, rgba(157,66,153,1) 83%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-card h2 {
            font-size: 3em;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        /* Evolution Chart */
        .evolution-container {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            margin-bottom: 40px;
            border: 3px solid #667eea;
            min-height: 400px;
        }
        
        .evolution-container h2 {
            color: #667eea;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2em;
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        .charts-grid.pies {
            grid-template-columns: repeat(2, 1fr);
        }

        .charts-grid.bars {
            grid-template-columns: repeat(2, 1fr);
        }
        .chart-container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-height: 400px;
            position: relative;
            height: 400px;
        }
        .chart-container canvas {
            max-height: 300px !important;
            height: 300px !important;
            width: 100% !important;
        }
        .chart-container h3 {
            color: #667eea;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .stats-table {
            margin-top: 40px;
        }
        
        .stats-table h2 {
            color: #667eea;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #666;
        }
        @media (max-width: 900px) {
            .charts-grid.pies,
            .charts-grid.bars {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 768px) {
            .stats-overview {
                grid-template-columns: 1fr;
            }
            .stat-card h2 {
                font-size: 2em;
            }
            .stat-card {
                padding: 15px;
            }
            .charts-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .chart-container {
                min-height: 300px;
                height: 300px;
                padding: 15px;
                font-size: 12px;
            }
            .chart-container canvas {
                max-height: 250px !important;
                height: 250px !important;
            }
            .container {
                padding: 20px;
            }
            .evolution-container {
                padding: 15px;
                min-height: 300px;
            }
            h1 {
                font-size: 1.8em;
            }
            
            .evolution-container h2 {
                font-size: 1.5em;
            }
            
            .chart-container h3 {
                font-size: 1.2em;
            }
            canvas {
        font-size: 11px !important;
    }
        }
    </style>
</head>
<body>
<main>

    <div class="container">
        <a href="index.php" class="back-link">← Back to Experiences</a>
        
        <header>
            <h1>Driving Statistics</h1>
            <p>Comprehensive overview of your driving experience</p>
        </header>
        
        <div class="stats-overview">
            <div class="stat-card">
                <h2><?php echo $totalExperiences; ?></h2>
                <p>Total Experiences</p>
            </div>
            <div class="stat-card">
                <h2><?php echo number_format($totalKm, 1); ?> km</h2>
                <p>Total Distance</p>
            </div>
            <div class="stat-card">
                <h2><?php echo number_format($avgKm, 1); ?> km</h2>
                <p>Average per Session</p>
            </div>
        </div>
        
        <!-- Featured: Total KM Evolution Chart -->
        <div class="evolution-container">
            <h2>Total Kilometers Evolution Over Time</h2>
            <canvas id="evolutionChart"></canvas>
        </div>
        <!-- PIE CHART ROW -->
        <div class="charts-grid pies">
            <div class="chart-container">
                <h3>Experiences by Weather</h3>
                <canvas id="weatherChart"></canvas>
            </div>

            <div class="chart-container">
                <h3>Experiences by Traffic</h3>
                <canvas id="trafficChart"></canvas>
            </div>
        </div>

        <!-- BAR CHART ROW -->
        <div class="charts-grid bars">
            <div class="chart-container">
                <h3>Experiences by Road Type</h3>
                <canvas id="roadChart"></canvas>
            </div>

            <div class="chart-container">
                <h3>Experiences by Journey Type</h3>
                <canvas id="journeyChart"></canvas>
            </div>
        </div>

        
        <div class="stats-table">
            <h2>Detailed Statistics</h2>
            
            <h3>Weather Conditions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Weather</th>
                        <th>Count</th>
                        <th>Total Distance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $weatherStats->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['weather_condition']); ?></td>
                        <td><?php echo $row['count']; ?></td>
                        <td><?php echo number_format($row['total_km'], 1); ?> km</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <h3>Traffic Conditions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Traffic</th>
                        <th>Count</th>
                        <th>Total Distance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $trafficStats->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['traffic_condition']); ?></td>
                        <td><?php echo $row['count']; ?></td>
                        <td><?php echo number_format($row['total_km'], 1); ?> km</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <h3>Most Common Maneuvers</h3>
            <table>
                <thead>
                    <tr>
                        <th>Maneuver</th>
                        <th>Times Performed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $maneuverStats->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['maneuver']); ?></td>
                        <td><?php echo $row['count']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <footer>
            <p>&copy; 2025 Supervised Driving Experience Tracker. All rights reserved.</p>
    </footer>
    </div>

    </main>
   
    <script>
        // Chart.js configurations
        const chartColors = [
            '#667eea', '#764ba2', '#f093fb', '#4facfe',
            '#43e97b', '#fa709a', '#fee140', '#30cfd0'
        ];
        
        // Evolution Chart - Line Chart showing cumulative KM
        const evolutionData = <?php echo json_encode($evolutionData); ?>;
        const evolutionLabels = evolutionData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' });
        });
        const evolutionValues = evolutionData.map(item => item.cumulative);
        
        new Chart(document.getElementById('evolutionChart'), {
            type: 'line',
            data: {
                labels: evolutionLabels,
                datasets: [{
                    label: 'Cumulative Kilometers',
                    data: evolutionValues,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                family: 'Oxanium'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Total: ' + context.parsed.y + ' km';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Kilometers',
                            font: {
                                size: 14,
                                family: 'Oxanium'
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                            font: {
                                size: 14,
                                family: 'Oxanium'
                            }
                        }
                    }
                }
            }
        });
        
        // Weather Chart
        new Chart(document.getElementById('weatherChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($weatherLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($weatherData); ?>,
                    backgroundColor: chartColors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
        
        // Traffic Chart
        new Chart(document.getElementById('trafficChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($trafficLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($trafficData); ?>,
                    backgroundColor: chartColors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
        
        // Road Type Chart
        new Chart(document.getElementById('roadChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($roadLabels); ?>,
                datasets: [{
                    label: 'Experiences',
                    data: <?php echo json_encode($roadData); ?>,
                    backgroundColor: '#667eea'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
        
        // Journey Type Chart
        new Chart(document.getElementById('journeyChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($journeyLabels); ?>,
                datasets: [{
                    label: 'Experiences',
                    data: <?php echo json_encode($journeyData); ?>,
                    backgroundColor: '#764ba2'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
        $(document).ready(function() {
            // Auto-fade alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(1000, function() {
                $(this).remove();
            });
        });
    </script>

</body>
</html>
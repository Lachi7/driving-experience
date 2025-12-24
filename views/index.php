<?php
// views/index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervised Driving Experience</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@200..800&display=swap" rel="stylesheet">
    
    <!-- jQuery and jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #666;
            font-size: 1.1em;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .nav-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            background: linear-gradient(55deg, rgba(55,16,120,1) 0%, rgba(157,66,153,1) 83%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            border: none;
            cursor: pointer;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .stats-card h2 {
            font-size: 3em;
            margin-bottom: 5px;
        }
        
        .stats-card p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        /* Desktop table styles */
        .desktop-table {
            display: block;
        }
        
        .mobile-cards {
            display: none;
        }
        
        table.dataTable {
            width: 100% !important;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table.dataTable th, 
        table.dataTable td {
            padding: 15px 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            font-size: 1em;
        }
        
        table.dataTable th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        
        table.dataTable tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .action-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-links a {
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            transition: all 0.3s;
            font-weight: 600;
            min-height: 36px;
            display: inline-flex;
            align-items: center;
        }
        
        .edit-link {
            background-color: #4CAF50;
            color: white;
        }
        
        .edit-link:hover {
            background-color: #45a049;
        }
        
        .delete-link {
            background-color: #f44336;
            color: white;
        }
        
        .delete-link:hover {
            background-color: #da190b;
        }
        
        /* Mobile card styles */
        .experience-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .experience-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 2px solid #667eea;
        }
        
        .experience-card .card-date {
            font-weight: 700;
            color: #667eea;
            font-size: 1.1em;
        }
        
        .experience-card .card-km {
            background: #667eea;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: 600;
        }
        
        .experience-card .card-body {
            display: grid;
            gap: 10px;
            margin-bottom: 12px;
        }
        
        .experience-card .card-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        
        .experience-card .card-label {
            font-weight: 600;
            color: #666;
            min-width: 80px;
        }
        
        .experience-card .card-value {
            color: #333;
            text-align: right;
            flex: 1;
        }
        
        .experience-card .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }
        
        .experience-card .card-actions a {
            flex: 1;
            text-align: center;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-state h2 {
            font-size: 2em;
            margin-bottom: 15px;
            color: #999;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        
        .alert-error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        
        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #666;
        }
        
        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #667eea;
            border-radius: 5px;
            padding: 8px 12px;
            font-family: "Oxanium", sans-serif;
            font-size: 1em;
            margin-left: 8px;
        }
        
        .dataTables_wrapper .dataTables_length select {
            border: 2px solid #667eea;
            border-radius: 5px;
            padding: 6px 10px;
            font-family: "Oxanium", sans-serif;
            font-size: 1em;
            margin: 0 8px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 6px 12px;
            margin: 0 2px;
            border-radius: 4px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #667eea;
            color: white !important;
        }
        
        /* Print styles */
        @media print {
            nav, .back-link, .btn, .action-links, 
            .dataTables_filter, .dataTables_length, 
            .dataTables_info, .dataTables_paginate {
                display: none !important;
            }
            
            body {
                background: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                border-radius: 0;
            }
            
            table {
                page-break-inside: avoid;
            }
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            h1 {
                font-size: 1.8em;
            }
            
            nav {
                flex-direction: column;
            }
            
            .nav-links {
                width: 100%;
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                min-height: 48px;
            }
            
            .stats-card h2 {
                font-size: 2.5em;
            }
            
            /* Hide desktop table, show mobile cards */
            .desktop-table {
                display: none;
            }
            
            .mobile-cards {
                display: block;
            }
            
            .action-links {
                width: 100%;
            }
            
            .action-links a {
                flex: 1;
                justify-content: center;
                min-height: 44px;
            }
            .container {
                overflow-x: visible;
            }
            
            table.dataTable {
                min-width: auto;
            }
        }
        @media (max-width: 990px) {
            .container {
                overflow-x: auto;
            }
            
            .dataTables_wrapper {
                overflow-x: auto;
            }
            
            table.dataTable {
                min-width: 800px;
            }
        }       
</style>
</head>
<body>
<main>
    <div class="container">
        <header>
            <h1>Supervised Driving Experience</h1>
            <p class="subtitle">Track and manage your driving sessions</p>
        </header>
        
        <nav>
            <div class="nav-links">
                <a href="index.php?action=form&code=<?php echo htmlspecialchars($newExpCode); ?>" class="btn" title="Add a new driving experience">
                    Add New Driving Experience
                </a>
                <a href="index.php?action=statistics" class="btn btn-secondary" title="View detailed statistics and charts">
                    View Statistics
                </a>
            </div>
        </nav>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-error" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(count($experiences) > 0): ?>
            <div class="stats-card">
                <h2><?php echo number_format($totalKm, 2); ?> km</h2>
                <p>Total Distance Covered in <?php echo $totalCount; ?> Sessions</p>
            </div>
            
            <!-- Desktop Table with DataTables -->
            <div class="desktop-table">
                <table id="experiencesTable" class="display">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Duration</th>
                            <th>Distance</th>
                            <th>Weather</th>
                            <th>Traffic</th>
                            <th>Road Type</th>
                            <th>Journey</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($experiences as $experience): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($experience->date)); ?></td>
                                <td><?php echo htmlspecialchars($experience->departure_time); ?> - <?php echo htmlspecialchars($experience->arrival_time); ?></td>
                                <td><?php echo $experience->calculateDuration(); ?></td>
                                <td><?php echo htmlspecialchars($experience->km_covered); ?> km</td>
                                <td><?php echo htmlspecialchars($experience->weather_condition); ?></td>
                                <td><?php echo htmlspecialchars($experience->traffic_condition); ?></td>
                                <td><?php echo htmlspecialchars($experience->road_type); ?></td>
                                <td><?php echo htmlspecialchars($experience->journey_type); ?></td>
                                <td class='action-links'>
                                    <a href='index.php?action=form&code=<?php echo htmlspecialchars($codes[$experience->id]); ?>' class='edit-link' title='Edit this experience'>Edit</a>
                                    <a href='index.php?action=delete&code=<?php echo htmlspecialchars($codes[$experience->id]); ?>' class='delete-link' onclick='return confirm("Are you sure you want to delete this experience?")' title='Delete this experience'>Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- search for mobile cards -->
            <div class="mobile-search-container" style="display: none; margin-bottom: 20px;">
                <input type="text" id="mobileSearch" placeholder="Search experiences..." 
                    style="width: 100%; padding: 12px; border: 2px solid #667eea; border-radius: 8px;">
            </div>
            <!-- Mobile Cards -->
            <!-- Mobile Cards -->
            <div class="mobile-cards">
            <?php foreach($experiences as $experience): ?>
                <div class="experience-card" 
                    data-search="<?php echo htmlspecialchars(strtolower(
                        date('d/m/Y', strtotime($experience->date)) . ' ' .
                        $experience->departure_time . ' ' .
                        $experience->arrival_time . ' ' .
                        $experience->km_covered . 'km ' .
                        $experience->weather_condition . ' ' .
                        $experience->traffic_condition . ' ' .
                        $experience->road_type . ' ' .
                        $experience->journey_type
                    )); ?>">
                    <div class="card-header">
                        <span class="card-date"><?php echo date('d/m/Y', strtotime($experience->date)); ?></span>
                        <span class="card-km"><?php echo htmlspecialchars($experience->km_covered); ?> km</span>
                    </div>
                    <div class="card-body">
                        <div class="card-row">
                            <span class="card-label">Time:</span>
                            <span class="card-value"><?php echo htmlspecialchars($experience->departure_time); ?> - <?php echo htmlspecialchars($experience->arrival_time); ?></span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">Duration:</span>
                            <span class="card-value"><?php echo $experience->calculateDuration(); ?></span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">Weather:</span>
                            <span class="card-value"><?php echo htmlspecialchars($experience->weather_condition); ?></span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">Traffic:</span>
                            <span class="card-value"><?php echo htmlspecialchars($experience->traffic_condition); ?></span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">Road:</span>
                            <span class="card-value"><?php echo htmlspecialchars($experience->road_type); ?></span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">Journey:</span>
                            <span class="card-value"><?php echo htmlspecialchars($experience->journey_type); ?></span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="index.php?action=form&code=<?php echo htmlspecialchars($codes[$experience->id]); ?>" class="edit-link">Edit</a>
                        <a href="index.php?action=delete&code=<?php echo htmlspecialchars($codes[$experience->id]); ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this experience?')">Delete</a>
                    </div>
                </div>
    <?php endforeach; ?>
</div>
        <?php else: ?>
            <div class="empty-state">
                <h2>No Driving Experiences Yet</h2>
                <p>Start tracking your supervised driving by adding your first experience!</p>
            </div>
        <?php endif; ?>
        
        <footer>
            <p>&copy; 2025 Supervised Driving Experience Tracker. All rights reserved.</p>
        </footer>
    </div>
</main>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#experiencesTable').DataTable({
            "pageLength": 10,
            "order": [[0, "desc"]],
            "language": {
                "search": "Search experiences:",
                "lengthMenu": "Show _MENU_ experiences per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ experiences",
                "infoEmpty": "No experiences to show",
                "infoFiltered": "(filtered from _MAX_ total experiences)",
                "zeroRecords": "No matching experiences found",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": 8 }
            ]
            
        });
        
        $('.alert').delay(5000).fadeOut(1000, function() {
            $(this).remove();
        }); 
        // Show/hide search based on screen size
        function toggleSearch() {
            if ($(window).width() <= 768) {
                $('.mobile-search-container').show();
                $('.dataTables_filter').hide();
            } else {
                $('.mobile-search-container').hide();
                $('.dataTables_filter').show();
            }
        }

        // Initial check
        toggleSearch();
        $(window).resize(toggleSearch);

        // Mobile search functionality
        $('#mobileSearch').on('keyup', function() {
            var search = $(this).val().toLowerCase();
            $('.experience-card').each(function() {
                var text = $(this).data('search');
                $(this).toggle(text.indexOf(search) > -1);
            });
        });
    });
</script>
</body>
</html>
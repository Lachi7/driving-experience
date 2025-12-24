<?php
// views/form.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $experienceId == 0 ? 'New' : 'Edit'; ?> Driving Experience</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@200..800&display=swap" rel="stylesheet">
    
    <!-- jQuery and jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
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
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        form {
            display: grid;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 1.1em;
        }
        
        input[type="date"],
        input[type="time"],
        input[type="number"],
        select {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            font-family: "Oxanium", sans-serif;
            transition: border-color 0.3s;
        }
        
        input:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .maneuver-group {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
        }
        
        .maneuver-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        fieldset {
            border: none;
            padding: 0;
            margin: 0;
        }

        legend {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 1.1em;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .checkbox-item label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
            font-size: 1em;
        }
        
        button {
            padding: 15px 30px;
            background: linear-gradient(55deg, rgba(55,16,120,1) 0%, rgba(157,66,153,1) 83%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .required {
            color: red;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #666;
        }
        /* Mobile responsive */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .maneuver-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <script>
        $(document).ready(function() {
            <?php if($experienceId == 0): ?>
            // Set defaults for new experiences
            if(!$('#date').val()) {
                const today = new Date().toISOString().split('T')[0];
                $('#date').val(today);
            }
            
            if(!$('#departure_time').val()) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                $('#departure_time').val(hours + ':' + minutes);
            }
            <?php endif; ?>
            
            // jQuery UI Datepicker
            $('#date').datepicker({
                dateFormat: 'yy-mm-dd',
                maxDate: 0,
                changeMonth: true,
                changeYear: true
            });
            
            $('.alert').delay(5000).fadeOut(1000, function() {
                $(this).remove();
            });
        });
        
        function validateForm() {
            const date = $('#date').val();
            const departure = $('#departure_time').val();
            const arrival = $('#arrival_time').val();
            
            if(date && departure && arrival) {
                const departureFull = new Date(date + 'T' + departure);
                const arrivalFull = new Date(date + 'T' + arrival);
                
                if(arrivalFull <= departureFull) {
                    alert('Arrival time must be after departure time!');
                    return false;
                }
            }
            
            // Maneuver validation
            const checkboxes = document.querySelectorAll('input[name="maneuvers[]"]');
            let atLeastOneChecked = false;
            
            checkboxes.forEach(checkbox => {
                if(checkbox.checked) atLeastOneChecked = true;
            });
            
            if(!atLeastOneChecked) {
                alert('Please select at least one maneuver!');
                return false;
            }
            
            return true;
        }
    </script>
</head>
<body>
<main>
    <div class="container">
        <a href="index.php" class="back-link">← Back to List</a>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?> 
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <header>
            <h1><?php echo $experienceId == 0 ? 'New' : '✏️ Edit'; ?> Driving Experience</h1>
        </header>
        
        <form action="index.php?action=save" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="code" value="<?php echo htmlspecialchars($_GET['code']); ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Date <span class="required">*</span></label>
                    <input type="date" id="date" name="date" required 
                           value="<?php echo $experience ? htmlspecialchars($experience->date) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="km_covered">Distance (km) <span class="required">*</span></label>
                    <input type="number" id="km_covered" name="km_covered" 
                           min="0" step="0.1" required placeholder="e.g., 15.5"
                           value="<?php echo $experience ? htmlspecialchars($experience->km_covered) : ''; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="departure_time">Departure Time <span class="required">*</span></label>
                    <input type="time" id="departure_time" name="departure_time" required
                           value="<?php echo $experience ? htmlspecialchars($experience->departure_time) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="arrival_time">Arrival Time <span class="required">*</span></label>
                    <input type="time" id="arrival_time" name="arrival_time" required
                           value="<?php echo $experience ? htmlspecialchars($experience->arrival_time) : ''; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="weather_id">Weather Condition <span class="required">*</span></label>
                    <select id="weather_id" name="weather_id" required>
                        <option value="">-- Choose Weather --</option>
                        <?php foreach($lookupData['weather'] as $weather): ?>
                            <option value="<?php echo $weather->id; ?>" 
                                <?php echo ($experience && $experience->weather_id == $weather->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($weather->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="traffic_id">Traffic Condition <span class="required">*</span></label>
                    <select id="traffic_id" name="traffic_id" required>
                        <option value="">-- Choose Traffic --</option>
                        <?php foreach($lookupData['traffic'] as $traffic): ?>
                            <option value="<?php echo $traffic->id; ?>"
                                <?php echo ($experience && $experience->traffic_id == $traffic->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($traffic->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="road_type_id">Road Type <span class="required">*</span></label>
                    <select id="road_type_id" name="road_type_id" required>
                        <option value="">-- Choose Road Type --</option>
                        <?php foreach($lookupData['road'] as $road): ?>
                            <option value="<?php echo $road->id; ?>"
                                <?php echo ($experience && $experience->road_type_id == $road->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($road->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="journey_type_id">Journey Type <span class="required">*</span></label>
                    <select id="journey_type_id" name="journey_type_id" required>
                        <option value="">-- Choose Journey --</option>
                        <?php foreach($lookupData['journey'] as $journey): ?>
                            <option value="<?php echo $journey->id; ?>"
                                <?php echo ($experience && $experience->journey_type_id == $journey->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($journey->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <fieldset class="form-group maneuver-group">
            <legend>
                Maneuvers Performed <span class="required">*</span>
            </legend>
            <div class="maneuver-grid">
                <?php foreach($lookupData['maneuvers'] as $maneuver): ?>
                    <div class="checkbox-item">
                        <input
                            type="checkbox"
                            id="maneuver_<?php echo $maneuver->id; ?>"
                            name="maneuvers[]"
                            value="<?php echo $maneuver->id; ?>"
                            <?php echo ($experience && in_array($maneuver->id, $experience->maneuvers)) ? 'checked' : ''; ?>
                        >
                        <label for="maneuver_<?php echo $maneuver->id; ?>">
                            <?php echo htmlspecialchars($maneuver->name); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </fieldset>
            <button type="submit">
                <?php echo $experienceId == 0 ? '✓ Save Experience' : '✓ Update Experience'; ?>
            </button>
        </form>
        <footer>
            <p>&copy; 2025 Supervised Driving Experience Tracker. All rights reserved.</p>
        </footer>
    </div>
</main>
</body>
</html>
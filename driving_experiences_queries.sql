-- 1. Query for Total Distance Covered (in Kilometers)
SELECT SUM(km_covered) AS total_distance_covered
FROM driving_experience;

-- 2. Query for Number of Driving Experiences Per Weather Condition
SELECT w.weather_condition, COUNT(de.driving_experience_id) AS num_experiences
FROM weather_conditions w
LEFT JOIN driving_experience de ON w.weather_id = de.weather_id
GROUP BY w.weather_condition;

-- 3. Query for Number of Driving Experiences by Journey Type
SELECT jt.journey_type, COUNT(de.driving_experience_id) AS num_experiences
FROM journey_types jt
LEFT JOIN driving_experience de ON jt.journey_type_id = de.journey_type_id
GROUP BY jt.journey_type;

-- 4. Query for Driving Experience Count by Road Type (e.g., Paved vs. Gravel)
SELECT r.road_type, COUNT(de.driving_experience_id) AS num_experiences
FROM road_types r
LEFT JOIN driving_experience de ON r.road_type_id = de.road_type_id
GROUP BY r.road_type;

-- 5. Query for Experiences in Specific Traffic Conditions (e.g., Heavy Traffic)
SELECT COUNT(driving_experience_id) AS num_heavy_traffic_experiences
FROM driving_experience
WHERE traffic_id = (SELECT traffic_id FROM traffic_conditions WHERE traffic_id = 3);

-- 6. Query for Total Number of Maneuvers Per Driving Experience
SELECT de.driving_experience_id, COUNT(dem.maneuver_id) AS num_maneuvers
FROM driving_experience de
JOIN driving_experiences_maneuvers dem ON de.driving_experience_id = dem.driving_experience_id
GROUP BY de.driving_experience_id;

-- 7. Query for Driving Experience Duration (Arrival Time - Departure Time)
SELECT driving_experience_id, 
       TIMEDIFF(arrival_time, departure_time) AS experience_duration
FROM driving_experience;

-- 8. Query for Average Kilometers Driven Per Driving Experience
SELECT ROUND(AVG(km_covered), 2) AS avg_km
FROM driving_experience;

-- 9. Query for Average Kilometers Driven Per Weather Condition
SELECT w.weather_condition, ROUND(AVG(de.km_covered), 2) AS avg_km
FROM weather_conditions w
LEFT JOIN driving_experience de ON w.weather_id = de.weather_id
GROUP BY w.weather_condition;

-- 10. Query for Journey Types Not Yet Used
SELECT jt.journey_type
FROM journey_types jt
LEFT JOIN driving_experience de ON jt.journey_type_id = de.journey_type_id
WHERE de.driving_experience_id IS NULL;
-- PROGRAM OUTPUTS NOTHING AS ALL JOURNEY TYPES WERE USED

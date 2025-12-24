CREATE TABLE road_types
(
  road_type_id TINYINT AUTO_INCREMENT NOT NULL,
  road_type VARCHAR(50) NOT NULL,
  PRIMARY KEY (road_type_id)
);

CREATE TABLE journey_types
(
  journey_type_id TINYINT AUTO_INCREMENT NOT NULL,
  journey_type VARCHAR(50) NOT NULL,
  PRIMARY KEY (journey_type_id)
);

CREATE TABLE maneuvers
(
  maneuver_id TINYINT AUTO_INCREMENT NOT NULL,
  maneuver VARCHAR(50) NOT NULL,
  PRIMARY KEY (maneuver_id)
);

CREATE TABLE traffic_conditions
(
  traffic_id TINYINT AUTO_INCREMENT NOT NULL,
  traffic_condition VARCHAR(50) NOT NULL,
  PRIMARY KEY (traffic_id)
);

CREATE TABLE weather_conditions
(
  weather_id TINYINT AUTO_INCREMENT NOT NULL,
  weather_condition VARCHAR(50) NOT NULL,
  PRIMARY KEY (weather_id)
);

CREATE TABLE driving_experience
(
  driving_experience_id INT AUTO_INCREMENT NOT NULL,
  date DATE NOT NULL,
  departure_time TIME NOT NULL,
  arrival_time TIME NOT NULL,
  km_covered FLOAT NOT NULL,
  journey_type_id TINYINT NOT NULL,
  traffic_id TINYINT NOT NULL,
  road_type_id TINYINT NOT NULL,
  weather_id TINYINT NOT NULL,
  PRIMARY KEY (driving_experience_id),
  FOREIGN KEY (journey_type_id) REFERENCES journey_types(journey_type_id),
  FOREIGN KEY (traffic_id) REFERENCES traffic_conditions(traffic_id),
  FOREIGN KEY (road_type_id) REFERENCES road_types(road_type_id),
  FOREIGN KEY (weather_id) REFERENCES weather_conditions(weather_id)
);

CREATE TABLE driving_experiences_maneuvers
(
  driving_experience_id INT NOT NULL,
  maneuver_id TINYINT NOT NULL,
  PRIMARY KEY (driving_experience_id, maneuver_id),
  FOREIGN KEY (driving_experience_id) REFERENCES driving_experience(driving_experience_id),
  FOREIGN KEY (maneuver_id) REFERENCES maneuvers(maneuver_id),
  UNIQUE (driving_experience_id, maneuver_id)
);
-- Insert Data into weather_conditions
INSERT INTO weather_conditions (weather_condition)
VALUES
    ('Sunny'),
    ('Rainy'),
    ('Snowy'),
    ('Windy'),
    ('Foggy'),
    ('Stormy'),
    ('Cloudy'),
    ('Partly Cloudy');

-- Insert Data into maneuvers
INSERT INTO maneuvers (maneuver)
VALUES
    ('Parallel Parking'),
    ('Three-Point Turn'),
    ('Lane Change'),
    ('Reversing'),
    ('Emergency Stop');

-- Insert Data into road_types
INSERT INTO road_types (road_type)
VALUES
    ('Paved Roads'),
    ('Gravel Roads'),
    ('Unpaved Roads'),
    ('Highways'),
    ('Off-road');

-- Insert Data into traffic_conditions
INSERT INTO traffic_conditions (traffic_condition)
VALUES
    ('Light Traffic'),
    ('Moderate Traffic'),
    ('Heavy Traffic'),
    ('Standstill');

-- Insert Data into journey_types
INSERT INTO journey_types (journey_type)
VALUES
    ('City Driving'),
    ('Countryside Driving'),
    ('Mountain Driving'),
    ('Highway Driving');
-- Insert Data into driving_experience
INSERT INTO driving_experience (driving_experience_id, date, departure_time, arrival_time, km_covered, journey_type_id, traffic_id, road_type_id, weather_id)
VALUES
    (NULL, '2025-05-10', '08:30:00', '09:15:00', 15, 1, 1, 1, 1),  -- Example 1
    (NULL, '2025-05-11', '14:00:00', '14:45:00', 25, 4, 2, 2, 2),  -- Example 2
    (NULL, '2025-05-12', '07:45:00', '08:30:00', 40, 3, 3, 4, 3),  -- Example 3
    (NULL, '2025-05-13', '18:00:00', '19:00:00', 5, 2, 4, 3, 4),   -- Example 4
    (NULL, '2025-05-14', '10:30:00', '11:10:00', 30, 1, 1, 5, 5),  -- Example 5
    (NULL, '2025-05-15', '22:00:00', '22:50:00', 60, 4, 2, 4, 6),  -- Example 6
    (NULL, '2025-05-16', '09:00:00', '09:45:00', 35, 3, 3, 2, 7),  -- Example 7
    (NULL, '2025-05-17', '12:30:00', '13:15:00', 50, 2, 1, 4, 8),  -- Example 8
    (NULL, '2025-05-18', '06:30:00', '07:00:00', 20, 1, 4, 1, 1),  -- Example 9
    (NULL, '2025-05-19', '16:45:00', '17:30:00', 10, 4, 2, 3, 2);  -- Example 10
-- Insert Maneuvers for Driving Experiences
INSERT INTO driving_experiences_maneuvers (driving_experience_id, maneuver_id)
VALUES
    (1, 1),  -- Example 1: Parallel Parking (Maneuver ID 1)
    (2, 3),  -- Example 2: Lane Change (Maneuver ID 3)
    (3, 5),  -- Example 3: Emergency Stop (Maneuver ID 5)
    (4, 2),  -- Example 4: Three-Point Turn (Maneuver ID 2)
    (5, 4),  -- Example 5: Reversing (Maneuver ID 4)
    (6, 1),  -- Example 6: Parallel Parking (Maneuver ID 1)
    (7, 3),  -- Example 7: Lane Change (Maneuver ID 3)
    (8, 5),  -- Example 8: Emergency Stop (Maneuver ID 5)
    (9, 2),  -- Example 9: Three-Point Turn (Maneuver ID 2)
    (10, 4); -- Example 10: Reversing (Maneuver ID 4)


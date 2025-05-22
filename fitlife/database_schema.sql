CREATE DATABASE IF NOT EXISTS fitness_db;
USE fitness_db;

-- Users Table: Stores information about all users (clients, trainers, admins)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- Store hashed passwords
    email VARCHAR(100) NOT NULL UNIQUE,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    role ENUM('client', 'trainer', 'admin') NOT NULL DEFAULT 'client',
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    phone_number VARCHAR(20),
    address TEXT,
    profile_picture_url VARCHAR(255) DEFAULT 'assets/images/default_profile.png',
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE, -- To deactivate accounts instead of deleting
    reset_token VARCHAR(100) NULL,
    reset_token_expires_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Trainers Table: Stores additional information specific to trainers
CREATE TABLE trainers (
    user_id INT PRIMARY KEY,
    specialization VARCHAR(100), -- e.g., Weight Loss, Muscle Gain, Yoga
    certification TEXT, -- Details about certifications
    years_of_experience INT,
    hourly_rate DECIMAL(10, 2),
    availability TEXT, -- e.g., "Mon-Fri 9am-5pm"
    bio TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Clients Table: Stores additional information specific to clients
CREATE TABLE clients (
    user_id INT PRIMARY KEY,
    trainer_id INT NULL, -- Assigned trainer
    health_goals TEXT,
    medical_conditions TEXT,
    dietary_preferences TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fitness Goals Table: Stores specific goals for clients
CREATE TABLE fitness_goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    goal_type ENUM('weight_loss', 'muscle_gain', 'endurance', 'flexibility', 'general_health') NOT NULL,
    target_value VARCHAR(50), -- e.g., "10 kg", "run 5km", "touch toes"
    start_date DATE,
    target_date DATE,
    status ENUM('active', 'completed', 'abandoned') DEFAULT 'active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Workout Plans Table: Stores predefined workout plans or templates
CREATE TABLE workout_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trainer_id INT NULL, -- Trainer who created the plan, NULL if system default
    plan_name VARCHAR(100) NOT NULL,
    description TEXT,
    difficulty_level ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
    estimated_duration_mins INT, -- Duration in minutes
    target_audience TEXT, -- e.g., "Weight loss", "Strength building"
    is_public BOOLEAN DEFAULT TRUE, -- Can clients browse this plan?
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trainer_id) REFERENCES trainers(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exercises Table: Stores details of individual exercises
CREATE TABLE exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exercise_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    muscle_group_targeted VARCHAR(100), -- e.g., "Chest", "Legs", "Core"
    equipment_needed VARCHAR(100), -- e.g., "Dumbbells", "None", "Treadmill"
    video_url VARCHAR(255), -- Link to an instructional video
    image_url VARCHAR(255) -- Link to an image
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Workout Plan Exercises Table: Links exercises to workout plans (Many-to-Many)
CREATE TABLE workout_plan_exercises (
    plan_id INT NOT NULL,
    exercise_id INT NOT NULL,
    sets INT,
    reps VARCHAR(50), -- e.g., "10-12", "AMRAP" (As Many Reps As Possible), "30 seconds"
    rest_period_seconds INT,
    notes TEXT,
    PRIMARY KEY (plan_id, exercise_id),
    FOREIGN KEY (plan_id) REFERENCES workout_plans(id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Client Assigned Workout Plans Table: Tracks workout plans assigned to clients
CREATE TABLE client_assigned_workout_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    plan_id INT NOT NULL,
    trainer_id INT NULL, -- Trainer who assigned or customized it
    start_date DATE,
    end_date DATE,
    notes TEXT, -- Custom notes for the client
    status ENUM('active', 'completed', 'paused') DEFAULT 'active',
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(user_id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES workout_plans(id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Progress Tracking Table: Logs client progress (weight, measurements, performance)
CREATE TABLE progress_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    log_date DATE NOT NULL,
    weight_kg DECIMAL(5, 2),
    body_fat_percentage DECIMAL(4, 2),
    waist_cm DECIMAL(5, 2),
    chest_cm DECIMAL(5, 2),
    hips_cm DECIMAL(5, 2),
    notes TEXT, -- Any specific notes for that day
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Diet Logs Table: Clients log their food intake
CREATE TABLE diet_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    log_date DATE NOT NULL,
    meal_type ENUM('breakfast', 'lunch', 'dinner', 'snack') NOT NULL,
    food_item VARCHAR(255) NOT NULL,
    calories INT,
    protein_grams DECIMAL(5,1),
    carbs_grams DECIMAL(5,1),
    fat_grams DECIMAL(5,1),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dietary Plans Table: Stores predefined dietary plans or templates
CREATE TABLE dietary_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trainer_id INT NULL, -- Trainer who created the plan, NULL if system default
    plan_name VARCHAR(100) NOT NULL,
    description TEXT,
    target_calories_per_day INT,
    macronutrient_distribution TEXT, -- e.g., "40% carbs, 30% protein, 30% fat"
    type ENUM('weight_loss', 'muscle_gain', 'maintenance', 'keto', 'vegan') NOT NULL,
    is_public BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trainer_id) REFERENCES trainers(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Client Assigned Dietary Plans Table: Tracks dietary plans assigned to clients
CREATE TABLE client_assigned_dietary_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    plan_id INT NOT NULL,
    trainer_id INT NULL, -- Trainer who assigned or customized it
    start_date DATE,
    end_date DATE,
    notes TEXT, -- Custom notes for the client
    status ENUM('active', 'completed', 'paused') DEFAULT 'active',
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(user_id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES dietary_plans(id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recipes Table: Stores recipes
CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_name VARCHAR(255) NOT NULL,
    description TEXT,
    ingredients TEXT NOT NULL, -- Could be JSON or comma-separated
    instructions TEXT NOT NULL,
    preparation_time_mins INT,
    cooking_time_mins INT,
    calories INT,
    protein_grams DECIMAL(5,1),
    carbs_grams DECIMAL(5,1),
    fat_grams DECIMAL(5,1),
    dietary_plan_id INT NULL, -- If this recipe is part of a specific plan template
    created_by_user_id INT NULL, -- User who submitted this recipe (can be trainer or client)
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dietary_plan_id) REFERENCES dietary_plans(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Messages Table: For direct messaging between users (client-trainer, admin-user)
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subject VARCHAR(255),
    body TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications Table: For system notifications to users
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'success', 'alert') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    link_url VARCHAR(255), -- Optional link for the notification
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Appointments Table: For scheduling sessions between clients and trainers
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    trainer_id INT NOT NULL,
    appointment_time TIMESTAMP NOT NULL,
    duration_mins INT DEFAULT 60,
    status ENUM('scheduled', 'completed', 'cancelled_by_client', 'cancelled_by_trainer', 'no_show') DEFAULT 'scheduled',
    notes TEXT, -- e.g., focus for the session
    location VARCHAR(255), -- Physical location or "Online"
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(user_id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Indexes for performance
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_fitness_goals_client_status ON fitness_goals(client_id, status);
CREATE INDEX idx_workout_plans_difficulty ON workout_plans(difficulty_level);
CREATE INDEX idx_client_assigned_workouts_client_status ON client_assigned_workout_plans(client_id, status);
CREATE INDEX idx_progress_client_date ON progress_tracking(client_id, log_date);
CREATE INDEX idx_diet_logs_client_date ON diet_logs(client_id, log_date);
CREATE INDEX idx_dietary_plans_type ON dietary_plans(type);
CREATE INDEX idx_client_assigned_dietary_client_status ON client_assigned_dietary_plans(client_id, status);
CREATE INDEX idx_messages_sender_receiver ON messages(sender_id, receiver_id);
CREATE INDEX idx_messages_receiver_read ON messages(receiver_id, is_read);
CREATE INDEX idx_notifications_user_read ON notifications(user_id, is_read);
CREATE INDEX idx_appointments_client_time ON appointments(client_id, appointment_time);
CREATE INDEX idx_appointments_trainer_time ON appointments(trainer_id, appointment_time);


-- Sample Data (Passwords are placeholders - use strong hashes in reality)
-- For password_hash, use something like: password_hash('password123', PASSWORD_DEFAULT) in PHP

-- Admin User
INSERT INTO users (username, password_hash, email, first_name, last_name, role, is_active) VALUES
('admin_user', '$2y$10$examplehashedpasswordadmin', 'admin@fitlife.com', 'Admin', 'FitLife', 'admin', TRUE);

-- Trainer Users
INSERT INTO users (username, password_hash, email, first_name, last_name, role, date_of_birth, gender, phone_number, address, is_active) VALUES
('trainer_jane', '$2y$10$examplehashedpasswordjane', 'jane.doe@fitlife.com', 'Jane', 'Doe', 'trainer', '1985-05-15', 'female', '555-0101', '123 Fitness Ave, Gymtown', TRUE),
('trainer_john', '$2y$10$examplehashedpasswordjohn', 'john.smith@fitlife.com', 'John', 'Smith', 'trainer', '1990-08-20', 'male', '555-0102', '456 Wellness Blvd, Healthville', TRUE);

-- Client Users
INSERT INTO users (username, password_hash, email, first_name, last_name, role, date_of_birth, gender, phone_number, address, is_active) VALUES
('client_alice', '$2y$10$examplehashedpasswordalice', 'alice.wonder@email.com', 'Alice', 'Wonder', 'client', '1992-03-10', 'female', '555-0201', '789 Active St, Workoutcity', TRUE),
('client_bob', '$2y$10$examplehashedpasswordbob', 'bob.builder@email.com', 'Bob', 'Builder', 'client', '1988-11-25', 'male', '555-0202', '101 Fit Ln, Progress Peak', TRUE);

-- Populate Trainers Table (assuming user_ids from above insertions; typically auto-incremented)
-- Let's say admin_user.id = 1, trainer_jane.id = 2, trainer_john.id = 3, client_alice.id = 4, client_bob.id = 5
INSERT INTO trainers (user_id, specialization, certification, years_of_experience, hourly_rate, availability, bio) VALUES
(2, 'Weight Loss, Strength Training', 'Certified Personal Trainer (CPT), Nutrition Coach', 7, 60.00, 'Mon-Fri 8am-6pm, Sat 10am-2pm', 'Experienced trainer passionate about helping clients achieve their weight loss and strength goals.'),
(3, 'Yoga, Flexibility, Mindfulness', 'Registered Yoga Teacher (RYT 500)', 5, 50.00, 'Tue-Sat 9am-5pm', 'Dedicated yoga instructor focused on improving flexibility, balance, and mental well-being.');

-- Populate Clients Table
INSERT INTO clients (user_id, trainer_id, health_goals, medical_conditions, dietary_preferences) VALUES
(4, 2, 'Lose 10kg, run a 5k', 'None', 'Vegetarian'),
(5, 3, 'Increase muscle mass, improve posture', 'Mild back pain (consulted doctor)', 'High protein');

-- Sample Fitness Goals
INSERT INTO fitness_goals (client_id, goal_type, target_value, start_date, target_date, status) VALUES
(4, 'weight_loss', '10 kg', '2024-01-10', '2024-07-10', 'active'),
(4, 'endurance', 'Run 5km without stopping', '2024-01-10', '2024-04-10', 'active'),
(5, 'muscle_gain', 'Gain 5kg muscle', '2024-02-01', '2024-08-01', 'active');

-- Sample Exercises
INSERT INTO exercises (exercise_name, description, muscle_group_targeted, equipment_needed) VALUES
('Push-up', 'Classic bodyweight exercise targeting upper body.', 'Chest, Shoulders, Triceps', 'None'),
('Squat', 'Compound lower body exercise.', 'Quads, Hamstrings, Glutes', 'None / Barbell'),
('Plank', 'Core strength exercise.', 'Core, Abs', 'None'),
('Bicep Curl', 'Isolation exercise for biceps.', 'Biceps', 'Dumbbells'),
('Treadmill Run', 'Cardiovascular exercise.', 'Full Body, Cardiovascular', 'Treadmill');

-- Sample Workout Plans
INSERT INTO workout_plans (trainer_id, plan_name, description, difficulty_level, estimated_duration_mins, target_audience, is_public) VALUES
(2, 'Beginner Full Body Blast', 'A great starting point for new gym-goers.', 'beginner', 45, 'General Fitness, Weight Loss', TRUE),
(3, 'Yoga for Flexibility', 'Improve your flexibility and reduce stress.', 'intermediate', 60, 'Flexibility, Stress Relief', TRUE),
(NULL, 'Quick Core Workout', '15-minute core strengthening routine.', 'beginner', 15, 'Core Strength', TRUE);

-- Link Exercises to Workout Plans (workout_plan_exercises)
-- For "Beginner Full Body Blast" (let's assume its ID is 1)
-- And Exercise IDs: Push-up=1, Squat=2, Plank=3
INSERT INTO workout_plan_exercises (plan_id, exercise_id, sets, reps, rest_period_seconds) VALUES
(1, 1, 3, '10-12', 60),
(1, 2, 3, '10-12', 60),
(1, 3, 3, '30-60 seconds', 45);

-- For "Yoga for Flexibility" (let's assume its ID is 2)
-- (Add yoga pose exercises first if not present, then link them)

-- Assign Workout Plan to Client
INSERT INTO client_assigned_workout_plans (client_id, plan_id, trainer_id, start_date, status) VALUES
(4, 1, 2, '2024-01-15', 'active');

-- Sample Progress Tracking
INSERT INTO progress_tracking (client_id, log_date, weight_kg, body_fat_percentage, notes) VALUES
(4, '2024-01-15', 75.0, 30.0, 'First day! Feeling motivated.'),
(4, '2024-02-15', 73.5, 29.0, 'Lost 1.5kg. Workout going well.');

-- Sample Dietary Plans
INSERT INTO dietary_plans (trainer_id, plan_name, description, target_calories_per_day, macronutrient_distribution, type, is_public) VALUES
(2, 'Balanced Weight Loss Diet', 'A balanced diet focusing on whole foods for sustainable weight loss.', 1800, '40% carbs, 30% protein, 30% fat', 'weight_loss', TRUE),
(NULL, 'General Healthy Eating Guide', 'Tips for maintaining a healthy diet.', 2200, '50% carbs, 20% protein, 30% fat', 'maintenance', TRUE);

-- Assign Dietary Plan to Client
INSERT INTO client_assigned_dietary_plans (client_id, plan_id, trainer_id, start_date, status) VALUES
(4, 1, 2, '2024-01-15', 'active'); -- Assuming dietary_plan_id 1 is 'Balanced Weight Loss Diet'

-- Sample Diet Logs
INSERT INTO diet_logs (client_id, log_date, meal_type, food_item, calories, protein_grams, carbs_grams, fat_grams) VALUES
(4, '2024-01-16', 'breakfast', 'Oatmeal with berries and nuts', 400, 15, 60, 10),
(4, '2024-01-16', 'lunch', 'Grilled chicken salad', 500, 40, 20, 25);

-- Sample Recipes
INSERT INTO recipes (recipe_name, description, ingredients, instructions, preparation_time_mins, cooking_time_mins, calories, protein_grams, carbs_grams, fat_grams, dietary_plan_id, created_by_user_id) VALUES
('Simple Grilled Chicken Salad', 'A quick and healthy grilled chicken salad.', 'Chicken breast, mixed greens, cherry tomatoes, cucumber, olive oil, lemon juice', '1. Grill chicken. 2. Chop vegetables. 3. Combine and dress.', 10, 15, 500, 40, 20, 25, 1, 2),
('Morning Oatmeal Power Bowl', 'Nutrient-rich oatmeal to start your day.', 'Rolled oats, almond milk, berries, chia seeds, walnuts', '1. Cook oats with almond milk. 2. Top with berries, chia seeds, and walnuts.', 5, 5, 400, 15, 60, 10, 1, 2);

-- Sample Messages
INSERT INTO messages (sender_id, receiver_id, subject, body) VALUES
(4, 2, 'Question about workout', 'Hi Jane, I had a question about the squat form. Can we discuss this?'),
(2, 4, 'Re: Question about workout', 'Hi Alice, Sure! Let me know when you are free for a quick chat or send me a video.');

-- Sample Notifications
INSERT INTO notifications (user_id, message, type, link_url) VALUES
(4, 'Your new workout plan "Beginner Full Body Blast" has been assigned.', 'success', '/workouts/assigned/1'), -- Example URL
(5, 'Welcome to FitLife! Complete your profile to get started.', 'info', '/profile/edit');

-- Sample Appointments
INSERT INTO appointments (client_id, trainer_id, appointment_time, duration_mins, status, notes, location) VALUES
(4, 2, '2024-02-01 10:00:00', 60, 'scheduled', 'Focus on squat and deadlift form.', 'Main Gym Floor'),
(5, 3, '2024-02-05 14:00:00', 45, 'scheduled', 'Introduction to Yoga session.', 'Yoga Studio');

COMMIT;
-- Note: The COMMIT at the end is good practice for standalone scripts, 
-- ensuring all statements are processed together if the SQL client settings require it.
-- For many clients (like phpMyAdmin or command line), individual statements are auto-committed if not in a transaction block.

-- Further considerations for a production environment:
-- 1. Password Hashing: Ensure actual strong hashes are used, not plain text or weak examples.
--    The application layer (PHP) should handle hashing before inserting.
-- 2. User IDs in Sample Data: The sample data assumes sequential user IDs (1, 2, 3, ...).
--    In a real system, these are auto-incremented. If running this script multiple times or after
--    manual insertions, these IDs might conflict or be different.
--    It's better to fetch IDs programmatically if inserting related data via scripts after initial user creation.
-- 3. Foreign Key Constraints: The order of INSERT statements matters to avoid foreign key violations.
--    Parent records must exist before child records referencing them.
-- 4. Timestamps: CURRENT_TIMESTAMP is used for creation dates. `updated_at` in `appointments`
--    shows an example of auto-updating on modification.
-- 5. Character Sets and Collations: utf8mb4 is used for broader character support, including emojis.
-- 6. ENUMs: Used for fields with a fixed set of possible values. Consider if these might change
--    and how updates would be managed (e.g., using lookup tables instead for more flexibility).
-- 7. Security: SQL Injection prevention must be handled at the application layer (e.g., prepared statements).
--    This schema itself does not prevent SQL injection.
-- 8. Backups: Regular database backups are crucial.
-- 9. Normalization: The schema aims for a reasonable level of normalization.
--    Review based on specific query patterns and performance needs.

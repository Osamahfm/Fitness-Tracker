<?php

/**
 * Workout Model
 * Handles all DB interactions for the workouts table.
 * Implements the MET-based calorie burn formula:
 *   Calories = MET × weight_kg × duration_hours
 */
class Workout {
    private Database $db;

    /**
     * MET (Metabolic Equivalent of Task) values per activity type.
     * Source: Compendium of Physical Activities (Ainsworth et al.)
     */
    public const MET_VALUES = [
        'Running'         => 9.8,
        'Walking'         => 3.5,
        'Cycling'         => 7.5,
        'Swimming'        => 8.0,
        'Weight Training' => 5.0,
        'HIIT'            => 10.0,
        'Yoga'            => 2.5,
        'Jump Rope'       => 11.0,
        'Rowing'          => 8.5,
        'Elliptical'      => 5.0,
        'Pilates'         => 3.0,
        'Dancing'         => 5.5,
    ];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Calculate calories burned using the MET formula.
     * @param string $activityType
     * @param float  $durationMinutes
     * @param float  $weightKg
     * @return int
     */
    public static function calculateCalories(string $activityType, float $durationMinutes, float $weightKg): int {
        $met = self::MET_VALUES[$activityType] ?? 5.0;
        $durationHours = $durationMinutes / 60;
        return (int) round($met * $weightKg * $durationHours);
    }

    /**
     * Log a new workout entry.
     * @param array $data Keys: user_id, type, duration, distance_km, calories_burned, workout_date
     * @return bool
     */
    public function logWorkout(array $data): bool {
        $this->db->query(
            'INSERT INTO workouts (user_id, type, duration, distance_km, calories_burned, workout_date)
             VALUES (:user_id, :type, :duration, :distance_km, :calories_burned, :workout_date)'
        );
        $this->db->bind(':user_id',        $data['user_id']);
        $this->db->bind(':type',           $data['type']);
        $this->db->bind(':duration',       $data['duration']);
        $this->db->bind(':distance_km',    $data['distance_km'] ?? null);
        $this->db->bind(':calories_burned', $data['calories_burned']);
        $this->db->bind(':workout_date',   $data['workout_date']);
        return $this->db->execute();
    }

    /**
     * Delete a workout by ID, ensuring it belongs to the user.
     */
    public function deleteWorkout(int $workoutId, int $userId): bool {
        $this->db->query('DELETE FROM workouts WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $workoutId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * Get all workouts for a user, newest first.
     */
    public function getUserWorkouts(int $userId): array {
        $this->db->query(
            'SELECT * FROM workouts WHERE user_id = :user_id ORDER BY workout_date DESC, created_at DESC'
        );
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /**
     * Get recent workouts (for dashboard widget).
     */
    public function getRecentWorkouts(int $userId, int $limit = 5): array {
        $this->db->query(
            'SELECT * FROM workouts WHERE user_id = :user_id ORDER BY workout_date DESC, created_at DESC LIMIT :limit'
        );
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit',   $limit);
        return $this->db->resultSet();
    }

    /**
     * Get aggregate stats for a user (total workouts, total calories, total duration).
     */
    public function getTotalStats(int $userId): object|bool {
        $this->db->query(
            'SELECT COUNT(*) as total_workouts,
                    COALESCE(SUM(calories_burned), 0) as total_calories,
                    COALESCE(SUM(duration), 0) as total_minutes,
                    COALESCE(SUM(distance_km), 0) as total_distance
             FROM workouts WHERE user_id = :user_id'
        );
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /**
     * Get calories burned per day for the last 7 days (for Chart.js).
     * Returns array indexed by date string.
     */
    public function getWeeklyCalories(int $userId): array {
        $this->db->query(
            'SELECT workout_date, SUM(calories_burned) as daily_calories
             FROM workouts
             WHERE user_id = :user_id
               AND workout_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
             GROUP BY workout_date
             ORDER BY workout_date ASC'
        );
        $this->db->bind(':user_id', $userId);
        $rows = $this->db->resultSet();
        // Build a full 7-day indexed map
        $map = [];
        foreach ($rows as $row) {
            $map[$row->workout_date] = (int)$row->daily_calories;
        }
        return $map;
    }

    /**
     * Count workouts logged this week (Mon–Sun).
     */
    public function getWeeklyWorkoutCount(int $userId): int {
        $this->db->query(
            'SELECT COUNT(*) as cnt FROM workouts
             WHERE user_id = :user_id
               AND YEARWEEK(workout_date, 1) = YEARWEEK(CURDATE(), 1)'
        );
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return (int)($result->cnt ?? 0);
    }
}

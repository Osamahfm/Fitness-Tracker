<?php

/**
 * Report Model (R7)
 * Aggregates workout and meal data to generate daily and weekly reports.
 */
class Report {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Get a full summary for a specific date. */
    public function getDailySummary(int $userId, string $date): object {
        // Workout summary for the day
        $this->db->query(
            'SELECT COUNT(*) as workout_count,
                    COALESCE(SUM(calories_burned),0) as calories_burned,
                    COALESCE(SUM(duration),0) as total_minutes,
                    COALESCE(SUM(distance_km),0) as total_distance
             FROM workouts WHERE user_id = :uid AND workout_date = :date'
        );
        $this->db->bind(':uid',  $userId);
        $this->db->bind(':date', $date);
        $workout = $this->db->single();

        // Meal summary for the day
        $this->db->query(
            'SELECT COUNT(*) as meal_count,
                    COALESCE(SUM(calories),0) as calories_consumed,
                    COALESCE(SUM(protein),0)  as total_protein,
                    COALESCE(SUM(carbs),0)    as total_carbs,
                    COALESCE(SUM(fats),0)     as total_fats
             FROM meals WHERE user_id = :uid AND DATE(logged_at) = :date'
        );
        $this->db->bind(':uid',  $userId);
        $this->db->bind(':date', $date);
        $meals = $this->db->single();

        // Individual workouts for the table
        $this->db->query(
            'SELECT type, duration, distance_km, calories_burned FROM workouts
             WHERE user_id = :uid AND workout_date = :date ORDER BY created_at ASC'
        );
        $this->db->bind(':uid',  $userId);
        $this->db->bind(':date', $date);
        $workoutList = $this->db->resultSet();

        // Individual meals for the table
        $this->db->query(
            'SELECT food_name, calories, protein, carbs, fats, logged_at FROM meals
             WHERE user_id = :uid AND DATE(logged_at) = :date ORDER BY logged_at ASC'
        );
        $this->db->bind(':uid',  $userId);
        $this->db->bind(':date', $date);
        $mealList = $this->db->resultSet();

        $netBalance = (int)$meals->calories_consumed - (int)$workout->calories_burned;

        return (object)[
            'date'             => $date,
            'workout_count'    => (int)$workout->workout_count,
            'calories_burned'  => (int)$workout->calories_burned,
            'total_minutes'    => (int)$workout->total_minutes,
            'total_distance'   => (float)$workout->total_distance,
            'meal_count'       => (int)$meals->meal_count,
            'calories_consumed'=> (int)$meals->calories_consumed,
            'total_protein'    => (float)$meals->total_protein,
            'total_carbs'      => (float)$meals->total_carbs,
            'total_fats'       => (float)$meals->total_fats,
            'net_balance'      => $netBalance,
            'workouts'         => $workoutList,
            'meals'            => $mealList,
        ];
    }

    /** Get the last 30 days of calorie in/out data for the trend chart. */
    public function getMonthlyTrend(int $userId): array {
        $days    = [];
        $burned  = [];
        $consumed = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));

            $this->db->query(
                'SELECT COALESCE(SUM(calories_burned),0) as b FROM workouts WHERE user_id=:uid AND workout_date=:date'
            );
            $this->db->bind(':uid', $userId); $this->db->bind(':date', $date);
            $b = $this->db->single();

            $this->db->query(
                'SELECT COALESCE(SUM(calories),0) as c FROM meals WHERE user_id=:uid AND DATE(logged_at)=:date'
            );
            $this->db->bind(':uid', $userId); $this->db->bind(':date', $date);
            $c = $this->db->single();

            $days[]     = date('M j', strtotime($date));
            $burned[]   = (int)$b->b;
            $consumed[] = (int)$c->c;
        }

        return compact('days', 'burned', 'consumed');
    }

    /** Check if user has logged a workout today (for R6 alarm logic). */
    public function hasWorkoutToday(int $userId): bool {
        $this->db->query(
            'SELECT COUNT(*) as cnt FROM workouts WHERE user_id=:uid AND workout_date=CURDATE()'
        );
        $this->db->bind(':uid', $userId);
        $r = $this->db->single();
        return (int)$r->cnt > 0;
    }

    /** Get list of dates the user has any data (for date picker). */
    public function getActiveDates(int $userId): array {
        $this->db->query(
            'SELECT DISTINCT workout_date as d FROM workouts WHERE user_id=:uid
             UNION
             SELECT DISTINCT DATE(logged_at) as d FROM meals WHERE user_id=:uid
             ORDER BY d DESC LIMIT 60'
        );
        $this->db->bind(':uid', $userId);
        return $this->db->resultSet();
    }
}

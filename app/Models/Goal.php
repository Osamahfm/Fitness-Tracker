<?php

/**
 * Goal Model (R2)
 * Manages fitness goals and provides recommendations.
 */
class Goal {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Create a new goal. Marks any existing active goal as abandoned first. */
    public function createGoal(array $data): bool {
        // Abandon previous active goal
        $this->db->query(
            "UPDATE goals SET status = 'abandoned' WHERE user_id = :user_id AND status = 'active'"
        );
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->execute();

        $this->db->query(
            'INSERT INTO goals (user_id, goal_type, target_weight, target_calories, target_workouts_per_week, notes)
             VALUES (:user_id, :goal_type, :target_weight, :target_calories, :target_workouts_per_week, :notes)'
        );
        $this->db->bind(':user_id',                $data['user_id']);
        $this->db->bind(':goal_type',              $data['goal_type']);
        $this->db->bind(':target_weight',          $data['target_weight']);
        $this->db->bind(':target_calories',        $data['target_calories']);
        $this->db->bind(':target_workouts_per_week', $data['target_workouts_per_week']);
        $this->db->bind(':notes',                  $data['notes'] ?? null);
        return $this->db->execute();
    }

    /** Get the current active goal for a user. */
    public function getActiveGoal(int $userId): object|bool {
        $this->db->query(
            "SELECT * FROM goals WHERE user_id = :user_id AND status = 'active' ORDER BY created_at DESC LIMIT 1"
        );
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /** Get all goals for a user. */
    public function getUserGoals(int $userId): array {
        $this->db->query(
            'SELECT * FROM goals WHERE user_id = :user_id ORDER BY created_at DESC'
        );
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /** Mark a goal as achieved. */
    public function markAchieved(int $goalId, int $userId): bool {
        $this->db->query(
            "UPDATE goals SET status = 'achieved' WHERE id = :id AND user_id = :user_id"
        );
        $this->db->bind(':id',      $goalId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /** Delete a goal. */
    public function deleteGoal(int $goalId, int $userId): bool {
        $this->db->query('DELETE FROM goals WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id',      $goalId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * R2 — Recommend a goal based on goal type.
     * Returns suggested target_calories and target_workouts_per_week.
     */
    public static function getRecommendation(string $goalType, float $weightKg): array {
        $bmr = 1800; // approximate base
        return match($goalType) {
            'weight_loss'   => ['calories' => max(1200, (int)($bmr * 0.8)),  'workouts' => 5, 'desc' => 'Moderate caloric deficit with 5 sessions/week for sustainable fat loss.'],
            'muscle_gain'   => ['calories' => (int)($bmr * 1.15),             'workouts' => 4, 'desc' => 'Caloric surplus with 4 strength sessions/week to support muscle growth.'],
            'maintenance'   => ['calories' => (int)$bmr,                       'workouts' => 3, 'desc' => 'Maintain current weight with balanced diet and 3 sessions/week.'],
            'endurance'     => ['calories' => (int)($bmr * 1.1),               'workouts' => 5, 'desc' => 'High-carb fuel with 5 cardio sessions/week to build stamina.'],
            default         => ['calories' => (int)$bmr,                       'workouts' => 3, 'desc' => 'Balanced approach.'],
        };
    }
}

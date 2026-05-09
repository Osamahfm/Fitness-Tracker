<?php

/**
 * Goals Controller (R2)
 * Handles goal creation, tracking, and smart recommendations.
 */
class GoalsController extends Controller {
    private Goal $goalModel;
    private int $userId;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/users/login'); exit;
        }
        $this->goalModel = $this->model('Goal');
        $this->userId = (int)$_SESSION['user_id'];
    }

    /** GET /goals — Show active goal, history, and recommendations. */
    public function index(): void {
        $workoutModel  = $this->model('Workout');
        $activeGoal    = $this->goalModel->getActiveGoal($this->userId);
        $allGoals      = $this->goalModel->getUserGoals($this->userId);
        $workoutStats  = $workoutModel->getTotalStats($this->userId);
        $weeklyCount   = $workoutModel->getWeeklyWorkoutCount($this->userId);

        // Progress: weekly workouts vs target
        $targetWorkouts = $activeGoal ? (int)$activeGoal->target_workouts_per_week : 3;
        $workoutProgress = $targetWorkouts > 0 ? min(100, round(($weeklyCount / $targetWorkouts) * 100)) : 0;

        $this->view('goals/index', [
            'title'           => 'My Goals',
            'activeGoal'      => $activeGoal,
            'allGoals'        => $allGoals,
            'workoutStats'    => $workoutStats,
            'weeklyCount'     => $weeklyCount,
            'workoutProgress' => $workoutProgress,
        ], 'app');
    }

    /** GET/POST /goals/create — Create a new goal with smart recommendations. */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $goalType    = trim($_POST['goal_type']    ?? 'maintenance');
            $targetWeight= trim($_POST['target_weight'] ?? '');
            $targetCal   = trim($_POST['target_calories'] ?? '');
            $targetWPW   = trim($_POST['target_workouts_per_week'] ?? '3');
            $notes       = trim($_POST['notes'] ?? '');

            $errors = [];
            if (!is_numeric($targetWeight) || (float)$targetWeight <= 0) $errors['target_weight']  = 'Please enter a valid target weight.';
            if (!is_numeric($targetCal)    || (int)$targetCal    <= 0)   $errors['target_calories'] = 'Please enter a valid daily calorie target.';

            if (empty($errors)) {
                $this->goalModel->createGoal([
                    'user_id'                  => $this->userId,
                    'goal_type'                => $goalType,
                    'target_weight'            => (float)$targetWeight,
                    'target_calories'          => (int)$targetCal,
                    'target_workouts_per_week' => max(1, min(7, (int)$targetWPW)),
                    'notes'                    => $notes ?: null,
                ]);
                $_SESSION['flash_success'] = 'New goal set! Stay consistent and you will get there.';
                header('Location: ' . URLROOT . '/goals'); exit;
            }

            $this->view('goals/create', [
                'title'  => 'Set New Goal',
                'errors' => $errors,
                'old'    => $_POST,
            ], 'app');
        } else {
            $this->view('goals/create', [
                'title'  => 'Set New Goal',
                'errors' => [],
                'old'    => [],
            ], 'app');
        }
    }

    /** POST /goals/achieve/{id} */
    public function achieve(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->goalModel->markAchieved((int)$id, $this->userId);
            $_SESSION['flash_success'] = '🎉 Congratulations! Goal marked as achieved!';
        }
        header('Location: ' . URLROOT . '/goals'); exit;
    }

    /** POST /goals/delete/{id} */
    public function delete(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->goalModel->deleteGoal((int)$id, $this->userId);
        }
        header('Location: ' . URLROOT . '/goals'); exit;
    }
}

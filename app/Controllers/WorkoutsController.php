<?php

/**
 * Workouts Controller
 * Handles workout logging (R1) and calorie calculation (R4).
 * Requires authenticated session.
 */
class WorkoutsController extends Controller {
    private Workout $workoutModel;
    private int $userId;
    private float $userWeight;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/users/login');
            exit;
        }
        $this->workoutModel = $this->model('Workout');
        $this->userId = (int)$_SESSION['user_id'];
        $this->userWeight = (float)($_SESSION['user_weight'] ?? 70.0);
    }

    /**
     * GET /workouts — List all workouts for the current user.
     */
    public function index(): void {
        $workouts = $this->workoutModel->getUserWorkouts($this->userId);
        $stats    = $this->workoutModel->getTotalStats($this->userId);

        $data = [
            'title'    => 'My Workouts',
            'workouts' => $workouts,
            'stats'    => $stats,
            'met'      => Workout::MET_VALUES,
        ];
        $this->view('workouts/index', $data, 'app');
    }

    /**
     * GET  /workouts/create — Show the log workout form.
     * POST /workouts/create — Validate, calculate calories, and save.
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type        = trim($_POST['type'] ?? '');
            $durationRaw = trim($_POST['duration'] ?? '');
            $distanceRaw = trim($_POST['distance_km'] ?? '');
            $date        = trim($_POST['workout_date'] ?? date('Y-m-d'));
            $weightRaw   = trim($_POST['weight_kg'] ?? '70');

            $errors = [];
            if (empty($type) || !array_key_exists($type, Workout::MET_VALUES)) {
                $errors['type'] = 'Please select a valid activity type.';
            }
            if (!is_numeric($durationRaw) || (float)$durationRaw <= 0) {
                $errors['duration'] = 'Please enter a valid duration (minutes > 0).';
            }
            if (!is_numeric($weightRaw) || (float)$weightRaw <= 0) {
                $errors['weight'] = 'Please enter a valid body weight.';
            }
            if (empty($date)) {
                $errors['date'] = 'Please select a date.';
            }

            if (empty($errors)) {
                $duration    = (float)$durationRaw;
                $weight      = (float)$weightRaw;
                $distanceKm  = is_numeric($distanceRaw) ? (float)$distanceRaw : null;
                $calories    = Workout::calculateCalories($type, $duration, $weight);

                // Persist weight to session for next time
                $_SESSION['user_weight'] = $weight;

                $saved = $this->workoutModel->logWorkout([
                    'user_id'        => $this->userId,
                    'type'           => $type,
                    'duration'       => (int)$duration,
                    'distance_km'    => $distanceKm,
                    'calories_burned'=> $calories,
                    'workout_date'   => $date,
                ]);

                if ($saved) {
                    $_SESSION['flash_success'] = "Workout logged! You burned approximately <strong>{$calories} kcal</strong>.";
                    header('Location: ' . URLROOT . '/workouts');
                    exit;
                } else {
                    $errors['general'] = 'Something went wrong. Please try again.';
                }
            }

            $this->view('workouts/create', [
                'title'    => 'Log Workout',
                'errors'   => $errors,
                'old'      => $_POST,
                'met'      => Workout::MET_VALUES,
                'weight'   => $weightRaw,
            ], 'app');

        } else {
            $this->view('workouts/create', [
                'title'  => 'Log Workout',
                'errors' => [],
                'old'    => [],
                'met'    => Workout::MET_VALUES,
                'weight' => $_SESSION['user_weight'] ?? 70,
            ], 'app');
        }
    }

    /**
     * POST /workouts/delete/{id} — Delete a workout.
     */
    public function delete(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->workoutModel->deleteWorkout((int)$id, $this->userId);
            $_SESSION['flash_success'] = 'Workout deleted.';
        }
        header('Location: ' . URLROOT . '/workouts');
        exit;
    }
}

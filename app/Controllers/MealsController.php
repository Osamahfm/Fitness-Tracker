<?php

/**
 * Meals Controller (R3)
 * Handles meal logging, daily tracking, and meal recommendations.
 */
class MealsController extends Controller {
    private Meal $mealModel;
    private int $userId;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/users/login'); exit;
        }
        $this->mealModel = $this->model('Meal');
        $this->userId = (int)$_SESSION['user_id'];
    }

    /** GET /meals — Show all meals, today's totals, and meal recommendations. */
    public function index(): void {
        $goalModel   = $this->model('Goal');
        $activeGoal  = $goalModel->getActiveGoal($this->userId);
        $todayTotals = $this->mealModel->getTodayTotals($this->userId);
        $todayMeals  = $this->mealModel->getTodayMeals($this->userId);
        $allMeals    = $this->mealModel->getUserMeals($this->userId);
        $allTotals   = $this->mealModel->getAllTimeTotals($this->userId);

        $targetCalories  = $activeGoal ? (int)$activeGoal->target_calories : 2000;
        $consumedToday   = (int)($todayTotals->total_calories ?? 0);
        $remainingToday  = max(0, $targetCalories - $consumedToday);
        $recommendations = Meal::getRecommendations($remainingToday, 4);

        $this->view('meals/index', [
            'title'          => 'Nutrition',
            'todayMeals'     => $todayMeals,
            'allMeals'       => $allMeals,
            'todayTotals'    => $todayTotals,
            'allTotals'      => $allTotals,
            'targetCalories' => $targetCalories,
            'remainingToday' => $remainingToday,
            'recommendations'=> $recommendations,
            'activeGoal'     => $activeGoal,
        ], 'app');
    }

    /** GET/POST /meals/log — Log a new meal. */
    public function log(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $foodName = trim($_POST['food_name'] ?? '');
            $calories = trim($_POST['calories'] ?? '');
            $protein  = trim($_POST['protein']  ?? '0');
            $carbs    = trim($_POST['carbs']    ?? '0');
            $fats     = trim($_POST['fats']     ?? '0');

            $errors = [];
            if (empty($foodName))                          $errors['food_name'] = 'Please enter a food name.';
            if (!is_numeric($calories) || (int)$calories <= 0) $errors['calories']  = 'Please enter valid calories.';

            if (empty($errors)) {
                $this->mealModel->logMeal([
                    'user_id'   => $this->userId,
                    'food_name' => $foodName,
                    'calories'  => (int)$calories,
                    'protein'   => is_numeric($protein) ? (float)$protein : 0,
                    'carbs'     => is_numeric($carbs)   ? (float)$carbs   : 0,
                    'fats'      => is_numeric($fats)    ? (float)$fats    : 0,
                ]);
                $_SESSION['flash_success'] = "Meal logged: <strong>" . htmlspecialchars($foodName) . "</strong> ({$calories} kcal)";
                header('Location: ' . URLROOT . '/meals'); exit;
            }

            $this->view('meals/log', [
                'title'  => 'Log Meal',
                'errors' => $errors,
                'old'    => $_POST,
            ], 'app');
        } else {
            // Pre-fill from quick-add recommendation
            $prefill = [
                'food_name' => $_GET['name']     ?? '',
                'calories'  => $_GET['calories'] ?? '',
                'protein'   => $_GET['protein']  ?? '',
                'carbs'     => $_GET['carbs']    ?? '',
                'fats'      => $_GET['fats']     ?? '',
            ];
            $this->view('meals/log', [
                'title'  => 'Log Meal',
                'errors' => [],
                'old'    => $prefill,
            ], 'app');
        }
    }

    /** POST /meals/delete/{id} */
    public function delete(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->mealModel->deleteMeal((int)$id, $this->userId);
            $_SESSION['flash_success'] = 'Meal entry deleted.';
        }
        header('Location: ' . URLROOT . '/meals'); exit;
    }
}

<?php

/**
 * Dashboard Controller
 * Protected area — requires authentication.
 * Fetches real stats from the Workout model for display.
 */
class DashboardController extends Controller {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/users/login');
            exit;
        }
    }

    public function index(): void {
        $workoutModel = $this->model('Workout');
        $userId = (int)$_SESSION['user_id'];

        // Aggregate stats
        $stats        = $workoutModel->getTotalStats($userId);
        $weeklyCount  = $workoutModel->getWeeklyWorkoutCount($userId);
        $recentLogs   = $workoutModel->getRecentWorkouts($userId, 5);
        $weeklyMap    = $workoutModel->getWeeklyCalories($userId);

        // Build 7-day labels and calories array for Chart.js
        $labels   = [];
        $calories = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr    = date('Y-m-d', strtotime("-{$i} days"));
            $labels[]   = date('D', strtotime($dateStr));   // Mon, Tue...
            $calories[] = $weeklyMap[$dateStr] ?? 0;
        }

        $this->view('dashboard/index', [
            'title'       => 'Dashboard',
            'stats'       => $stats,
            'weeklyCount' => $weeklyCount,
            'recentLogs'  => $recentLogs,
            'chartLabels' => json_encode($labels),
            'chartData'   => json_encode($calories),
        ], 'app');
    }
}

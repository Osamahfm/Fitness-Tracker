<?php

/**
 * Reports Controller (R7)
 * Generates daily and monthly fitness activity reports.
 */
class ReportsController extends Controller {
    private Report $reportModel;
    private int $userId;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/users/login'); exit;
        }
        $this->reportModel = $this->model('Report');
        $this->userId = (int)$_SESSION['user_id'];
    }

    /** GET /reports — Show daily report. Date selectable via ?date=YYYY-MM-DD */
    public function index(): void {
        $date = $_GET['date'] ?? date('Y-m-d');
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $date = date('Y-m-d');
        }

        $goalModel   = $this->model('Goal');
        $summary     = $this->reportModel->getDailySummary($this->userId, $date);
        $trend       = $this->reportModel->getMonthlyTrend($this->userId);
        $activeDates = $this->reportModel->getActiveDates($this->userId);
        $activeGoal  = $goalModel->getActiveGoal($this->userId);
        $hasWorkout  = $this->reportModel->hasWorkoutToday($this->userId);

        $this->view('reports/index', [
            'title'        => 'Daily Report',
            'date'         => $date,
            'summary'      => $summary,
            'trend'        => $trend,
            'activeDates'  => $activeDates,
            'activeGoal'   => $activeGoal,
            'hasWorkout'   => $hasWorkout,
        ], 'app');
    }
}

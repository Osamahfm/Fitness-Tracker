<?php

class PagesController extends Controller {
    public function __construct() {}

    public function index(): void {
        // If already logged in, redirect to dashboard
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/dashboard');
            exit;
        }
        $this->view('pages/index', ['title' => 'Elite Fitness Tracking'], 'main');
    }
}

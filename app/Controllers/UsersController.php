<?php

/**
 * Users Controller
 * Handles user authentication: registration, login, and logout.
 */
class UsersController extends Controller {
    private User $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');

        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Register page: GET shows form, POST processes registration.
     */
    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $data = [
                'name'             => trim(htmlspecialchars($_POST['name'] ?? '')),
                'email'            => trim(htmlspecialchars($_POST['email'] ?? '')),
                'password'         => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'name_err'         => '',
                'email_err'        => '',
                'password_err'     => '',
                'confirm_err'      => '',
            ];

            // --- Validation ---
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name.';
            }
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter a valid email.';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'That email is already registered.';
            }
            if (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters.';
            }
            if ($data['password'] !== $data['confirm_password']) {
                $data['confirm_err'] = 'Passwords do not match.';
            }

            // If no errors, register user
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_err'])) {
                if ($this->userModel->register($data)) {
                    $_SESSION['success_msg'] = 'You are now registered. Please log in.';
                    header('Location: ' . URLROOT . '/users/login');
                    exit;
                } else {
                    die('Something went wrong. Please try again.');
                }
            } else {
                $data['title'] = 'Create Account';
                $this->view('users/register', $data, 'main');
            }
        } else {
            $data = [
                'title'            => 'Create Account',
                'name'             => '',
                'email'            => '',
                'password'         => '',
                'confirm_password' => '',
                'name_err'         => '',
                'email_err'        => '',
                'password_err'     => '',
                'confirm_err'      => '',
            ];
            $this->view('users/register', $data, 'main');
        }
    }

    /**
     * Login page: GET shows form, POST processes login.
     */
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $data = [
                'title'      => 'Welcome Back',
                'email'      => htmlspecialchars($email),
                'email_err'  => '',
                'password_err' => '',
            ];

            if (empty($email)) {
                $data['email_err'] = 'Please enter your email.';
            }
            if (empty($password)) {
                $data['password_err'] = 'Please enter your password.';
            }

            if (empty($data['email_err']) && empty($data['password_err'])) {
                $user = $this->userModel->findUserByEmail($email);
                if ($user && password_verify($password, $user->password_hash)) {
                    // Set session
                    $_SESSION['user_id']     = $user->id;
                    $_SESSION['user_name']   = $user->name;
                    $_SESSION['user_email']  = $user->email;
                    $_SESSION['user_weight'] = $user->weight_kg ?? 70.0;
                    header('Location: ' . URLROOT . '/dashboard');
                    exit;
                } else {
                    $data['password_err'] = 'Incorrect email or password.';
                    $this->view('users/login', $data, 'main');
                }
            } else {
                $this->view('users/login', $data, 'main');
            }
        } else {
            $data = [
                'title'        => 'Welcome Back',
                'email'        => '',
                'email_err'    => '',
                'password_err' => '',
            ];
            $this->view('users/login', $data, 'main');
        }
    }

    /**
     * Logout: Destroy session and redirect.
     */
    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: ' . URLROOT . '/users/login');
        exit;
    }
}

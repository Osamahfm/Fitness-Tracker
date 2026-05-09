<?php

/**
 * Meal Model (R3)
 * Handles all DB interactions for the meals table.
 * Includes a built-in recommendation engine based on remaining calorie budget.
 */
class Meal {
    private Database $db;

    /**
     * Pre-defined meal recommendations database.
     * Each meal has: name, calories, protein(g), carbs(g), fats(g), category
     */
    public const MEAL_LIBRARY = [
        // Breakfast
        ['name'=>'Oatmeal with Berries',     'calories'=>320, 'protein'=>10, 'carbs'=>58, 'fats'=>6,  'category'=>'Breakfast'],
        ['name'=>'Greek Yogurt & Granola',   'calories'=>280, 'protein'=>15, 'carbs'=>42, 'fats'=>5,  'category'=>'Breakfast'],
        ['name'=>'Scrambled Eggs on Toast',  'calories'=>350, 'protein'=>20, 'carbs'=>28, 'fats'=>16, 'category'=>'Breakfast'],
        ['name'=>'Banana Protein Smoothie',  'calories'=>310, 'protein'=>25, 'carbs'=>45, 'fats'=>4,  'category'=>'Breakfast'],
        ['name'=>'Avocado Toast',            'calories'=>380, 'protein'=>12, 'carbs'=>38, 'fats'=>20, 'category'=>'Breakfast'],
        // Lunch
        ['name'=>'Grilled Chicken Salad',    'calories'=>420, 'protein'=>38, 'carbs'=>18, 'fats'=>14, 'category'=>'Lunch'],
        ['name'=>'Tuna Wrap',                'calories'=>480, 'protein'=>32, 'carbs'=>52, 'fats'=>12, 'category'=>'Lunch'],
        ['name'=>'Lentil Soup & Bread',      'calories'=>390, 'protein'=>18, 'carbs'=>62, 'fats'=>6,  'category'=>'Lunch'],
        ['name'=>'Quinoa Buddha Bowl',       'calories'=>520, 'protein'=>22, 'carbs'=>68, 'fats'=>15, 'category'=>'Lunch'],
        ['name'=>'Turkey Sandwich',          'calories'=>450, 'protein'=>28, 'carbs'=>55, 'fats'=>10, 'category'=>'Lunch'],
        // Dinner
        ['name'=>'Baked Salmon & Veggies',   'calories'=>560, 'protein'=>45, 'carbs'=>22, 'fats'=>28, 'category'=>'Dinner'],
        ['name'=>'Chicken Rice & Broccoli',  'calories'=>580, 'protein'=>48, 'carbs'=>55, 'fats'=>10, 'category'=>'Dinner'],
        ['name'=>'Beef Stir Fry & Noodles',  'calories'=>640, 'protein'=>38, 'carbs'=>72, 'fats'=>18, 'category'=>'Dinner'],
        ['name'=>'Pasta with Tomato Sauce',  'calories'=>520, 'protein'=>18, 'carbs'=>88, 'fats'=>8,  'category'=>'Dinner'],
        ['name'=>'Grilled Tilapia & Rice',   'calories'=>480, 'protein'=>40, 'carbs'=>50, 'fats'=>8,  'category'=>'Dinner'],
        // Snacks
        ['name'=>'Mixed Nuts (30g)',         'calories'=>180, 'protein'=>5,  'carbs'=>6,  'fats'=>16, 'category'=>'Snack'],
        ['name'=>'Apple & Peanut Butter',    'calories'=>220, 'protein'=>7,  'carbs'=>30, 'fats'=>10, 'category'=>'Snack'],
        ['name'=>'Protein Bar',              'calories'=>200, 'protein'=>20, 'carbs'=>22, 'fats'=>6,  'category'=>'Snack'],
        ['name'=>'Cottage Cheese & Fruit',   'calories'=>190, 'protein'=>18, 'carbs'=>20, 'fats'=>3,  'category'=>'Snack'],
        ['name'=>'Rice Cakes with Hummus',   'calories'=>160, 'protein'=>5,  'carbs'=>28, 'fats'=>4,  'category'=>'Snack'],
    ];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Log a new meal entry. */
    public function logMeal(array $data): bool {
        $this->db->query(
            'INSERT INTO meals (user_id, food_name, calories, protein, carbs, fats)
             VALUES (:user_id, :food_name, :calories, :protein, :carbs, :fats)'
        );
        $this->db->bind(':user_id',   $data['user_id']);
        $this->db->bind(':food_name', $data['food_name']);
        $this->db->bind(':calories',  $data['calories']);
        $this->db->bind(':protein',   $data['protein']);
        $this->db->bind(':carbs',     $data['carbs']);
        $this->db->bind(':fats',      $data['fats']);
        return $this->db->execute();
    }

    /** Delete a meal by ID, ensuring it belongs to the user. */
    public function deleteMeal(int $mealId, int $userId): bool {
        $this->db->query('DELETE FROM meals WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id',      $mealId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /** Get all meals for a user, newest first. */
    public function getUserMeals(int $userId): array {
        $this->db->query(
            'SELECT * FROM meals WHERE user_id = :user_id ORDER BY logged_at DESC'
        );
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /** Get today's meals for a user. */
    public function getTodayMeals(int $userId): array {
        $this->db->query(
            'SELECT * FROM meals WHERE user_id = :user_id AND DATE(logged_at) = CURDATE() ORDER BY logged_at ASC'
        );
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /** Get today's macro totals for a user. */
    public function getTodayTotals(int $userId): object|bool {
        $this->db->query(
            'SELECT COALESCE(SUM(calories),0) as total_calories,
                    COALESCE(SUM(protein),0)  as total_protein,
                    COALESCE(SUM(carbs),0)    as total_carbs,
                    COALESCE(SUM(fats),0)     as total_fats
             FROM meals WHERE user_id = :user_id AND DATE(logged_at) = CURDATE()'
        );
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /** Get all-time macro totals. */
    public function getAllTimeTotals(int $userId): object|bool {
        $this->db->query(
            'SELECT COUNT(*) as total_entries,
                    COALESCE(SUM(calories),0) as total_calories,
                    COALESCE(SUM(protein),0)  as total_protein,
                    COALESCE(SUM(carbs),0)    as total_carbs,
                    COALESCE(SUM(fats),0)     as total_fats
             FROM meals WHERE user_id = :user_id'
        );
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /**
     * R3 — Recommend meals that fit within the remaining calorie budget.
     * @param int $remainingCalories
     * @param int $count Number of recommendations to return
     * @return array
     */
    public static function getRecommendations(int $remainingCalories, int $count = 4): array {
        $fits = array_filter(self::MEAL_LIBRARY, fn($m) => $m['calories'] <= $remainingCalories);
        shuffle($fits);
        return array_slice(array_values($fits), 0, $count);
    }
}

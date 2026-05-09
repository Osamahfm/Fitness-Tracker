<?php
// Flash message helper
$flash = '';
if (!empty($_SESSION['flash_success'])) {
    $flash = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}
?>

<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">My Workouts</h2>
            <p class="text-slate-400 text-sm mt-0.5">Track your physical activity and calories burned (R1 + R4)</p>
        </div>
        <a href="<?php echo URLROOT; ?>/workouts/create"
           style="background:linear-gradient(135deg,#14b8a6,#0d9488);"
           class="inline-flex items-center gap-2 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Log Workout
        </a>
    </div>

    <!-- Flash Message -->
    <?php if ($flash): ?>
    <div class="flex items-center gap-3 px-5 py-4 rounded-xl border border-green-500/30 text-green-300 text-sm" style="background:rgba(34,197,94,0.08);">
        <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span><?php echo $flash; ?></span>
    </div>
    <?php endif; ?>

    <!-- Stats Summary Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <?php
        $totalWorkouts  = $stats->total_workouts ?? 0;
        $totalCalories  = $stats->total_calories ?? 0;
        $totalMinutes   = $stats->total_minutes  ?? 0;
        $totalDistance  = $stats->total_distance ?? 0;
        $totalHours     = floor($totalMinutes / 60);
        $remainMins     = $totalMinutes % 60;
        $cards = [
            ['label'=>'Total Workouts',    'value'=> $totalWorkouts,   'suffix'=>'sessions',  'color'=>'#3b82f6', 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label'=>'Calories Burned',   'value'=> number_format($totalCalories), 'suffix'=>'kcal', 'color'=>'#f97316', 'icon'=>'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z'],
            ['label'=>'Time Trained',      'value'=> "{$totalHours}h {$remainMins}m", 'suffix'=>'total', 'color'=>'#8b5cf6', 'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'Distance Covered',  'value'=> number_format((float)$totalDistance, 1), 'suffix'=>'km',  'color'=>'#14b8a6', 'icon'=>'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
        ];
        foreach ($cards as $c): ?>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-slate-400 uppercase tracking-wider"><?php echo $c['label']; ?></span>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:<?php echo $c['color']; ?>1a;">
                    <svg class="w-4 h-4" style="color:<?php echo $c['color']; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?php echo $c['icon']; ?>"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white"><?php echo $c['value']; ?></p>
            <p class="text-xs text-slate-500 mt-1"><?php echo $c['suffix']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Workout History Table -->
    <div class="rounded-2xl border border-white/5 overflow-hidden" style="background:rgba(255,255,255,0.02);">
        <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
            <h3 class="font-semibold text-white">Workout History</h3>
            <span class="text-xs text-slate-500"><?php echo count($workouts); ?> entries</span>
        </div>

        <?php if (empty($workouts)): ?>
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4" style="background:rgba(20,184,166,0.1);">
                <svg class="w-8 h-8 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <p class="text-white font-semibold text-lg">No workouts yet</p>
            <p class="text-slate-400 text-sm mt-1 mb-6">Log your first workout to start tracking your progress.</p>
            <a href="<?php echo URLROOT; ?>/workouts/create"
               style="background:linear-gradient(135deg,#14b8a6,#0d9488);"
               class="text-white font-medium px-6 py-2.5 rounded-xl text-sm hover:-translate-y-0.5 transition-all inline-block">
                Log First Workout
            </a>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Date</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Activity</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Duration</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Distance</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Calories</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($workouts as $w):
                        $typeColors = [
                            'Running'=>'#3b82f6','Walking'=>'#22c55e','Cycling'=>'#f59e0b',
                            'Swimming'=>'#06b6d4','Weight Training'=>'#8b5cf6','HIIT'=>'#ef4444',
                            'Yoga'=>'#ec4899','Jump Rope'=>'#f97316','Rowing'=>'#14b8a6',
                            'Elliptical'=>'#6366f1','Pilates'=>'#a855f7','Dancing'=>'#f43f5e',
                        ];
                        $color = $typeColors[$w->type] ?? '#14b8a6';
                    ?>
                    <tr class="hover:bg-white/3 transition-colors">
                        <td class="px-6 py-4 text-slate-300 whitespace-nowrap">
                            <?php echo date('M j, Y', strtotime($w->workout_date)); ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:<?php echo $color; ?>;"></span>
                                <span class="font-medium text-white"><?php echo htmlspecialchars($w->type); ?></span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-300"><?php echo $w->duration; ?> min</td>
                        <td class="px-6 py-4 text-slate-300">
                            <?php echo $w->distance_km ? number_format((float)$w->distance_km, 1) . ' km' : '—'; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold" style="color:#f97316;"><?php echo number_format($w->calories_burned); ?> kcal</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="<?php echo URLROOT; ?>/workouts/delete/<?php echo $w->id; ?>"
                                  onsubmit="return confirm('Delete this workout?')">
                                <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors text-xs font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

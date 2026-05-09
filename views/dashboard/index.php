<?php
$flash = '';
if (!empty($_SESSION['flash_success'])) {
    $flash = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}
$totalWorkouts = $stats->total_workouts ?? 0;
$totalCalories = (int)($stats->total_calories ?? 0);
$totalMinutes  = (int)($stats->total_minutes ?? 0);
$totalDistance = (float)($stats->total_distance ?? 0);
$totalHours    = floor($totalMinutes / 60);
$remainMins    = $totalMinutes % 60;
?>

<div class="space-y-6">

    <!-- Flash Message -->
    <?php if ($flash): ?>
    <div class="flex items-center gap-3 px-5 py-4 rounded-xl border border-green-500/30 text-green-300 text-sm" style="background:rgba(34,197,94,0.08);">
        <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span><?php echo $flash; ?></span>
    </div>
    <?php endif; ?>

    <!-- Welcome Banner -->
    <div class="relative rounded-2xl p-7 overflow-hidden border border-brand-500/20" style="background:linear-gradient(135deg,rgba(20,184,166,0.1),rgba(13,148,136,0.05));">
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10" style="background:radial-gradient(circle,#14b8a6,transparent);transform:translate(30%,-30%);"></div>
        <div class="relative">
            <p class="text-brand-400 font-medium text-sm mb-1">👋 Welcome back,</p>
            <h1 class="text-2xl font-bold text-white">
                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500/20 text-red-400 border border-red-500/30 align-middle">ADMIN</span>
                <?php endif; ?>
            </h1>
            <p class="text-slate-400 text-sm mt-1.5">
                <?php if ($totalWorkouts == 0): ?>
                    Ready to start your fitness journey? Log your first workout below.
                <?php else: ?>
                    You've logged <strong class="text-white"><?php echo $totalWorkouts; ?> workout<?php echo $totalWorkouts > 1 ? 's' : ''; ?></strong> and burned <strong class="text-brand-400"><?php echo number_format($totalCalories); ?> kcal</strong> in total. Keep it up!
                <?php endif; ?>
            </p>
        </div>
        <a href="<?php echo URLROOT; ?>/workouts/create"
           style="background:linear-gradient(135deg,#14b8a6,#0d9488);"
           class="absolute right-7 top-1/2 -translate-y-1/2 hidden lg:inline-flex items-center gap-2 text-white font-semibold px-5 py-2.5 rounded-xl text-sm hover:-translate-y-1 transition-all hover:shadow-lg hover:shadow-brand-500/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Log Workout
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <?php
        $statCards = [
            ['label'=>'Workouts This Week', 'value'=>$weeklyCount,                           'unit'=>'sessions',              'color'=>'#3b82f6', 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label'=>'Total Calories',     'value'=>number_format($totalCalories),            'unit'=>'kcal burned',           'color'=>'#f97316', 'icon'=>'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z'],
            ['label'=>'Time Trained',       'value'=>"{$totalHours}h {$remainMins}m",         'unit'=>'total duration',        'color'=>'#8b5cf6', 'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'Distance Covered',   'value'=>number_format($totalDistance, 1) . ' km','unit'=>'all workouts',          'color'=>'#14b8a6', 'icon'=>'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
        ];
        foreach ($statCards as $c): ?>
        <div class="stat-card group cursor-default">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider leading-tight"><?php echo $c['label']; ?></p>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110" style="background:<?php echo $c['color']; ?>1a;">
                    <svg class="w-5 h-5" style="color:<?php echo $c['color']; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?php echo $c['icon']; ?>"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-black text-white"><?php echo $c['value']; ?></p>
            <p class="text-xs text-slate-500 mt-1"><?php echo $c['unit']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Chart + Recent Activity -->
    <div class="grid lg:grid-cols-5 gap-6">

        <!-- Weekly Calorie Chart (3/5 width) -->
        <div class="lg:col-span-3 rounded-2xl border border-white/5 p-6" style="background:rgba(255,255,255,0.02);">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-semibold text-white">Weekly Calories Burned</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Last 7 days</p>
                </div>
                <div class="px-3 py-1 rounded-full text-xs font-medium" style="background:rgba(20,184,166,0.1);color:#2dd4bf;">
                    R4 — MET Formula
                </div>
            </div>
            <canvas id="weeklyCalChart" height="200"></canvas>
        </div>

        <!-- Recent Workouts (2/5 width) -->
        <div class="lg:col-span-2 rounded-2xl border border-white/5 p-6" style="background:rgba(255,255,255,0.02);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-white">Recent Activity</h3>
                <a href="<?php echo URLROOT; ?>/workouts" class="text-xs text-brand-400 hover:text-brand-300 transition-colors font-medium">View all →</a>
            </div>

            <?php if (empty($recentLogs)): ?>
            <div class="flex flex-col items-center justify-center h-40 text-center">
                <svg class="w-10 h-10 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <p class="text-slate-400 text-sm">No workouts yet.</p>
                <a href="<?php echo URLROOT; ?>/workouts/create" class="text-brand-400 text-xs mt-2 hover:text-brand-300 transition-colors">Log your first →</a>
            </div>
            <?php else: ?>
            <div class="space-y-3">
                <?php
                $typeColors = ['Running'=>'#3b82f6','Walking'=>'#22c55e','Cycling'=>'#f59e0b','Swimming'=>'#06b6d4','Weight Training'=>'#8b5cf6','HIIT'=>'#ef4444','Yoga'=>'#ec4899','Jump Rope'=>'#f97316','Rowing'=>'#14b8a6','Elliptical'=>'#6366f1','Pilates'=>'#a855f7','Dancing'=>'#f43f5e'];
                foreach ($recentLogs as $w):
                    $color = $typeColors[$w->type] ?? '#14b8a6';
                ?>
                <div class="flex items-center gap-3 p-3 rounded-xl transition-colors hover:bg-white/3" style="border:1px solid rgba(255,255,255,0.04);">
                    <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center text-white" style="background:<?php echo $color; ?>20;">
                        <span class="text-base" style="color:<?php echo $color; ?>;">⚡</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate"><?php echo htmlspecialchars($w->type); ?></p>
                        <p class="text-xs text-slate-500"><?php echo date('M j', strtotime($w->workout_date)); ?> · <?php echo $w->duration; ?> min</p>
                    </div>
                    <span class="text-sm font-semibold flex-shrink-0" style="color:#f97316;"><?php echo number_format($w->calories_burned); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Action Cards -->
    <div class="grid sm:grid-cols-3 gap-4">
        <?php
        $quickActions = [
            ['href'=>'/workouts/create', 'label'=>'Log Workout',   'desc'=>'Record a new session',    'color'=>'#14b8a6', 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
            ['href'=>'/meals',           'label'=>'Track Nutrition','desc'=>'Log meals & macros',       'color'=>'#22c55e', 'icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['href'=>'/goals',           'label'=>'Set Goals',      'desc'=>'Define your targets',      'color'=>'#8b5cf6', 'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
        foreach ($quickActions as $a): ?>
        <a href="<?php echo URLROOT . $a['href']; ?>"
           class="flex items-center gap-4 p-5 rounded-2xl border transition-all hover:-translate-y-1 hover:shadow-lg group"
           style="background:rgba(255,255,255,0.02); border-color:rgba(255,255,255,0.06);">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110" style="background:<?php echo $a['color']; ?>1a;">
                <svg class="w-6 h-6" style="color:<?php echo $a['color']; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?php echo $a['icon']; ?>"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-white text-sm"><?php echo $a['label']; ?></p>
                <p class="text-xs text-slate-500"><?php echo $a['desc']; ?></p>
            </div>
            <svg class="w-4 h-4 text-slate-600 ml-auto group-hover:text-slate-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels   = <?php echo $chartLabels; ?>;
const calories = <?php echo $chartData; ?>;

const ctx = document.getElementById('weeklyCalChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Calories Burned',
            data: calories,
            backgroundColor: calories.map(v => v > 0 ? 'rgba(20,184,166,0.5)' : 'rgba(255,255,255,0.04)'),
            borderColor:     calories.map(v => v > 0 ? '#14b8a6' : 'rgba(255,255,255,0.08)'),
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1e293b',
                titleColor: '#94a3b8',
                bodyColor: '#f1f5f9',
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: ctx => `${ctx.parsed.y.toLocaleString()} kcal`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,0.04)' },
                ticks: { color: '#475569', font: { family: 'Inter', size: 11 } }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#64748b', font: { family: 'Inter', size: 12 } }
            }
        }
    }
});
</script>

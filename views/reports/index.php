<?php
$netColor = $summary->net_balance <= 0 ? '#22c55e' : '#f97316';
$netLabel = $summary->net_balance <= 0 ? 'Caloric Deficit ✓' : 'Caloric Surplus';
$formattedDate = date('l, F j, Y', strtotime($summary->date));
$targetCal = $activeGoal ? (int)$activeGoal->target_calories : 2000;
$isToday = ($summary->date === date('Y-m-d'));
?>

<div class="space-y-6">

    <!-- Header + Date Picker -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">Daily Report</h2>
            <p class="text-slate-400 text-sm mt-0.5">Auto-generated fitness summary (R7)</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="<?php echo URLROOT; ?>/reports" class="flex items-center gap-2">
                <input type="date" name="date"
                       value="<?php echo $date; ?>"
                       max="<?php echo date('Y-m-d'); ?>"
                       onchange="this.form.submit()"
                       class="rounded-xl px-4 py-2.5 text-sm text-white border outline-none transition-all cursor-pointer"
                       style="background:#1e293b;border-color:rgba(255,255,255,0.1);color-scheme:dark;font-family:Inter,sans-serif;">
            </form>
            <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-300 border transition-all hover:bg-white/5"
                    style="border-color:rgba(255,255,255,0.1);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Export
            </button>
        </div>
    </div>

    <!-- R6 Activity Alarm Banner -->
    <?php if ($isToday && !$hasWorkout): ?>
    <div id="alarm-banner" class="flex items-center justify-between gap-4 px-5 py-4 rounded-xl border" style="background:rgba(249,115,22,0.08);border-color:rgba(249,115,22,0.3);">
        <div class="flex items-center gap-3">
            <span class="text-2xl animate-pulse">⏰</span>
            <div>
                <p class="text-sm font-semibold text-orange-300">Activity Alarm (R6)</p>
                <p class="text-xs text-orange-400/70">You haven't logged a workout today. Stay consistent!</p>
            </div>
        </div>
        <a href="<?php echo URLROOT; ?>/workouts/create"
           class="flex-shrink-0 text-xs font-semibold px-4 py-2 rounded-lg transition-all hover:scale-105"
           style="background:rgba(249,115,22,0.2);color:#fb923c;border:1px solid rgba(249,115,22,0.3);">
            Log Now →
        </a>
    </div>
    <?php elseif ($isToday && $hasWorkout): ?>
    <div class="flex items-center gap-3 px-5 py-4 rounded-xl border border-green-500/30 text-green-300 text-sm" style="background:rgba(34,197,94,0.06);">
        <span class="text-lg">✅</span>
        <span><strong>Great work!</strong> You've logged a workout today — alarm satisfied (R6).</span>
    </div>
    <?php endif; ?>

    <!-- Date Badge -->
    <div class="flex items-center gap-3">
        <h3 class="text-lg font-semibold text-white"><?php echo $formattedDate; ?></h3>
        <?php if ($isToday): ?><span class="text-xs px-2.5 py-1 rounded-full font-medium" style="background:rgba(20,184,166,0.1);color:#2dd4bf;">Today</span><?php endif; ?>
    </div>

    <!-- Key Metrics Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <?php
        $metrics = [
            ['label'=>'Calories Burned',   'val'=>number_format($summary->calories_burned),  'unit'=>'kcal', 'color'=>'#f97316', 'icon'=>'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z'],
            ['label'=>'Calories Consumed', 'val'=>number_format($summary->calories_consumed), 'unit'=>'kcal', 'color'=>'#22c55e', 'icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['label'=>'Workouts',          'val'=>$summary->workout_count,                   'unit'=>'sessions', 'color'=>'#3b82f6', 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label'=>'Meals Logged',      'val'=>$summary->meal_count,                      'unit'=>'entries', 'color'=>'#8b5cf6', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ];
        foreach ($metrics as $m): ?>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider"><?php echo $m['label']; ?></p>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:<?php echo $m['color']; ?>1a;">
                    <svg class="w-4 h-4" style="color:<?php echo $m['color']; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?php echo $m['icon']; ?>"/></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-white"><?php echo $m['val']; ?></p>
            <p class="text-xs text-slate-500 mt-1"><?php echo $m['unit']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Net Balance Card -->
    <div class="rounded-2xl p-6 border" style="background:<?php echo $summary->net_balance <= 0 ? 'rgba(34,197,94,0.06)' : 'rgba(249,115,22,0.06)'; ?>;border-color:<?php echo $summary->net_balance <= 0 ? 'rgba(34,197,94,0.2)' : 'rgba(249,115,22,0.2)'; ?>;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:<?php echo $netColor; ?>;"><?php echo $netLabel; ?></p>
                <p class="text-4xl font-black text-white"><?php echo ($summary->net_balance > 0 ? '+' : '') . number_format($summary->net_balance); ?> <span class="text-lg font-normal text-slate-400">kcal net</span></p>
                <p class="text-sm text-slate-400 mt-2">
                    <?php echo number_format($summary->calories_consumed); ?> consumed
                    − <?php echo number_format($summary->calories_burned); ?> burned
                    <?php if ($activeGoal): ?> · Goal: <?php echo number_format($targetCal); ?> kcal/day<?php endif; ?>
                </p>
            </div>
            <div class="text-6xl"><?php echo $summary->net_balance <= 0 ? '🔥' : '🍽️'; ?></div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid lg:grid-cols-3 gap-6">

        <!-- 30-Day Trend Chart -->
        <div class="lg:col-span-2 rounded-2xl border border-white/5 p-6" style="background:rgba(255,255,255,0.02);">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-semibold text-white">30-Day Calorie Trend</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Burned vs Consumed</p>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm inline-block" style="background:#f97316;"></span> Burned</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm inline-block" style="background:#22c55e;"></span> Consumed</span>
                </div>
            </div>
            <canvas id="trendChart" height="180"></canvas>
        </div>

        <!-- Macros Pie Chart -->
        <div class="rounded-2xl border border-white/5 p-6" style="background:rgba(255,255,255,0.02);">
            <h3 class="font-semibold text-white mb-1">Today's Macros</h3>
            <p class="text-xs text-slate-500 mb-5">Protein / Carbs / Fats</p>
            <?php if ($summary->meal_count > 0): ?>
            <canvas id="macroChart" height="180"></canvas>
            <div class="grid grid-cols-3 gap-2 mt-4 text-center text-xs">
                <div><p class="font-bold text-white"><?php echo number_format($summary->total_protein, 1); ?>g</p><p class="text-blue-400">Protein</p></div>
                <div><p class="font-bold text-white"><?php echo number_format($summary->total_carbs, 1); ?>g</p><p class="text-orange-400">Carbs</p></div>
                <div><p class="font-bold text-white"><?php echo number_format($summary->total_fats, 1); ?>g</p><p class="text-purple-400">Fats</p></div>
            </div>
            <?php else: ?>
            <div class="flex flex-col items-center justify-center h-40 text-center">
                <p class="text-slate-500 text-sm">No meals logged for this date.</p>
                <?php if ($isToday): ?><a href="<?php echo URLROOT; ?>/meals/log" class="text-green-400 text-xs mt-2 hover:text-green-300">Log a meal →</a><?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Workout Detail Table -->
    <?php if (!empty($summary->workouts)): ?>
    <div class="rounded-2xl border border-white/5 overflow-hidden" style="background:rgba(255,255,255,0.02);">
        <div class="px-6 py-4 border-b border-white/5"><h3 class="font-semibold text-white">Workouts</h3></div>
        <table class="w-full text-sm">
            <thead><tr class="border-b border-white/5">
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Activity</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Duration</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Distance</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Calories</th>
            </tr></thead>
            <tbody class="divide-y divide-white/5">
            <?php foreach ($summary->workouts as $w): ?>
            <tr><td class="px-6 py-3 font-medium text-white"><?php echo htmlspecialchars($w->type); ?></td>
                <td class="px-6 py-3 text-slate-300"><?php echo $w->duration; ?> min</td>
                <td class="px-6 py-3 text-slate-300"><?php echo $w->distance_km ? number_format((float)$w->distance_km, 1) . ' km' : '—'; ?></td>
                <td class="px-6 py-3 font-semibold" style="color:#f97316;"><?php echo number_format($w->calories_burned); ?> kcal</td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Meals Detail Table -->
    <?php if (!empty($summary->meals)): ?>
    <div class="rounded-2xl border border-white/5 overflow-hidden" style="background:rgba(255,255,255,0.02);">
        <div class="px-6 py-4 border-b border-white/5"><h3 class="font-semibold text-white">Meals</h3></div>
        <table class="w-full text-sm">
            <thead><tr class="border-b border-white/5">
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Food</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Protein</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Carbs</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Fats</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Calories</th>
            </tr></thead>
            <tbody class="divide-y divide-white/5">
            <?php foreach ($summary->meals as $m): ?>
            <tr><td class="px-6 py-3 font-medium text-white"><?php echo htmlspecialchars($m->food_name); ?></td>
                <td class="px-6 py-3 text-slate-300"><?php echo $m->protein; ?>g</td>
                <td class="px-6 py-3 text-slate-300"><?php echo $m->carbs; ?>g</td>
                <td class="px-6 py-3 text-slate-300"><?php echo $m->fats; ?>g</td>
                <td class="px-6 py-3 font-semibold" style="color:#22c55e;"><?php echo $m->calories; ?> kcal</td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 30-Day Trend Chart
const trendCtx = document.getElementById('trendChart').getContext('2d');
new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($trend['days']); ?>,
        datasets: [
            {
                label: 'Burned',
                data: <?php echo json_encode($trend['burned']); ?>,
                borderColor: '#f97316',
                backgroundColor: 'rgba(249,115,22,0.08)',
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 4,
                tension: 0.4,
                fill: true,
            },
            {
                label: 'Consumed',
                data: <?php echo json_encode($trend['consumed']); ?>,
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,0.08)',
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 4,
                tension: 0.4,
                fill: true,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: '#1e293b',
                titleColor: '#94a3b8',
                bodyColor: '#f1f5f9',
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#475569', font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { color: '#475569', font: { size: 9 }, maxTicksLimit: 10 } }
        }
    }
});

// Macros Pie Chart
<?php if ($summary->meal_count > 0): ?>
const macroCtx = document.getElementById('macroChart').getContext('2d');
new Chart(macroCtx, {
    type: 'doughnut',
    data: {
        labels: ['Protein', 'Carbs', 'Fats'],
        datasets: [{
            data: [<?php echo $summary->total_protein; ?>, <?php echo $summary->total_carbs; ?>, <?php echo $summary->total_fats; ?>],
            backgroundColor: ['rgba(59,130,246,0.8)', 'rgba(249,115,22,0.8)', 'rgba(168,85,247,0.8)'],
            borderColor: ['#3b82f6', '#f97316', '#a855f7'],
            borderWidth: 2,
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: { backgroundColor: '#1e293b', bodyColor: '#f1f5f9', borderColor: 'rgba(255,255,255,0.08)', borderWidth: 1 }
        }
    }
});
<?php endif; ?>

// R6 — Activity Alarm: Browser Notification API
<?php if ($isToday && !$hasWorkout): ?>
function requestWorkoutAlarm() {
    if ('Notification' in window && Notification.permission !== 'granted') {
        Notification.requestPermission().then(perm => {
            if (perm === 'granted') {
                new Notification('FitTrack Reminder 💪', {
                    body: "You haven't logged a workout today. Time to move!",
                    icon: ''
                });
            }
        });
    }
}
// Auto-request on page load
window.addEventListener('load', () => setTimeout(requestWorkoutAlarm, 1500));

// Alarm scheduler from localStorage
const alarmTime = localStorage.getItem('fittrack_alarm_time');
if (alarmTime) {
    const [alarmH, alarmM] = alarmTime.split(':').map(Number);
    const now = new Date();
    if (now.getHours() >= alarmH && now.getMinutes() >= alarmM) {
        requestWorkoutAlarm();
    }
}
<?php endif; ?>
</script>

<!-- Print Styles -->
<style media="print">
    #sidebar, header, .no-print { display:none !important; }
    body { background: white !important; color: black !important; }
    .stat-card, .rounded-2xl { border: 1px solid #e5e7eb !important; background: white !important; }
    .text-white { color: #111 !important; }
    .text-slate-400, .text-slate-500 { color: #6b7280 !important; }
</style>

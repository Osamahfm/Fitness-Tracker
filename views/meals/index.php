<?php
$flash = '';
if (!empty($_SESSION['flash_success'])) { $flash = $_SESSION['flash_success']; unset($_SESSION['flash_success']); }
$todayCal    = (int)($todayTotals->total_calories ?? 0);
$todayProt   = (float)($todayTotals->total_protein ?? 0);
$todayCarbs  = (float)($todayTotals->total_carbs ?? 0);
$todayFats   = (float)($todayTotals->total_fats ?? 0);
$calPct      = $targetCalories > 0 ? min(100, round(($todayCal / $targetCalories) * 100)) : 0;
$barColor    = $calPct >= 100 ? '#ef4444' : ($calPct >= 80 ? '#f97316' : '#14b8a6');
?>

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">Nutrition Tracker</h2>
            <p class="text-slate-400 text-sm mt-0.5">Track meals and get smart recommendations (R3)</p>
        </div>
        <a href="<?php echo URLROOT; ?>/meals/log"
           style="background:linear-gradient(135deg,#22c55e,#16a34a);"
           class="inline-flex items-center gap-2 text-white font-semibold px-5 py-2.5 rounded-xl text-sm hover:-translate-y-0.5 transition-all hover:shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Log Meal
        </a>
    </div>

    <!-- Flash -->
    <?php if ($flash): ?>
    <div class="flex items-center gap-3 px-5 py-4 rounded-xl border border-green-500/30 text-green-300 text-sm" style="background:rgba(34,197,94,0.08);">
        <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span><?php echo $flash; ?></span>
    </div>
    <?php endif; ?>

    <!-- Today's Calorie Progress -->
    <div class="rounded-2xl p-6 border border-white/5" style="background:rgba(255,255,255,0.02);">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Today's Calorie Goal</p>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-black text-white"><?php echo number_format($todayCal); ?></span>
                    <span class="text-slate-400 text-sm mb-1">/ <?php echo number_format($targetCalories); ?> kcal</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 mb-1">Remaining</p>
                <p class="text-xl font-bold <?php echo $remainingToday <= 0 ? 'text-red-400' : 'text-brand-400'; ?>">
                    <?php echo $remainingToday <= 0 ? 'Goal reached!' : number_format($remainingToday) . ' kcal'; ?>
                </p>
            </div>
        </div>
        <!-- Progress bar -->
        <div class="h-3 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.05);">
            <div class="h-full rounded-full transition-all duration-700"
                 style="width:<?php echo $calPct; ?>%;background:<?php echo $barColor; ?>;box-shadow:0 0 10px <?php echo $barColor; ?>60;"></div>
        </div>
        <p class="text-xs text-slate-500 mt-2"><?php echo $calPct; ?>% of daily goal consumed</p>

        <!-- Macro breakdown -->
        <div class="grid grid-cols-3 gap-4 mt-5 pt-5 border-t border-white/5">
            <?php
            $macros = [
                ['label'=>'Protein', 'val'=>$todayProt, 'unit'=>'g', 'color'=>'#3b82f6'],
                ['label'=>'Carbs',   'val'=>$todayCarbs, 'unit'=>'g', 'color'=>'#f97316'],
                ['label'=>'Fats',    'val'=>$todayFats,  'unit'=>'g', 'color'=>'#a855f7'],
            ];
            foreach ($macros as $m): ?>
            <div class="text-center">
                <p class="text-lg font-bold text-white"><?php echo number_format($m['val'], 1); ?><span class="text-sm font-normal text-slate-400">g</span></p>
                <p class="text-xs font-medium mt-0.5" style="color:<?php echo $m['color']; ?>;"><?php echo $m['label']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="grid lg:grid-cols-5 gap-6">

        <!-- Today's Meals -->
        <div class="lg:col-span-3 rounded-2xl border border-white/5 overflow-hidden" style="background:rgba(255,255,255,0.02);">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-semibold text-white">Today's Meals</h3>
                <span class="text-xs text-slate-500"><?php echo count($todayMeals); ?> entries</span>
            </div>
            <?php if (empty($todayMeals)): ?>
            <div class="flex flex-col items-center justify-center py-14 text-center">
                <svg class="w-10 h-10 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <p class="text-slate-400 text-sm">No meals logged today.</p>
                <a href="<?php echo URLROOT; ?>/meals/log" class="text-green-400 text-xs mt-2 hover:text-green-300 transition-colors">Log your first meal →</a>
            </div>
            <?php else: ?>
            <div class="divide-y divide-white/5">
                <?php foreach ($todayMeals as $m): ?>
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-white/2 transition-colors">
                    <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center text-lg" style="background:rgba(34,197,94,0.1);">🥗</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate"><?php echo htmlspecialchars($m->food_name); ?></p>
                        <p class="text-xs text-slate-500">P: <?php echo $m->protein; ?>g &middot; C: <?php echo $m->carbs; ?>g &middot; F: <?php echo $m->fats; ?>g &middot; <?php echo date('H:i', strtotime($m->logged_at)); ?></p>
                    </div>
                    <span class="font-semibold text-sm" style="color:#22c55e;"><?php echo $m->calories; ?> kcal</span>
                    <form method="POST" action="<?php echo URLROOT; ?>/meals/delete/<?php echo $m->id; ?>" onsubmit="return confirm('Remove this meal?')">
                        <button type="submit" class="text-slate-600 hover:text-red-400 transition-colors text-xs">✕</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- R3 Meal Recommendations -->
        <div class="lg:col-span-2 rounded-2xl border border-white/5 p-6" style="background:rgba(255,255,255,0.02);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-white">Meal Suggestions</h3>
                <span class="text-xs px-2.5 py-1 rounded-full font-medium" style="background:rgba(34,197,94,0.1);color:#22c55e;">R3</span>
            </div>
            <?php if ($remainingToday <= 0): ?>
            <div class="flex flex-col items-center justify-center h-40 text-center">
                <p class="text-3xl mb-2">🎯</p>
                <p class="text-white font-semibold">Daily goal reached!</p>
                <p class="text-slate-400 text-xs mt-1">Great work staying on track today.</p>
            </div>
            <?php elseif (empty($recommendations)): ?>
            <p class="text-slate-400 text-sm">No meals fit your remaining budget. Log a goal to set your calorie target.</p>
            <?php else: ?>
            <p class="text-xs text-slate-500 mb-4">Fits within your <strong class="text-white"><?php echo number_format($remainingToday); ?> kcal</strong> remaining budget:</p>
            <div class="space-y-3">
                <?php foreach ($recommendations as $rec): ?>
                <div class="flex items-center gap-3 p-3 rounded-xl border transition-all hover:border-green-500/20" style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.05);">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate"><?php echo $rec['name']; ?></p>
                        <p class="text-xs text-slate-500"><?php echo $rec['category']; ?> &middot; P:<?php echo $rec['protein']; ?>g C:<?php echo $rec['carbs']; ?>g F:<?php echo $rec['fats']; ?>g</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-xs font-semibold text-green-400"><?php echo $rec['calories']; ?> kcal</span>
                        <a href="<?php echo URLROOT; ?>/meals/log?name=<?php echo urlencode($rec['name']); ?>&calories=<?php echo $rec['calories']; ?>&protein=<?php echo $rec['protein']; ?>&carbs=<?php echo $rec['carbs']; ?>&fats=<?php echo $rec['fats']; ?>"
                           class="w-6 h-6 rounded-lg flex items-center justify-center text-white text-sm transition-all hover:scale-110" style="background:rgba(34,197,94,0.2);">+</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Full Meal History -->
    <?php if (!empty($allMeals)): ?>
    <div class="rounded-2xl border border-white/5 overflow-hidden" style="background:rgba(255,255,255,0.02);">
        <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
            <h3 class="font-semibold text-white">Full History</h3>
            <span class="text-xs text-slate-500"><?php echo $allTotals->total_entries ?? 0; ?> total entries &middot; <?php echo number_format($allTotals->total_calories ?? 0); ?> kcal lifetime</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-white/5">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Date & Time</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Food</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Protein</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Carbs</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Fats</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Calories</th>
                    <th class="px-6 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-white/5">
                <?php foreach ($allMeals as $m): ?>
                <tr class="hover:bg-white/2 transition-colors">
                    <td class="px-6 py-3 text-slate-400 text-xs"><?php echo date('M j, H:i', strtotime($m->logged_at)); ?></td>
                    <td class="px-6 py-3 font-medium text-white"><?php echo htmlspecialchars($m->food_name); ?></td>
                    <td class="px-6 py-3 text-slate-300"><?php echo $m->protein; ?>g</td>
                    <td class="px-6 py-3 text-slate-300"><?php echo $m->carbs; ?>g</td>
                    <td class="px-6 py-3 text-slate-300"><?php echo $m->fats; ?>g</td>
                    <td class="px-6 py-3 font-semibold" style="color:#22c55e;"><?php echo $m->calories; ?></td>
                    <td class="px-6 py-3 text-right">
                        <form method="POST" action="<?php echo URLROOT; ?>/meals/delete/<?php echo $m->id; ?>" onsubmit="return confirm('Delete?')">
                            <button type="submit" class="text-slate-600 hover:text-red-400 transition-colors text-xs font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$flash = '';
if (!empty($_SESSION['flash_success'])) { $flash = $_SESSION['flash_success']; unset($_SESSION['flash_success']); }
$goalTypeLabels = ['weight_loss'=>'Weight Loss', 'muscle_gain'=>'Muscle Gain', 'maintenance'=>'Maintenance', 'endurance'=>'Endurance'];
$goalTypeColors = ['weight_loss'=>'#ef4444', 'muscle_gain'=>'#3b82f6', 'maintenance'=>'#14b8a6', 'endurance'=>'#f97316'];
$statusColors   = ['active'=>'#22c55e', 'achieved'=>'#14b8a6', 'abandoned'=>'#475569'];
?>

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">Fitness Goals</h2>
            <p class="text-slate-400 text-sm mt-0.5">Set and track your fitness objectives (R2)</p>
        </div>
        <a href="<?php echo URLROOT; ?>/goals/create"
           style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);"
           class="inline-flex items-center gap-2 text-white font-semibold px-5 py-2.5 rounded-xl text-sm hover:-translate-y-0.5 transition-all hover:shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Set New Goal
        </a>
    </div>

    <!-- Flash -->
    <?php if ($flash): ?>
    <div class="flex items-center gap-3 px-5 py-4 rounded-xl border border-green-500/30 text-green-300 text-sm" style="background:rgba(34,197,94,0.08);">
        <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span><?php echo $flash; ?></span>
    </div>
    <?php endif; ?>

    <?php if ($activeGoal): ?>
    <!-- Active Goal Card -->
    <div class="rounded-2xl p-7 border relative overflow-hidden" style="background:linear-gradient(135deg,rgba(139,92,246,0.1),rgba(124,58,237,0.05));border-color:rgba(139,92,246,0.3);">
        <div class="absolute top-0 right-0 w-48 h-48 rounded-full opacity-10" style="background:radial-gradient(circle,#8b5cf6,transparent);transform:translate(30%,-30%);"></div>
        <div class="relative">
            <div class="flex items-start justify-between flex-wrap gap-3 mb-5">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold mb-2" style="background:rgba(34,197,94,0.15);color:#22c55e;">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                        Active Goal
                    </span>
                    <h3 class="text-xl font-bold text-white"><?php echo $goalTypeLabels[$activeGoal->goal_type] ?? $activeGoal->goal_type; ?></h3>
                    <?php if ($activeGoal->notes): ?>
                    <p class="text-slate-400 text-sm mt-1"><?php echo htmlspecialchars($activeGoal->notes); ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="<?php echo URLROOT; ?>/goals/achieve/<?php echo $activeGoal->id; ?>">
                        <button type="submit" class="text-xs font-medium px-4 py-2 rounded-lg border transition-all hover:bg-white/5" style="border-color:rgba(34,197,94,0.3);color:#22c55e;">✓ Mark Achieved</button>
                    </form>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-5">
                <?php
                $goalStats = [
                    ['label'=>'Target Weight', 'val'=>number_format($activeGoal->target_weight, 1) . ' kg', 'color'=>'#8b5cf6'],
                    ['label'=>'Daily Calories', 'val'=>number_format($activeGoal->target_calories) . ' kcal', 'color'=>'#f97316'],
                    ['label'=>'Workouts/Week', 'val'=>$activeGoal->target_workouts_per_week . ' sessions', 'color'=>'#14b8a6'],
                ];
                foreach ($goalStats as $gs): ?>
                <div class="p-4 rounded-xl" style="background:rgba(255,255,255,0.04);">
                    <p class="text-xs text-slate-400 mb-1"><?php echo $gs['label']; ?></p>
                    <p class="font-bold text-white"><?php echo $gs['val']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Weekly Progress Bar -->
            <div>
                <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                    <span>Weekly Workout Progress</span>
                    <span><?php echo $weeklyCount; ?> / <?php echo $activeGoal->target_workouts_per_week; ?> sessions</span>
                </div>
                <div class="h-2.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                    <div class="h-full rounded-full transition-all duration-700"
                         style="width:<?php echo $workoutProgress; ?>%;background:linear-gradient(90deg,#8b5cf6,#6d28d9);"></div>
                </div>
                <p class="text-xs text-slate-500 mt-1.5"><?php echo $workoutProgress; ?>% of weekly workout goal</p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- No Active Goal -->
    <div class="rounded-2xl border border-dashed border-white/10 p-10 text-center">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(139,92,246,0.1);">
            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-white font-semibold text-lg mb-1">No active goal</p>
        <p class="text-slate-400 text-sm mb-6">Set a goal to get personalised recommendations and track your progress.</p>
        <a href="<?php echo URLROOT; ?>/goals/create"
           style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);"
           class="inline-flex items-center gap-2 text-white font-semibold px-6 py-3 rounded-xl text-sm hover:-translate-y-0.5 transition-all">
            Set Your First Goal
        </a>
    </div>
    <?php endif; ?>

    <!-- Goal History -->
    <?php if (!empty($allGoals)): ?>
    <div class="rounded-2xl border border-white/5 overflow-hidden" style="background:rgba(255,255,255,0.02);">
        <div class="px-6 py-4 border-b border-white/5">
            <h3 class="font-semibold text-white">Goal History</h3>
        </div>
        <div class="divide-y divide-white/5">
            <?php foreach ($allGoals as $g):
                $gc = $goalTypeColors[$g->goal_type] ?? '#14b8a6';
                $sc = $statusColors[$g->status]     ?? '#475569';
            ?>
            <div class="flex items-center gap-4 px-6 py-4 hover:bg-white/2 transition-colors">
                <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:<?php echo $gc; ?>;"></div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-white text-sm"><?php echo $goalTypeLabels[$g->goal_type] ?? $g->goal_type; ?></p>
                    <p class="text-xs text-slate-500"><?php echo date('M j, Y', strtotime($g->created_at)); ?> &middot; <?php echo $g->target_calories; ?> kcal/day &middot; <?php echo $g->target_workouts_per_week; ?>x/week</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full capitalize" style="background:<?php echo $sc; ?>20;color:<?php echo $sc; ?>;"><?php echo $g->status; ?></span>
                <?php if ($g->status === 'active'): ?>
                <form method="POST" action="<?php echo URLROOT; ?>/goals/delete/<?php echo $g->id; ?>" onsubmit="return confirm('Delete this goal?')">
                    <button type="submit" class="text-slate-600 hover:text-red-400 transition-colors text-xs font-medium">Delete</button>
                </form>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

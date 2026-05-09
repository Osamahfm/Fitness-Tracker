<?php
// MET values passed from controller
$metJson = json_encode($met);
?>

<div class="max-w-2xl mx-auto space-y-6">

    <!-- Page Header -->
    <div>
        <a href="<?php echo URLROOT; ?>/workouts"
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white text-sm mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Workouts
        </a>
        <h2 class="text-2xl font-bold text-white">Log a Workout</h2>
        <p class="text-slate-400 text-sm mt-1">Calories are calculated automatically using the MET formula.</p>
    </div>

    <!-- Calorie Preview Card -->
    <div id="calorie-preview" class="rounded-2xl p-5 border transition-all duration-300"
         style="background:rgba(20,184,166,0.08); border-color:rgba(20,184,166,0.2);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-brand-400 uppercase tracking-wider mb-1">Estimated Calories Burned</p>
                <p id="calorie-value" class="text-4xl font-black text-white">—</p>
                <p class="text-xs text-slate-400 mt-1" id="calorie-formula">Enter activity, duration, and weight to calculate.</p>
            </div>
            <div class="w-14 h-14 rounded-xl flex items-center justify-center" style="background:rgba(20,184,166,0.15);">
                <svg class="w-7 h-7 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3 h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
            <div id="calorie-bar" class="h-full rounded-full transition-all duration-500" style="width:0%;background:linear-gradient(90deg,#14b8a6,#0d9488);"></div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-2xl border border-white/5 overflow-hidden" style="background:rgba(255,255,255,0.02);">
        <div class="px-6 py-4 border-b border-white/5">
            <h3 class="font-semibold text-white text-sm">Workout Details</h3>
        </div>
        <form method="POST" action="<?php echo URLROOT; ?>/workouts/create" class="p-6 space-y-5">

            <!-- General Error -->
            <?php if (!empty($errors['general'])): ?>
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl border border-red-500/30 text-red-300 text-sm" style="background:rgba(239,68,68,0.08);">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php echo $errors['general']; ?>
            </div>
            <?php endif; ?>

            <!-- Activity Type -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Activity Type</label>
                <select id="activity-type" name="type"
                        class="w-full rounded-xl px-4 py-3 text-sm font-medium text-white border outline-none transition-all duration-200 appearance-none cursor-pointer
                               <?php echo !empty($errors['type']) ? 'border-red-500' : 'border-white/8'; ?>"
                        style="background:#1e293b; border-color:<?php echo !empty($errors['type']) ? '#ef4444' : 'rgba(255,255,255,0.08)'; ?>;">
                    <option value="">— Select an activity —</option>
                    <?php foreach ($met as $activity => $metVal): ?>
                    <option value="<?php echo $activity; ?>"
                            data-met="<?php echo $metVal; ?>"
                            <?php echo (($old['type'] ?? '') === $activity) ? 'selected' : ''; ?>>
                        <?php echo $activity; ?> (MET <?php echo $metVal; ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['type'])): ?>
                <p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $errors['type']; ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Duration -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Duration (minutes)</label>
                    <input type="number" id="duration" name="duration" min="1" max="600"
                           value="<?php echo htmlspecialchars($old['duration'] ?? ''); ?>"
                           placeholder="e.g. 45"
                           class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all duration-200
                                  <?php echo !empty($errors['duration']) ? 'border-red-500' : ''; ?>"
                           style="background:#1e293b; border-color:<?php echo !empty($errors['duration']) ? '#ef4444' : 'rgba(255,255,255,0.08)'; ?>; font-family:Inter,sans-serif;">
                    <?php if (!empty($errors['duration'])): ?>
                    <p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $errors['duration']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Distance (optional) -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Distance (km) <span class="text-slate-500">optional</span></label>
                    <input type="number" name="distance_km" min="0" step="0.1"
                           value="<?php echo htmlspecialchars($old['distance_km'] ?? ''); ?>"
                           placeholder="e.g. 5.5"
                           class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all duration-200"
                           style="background:#1e293b; border-color:rgba(255,255,255,0.08); font-family:Inter,sans-serif;">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Body Weight -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Your Weight (kg)</label>
                    <input type="number" id="weight-kg" name="weight_kg" min="20" max="300" step="0.5"
                           value="<?php echo htmlspecialchars((string)$weight); ?>"
                           placeholder="e.g. 75"
                           class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all duration-200
                                  <?php echo !empty($errors['weight']) ? 'border-red-500' : ''; ?>"
                           style="background:#1e293b; border-color:<?php echo !empty($errors['weight']) ? '#ef4444' : 'rgba(255,255,255,0.08)'; ?>; font-family:Inter,sans-serif;">
                    <?php if (!empty($errors['weight'])): ?>
                    <p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $errors['weight']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Workout Date</label>
                    <input type="date" name="workout_date"
                           value="<?php echo htmlspecialchars($old['workout_date'] ?? date('Y-m-d')); ?>"
                           max="<?php echo date('Y-m-d'); ?>"
                           class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all duration-200"
                           style="background:#1e293b; border-color:rgba(255,255,255,0.08); font-family:Inter,sans-serif; color-scheme:dark;">
                    <?php if (!empty($errors['date'])): ?>
                    <p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $errors['date']; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- MET Info Box -->
            <div class="flex items-start gap-3 px-4 py-3 rounded-xl border border-blue-500/20 text-blue-300 text-xs" style="background:rgba(59,130,246,0.06);">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><strong class="text-blue-300">Formula (R4):</strong> Calories = MET × Weight (kg) × Duration (hrs) — Based on the Compendium of Physical Activities (Ainsworth et al.)</span>
            </div>

            <!-- Submit -->
            <button type="submit"
                    style="background:linear-gradient(135deg,#14b8a6,#0d9488);"
                    class="w-full text-white font-semibold py-3.5 rounded-xl text-sm transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand-500/30 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Workout
            </button>
        </form>
    </div>
</div>

<script>
const MET_VALUES = <?php echo $metJson; ?>;

function updateCaloriePreview() {
    const typeSelect = document.getElementById('activity-type');
    const durationEl = document.getElementById('duration');
    const weightEl   = document.getElementById('weight-kg');
    const calValue   = document.getElementById('calorie-value');
    const calFormula = document.getElementById('calorie-formula');
    const calBar     = document.getElementById('calorie-bar');

    const type     = typeSelect.value;
    const duration = parseFloat(durationEl.value);
    const weight   = parseFloat(weightEl.value);

    if (type && duration > 0 && weight > 0) {
        const met      = MET_VALUES[type] || 5.0;
        const calories = Math.round(met * weight * (duration / 60));
        calValue.textContent = calories.toLocaleString() + ' kcal';
        calFormula.textContent = `${met} MET × ${weight} kg × ${(duration/60).toFixed(2)} hrs`;
        // Bar: max reference 1000 kcal
        const pct = Math.min((calories / 1000) * 100, 100);
        calBar.style.width = pct + '%';
    } else {
        calValue.textContent = '—';
        calFormula.textContent = 'Enter activity, duration, and weight to calculate.';
        calBar.style.width = '0%';
    }
}

document.getElementById('activity-type').addEventListener('change', updateCaloriePreview);
document.getElementById('duration').addEventListener('input', updateCaloriePreview);
document.getElementById('weight-kg').addEventListener('input', updateCaloriePreview);

// Trigger on load if values already present (POST error recovery)
updateCaloriePreview();
</script>

<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="<?php echo URLROOT; ?>/goals" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white text-sm mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Goals
        </a>
        <h2 class="text-2xl font-bold text-white">Set a New Goal</h2>
        <p class="text-slate-400 text-sm mt-1">Get smart recommendations based on your goal type (R2).</p>
    </div>

    <!-- R2 Smart Recommendations Banner -->
    <div id="recommendation-box" class="rounded-2xl p-5 border transition-all duration-300" style="background:rgba(139,92,246,0.08);border-color:rgba(139,92,246,0.25);display:none;">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-lg" style="background:rgba(139,92,246,0.15);">🎯</div>
            <div>
                <p class="text-xs font-semibold text-purple-400 uppercase tracking-wider mb-1">R2 — Smart Recommendation</p>
                <p id="rec-desc" class="text-white text-sm font-medium mb-2">Select a goal type to see recommendations.</p>
                <div class="flex gap-4 text-xs text-slate-400">
                    <span>📊 Suggested calories: <strong id="rec-cal" class="text-white">—</strong></span>
                    <span>🏃 Workouts/week: <strong id="rec-wk" class="text-white">—</strong></span>
                </div>
                <button type="button" id="apply-rec"
                        class="mt-3 text-xs font-semibold px-4 py-2 rounded-lg transition-all hover:scale-105"
                        style="background:rgba(139,92,246,0.2);color:#a78bfa;border:1px solid rgba(139,92,246,0.3);">
                    Apply Recommendations →
                </button>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-white/5 overflow-hidden" style="background:rgba(255,255,255,0.02);">
        <div class="px-6 py-4 border-b border-white/5">
            <h3 class="font-semibold text-white text-sm">Goal Configuration</h3>
        </div>
        <form method="POST" action="<?php echo URLROOT; ?>/goals/create" class="p-6 space-y-5" id="goal-form">

            <!-- Goal Type -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-3">Goal Type</label>
                <div class="grid grid-cols-2 gap-3" id="goal-type-grid">
                    <?php
                    $goalTypes = [
                        ['val'=>'weight_loss', 'label'=>'Weight Loss',   'emoji'=>'🔥', 'desc'=>'Burn fat, reduce weight',  'color'=>'#ef4444'],
                        ['val'=>'muscle_gain', 'label'=>'Muscle Gain',   'emoji'=>'💪', 'desc'=>'Build strength & mass',    'color'=>'#3b82f6'],
                        ['val'=>'maintenance', 'label'=>'Maintenance',   'emoji'=>'⚖️', 'desc'=>'Maintain current fitness', 'color'=>'#14b8a6'],
                        ['val'=>'endurance',   'label'=>'Endurance',     'emoji'=>'🏃', 'desc'=>'Improve stamina & cardio', 'color'=>'#f97316'],
                    ];
                    foreach ($goalTypes as $gt): ?>
                    <label class="goal-type-btn flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-all"
                           style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.07);"
                           data-val="<?php echo $gt['val']; ?>" data-color="<?php echo $gt['color']; ?>">
                        <input type="radio" name="goal_type" value="<?php echo $gt['val']; ?>"
                               <?php echo (($old['goal_type'] ?? '') === $gt['val']) ? 'checked' : ''; ?>
                               class="sr-only">
                        <span class="text-2xl"><?php echo $gt['emoji']; ?></span>
                        <div>
                            <p class="text-sm font-semibold text-white"><?php echo $gt['label']; ?></p>
                            <p class="text-xs text-slate-500"><?php echo $gt['desc']; ?></p>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Target Weight -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Target Weight (kg)</label>
                    <input type="number" id="target-weight" name="target_weight" min="30" max="300" step="0.5"
                           value="<?php echo htmlspecialchars($old['target_weight'] ?? ''); ?>"
                           placeholder="e.g. 75"
                           class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all"
                           style="background:#1e293b;border-color:<?php echo !empty($errors['target_weight']) ? '#ef4444' : 'rgba(255,255,255,0.08)'; ?>;font-family:Inter,sans-serif;"
                           onfocus="this.style.borderColor='#8b5cf6';this.style.boxShadow='0 0 0 3px rgba(139,92,246,0.12)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                    <?php if (!empty($errors['target_weight'])): ?><p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $errors['target_weight']; ?></p><?php endif; ?>
                </div>

                <!-- Daily Calorie Target -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Daily Calorie Target</label>
                    <input type="number" id="target-calories" name="target_calories" min="800" max="5000"
                           value="<?php echo htmlspecialchars($old['target_calories'] ?? ''); ?>"
                           placeholder="e.g. 1800"
                           class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all"
                           style="background:#1e293b;border-color:<?php echo !empty($errors['target_calories']) ? '#ef4444' : 'rgba(255,255,255,0.08)'; ?>;font-family:Inter,sans-serif;"
                           onfocus="this.style.borderColor='#8b5cf6';this.style.boxShadow='0 0 0 3px rgba(139,92,246,0.12)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                    <?php if (!empty($errors['target_calories'])): ?><p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $errors['target_calories']; ?></p><?php endif; ?>
                </div>
            </div>

            <!-- Workouts per week -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Workouts per Week: <span id="wk-label" class="text-purple-400 font-bold"><?php echo $old['target_workouts_per_week'] ?? 3; ?></span>
                </label>
                <input type="range" id="target-wk" name="target_workouts_per_week" min="1" max="7"
                       value="<?php echo $old['target_workouts_per_week'] ?? 3; ?>"
                       class="w-full accent-purple-500 cursor-pointer"
                       oninput="document.getElementById('wk-label').textContent = this.value">
                <div class="flex justify-between text-xs text-slate-600 mt-1"><span>1</span><span>2</span><span>3</span><span>4</span><span>5</span><span>6</span><span>7</span></div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Notes <span class="text-slate-500">(optional)</span></label>
                <textarea name="notes" rows="2" placeholder="e.g. Preparing for a 5K run in June..."
                          class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all resize-none"
                          style="background:#1e293b;border-color:rgba(255,255,255,0.08);font-family:Inter,sans-serif;"
                          onfocus="this.style.borderColor='#8b5cf6';this.style.boxShadow='0 0 0 3px rgba(139,92,246,0.12)'"
                          onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'"
                ><?php echo htmlspecialchars($old['notes'] ?? ''); ?></textarea>
            </div>

            <button type="submit"
                    style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);box-shadow:0 8px 25px rgba(139,92,246,0.25);"
                    class="w-full text-white font-bold py-3.5 rounded-xl text-sm transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Set Goal
            </button>
        </form>
    </div>
</div>

<script>
// R2 Recommendation engine (client-side)
const RECOMMENDATIONS = {
    weight_loss: { calories: 1440, workouts: 5, desc: 'Moderate caloric deficit with 5 sessions/week for sustainable fat loss.' },
    muscle_gain: { calories: 2070, workouts: 4, desc: 'Caloric surplus with 4 strength sessions/week to support muscle growth.' },
    maintenance: { calories: 1800, workouts: 3, desc: 'Maintain current weight with balanced diet and 3 sessions/week.' },
    endurance:   { calories: 1980, workouts: 5, desc: 'High-carb fuel with 5 cardio sessions/week to build stamina.' },
};

const btns = document.querySelectorAll('.goal-type-btn');
const recBox = document.getElementById('recommendation-box');

btns.forEach(btn => {
    btn.addEventListener('click', () => {
        const val = btn.dataset.val;
        const color = btn.dataset.color;
        // Highlight selected
        btns.forEach(b => { b.style.borderColor = 'rgba(255,255,255,0.07)'; b.style.background = 'rgba(255,255,255,0.02)'; });
        btn.style.borderColor = color;
        btn.style.background  = color + '15';
        btn.querySelector('input[type=radio]').checked = true;
        // Show recommendation
        const rec = RECOMMENDATIONS[val];
        if (rec) {
            document.getElementById('rec-desc').textContent = rec.desc;
            document.getElementById('rec-cal').textContent  = rec.calories.toLocaleString() + ' kcal';
            document.getElementById('rec-wk').textContent   = rec.workouts + ' sessions';
            recBox.style.display = 'block';
            document.getElementById('apply-rec').dataset.cal = rec.calories;
            document.getElementById('apply-rec').dataset.wk  = rec.workouts;
        }
    });
});

document.getElementById('apply-rec')?.addEventListener('click', () => {
    const btn = document.getElementById('apply-rec');
    document.getElementById('target-calories').value = btn.dataset.cal;
    document.getElementById('target-wk').value       = btn.dataset.wk;
    document.getElementById('wk-label').textContent  = btn.dataset.wk;
});

// Restore selection on page load (after POST error)
const checked = document.querySelector('input[name=goal_type]:checked');
if (checked) checked.closest('.goal-type-btn').click();
</script>

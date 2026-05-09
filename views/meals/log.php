<div class="max-w-xl mx-auto space-y-6">
    <div>
        <a href="<?php echo URLROOT; ?>/meals" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white text-sm mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Nutrition
        </a>
        <h2 class="text-2xl font-bold text-white">Log a Meal</h2>
        <p class="text-slate-400 text-sm mt-1">Track your food intake for the day.</p>
    </div>

    <div class="rounded-2xl border border-white/5 overflow-hidden" style="background:rgba(255,255,255,0.02);">
        <div class="px-6 py-4 border-b border-white/5">
            <h3 class="font-semibold text-white text-sm">Meal Details</h3>
        </div>
        <form method="POST" action="<?php echo URLROOT; ?>/meals/log" class="p-6 space-y-5">

            <!-- Food Name -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Food Name</label>
                <input type="text" name="food_name"
                       value="<?php echo htmlspecialchars($old['food_name'] ?? ''); ?>"
                       placeholder="e.g. Grilled Chicken Salad"
                       class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all"
                       style="background:#1e293b;border-color:<?php echo !empty($errors['food_name']) ? '#ef4444' : 'rgba(255,255,255,0.08)'; ?>;font-family:Inter,sans-serif;"
                       onfocus="this.style.borderColor='#22c55e';this.style.boxShadow='0 0 0 3px rgba(34,197,94,0.12)'"
                       onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                <?php if (!empty($errors['food_name'])): ?><p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $errors['food_name']; ?></p><?php endif; ?>
            </div>

            <!-- Calories -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Calories (kcal)</label>
                <input type="number" name="calories" min="1" max="5000"
                       value="<?php echo htmlspecialchars($old['calories'] ?? ''); ?>"
                       placeholder="e.g. 450"
                       class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all"
                       style="background:#1e293b;border-color:<?php echo !empty($errors['calories']) ? '#ef4444' : 'rgba(255,255,255,0.08)'; ?>;font-family:Inter,sans-serif;"
                       onfocus="this.style.borderColor='#22c55e';this.style.boxShadow='0 0 0 3px rgba(34,197,94,0.12)'"
                       onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                <?php if (!empty($errors['calories'])): ?><p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $errors['calories']; ?></p><?php endif; ?>
            </div>

            <!-- Macros -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Macronutrients <span class="text-slate-500">(grams, optional)</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <?php
                    $macroFields = [
                        ['name'=>'protein', 'label'=>'Protein', 'color'=>'#3b82f6', 'ph'=>'e.g. 35'],
                        ['name'=>'carbs',   'label'=>'Carbs',   'color'=>'#f97316', 'ph'=>'e.g. 45'],
                        ['name'=>'fats',    'label'=>'Fats',    'color'=>'#a855f7', 'ph'=>'e.g. 15'],
                    ];
                    foreach ($macroFields as $mf): ?>
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:<?php echo $mf['color']; ?>;"><?php echo $mf['label']; ?></label>
                        <input type="number" name="<?php echo $mf['name']; ?>" min="0" step="0.1"
                               value="<?php echo htmlspecialchars($old[$mf['name']] ?? ''); ?>"
                               placeholder="<?php echo $mf['ph']; ?>"
                               class="w-full rounded-xl px-3 py-2.5 text-sm text-white border outline-none transition-all"
                               style="background:#1e293b;border-color:rgba(255,255,255,0.08);font-family:Inter,sans-serif;"
                               onfocus="this.style.borderColor='<?php echo $mf['color']; ?>';this.style.boxShadow='0 0 0 3px <?php echo $mf['color']; ?>20'"
                               onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit"
                    style="background:linear-gradient(135deg,#22c55e,#16a34a);box-shadow:0 8px 25px rgba(34,197,94,0.25);"
                    class="w-full text-white font-bold py-3.5 rounded-xl text-sm transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Meal
            </button>
        </form>
    </div>
</div>

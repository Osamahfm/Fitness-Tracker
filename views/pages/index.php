<!-- Hero Section -->
<section class="min-h-screen flex items-center justify-center px-6 relative">
    <!-- Grid pattern overlay -->
    <div class="absolute inset-0 opacity-5" style="background-image:linear-gradient(rgba(255,255,255,0.1) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.1) 1px,transparent 1px);background-size:60px 60px;"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border text-xs font-semibold mb-8 animate-fade-up"
             style="background:rgba(20,184,166,0.08);border-color:rgba(20,184,166,0.25);color:#2dd4bf;">
            <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
            University Graduation Project — MIU 2026
        </div>

        <!-- Headline -->
        <h1 class="text-5xl md:text-7xl font-black leading-tight mb-6" style="animation:fadeUp 0.7s ease forwards;opacity:0;animation-delay:0.1s;">
            Track Every Rep.<br>
            <span class="text-gradient">Own Every Goal.</span>
        </h1>

        <!-- Subheadline -->
        <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed mb-10"
           style="animation:fadeUp 0.7s ease forwards;opacity:0;animation-delay:0.2s;">
            FitTrack is a precision fitness platform that calculates your calorie burn using the scientifically proven <strong class="text-white">MET formula</strong>, recommends personalized meals, and generates daily progress reports.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16"
             style="animation:fadeUp 0.7s ease forwards;opacity:0;animation-delay:0.3s;">
            <a href="<?php echo URLROOT; ?>/users/register"
               style="background:linear-gradient(135deg,#14b8a6,#0d9488);"
               class="inline-flex items-center gap-2 text-white font-bold px-8 py-4 rounded-2xl text-base hover:-translate-y-1 transition-all shadow-2xl" style2="box-shadow:0 20px 40px rgba(20,184,166,0.3);">
                Get Started Free
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <a href="<?php echo URLROOT; ?>/users/login"
               class="inline-flex items-center gap-2 text-slate-300 font-medium px-8 py-4 rounded-2xl text-base border transition-all hover:bg-white/5"
               style="border-color:rgba(255,255,255,0.1);">
                Sign In
            </a>
        </div>

        <!-- Stats Bar -->
        <div class="inline-flex flex-wrap justify-center gap-8 px-8 py-5 rounded-2xl border"
             style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.06);animation:fadeUp 0.7s ease forwards;opacity:0;animation-delay:0.4s;">
            <?php
            $heroStats = [
                ['num'=>'12+', 'label'=>'Activity Types'],
                ['num'=>'MET', 'label'=>'Based Formula'],
                ['num'=>'RTM', 'label'=>'Requirement Tracked'],
                ['num'=>'95%', 'label'=>'Target Uptime'],
            ];
            foreach ($heroStats as $s): ?>
            <div class="text-center">
                <p class="text-2xl font-black text-white"><?php echo $s['num']; ?></p>
                <p class="text-xs text-slate-500 mt-0.5"><?php echo $s['label']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-24 px-6 section-fade">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <p class="text-brand-400 font-semibold text-sm uppercase tracking-widest mb-3">Core Modules</p>
            <h2 class="text-4xl font-black text-white">Everything You Need to Achieve Peak Performance</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <?php
            $features = [
                ['icon'=>'M13 10V3L4 14h7v7l9-11h-7z', 'color'=>'#3b82f6', 'title'=>'Workout Tracking (R1)',      'desc'=>'Log any physical activity — running, HIIT, swimming, cycling. Track distance, duration, and effort for every session.'],
                ['icon'=>'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z', 'color'=>'#f97316', 'title'=>'Calorie Calculation (R4)', 'desc'=>'Uses the scientifically validated MET formula: Calories = MET × Weight × Duration. Real-time preview as you type.'],
                ['icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'color'=>'#22c55e', 'title'=>'Meal Recommendations (R3)',  'desc'=>'Intelligent meal suggestions based on your daily caloric budget. Track protein, carbs, and fats with full macro breakdown.'],
                ['icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color'=>'#8b5cf6', 'title'=>'Goal Setting (R2)',           'desc'=>'Define target weight and daily calorie goals. The system recommends personalised milestones based on your current data.'],
                ['icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'color'=>'#14b8a6', 'title'=>'Daily Reports (R7)',           'desc'=>'Automatically generated daily summaries. Interactive Chart.js visualisations of calorie burn, nutrition intake, and weekly trends.'],
                ['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color'=>'#f43f5e', 'title'=>'Activity Alarms (R6)',          'desc'=>'Browser-based reminders to keep you consistent. Get notified if you haven\'t logged a workout for the day.'],
            ];
            foreach ($features as $f): ?>
            <div class="glass glass-hover p-7 rounded-2xl group">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 transition-transform group-hover:scale-110" style="background:<?php echo $f['color']; ?>1a;">
                    <svg class="w-6 h-6" style="color:<?php echo $f['color']; ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?php echo $f['icon']; ?>"/>
                    </svg>
                </div>
                <h3 class="font-bold text-white text-lg mb-2"><?php echo $f['title']; ?></h3>
                <p class="text-slate-400 text-sm leading-relaxed"><?php echo $f['desc']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="how-it-works" class="py-24 px-6 section-fade" style="background:rgba(255,255,255,0.01);">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <p class="text-brand-400 font-semibold text-sm uppercase tracking-widest mb-3">Simple Process</p>
            <h2 class="text-4xl font-black text-white">Start Tracking in 3 Steps</h2>
        </div>
        <div class="space-y-6">
            <?php
            $steps = [
                ['n'=>'01', 'title'=>'Create Your Account',      'desc'=>'Register securely with email and password. Your data is hashed with bcrypt and stored safely in MySQL using PDO prepared statements.', 'color'=>'#14b8a6'],
                ['n'=>'02', 'title'=>'Log Your Activities',       'desc'=>'Choose from 12 activity types. Enter your duration and weight — FitTrack instantly calculates burned calories using the MET formula.', 'color'=>'#3b82f6'],
                ['n'=>'03', 'title'=>'Review Progress & Reports', 'desc'=>'Your dashboard charts weekly calorie burn with Chart.js. Get meal recommendations and daily reports that keep you aligned with your goals.', 'color'=>'#8b5cf6'],
            ];
            foreach ($steps as $s): ?>
            <div class="flex gap-6 items-start p-7 rounded-2xl border transition-all hover:border-white/10" style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.05);">
                <div class="text-3xl font-black flex-shrink-0 leading-none" style="color:<?php echo $s['color']; ?>;"><?php echo $s['n']; ?></div>
                <div>
                    <h3 class="font-bold text-white text-lg mb-2"><?php echo $s['title']; ?></h3>
                    <p class="text-slate-400 text-sm leading-relaxed"><?php echo $s['desc']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-24 px-6 section-fade">
    <div class="max-w-3xl mx-auto text-center">
        <div class="p-12 rounded-3xl border relative overflow-hidden" style="background:linear-gradient(135deg,rgba(20,184,166,0.08),rgba(139,92,246,0.05));border-color:rgba(20,184,166,0.2);">
            <div class="absolute inset-0 opacity-20" style="background:radial-gradient(circle at 50% 0%,#14b8a6,transparent 60%);"></div>
            <div class="relative">
                <h2 class="text-4xl font-black text-white mb-4">Ready to Transform Your Fitness?</h2>
                <p class="text-slate-400 mb-8">Join FitTrack and start achieving your goals today. Free, powerful, and built for results.</p>
                <a href="<?php echo URLROOT; ?>/users/register"
                   style="background:linear-gradient(135deg,#14b8a6,#0d9488);"
                   class="inline-flex items-center gap-2 text-white font-bold px-10 py-4 rounded-2xl text-base hover:-translate-y-1 transition-all shadow-2xl">
                    Start Free — No Credit Card
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

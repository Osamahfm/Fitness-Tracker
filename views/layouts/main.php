<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FitTrack — Premium fitness tracking for workouts, nutrition, and goals.">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' | ' . SITENAME : SITENAME . ' | Elite Fitness Tracking'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 300:'#5eead4', 400:'#2dd4bf', 500:'#14b8a6', 600:'#0d9488', 700:'#0f766e' },
                        surface: { 900:'#0a0f1e', 800:'#0f172a', 700:'#1e293b', 600:'#263348', 500:'#334155' }
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delay': 'float 6s ease-in-out 2s infinite',
                        'fade-up': 'fadeUp 0.6s ease forwards',
                    },
                    keyframes: {
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-20px)' } },
                        fadeUp: { '0%': { opacity: '0', transform: 'translateY(24px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                    }
                }
            }
        }
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { background-color: #0a0f1e; }
        .glass { background: rgba(255,255,255,0.03); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.06); }
        .glass-hover { transition: all 0.3s ease; }
        .glass-hover:hover { background: rgba(255,255,255,0.06); border-color: rgba(45,212,191,0.3); transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 0 1px rgba(45,212,191,0.1); }
        .glow-brand { box-shadow: 0 0 40px rgba(20,184,166,0.25); }
        .glow-brand-sm { box-shadow: 0 0 20px rgba(20,184,166,0.2); }
        .text-gradient { background: linear-gradient(135deg, #2dd4bf, #14b8a6 40%, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
        .nav-blur { backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: #0a0f1e; } ::-webkit-scrollbar-thumb { background: #334155; border-radius: 99px; } ::-webkit-scrollbar-thumb:hover { background: #2dd4bf; }
        .input-field { width:100%; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); color:#f1f5f9; border-radius:10px; padding:12px 16px; font-size:0.9rem; outline:none; transition:all 0.2s; font-family:'Inter',sans-serif; }
        .input-field::placeholder { color:#475569; }
        .input-field:focus { border-color:#14b8a6; background:rgba(20,184,166,0.05); box-shadow:0 0 0 3px rgba(20,184,166,0.1); }
        .input-error { border-color:#f87171 !important; }
        .btn-primary { background:linear-gradient(135deg,#14b8a6,#0d9488); color:#fff; font-weight:600; padding:12px 28px; border-radius:10px; border:none; cursor:pointer; transition:all 0.2s; font-family:'Inter',sans-serif; font-size:0.9rem; display:inline-flex; align-items:center; gap:8px; }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(20,184,166,0.35); }
        .btn-primary:active { transform:translateY(0); }
        .section-fade { opacity:0; transform:translateY(30px); transition:opacity 0.7s ease, transform 0.7s ease; }
        .section-fade.visible { opacity:1; transform:translateY(0); }
    </style>
</head>
<body class="font-sans antialiased text-slate-100 overflow-x-hidden" style="background-color:#0a0f1e;">

<!-- Ambient Background Orbs -->
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
    <div class="orb w-[600px] h-[600px] bg-brand-500/20 top-[-200px] left-[-200px] animate-float"></div>
    <div class="orb w-[500px] h-[500px] bg-violet-600/15 bottom-[-100px] right-[-150px] animate-float-delay"></div>
    <div class="orb w-[300px] h-[300px] bg-blue-600/10 top-[40%] left-[40%]"></div>
</div>

<!-- Navbar -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 nav-blur border-b border-white/5 transition-all duration-300" style="background:rgba(10,15,30,0.7);">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <!-- Logo -->
        <a href="<?php echo URLROOT; ?>" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-lg text-white glow-brand-sm" style="background:linear-gradient(135deg,#14b8a6,#0d9488);">F</div>
            <span class="font-bold text-lg tracking-tight text-white">Fit<span class="text-brand-400">Track</span></span>
        </a>
        <!-- Nav links -->
        <div class="hidden md:flex items-center gap-8">
            <a href="#features" class="text-sm text-slate-400 hover:text-brand-400 transition-colors font-medium">Features</a>
            <a href="#how-it-works" class="text-sm text-slate-400 hover:text-brand-400 transition-colors font-medium">How It Works</a>
        </div>
        <!-- Auth -->
        <div class="flex items-center gap-3">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo URLROOT; ?>/dashboard" class="text-sm text-slate-300 hover:text-brand-400 transition-colors font-medium">Dashboard</a>
                <a href="<?php echo URLROOT; ?>/users/logout" class="text-sm border border-white/10 hover:border-red-500/50 hover:text-red-400 text-slate-300 px-4 py-2 rounded-lg transition-all">Log out</a>
            <?php else: ?>
                <a href="<?php echo URLROOT; ?>/users/login" class="text-sm text-slate-300 hover:text-white transition-colors font-medium px-4 py-2">Log in</a>
                <a href="<?php echo URLROOT; ?>/users/register" class="btn-primary text-sm py-2 px-5">Sign up free</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Page Content -->
<main class="relative z-10 pt-16">
    <?php require_once $contentView; ?>
</main>

<!-- Footer -->
<footer class="relative z-10 border-t border-white/5 mt-24" style="background:rgba(10,15,30,0.8);">
    <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-sm text-white" style="background:linear-gradient(135deg,#14b8a6,#0d9488);">F</div>
            <span class="text-sm font-semibold text-white">FitTrack</span>
        </div>
        <p class="text-xs text-slate-500">&copy; <?php echo date('Y'); ?> FitTrack. University Graduation Project. All rights reserved.</p>
        <div class="flex gap-6">
            <a href="#" class="text-xs text-slate-500 hover:text-brand-400 transition-colors">Privacy</a>
            <a href="#" class="text-xs text-slate-500 hover:text-brand-400 transition-colors">Terms</a>
        </div>
    </div>
</footer>

<script>
// Scroll-aware navbar
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
        navbar.style.background = 'rgba(10,15,30,0.95)';
        navbar.style.borderBottomColor = 'rgba(255,255,255,0.08)';
    } else {
        navbar.style.background = 'rgba(10,15,30,0.7)';
        navbar.style.borderBottomColor = 'rgba(255,255,255,0.05)';
    }
});

// Scroll-triggered section animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });
document.querySelectorAll('.section-fade').forEach(el => observer.observe(el));
</script>
</body>
</html>

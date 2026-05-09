<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' | ' . SITENAME : SITENAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 300:'#5eead4', 400:'#2dd4bf', 500:'#14b8a6', 600:'#0d9488', 700:'#0f766e' },
                        surface: { 900:'#0a0f1e', 800:'#0f172a', 700:'#1e293b', 600:'#263348', 500:'#334155' }
                    }
                }
            }
        }
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { background-color: #0a0f1e; margin: 0; }
        .glass { background: rgba(255,255,255,0.03); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.06); }
        .sidebar-link { display:flex; align-items:center; gap:12px; padding:10px 16px; border-radius:10px; font-size:0.875rem; font-weight:500; color:#94a3b8; transition:all 0.2s; text-decoration:none; }
        .sidebar-link:hover { background:rgba(255,255,255,0.05); color:#f1f5f9; }
        .sidebar-link.active { background:linear-gradient(135deg,rgba(20,184,166,0.2),rgba(13,148,136,0.1)); color:#2dd4bf; border:1px solid rgba(20,184,166,0.2); }
        .sidebar-link svg { flex-shrink:0; }
        .stat-card { background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06); border-radius:16px; padding:24px; transition:all 0.25s; }
        .stat-card:hover { background:rgba(255,255,255,0.05); transform:translateY(-3px); box-shadow:0 16px 32px rgba(0,0,0,0.3); }
        .glow-brand-sm { box-shadow:0 0 20px rgba(20,184,166,0.2); }
        ::-webkit-scrollbar { width:5px; } ::-webkit-scrollbar-track { background:#0a0f1e; } ::-webkit-scrollbar-thumb { background:#334155; border-radius:99px; } ::-webkit-scrollbar-thumb:hover { background:#2dd4bf; }
        #sidebar { transition: transform 0.3s ease; }
        @media (max-width: 1024px) { #sidebar { transform: translateX(-100%); position:fixed; z-index:50; } #sidebar.open { transform: translateX(0); } }
    </style>
</head>
<body class="font-sans antialiased text-slate-100" style="background-color:#0a0f1e;">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 flex-shrink-0 h-full overflow-y-auto flex flex-col border-r border-white/5" style="background:#0f172a;">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-white/5 flex-shrink-0">
            <a href="<?php echo URLROOT; ?>/dashboard" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-white glow-brand-sm text-sm" style="background:linear-gradient(135deg,#14b8a6,#0d9488);">F</div>
                <span class="font-bold text-white">Fit<span style="color:#2dd4bf;">Track</span></span>
            </a>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-4 py-6 space-y-1">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mb-3">Main</p>
            <a href="<?php echo URLROOT; ?>/dashboard" class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false && strpos($_SERVER['REQUEST_URI'], '/workouts') === false && strpos($_SERVER['REQUEST_URI'], '/meals') === false && strpos($_SERVER['REQUEST_URI'], '/goals') === false) ? 'active' : ''; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="1.8"/><rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="1.8"/><rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="1.8"/><rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="1.8"/></svg>
                Dashboard
            </a>
            <a href="<?php echo URLROOT; ?>/workouts" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/workouts') !== false ? 'active' : ''; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Workouts
            </a>
            <a href="<?php echo URLROOT; ?>/meals" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/meals') !== false ? 'active' : ''; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                Nutrition
            </a>
            <a href="<?php echo URLROOT; ?>/goals" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/goals') !== false ? 'active' : ''; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Goals
            </a>
            <a href="<?php echo URLROOT; ?>/reports" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/reports') !== false ? 'active' : ''; ?>" style="position:relative;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Reports
                <span id="alarm-dot" class="ml-auto w-2 h-2 rounded-full bg-orange-400" style="display:none;"></span>
            </a>
        </nav>

        <!-- User Profile Footer -->
        <div class="px-4 py-4 border-t border-white/5 flex-shrink-0">
            <div class="flex items-center gap-3 px-3 py-3 rounded-xl" style="background:rgba(255,255,255,0.03);">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></p>
                    <p class="text-xs text-slate-500 truncate"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>
                </div>
                <a href="<?php echo URLROOT; ?>/users/logout" title="Log out" class="ml-auto text-slate-500 hover:text-red-400 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top bar -->
        <header class="h-16 flex items-center justify-between px-6 border-b border-white/5 flex-shrink-0" style="background:#0f172a;">
            <button id="menu-toggle" class="lg:hidden text-slate-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex items-center gap-2 lg:hidden">
                <span class="font-bold text-white">Fit<span style="color:#2dd4bf;">Track</span></span>
            </div>
            <div class="hidden lg:block">
                <h1 class="text-lg font-semibold text-white"><?php echo htmlspecialchars($title ?? 'Dashboard'); ?></h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-slate-500"><?php echo date('l, F j'); ?></p>
                </div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                </div>
            </div>
        </header>

        <!-- Scrollable Page Content -->
        <main class="flex-1 overflow-y-auto p-6" style="background:#0a0f1e;">
            <?php require_once $contentView; ?>
        </main>
    </div>
</div>

<!-- Sidebar overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden"></div>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebar-overlay');
const toggle = document.getElementById('menu-toggle');

toggle?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('hidden');
});
overlay?.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.add('hidden');
});
</script>
</body>
</html>

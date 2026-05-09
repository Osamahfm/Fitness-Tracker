<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-16 px-4">
    <div class="w-full max-w-md">
        <div class="rounded-2xl p-8 border" style="background:rgba(15,23,42,0.8);backdrop-filter:blur(24px);border-color:rgba(255,255,255,0.07);">

            <div class="text-center mb-8">
                <div class="inline-flex w-12 h-12 rounded-xl items-center justify-center text-white font-black text-xl mb-4" style="background:linear-gradient(135deg,#14b8a6,#0d9488);">F</div>
                <h1 class="text-2xl font-bold text-white">Welcome back</h1>
                <p class="text-slate-400 text-sm mt-1">Log in to continue your fitness journey.</p>
            </div>

            <?php if (!empty($_SESSION['success_msg'])): ?>
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl border border-green-500/30 text-green-300 text-sm mb-6" style="background:rgba(34,197,94,0.08);">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo URLROOT; ?>/users/login" novalidate class="space-y-4">

                <div>
                    <label for="login-email" class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                    <input type="email" id="login-email" name="email"
                           value="<?php echo htmlspecialchars($email); ?>"
                           placeholder="you@example.com" autocomplete="email"
                           class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all"
                           style="background:#1e293b;border-color:<?php echo !empty($email_err) ? '#ef4444' : 'rgba(255,255,255,0.08)'; ?>;font-family:Inter,sans-serif;"
                           onfocus="this.style.borderColor='#14b8a6';this.style.boxShadow='0 0 0 3px rgba(20,184,166,0.15)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                    <?php if (!empty($email_err)): ?>
                    <p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $email_err; ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="login-password" class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                    <input type="password" id="login-password" name="password"
                           placeholder="Your password" autocomplete="current-password"
                           class="w-full rounded-xl px-4 py-3 text-sm text-white border outline-none transition-all"
                           style="background:#1e293b;border-color:<?php echo !empty($password_err) ? '#ef4444' : 'rgba(255,255,255,0.08)'; ?>;font-family:Inter,sans-serif;"
                           onfocus="this.style.borderColor='#14b8a6';this.style.boxShadow='0 0 0 3px rgba(20,184,166,0.15)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.08)';this.style.boxShadow='none'">
                    <?php if (!empty($password_err)): ?>
                    <p class="text-red-400 text-xs mt-1.5">⚠ <?php echo $password_err; ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" id="login-btn"
                        class="w-full text-white font-bold py-3.5 rounded-xl text-sm transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-2"
                        style="background:linear-gradient(135deg,#14b8a6,#0d9488);box-shadow:0 8px 25px rgba(20,184,166,0.25);">
                    Log In
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <p class="text-center text-slate-500 text-sm mt-6">
                Don't have an account?
                <a href="<?php echo URLROOT; ?>/users/register" class="text-brand-400 font-semibold hover:text-brand-300 transition-colors ml-1">Sign up free</a>
            </p>
        </div>
    </div>
</div>

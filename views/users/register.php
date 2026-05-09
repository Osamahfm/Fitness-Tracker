<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">

        <!-- Card -->
        <div class="glass-panel rounded-2xl p-8 shadow-2xl border border-white/5">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex w-14 h-14 rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 items-center justify-center text-white font-bold text-2xl shadow-lg shadow-brand-500/30 mb-4">F</div>
                <h1 class="text-2xl font-bold text-white">Create your account</h1>
                <p class="text-gray-400 mt-1 text-sm">Start tracking your fitness journey today.</p>
            </div>

            <!-- Success message from redirect -->
            <?php if (isset($_SESSION['success_msg'])): ?>
                <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 mb-6 text-sm">
                    <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                </div>
            <?php endif; ?>

            <!-- Register Form -->
            <form method="POST" action="<?php echo URLROOT; ?>/users/register" novalidate class="space-y-5">

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Full Name</label>
                    <input
                        type="text" id="name" name="name"
                        value="<?php echo htmlspecialchars($name); ?>"
                        placeholder="John Doe"
                        class="w-full bg-white/5 border <?php echo !empty($name_err) ? 'border-red-500' : 'border-white/10'; ?> text-white placeholder-gray-500 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all duration-200"
                    >
                    <?php if (!empty($name_err)): ?>
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1"><span>⚠</span><?php echo $name_err; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email Address</label>
                    <input
                        type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($email); ?>"
                        placeholder="you@example.com"
                        class="w-full bg-white/5 border <?php echo !empty($email_err) ? 'border-red-500' : 'border-white/10'; ?> text-white placeholder-gray-500 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all duration-200"
                    >
                    <?php if (!empty($email_err)): ?>
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1"><span>⚠</span><?php echo $email_err; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
                    <input
                        type="password" id="password" name="password"
                        placeholder="Min. 6 characters"
                        class="w-full bg-white/5 border <?php echo !empty($password_err) ? 'border-red-500' : 'border-white/10'; ?> text-white placeholder-gray-500 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all duration-200"
                    >
                    <?php if (!empty($password_err)): ?>
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1"><span>⚠</span><?php echo $password_err; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-1.5">Confirm Password</label>
                    <input
                        type="password" id="confirm_password" name="confirm_password"
                        placeholder="Re-enter your password"
                        class="w-full bg-white/5 border <?php echo !empty($confirm_err) ? 'border-red-500' : 'border-white/10'; ?> text-white placeholder-gray-500 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all duration-200"
                    >
                    <?php if (!empty($confirm_err)): ?>
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1"><span>⚠</span><?php echo $confirm_err; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Submit -->
                <button type="submit" id="register-btn" class="w-full bg-brand-500 hover:bg-brand-600 active:scale-95 text-white font-semibold py-3 rounded-lg transition-all duration-200 shadow-lg shadow-brand-500/25 mt-2 flex items-center justify-center gap-2">
                    Create Account
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>

            <!-- Login Link -->
            <p class="text-center text-gray-400 text-sm mt-6">
                Already have an account?
                <a href="<?php echo URLROOT; ?>/users/login" class="text-brand-400 font-medium hover:text-brand-300 transition-colors ml-1">Log in</a>
            </p>
        </div>
    </div>
</div>

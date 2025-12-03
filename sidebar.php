<!-- Sidebar Component -->
<div class="hidden md:flex flex-col w-64 bg-white border-r border-gray-200 h-screen fixed left-0 top-0 z-20">
    <div class="p-6 flex items-center gap-3 border-b border-gray-100">
        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
            <span class="material-icons text-primary">account_balance_wallet</span>
        </div>
        <h1 class="text-xl font-bold text-dark">E-Wallet</h1>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-green-50 hover:text-primary transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-green-50 text-primary font-semibold' : ''; ?>">
            <span class="material-icons">dashboard</span>
            <span>Dashboard</span>
        </a>
        
        <a href="transfer.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-green-50 hover:text-primary transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'transfer.php' ? 'bg-green-50 text-primary font-semibold' : ''; ?>">
            <span class="material-icons">swap_horiz</span>
            <span>Transfer</span>
        </a>

        <a href="topup.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-green-50 hover:text-primary transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'topup.php' ? 'bg-green-50 text-primary font-semibold' : ''; ?>">
            <span class="material-icons">account_balance</span>
            <span>Top Up</span>
        </a>

        <a href="redeempoints.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-green-50 hover:text-primary transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'redeempoints.php' ? 'bg-green-50 text-primary font-semibold' : ''; ?>">
            <span class="material-icons">card_giftcard</span>
            <span>Redeem</span>
        </a>

        <a href="history.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-green-50 hover:text-primary transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'history.php' ? 'bg-green-50 text-primary font-semibold' : ''; ?>">
            <span class="material-icons">history</span>
            <span>History</span>
        </a>

        <a href="settings.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-green-50 hover:text-primary transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-green-50 text-primary font-semibold' : ''; ?>">
            <span class="material-icons">settings</span>
            <span>Settings</span>
        </a>
    </nav>

    <div class="p-4 border-t border-gray-100">
        <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-colors">
            <span class="material-icons">logout</span>
            <span>Log Out</span>
        </a>
    </div>
</div>

<!-- Mobile Header (Visible only on mobile) -->
<div class="md:hidden bg-white p-4 shadow-sm flex items-center justify-between sticky top-0 z-20">
    <div class="flex items-center gap-2">
        <span class="material-icons text-primary">account_balance_wallet</span>
        <h1 class="text-lg font-bold text-dark">E-Wallet</h1>
    </div>
    <button class="material-icons text-dark">menu</button>
</div>

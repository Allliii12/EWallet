<?php
require('config.php');
require('auth_session.php');

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch recent transactions for dashboard
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$recent_transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#10B981',
                        secondary: '#6B7280',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="md:ml-64 p-6 md:p-10">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-dark">Dashboard</h1>
                <p class="text-secondary text-sm">Welcome back, <?php echo htmlspecialchars($user['username']); ?></p>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                    <span class="material-icons text-primary text-sm">calendar_today</span>
                    <span class="text-sm font-medium text-dark"><?php echo date('F d, Y'); ?></span>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Balance Card -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg shadow-green-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/20 rounded-full blur-xl"></div>
                <div class="relative z-10">
                    <p class="text-green-100 text-sm mb-1">Total Balance</p>
                    <h2 class="text-3xl font-bold mb-4"><?php echo number_format($user['points']); ?> PTS</h2>
                    <div class="flex gap-2">
                        <a href="topup.php" class="bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors backdrop-blur-sm">
                            + Top Up
                        </a>
                        <a href="transfer.php" class="bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors backdrop-blur-sm">
                            Send
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats (Mock Data) -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                        <span class="material-icons text-blue-500">arrow_downward</span>
                    </div>
                    <div>
                        <p class="text-xs text-secondary">Income</p>
                        <p class="font-bold text-dark text-lg">+2,500</p>
                    </div>
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full w-[70%] rounded-full"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                        <span class="material-icons text-red-500">arrow_upward</span>
                    </div>
                    <div>
                        <p class="text-xs text-secondary">Expense</p>
                        <p class="font-bold text-dark text-lg">-1,200</p>
                    </div>
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-red-500 h-full w-[40%] rounded-full"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Transactions -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-dark text-lg">Recent Transactions</h3>
                    <a href="history.php" class="text-primary text-sm font-medium hover:underline">View All</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs text-secondary border-b border-gray-50">
                                <th class="pb-3 font-medium pl-2">Description</th>
                                <th class="pb-3 font-medium">Date</th>
                                <th class="pb-3 font-medium">Type</th>
                                <th class="pb-3 font-medium text-right pr-2">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php if (empty($recent_transactions)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-400">No transactions found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_transactions as $t): ?>
                                <tr class="group hover:bg-gray-50 transition-colors">
                                    <td class="py-4 pl-2 border-b border-gray-50 group-last:border-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                <span class="material-icons text-gray-400 text-xs">
                                                    <?php 
                                                        switch($t['type']) {
                                                            case 'earn': echo 'add'; break;
                                                            case 'redeem': echo 'remove'; break;
                                                            case 'transfer': echo 'swap_horiz'; break;
                                                            case 'topup': echo 'account_balance'; break;
                                                        }
                                                    ?>
                                                </span>
                                            </div>
                                            <span class="font-medium text-dark"><?php echo htmlspecialchars($t['description']); ?></span>
                                        </div>
                                    </td>
                                    <td class="py-4 border-b border-gray-50 text-secondary"><?php echo date('M d, Y', strtotime($t['created_at'])); ?></td>
                                    <td class="py-4 border-b border-gray-50">
                                        <span class="px-2 py-1 rounded text-xs font-medium 
                                            <?php 
                                                switch($t['type']) {
                                                    case 'earn': echo 'bg-green-100 text-green-700'; break;
                                                    case 'redeem': echo 'bg-purple-100 text-purple-700'; break;
                                                    case 'transfer': echo 'bg-blue-100 text-blue-700'; break;
                                                    case 'topup': echo 'bg-yellow-100 text-yellow-700'; break;
                                                }
                                            ?>">
                                            <?php echo ucfirst($t['type']); ?>
                                        </span>
                                    </td>
                                    <td class="py-4 pr-2 border-b border-gray-50 text-right font-bold <?php echo ($t['type'] == 'earn' || $t['type'] == 'topup' || ($t['type'] == 'transfer' && strpos($t['description'], 'Received') !== false)) ? 'text-primary' : 'text-dark'; ?>">
                                        <?php echo ($t['type'] == 'earn' || $t['type'] == 'topup' || ($t['type'] == 'transfer' && strpos($t['description'], 'Received') !== false)) ? '+' : '-'; ?>
                                        <?php echo number_format($t['amount']); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                <h3 class="font-bold text-dark text-lg mb-6">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="earnpoints.php" class="p-4 rounded-xl border border-gray-100 hover:border-primary hover:bg-green-50 transition-all flex flex-col items-center gap-2 group text-center">
                        <span class="material-icons text-primary group-hover:scale-110 transition-transform">calendar_today</span>
                        <span class="text-xs font-bold text-dark">Daily Check-in</span>
                    </a>
                    <a href="redeempoints.php" class="p-4 rounded-xl border border-gray-100 hover:border-purple-500 hover:bg-purple-50 transition-all flex flex-col items-center gap-2 group text-center">
                        <span class="material-icons text-purple-500 group-hover:scale-110 transition-transform">card_giftcard</span>
                        <span class="text-xs font-bold text-dark">Redeem</span>
                    </a>
                    <div class="p-4 rounded-xl border border-gray-100 flex flex-col items-center gap-2 opacity-50 cursor-not-allowed text-center">
                        <span class="material-icons text-gray-400">qr_code</span>
                        <span class="text-xs font-bold text-gray-400">Scan QR</span>
                    </div>
                    <div class="p-4 rounded-xl border border-gray-100 flex flex-col items-center gap-2 opacity-50 cursor-not-allowed text-center">
                        <span class="material-icons text-gray-400">more_horiz</span>
                        <span class="text-xs font-bold text-gray-400">More</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

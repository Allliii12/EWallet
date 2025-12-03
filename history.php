<?php
require('config.php');
require('auth_session.php');

$user_id = $_SESSION['user_id'];

// Fetch transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - E-Wallet</title>
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

    <div class="md:ml-64 p-6 md:p-10">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-2xl font-bold text-dark mb-6">Transaction History</h1>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="py-4 px-6 font-medium text-secondary text-sm">Type</th>
                                <th class="py-4 px-6 font-medium text-secondary text-sm">Description</th>
                                <th class="py-4 px-6 font-medium text-secondary text-sm">Date</th>
                                <th class="py-4 px-6 font-medium text-secondary text-sm text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-400">No transactions found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transactions as $t): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-xs font-medium 
                                            <?php 
                                                switch($t['type']) {
                                                    case 'earn': echo 'bg-green-100 text-green-700'; break;
                                                    case 'redeem': echo 'bg-purple-100 text-purple-700'; break;
                                                    case 'transfer': echo 'bg-blue-100 text-blue-700'; break;
                                                    case 'topup': echo 'bg-yellow-100 text-yellow-700'; break;
                                                }
                                            ?>">
                                            <span class="material-icons text-[14px]">
                                                <?php 
                                                    switch($t['type']) {
                                                        case 'earn': echo 'add'; break;
                                                        case 'redeem': echo 'remove'; break;
                                                        case 'transfer': echo 'swap_horiz'; break;
                                                        case 'topup': echo 'account_balance'; break;
                                                    }
                                                ?>
                                            </span>
                                            <?php echo ucfirst($t['type']); ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-dark font-medium"><?php echo htmlspecialchars($t['description']); ?></td>
                                    <td class="py-4 px-6 text-secondary text-sm"><?php echo date('M d, Y H:i', strtotime($t['created_at'])); ?></td>
                                    <td class="py-4 px-6 text-right font-bold <?php echo ($t['type'] == 'earn' || $t['type'] == 'topup' || ($t['type'] == 'transfer' && strpos($t['description'], 'Received') !== false)) ? 'text-primary' : 'text-dark'; ?>">
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
        </div>
    </div>
</body>
</html>

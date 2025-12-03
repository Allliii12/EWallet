<?php
require('config.php');
require('auth_session.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = 500; // Fixed amount for demo
    $user_id = $_SESSION['user_id'];
    $method = $_POST['method'];

    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
        $stmt->execute([$amount, $user_id]);
        
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'topup', ?, ?)");
        $stmt->execute([$user_id, $amount, "Top Up via $method"]);
        
        $pdo->commit();
        $message = "Successfully added $amount points via $method!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Top Up failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up - E-Wallet</title>
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
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold text-dark mb-6">Top Up Balance</h1>

            <?php if ($message): ?>
                <div class="bg-green-100 text-green-700 border border-green-400 px-4 py-3 rounded-lg mb-6">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <form method="post" class="h-full">
                    <input type="hidden" name="method" value="Debit Card">
                    <button type="submit" class="w-full h-full bg-white border border-gray-200 rounded-2xl p-8 flex flex-col items-center justify-center gap-6 hover:border-primary hover:bg-green-50 hover:shadow-md transition-all group cursor-pointer">
                        <div class="w-20 h-20 rounded-full bg-gray-50 group-hover:bg-white flex items-center justify-center transition-colors">
                            <span class="material-icons text-5xl text-gray-400 group-hover:text-primary transition-colors">credit_card</span>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-dark text-lg mb-1">Debit / Credit Card</h3>
                            <p class="text-secondary text-sm">Instant top up via card</p>
                        </div>
                    </button>
                </form>

                <form method="post" class="h-full">
                    <input type="hidden" name="method" value="Gift Code">
                    <button type="submit" class="w-full h-full bg-white border border-gray-200 rounded-2xl p-8 flex flex-col items-center justify-center gap-6 hover:border-primary hover:bg-green-50 hover:shadow-md transition-all group cursor-pointer">
                        <div class="w-20 h-20 rounded-full bg-gray-50 group-hover:bg-white flex items-center justify-center transition-colors">
                            <span class="material-icons text-5xl text-gray-400 group-hover:text-primary transition-colors">card_giftcard</span>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-dark text-lg mb-1">Redeem Gift Code</h3>
                            <p class="text-secondary text-sm">Enter a voucher code</p>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

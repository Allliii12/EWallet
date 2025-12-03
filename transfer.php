<?php
require('config.php');
require('auth_session.php');

$message = "";
$status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_username = $_POST['recipient'];
    $amount = intval($_POST['amount']);
    $sender_id = $_SESSION['user_id'];

    if ($amount <= 0) {
        $message = "Invalid amount.";
        $status = "error";
    } else {
        // Check sender balance
        $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
        $stmt->execute([$sender_id]);
        $sender = $stmt->fetch();

        if ($sender['points'] < $amount) {
            $message = "Insufficient balance.";
            $status = "error";
        } else {
            // Check recipient exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$recipient_username]);
            $recipient = $stmt->fetch();

            if (!$recipient) {
                $message = "Recipient not found.";
                $status = "error";
            } elseif ($recipient['id'] == $sender_id) {
                $message = "Cannot transfer to yourself.";
                $status = "error";
            } else {
                // Perform Transfer
                try {
                    $pdo->beginTransaction();

                    // Deduct from sender
                    $stmt = $pdo->prepare("UPDATE users SET points = points - ? WHERE id = ?");
                    $stmt->execute([$amount, $sender_id]);

                    // Add to recipient
                    $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
                    $stmt->execute([$amount, $recipient['id']]);

                    // Log for sender
                    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'transfer', ?, ?)");
                    $stmt->execute([$sender_id, $amount, "Sent to $recipient_username"]);

                    // Log for recipient
                    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'transfer', ?, ?)");
                    $stmt->execute([$recipient['id'], $amount, "Received from " . $_SESSION['username']]);

                    $pdo->commit();
                    $message = "Successfully transferred $amount points to $recipient_username.";
                    $status = "success";
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $message = "Transfer failed: " . $e->getMessage();
                    $status = "error";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer - E-Wallet</title>
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
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold text-dark mb-6">Transfer Points</h1>

            <?php if ($message): ?>
                <div class="<?php echo $status === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400'; ?> border px-4 py-3 rounded-lg mb-6">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <form method="post" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Recipient Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-icons text-gray-400 text-sm">person</span>
                            </span>
                            <input type="text" name="recipient" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors" placeholder="Enter username">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-icons text-gray-400 text-sm">confirmation_number</span>
                            </span>
                            <input type="number" name="amount" required min="1" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors" placeholder="0">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-primary hover:bg-green-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/30 transition-all transform hover:scale-[1.01]">
                            Send Points
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
require('config.php');
require('auth_session.php');

$message = "";
$status = "";

// Handle Redemption
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reward_id'])) {
    $reward_id = $_POST['reward_id'];
    $user_id = $_SESSION['user_id'];
    
    // Get reward cost
    $stmt = $pdo->prepare("SELECT * FROM rewards WHERE id = ?");
    $stmt->execute([$reward_id]);
    $reward = $stmt->fetch();
    
    // Get user points
    $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if ($user['points'] >= $reward['cost']) {
        try {
            $pdo->beginTransaction();
            
            // Deduct points
            $stmt = $pdo->prepare("UPDATE users SET points = points - ? WHERE id = ?");
            $stmt->execute([$reward['cost'], $user_id]);
            
            // Log transaction
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'redeem', ?, ?)");
            $stmt->execute([$user_id, $reward['cost'], 'Redeemed: ' . $reward['name']]);
            
            $pdo->commit();
            $message = "Successfully redeemed " . $reward['name'] . "!";
            $status = "success";
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Transaction failed.";
            $status = "error";
        }
    } else {
        $message = "Insufficient points!";
        $status = "error";
    }
}

// Fetch Rewards
$rewards = $pdo->query("SELECT * FROM rewards")->fetchAll();
$user_points = $pdo->query("SELECT points FROM users WHERE id = " . $_SESSION['user_id'])->fetch()['points'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redeem Rewards</title>
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
<body class="bg-gray-50 min-h-screen pb-10">
    <!-- Header -->
    <div class="bg-white p-4 shadow-sm flex items-center gap-4 sticky top-0 z-10">
        <a href="dashboard.php" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
            <span class="material-icons text-dark">arrow_back</span>
        </a>
        <div class="flex-1">
            <h1 class="text-lg font-bold text-dark">Redeem Rewards</h1>
            <p class="text-xs text-secondary">Balance: <span class="font-bold text-primary"><?php echo number_format($user_points); ?> PTS</span></p>
        </div>
    </div>

    <div class="p-6 max-w-2xl mx-auto">
        <?php if ($message): ?>
            <div class="<?php echo $status === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400'; ?> border px-4 py-3 rounded-lg mb-6 sticky top-20 z-20 shadow-md">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="grid gap-4">
            <?php foreach ($rewards as $reward): ?>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <img src="<?php echo $reward['image_url']; ?>" alt="<?php echo $reward['name']; ?>" class="w-20 h-20 rounded-lg object-cover bg-gray-100">
                
                <div class="flex-1">
                    <h3 class="font-bold text-dark"><?php echo $reward['name']; ?></h3>
                    <p class="text-sm text-secondary mb-2"><?php echo $reward['description']; ?></p>
                    <span class="text-primary font-bold text-sm"><?php echo number_format($reward['cost']); ?> PTS</span>
                </div>

                <form method="post">
                    <input type="hidden" name="reward_id" value="<?php echo $reward['id']; ?>">
                    <button type="submit" class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" <?php echo ($user_points < $reward['cost']) ? 'disabled' : ''; ?>>
                        Redeem
                    </button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

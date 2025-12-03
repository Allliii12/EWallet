<?php
require('config.php');
require('auth_session.php');

$message = "";
$status = ""; // success or error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $today = date('Y-m-d');
    
    // Check last checkin
    $stmt = $pdo->prepare("SELECT last_checkin FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user['last_checkin'] === $today) {
        $message = "You have already checked in today!";
        $status = "error";
    } else {
        // Update points and last_checkin
        $points_to_add = 100; // Daily reward
        
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("UPDATE users SET points = points + ?, last_checkin = ? WHERE id = ?");
            $stmt->execute([$points_to_add, $today, $user_id]);
            
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'earn', ?, 'Daily Check-in')");
            $stmt->execute([$user_id, $points_to_add]);
            
            $pdo->commit();
            $message = "Successfully claimed +$points_to_add points!";
            $status = "success";
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Error processing check-in.";
            $status = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Check-in</title>
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
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <div class="bg-white p-4 shadow-sm flex items-center gap-4 sticky top-0 z-10">
        <a href="dashboard.php" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
            <span class="material-icons text-dark">arrow_back</span>
        </a>
        <h1 class="text-lg font-bold text-dark">Daily Check-in</h1>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center p-6">
        <div class="w-full max-w-sm text-center">
            <div class="w-32 h-32 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <span class="material-icons text-6xl text-primary">calendar_today</span>
            </div>
            
            <h2 class="text-2xl font-bold text-dark mb-2">Claim Your Daily Reward</h2>
            <p class="text-secondary mb-8">Check in every day to earn 100 points!</p>

            <?php if ($message): ?>
                <div class="<?php echo $status === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-yellow-100 text-yellow-700 border-yellow-400'; ?> border px-4 py-3 rounded-lg mb-6">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <button type="submit" class="w-full bg-primary hover:bg-green-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/30 transition-all transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" <?php echo ($status === 'success' || (isset($user['last_checkin']) && $user['last_checkin'] === date('Y-m-d'))) ? 'disabled' : ''; ?>>
                    <?php echo ($status === 'success' || (isset($user['last_checkin']) && $user['last_checkin'] === date('Y-m-d'))) ? 'Checked In' : 'Check In Now'; ?>
                </button>
            </form>
        </div>
    </div>
</body>
</html>

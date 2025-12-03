<?php
require('config.php');
require('auth_session.php');

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - E-Wallet</title>
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
        <div class="max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold text-dark mb-6">Account Settings</h1>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="p-8 border-b border-gray-100 flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center text-3xl font-bold text-gray-500">
                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-dark mb-1"><?php echo htmlspecialchars($user['username']); ?></h2>
                        <p class="text-secondary"><?php echo htmlspecialchars($user['email']); ?></p>
                        <button class="mt-4 text-primary text-sm font-semibold hover:underline">Change Profile Picture</button>
                    </div>
                </div>

                <div class="p-4">
                    <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-100 transition-colors">
                                <span class="material-icons">person</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark">Personal Information</h3>
                                <p class="text-xs text-secondary">Name, Date of Birth, Address</p>
                            </div>
                        </div>
                        <span class="material-icons text-gray-300">chevron_right</span>
                    </a>

                    <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500 group-hover:bg-purple-100 transition-colors">
                                <span class="material-icons">credit_card</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark">Card Management</h3>
                                <p class="text-xs text-secondary">Manage your saved cards</p>
                            </div>
                        </div>
                        <span class="material-icons text-gray-300">chevron_right</span>
                    </a>

                    <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-500 group-hover:bg-yellow-100 transition-colors">
                                <span class="material-icons">lock</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark">Security</h3>
                                <p class="text-xs text-secondary">Password, 2FA</p>
                            </div>
                        </div>
                        <span class="material-icons text-gray-300">chevron_right</span>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4">
                    <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-gray-200 transition-colors">
                                <span class="material-icons">help</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark">Help & Support</h3>
                                <p class="text-xs text-secondary">FAQ, Contact Us</p>
                            </div>
                        </div>
                        <span class="material-icons text-gray-300">chevron_right</span>
                    </a>
                    
                    <a href="logout.php" class="flex items-center justify-between p-4 hover:bg-red-50 rounded-xl transition-colors group mt-2">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-500 group-hover:bg-red-100 transition-colors">
                                <span class="material-icons">logout</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-red-500">Log Out</h3>
                                <p class="text-xs text-red-300">Sign out of your account</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
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
<body class="bg-white min-h-screen flex flex-col p-6 font-sans">
    <div class="mb-8">
        <div class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center mb-6">
            <span class="material-icons text-dark">menu</span>
        </div>
        <h1 class="text-3xl font-bold text-dark mb-2">Log In</h1>
    </div>

    <?php
    require('config.php');
    session_start();
    
    if (isset($_POST['username'])) {
        $user_input = $_POST['username'];
        $pass_input = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$user_input, $user_input]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass_input, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<div class='bg-red-50 text-red-500 p-3 rounded-lg mb-4 text-sm'>Incorrect username or password.</div>";
        }
    }
    ?>

    <form method="post" action="" class="space-y-4 flex-1">
        <div>
            <input type="text" name="username" required class="w-full p-4 border border-gray-200 rounded-xl focus:outline-none focus:border-dark transition-colors" placeholder="Email or Username">
        </div>
        
        <div>
            <input type="password" name="password" required class="w-full p-4 border border-gray-200 rounded-xl focus:outline-none focus:border-dark transition-colors" placeholder="Password">
        </div>

        <div class="text-right">
            <a href="#" class="text-sm text-dark font-medium">Forgot Password?</a>
        </div>

        <div class="mt-auto pt-8">
            <div class="flex justify-center mb-8">
                <div class="w-16 h-16 border border-gray-200 rounded-xl flex items-center justify-center">
                    <span class="material-icons text-3xl text-dark">face</span>
                </div>
            </div>
        </div>
    </form>
    
    <button onclick="document.querySelector('form').submit()" class="w-full bg-dark text-white font-bold py-4 rounded-xl mt-4 hover:bg-black transition-colors">
        Log In
    </button>

    <div class="mt-6 text-center text-sm">
        <span class="text-gray-400">Don't have an account?</span>
        <a href="register.php" class="text-dark font-bold ml-1">Sign Up</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
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
        <h1 class="text-3xl font-bold text-dark mb-2">Create Account</h1>
    </div>

    <?php
    require('config.php');
    if (isset($_POST['email'])) {
        $username = explode('@', $_POST['email'])[0]; // Simple username generation
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                echo "<div class='bg-green-50 text-green-600 p-3 rounded-lg mb-4 text-sm'>
                        Account created! <a href='index.php' class='font-bold underline'>Log In</a>
                      </div>";
            }
        } catch (PDOException $e) {
            echo "<div class='bg-red-50 text-red-500 p-3 rounded-lg mb-4 text-sm'>Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>

    <form method="post" action="" class="space-y-4 flex-1">
        <div>
            <input type="email" name="email" required class="w-full p-4 border border-gray-200 rounded-xl focus:outline-none focus:border-dark transition-colors" placeholder="Email">
        </div>
        
        <div>
            <input type="password" name="password" required class="w-full p-4 border border-gray-200 rounded-xl focus:outline-none focus:border-dark transition-colors" placeholder="Password">
        </div>

        <div class="flex items-start gap-3 mt-4">
            <input type="checkbox" required id="terms" class="mt-1 w-5 h-5 border-gray-300 rounded text-dark focus:ring-dark">
            <label for="terms" class="text-sm text-secondary leading-tight">
                By creating an account you agree to our <a href="#" class="text-dark font-bold">Terms and Conditions</a>
            </label>
        </div>

        <button type="submit" class="w-full bg-white border-2 border-dark text-dark font-bold py-4 rounded-xl mt-8 hover:bg-gray-50 transition-colors">
            Sign Up
        </button>
    </form>

    <div class="mt-6 text-center text-sm pb-8">
        <span class="text-gray-400">Already have an account?</span>
        <a href="index.php" class="text-dark font-bold ml-1">Log In</a>
    </div>
</body>
</html>

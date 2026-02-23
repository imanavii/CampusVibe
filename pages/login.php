<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Campus Vibe | Login</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .login-box {
            width: 320px;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #4338ca;
        }

        .signup {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .signup a {
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Login</h2>

        <form>
            <input type="text" placeholder="Username" required>
            <input type="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="signup">
            New user? <a href="#">Sign up</a>
        </div>
    </div>

</body>
</html>

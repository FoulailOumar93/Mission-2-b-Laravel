<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            border: 2px solid #4CAF50;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            color: #4CAF50;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .password-container {
            position: relative;
        }

        input[type="password"], 
        input[type="text"] {
            width: 100%;
            padding: 8px 40px 8px 8px; 
            border: 2px solid #4CAF50;
            border-radius: 8px;
            outline: none;
            transition: border 0.3s ease;
            box-sizing: border-box;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            font-size: 18px;
        }

        .btn {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .password-rules {
            text-align: left;
            font-size: 0.9em;
            color: #666;
            margin-top: -10px;
            margin-bottom: 15px;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Réinitialisation du mot de passe</h1>

        @if($errors->any())
            @foreach($errors->all() as $error)
                <p class="error">{{ $error }}</p>
            @endforeach
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="login" value="{{ $login }}">

            <div class="form-group">
                <label for="new_password">Nouveau mot de passe :</label>
                <div class="password-container">
                    <input type="password" id="new_password" name="new_password" placeholder="Entrez votre nouveau mot de passe..." required>
                    <span class="toggle-password" onclick="togglePassword('new_password', this)">👁️</span>
                </div>
            </div>

            <div class="password-rules">
                ➤ Minimum 8 caractères<br>
                ➤ Une majuscule et une minuscule<br>
                ➤ Un chiffre et un caractère spécial
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Confirmez le mot de passe :</label>
                <div class="password-container">
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirmez votre mot de passe..." required>
                    <span class="toggle-password" onclick="togglePassword('new_password_confirmation', this)">👁️</span>
                </div>
            </div>

            <button type="submit" class="btn">Modifier le mot de passe</button>
        </form>

        <a href="{{ route('chemin_connexion') }}">Retour à la connexion</a>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(function(icon) {
            icon.addEventListener('click', function() {
                const input = document.getElementById(icon.getAttribute('onclick').match(/'([^']+)'/)[1]);
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = '🙈';
                } else {
                    input.type = 'password';
                    icon.textContent = '👁️';
                }
            });
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            const newPassword = document.getElementById('new_password').value;
            const oldPassword = "{{ $oldPassword ?? '' }}";

            if (newPassword === oldPassword) {
                alert("Le nouveau mot de passe ne peut pas être identique à l'ancien.");
                event.preventDefault();
            }
        });
    });
    </script>

</body>
</html>

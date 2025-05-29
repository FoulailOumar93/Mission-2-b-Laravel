<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
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
            border: 2px solid #3498db;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            color: #3498db;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 2px solid #3498db;
            border-radius: 8px;
            outline: none;
            transition: border 0.3s ease;
        }

        .btn {
            background-color: #3498db;
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
            background-color: #2980b9;
        }

        .message {
            margin-top: 15px;
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mot de passe oublié</h1>

        @if(session('success'))
            <p class="message">{{ session('success') }}</p>
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                <p class="error">{{ $error }}</p>
            @endforeach
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="login">Login :</label>
                <input type="text" id="login" name="login" placeholder="Entrez votre login..." required>
            </div>

            <button type="submit" class="btn">Vérifier le login</button>
        </form>

        <a href="{{ route('chemin_connexion') }}">Retour à la connexion</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @stack('styles')
</head>
<body id="body"> <!-- ✅ Ne pas forcer dark-mode ici -->

    <div id="page">

        <!-- 🟦 EN-TÊTE -->
        <header id="entete" style="position: relative;">
            <img src="{{ asset('images/logo.jpg') }}" id="logoGSB" alt="GSB Logo" />
            <h1>Suivi du remboursement des frais</h1>

            <!-- 🌗 BOUTON THÈME -->
            <button id="toggleTheme" style="position: absolute; top: 20px; right: 30px; background: none; border: none; font-weight: bold; color: #007BBA; cursor: pointer;">
                🌙 Mode sombre
            </button>
        </header>

        <!-- 🧱 STRUCTURE GLOBALE -->
        <div id="wrapper">
            @yield('menu')

            <div style="flex: 1; padding: 30px;">
                <!-- ✅ MESSAGE SUCCÈS -->
                @if (session('message'))
                    <div class="flash-message">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- ❌ MESSAGE ERREUR -->
                @if (session('erreurs'))
                    <div class="flash-erreur">
                        @foreach ((array) session('erreurs') as $erreur)
                            <p>{{ $erreur }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- 📦 CONTENU PRINCIPAL -->
                <main id="contenu">
                    @yield('contenu1')
                    @yield('contenu2')
                    @yield('contenu3')
                    @yield('contenu4')
                </main>
            </div>
        </div>
    </div>

    <!-- ⏳ AUTO-FERMETURE DES MESSAGES -->
    <script>
        setTimeout(() => {
            document.querySelectorAll('.flash-message, .flash-erreur').forEach(e => e.remove());
        }, 5000);
    </script>

    <!-- 🌗 SCRIPT THÈME -->
    <script>
        const body = document.getElementById('body');
        const toggleBtn = document.getElementById('toggleTheme');

        // 🧠 Appliquer le thème sauvegardé ou celui du système si aucun
        if (!localStorage.getItem('theme')) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            localStorage.setItem('theme', prefersDark ? 'dark' : 'light');
        }

        // 🚀 Appliquer le thème
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            toggleBtn.innerHTML = '☀️ Mode clair';
        }

        // 🎯 Changement de thème au clic
        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            toggleBtn.innerHTML = isDark ? '☀️ Mode clair' : '🌙 Mode sombre';
        });
    </script>

    @stack('scripts')
</body>
</html>

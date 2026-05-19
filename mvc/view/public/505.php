<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>505 - Database Unavailable</title>
    <style>
        :root {
            --bg: #070707;
            --panel: rgba(16, 16, 16, 0.92);
            --border: rgba(230, 57, 70, 0.18);
            --text: #f5eee5;
            --muted: rgba(245, 238, 229, 0.7);
            --red: #E63946;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at top, rgba(230,57,70,0.15), transparent 35%),
                radial-gradient(circle at 20% 20%, rgba(255, 214, 102, 0.08), transparent 18%),
                var(--bg);
            color: var(--text);
            font-family: Arial, sans-serif;
            padding: 24px;
        }
        .wrap {
            width: min(920px, 100%);
            border: 1px solid var(--border);
            background: var(--panel);
            border-radius: 24px;
            padding: 34px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.45);
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 28px;
            align-items: center;
        }
        .art {
            width: 100%;
            max-width: 320px;
            justify-self: center;
        }
        .title {
            margin: 0 0 10px;
            font-size: clamp(2.2rem, 4vw, 3.6rem);
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .code {
            display: inline-block;
            margin-bottom: 10px;
            color: var(--red);
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .text {
            margin: 0 0 18px;
            color: var(--muted);
            line-height: 1.65;
            font-size: 1rem;
        }
        .meta {
            display: grid;
            gap: 10px;
            padding: 16px 0 0;
            border-top: 1px solid rgba(255,255,255,0.08);
            color: rgba(245, 238, 229, 0.76);
            font-size: 0.95rem;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 10px 16px;
            background: rgba(255,255,255,0.03);
            color: var(--text);
            text-decoration: none;
            margin-top: 8px;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--red);
            box-shadow: 0 0 16px rgba(230,57,70,0.8);
        }
        @media (max-width: 760px) {
            .wrap { grid-template-columns: 1fr; text-align: center; }
            .art { max-width: 240px; }
            .pill { margin-inline: auto; }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <img class="art" src="/e_commerce-bijoux-chatbot/mvc/view/images/505.svg" alt="505 error illustration">
        <section>
            <div class="code">505</div>
            <h1 class="title">Base de données indisponible</h1>
            <p class="text">Le serveur MySQL ne répond pas pour le moment. La boutique ne peut pas charger les données tant que la base n’est pas rétablie.</p>
            <div class="meta">
                <div>Vérifiez que MySQL est démarré dans XAMPP et que la base <strong>shopping</strong> est accessible.</div>
                <div>Si le problème persiste, rechargez la page après redémarrage du service.</div>
            </div>
            <a class="pill" href="/e_commerce-bijoux-chatbot/mvc/view/public/index.php"><span class="dot"></span> Retour à l’accueil</a>
        </section>
    </main>
</body>
</html>

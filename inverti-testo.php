<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inverti Testo - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🔃 Inverti Testo</h1>
            
            <?php
            $testo = '';
            $risultato = '';
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $testo = $_POST['testo'] ?? '';
                
                if ($testo !== '') {
                    $risultato = strrev($testo);
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="testo">Testo da invertire</label>
                    <textarea id="testo" name="testo" placeholder="Scrivi o incolla il tuo testo..."><?php echo htmlspecialchars($testo); ?></textarea>
                </div>
                
                <button type="submit">Inverti</button>
            </form>
            
            <?php if ($risultato !== ''): ?>
            <div class="result">
                <div class="result-label">Risultato:</div>
                <p style="font-size: 1.1rem; font-family: monospace; word-break: break-all; margin: 10px 0;">
                    <?php echo htmlspecialchars($risultato); ?>
                </p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo addslashes($risultato); ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

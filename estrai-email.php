<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estrai Email - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>📧 Estrai Email da Testo</h1>
            
            <?php
            $testo = '';
            $email_trovate = [];
            $unica = false;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $testo = $_POST['testo'] ?? '';
                $unica = isset($_POST['unica']);
                
                if ($testo !== '') {
                    $pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
                    preg_match_all($pattern, $testo, $matches);
                    
                    $email_trovate = $matches[0];
                    
                    if ($unica && !empty($email_trovate)) {
                        $email_trovate = array_values(array_unique($email_trovate));
                    }
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="testo">Incolla il testo contenente email</label>
                    <textarea id="testo" name="testo" placeholder="Incolla qui il testo da cui estrarre le email..." style="min-height: 150px;"><?php echo htmlspecialchars($testo); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="unica" <?php echo $unica ? 'checked' : ''; ?>>
                        Solo email uniche (rimuovi duplicati)
                    </label>
                </div>
                
                <button type="submit">Estrai Email</button>
            </form>
            
            <?php if (!empty($email_trovate)): ?>
            <div class="result">
                <div class="result-label">Email trovate: <?php echo count($email_trovate); ?></div>
                <p style="font-family: monospace; font-size: 0.9rem; margin: 10px 0; white-space: pre-wrap;">
                    <?php echo htmlspecialchars(implode("\n", $email_trovate)); ?>
                </p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo addslashes(implode("\n", $email_trovate)); ?>'); alert('Copiato!');">
                    📋 Copia tutte
                </button>
            </div>
            <?php elseif ($testo !== ''): ?>
            <div class="result">
                <p style="color: #666;">Nessuna email trovata nel testo.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

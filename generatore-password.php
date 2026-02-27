<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generatore Password - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🔐 Generatore Password</h1>
            
            <?php
            $password = '';
            $lunghezza = 16;
            $maiuscole = true;
            $minuscole = true;
            $min_numeri = 0;
            $min_simboli = 0;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $lunghezza = intval($_POST['lunghezza'] ?? 16);
                $lunghezza = max(4, min(64, $lunghezza));
                $maiuscole = isset($_POST['maiuscole']);
                $minuscole = isset($_POST['minuscole']);
                $min_numeri = intval($_POST['min_numeri'] ?? 0);
                $min_simboli = intval($_POST['min_simboli'] ?? 0);
                
                $caratteri_base = '';
                if ($maiuscole) $caratteri_base .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                if ($minuscole) $caratteri_base .= 'abcdefghijklmnopqrstuvwxyz';
                $numeri_set = '0123456789';
                $simboli_set = '!@#$%^&*()_+-=[]{}|;:,.<>?';
                
                if ($caratteri_base === '') {
                    $caratteri_base = 'abcdefghijklmnopqrstuvwxyz';
                }
                
                $password = '';
                
                // Aggiungi prima i caratteri obbligatori
                for ($i = 0; $i < $min_numeri; $i++) {
                    $password .= $numeri_set[random_int(0, 9)];
                }
                for ($i = 0; $i < $min_simboli; $i++) {
                    $password .= $simboli_set[random_int(0, strlen($simboli_set) - 1)];
                }
                
                // Riempi il resto con caratteri casuali
                $caratteri_totali = $caratteri_base . $numeri_set . $simboli_set;
                $rimanenti = $lunghezza - strlen($password);
                for ($i = 0; $i < $rimanenti; $i++) {
                    $password .= $caratteri_totali[random_int(0, strlen($caratteri_totali) - 1)];
                }
                
                // Mischia la password
                $password = str_shuffle($password);
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="lunghezza">Lunghezza password: <?php echo $lunghezza; ?> caratteri</label>
                    <input type="number" min="4" max="64" id="lunghezza" name="lunghezza" 
                           value="<?php echo $lunghezza; ?>">
                </div>
                
                <div class="form-group">
                    <label>Tipi di caratteri:</label>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="maiuscole" <?php echo $maiuscole ? 'checked' : ''; ?>>
                            Maiuscole (A-Z)
                        </label>
                        <label>
                            <input type="checkbox" name="minuscole" <?php echo $minuscole ? 'checked' : ''; ?>>
                            Minuscole (a-z)
                        </label>
                    </div>
                </div>
                
                <div class="inline-group">
                    <div class="form-group" style="flex:1;">
                        <label for="min_numeri">Min. numeri</label>
                        <input type="number" min="0" max="64" id="min_numeri" name="min_numeri" 
                               value="<?php echo $min_numeri; ?>">
                    </div>
                    
                    <div class="form-group" style="flex:1;">
                        <label for="min_simboli">Min. simboli</label>
                        <input type="number" min="0" max="64" id="min_simboli" name="min_simboli" 
                               value="<?php echo $min_simboli; ?>">
                    </div>
                </div>
                
                <button type="submit">Genera Password</button>
            </form>
            
            <?php if ($password !== ''): ?>
            <div class="result">
                <div class="result-label">Password generata:</div>
                <p style="font-size: 1.3rem; font-family: monospace; word-break: break-all;">
                    <?php echo htmlspecialchars($password); ?>
                </p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo addslashes($password); ?>'); alert('Password copiata!');">
                    📋 Copia password
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

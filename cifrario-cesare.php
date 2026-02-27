<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cifrario di Cesare - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🔐 Cifrario di Cesare</h1>
            
            <?php
            $testo = '';
            $chiave = 3;
            $azione = 'cifra';
            $risultato = '';
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $testo = $_POST['testo'] ?? '';
                $chiave = intval($_POST['chiave'] ?? 3);
                $azione = $_POST['azione'] ?? 'cifra';
                
                if ($testo !== '') {
                    // Normalizza la chiave (0-25)
                    $chiave = $chiave % 26;
                    if ($chiave < 0) $chiave += 26;
                    
                    if ($azione === 'decifra') {
                        $chiave = 26 - $chiave;
                    }
                    
                    $risultato = '';
                    $lunghezza = strlen($testo);
                    for ($i = 0; $i < $lunghezza; $i++) {
                        $c = $testo[$i];
                        
                        if (ctype_upper($c)) {
                            // Lettera maiuscola
                            $risultato .= chr((ord($c) - ord('A') + $chiave) % 26 + ord('A'));
                        } elseif (ctype_lower($c)) {
                            // Lettera minuscola
                            $risultato .= chr((ord($c) - ord('a') + $chiave) % 26 + ord('a'));
                        } else {
                            // Carattere non alfabetico (numeri, punteggiatura, spazi)
                            $risultato .= $c;
                        }
                    }
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="testo">Testo da cifrare/decifrare</label>
                    <textarea id="testo" name="testo" placeholder="Inserisci il tuo testo..."><?php echo htmlspecialchars($testo); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="chiave">Chiave (spostamento): <?php echo $chiave; ?> posizioni</label>
                    <input type="number" min="1" id="chiave" name="chiave" 
                           value="<?php echo $chiave > 0 ? $chiave : 3; ?>">
                    <p style="font-size: 0.85rem; color: #888; margin-top: 6px;">
                        La chiave indica di quante posizioni spostare le lettere nell'alfabeto.
                        Valori superiori a 25 vengono automaticamente normalizzati (es. 27 = 1)
                    </p>
                </div>
                
                <div class="form-group">
                    <label>Operazione:</label>
                    <div class="checkbox-group">
                        <label style="padding: 10px 16px; border: 1px solid <?php echo $azione === 'cifra' ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo $azione === 'cifra' ? '#1a1a1a' : '#fff'; ?>; color: <?php echo $azione === 'cifra' ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="azione" value="cifra" <?php echo $azione === 'cifra' ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;">
                            🔒 Cifra
                        </label>
                        <label style="padding: 10px 16px; border: 1px solid <?php echo $azione === 'decifra' ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo $azione === 'decifra' ? '#1a1a1a' : '#fff'; ?>; color: <?php echo $azione === 'decifra' ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="azione" value="decifra" <?php echo $azione === 'decifra' ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;">
                            🔓 Decifra
                        </label>
                    </div>
                </div>
                
                <button type="submit">Esegui</button>
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
            
            <div class="result" style="margin-top: 15px; background: #fff; border-color: #ddd;">
                <div class="result-label">Info:</div>
                <p style="font-size: 0.9rem; color: #666;">
                    Il <strong>Cifrario di Cesare</strong> è una tecnica di crittografia a sostituzione monoalfabetica. 
                    Ogni lettera viene spostata di un numero fisso di posizioni nell'alfabeto. 
                    Con chiave 3: A→D, B→E, C→F, ecc.
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

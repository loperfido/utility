<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base64 Encoder/Decoder - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🔄 Base64 Encoder/Decoder</h1>
            
            <?php
            $input = '';
            $output = '';
            $operazione = '';
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = $_POST['input'] ?? '';
                $operazione = $_POST['operazione'] ?? 'encode';
                
                if ($input !== '') {
                    if ($operazione === 'encode') {
                        $output = base64_encode($input);
                    } else {
                        $output = base64_decode($input);
                        if ($output === false) {
                            $output = 'Errore: Input Base64 non valido';
                        }
                    }
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="input">Testo</label>
                    <textarea id="input" name="input" placeholder="Inserisci testo o stringa Base64..."><?php echo htmlspecialchars($input); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Operazione:</label>
                    <div class="checkbox-group">
                        <label style="padding: 10px 16px; border: 1px solid <?php echo ($operazione ?? 'encode') === 'encode' ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo ($operazione ?? 'encode') === 'encode' ? '#1a1a1a' : '#fff'; ?>; color: <?php echo ($operazione ?? 'encode') === 'encode' ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="operazione" value="encode" <?php echo ($operazione ?? 'encode') === 'encode' ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;">
                            Codifica → Base64
                        </label>
                        <label style="padding: 10px 16px; border: 1px solid <?php echo ($operazione ?? '') === 'decode' ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo ($operazione ?? '') === 'decode' ? '#1a1a1a' : '#fff'; ?>; color: <?php echo ($operazione ?? '') === 'decode' ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="operazione" value="decode" <?php echo ($operazione ?? '') === 'decode' ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;">
                            ← Decodifica Base64
                        </label>
                    </div>
                </div>
                
                <button type="submit">Esegui</button>
            </form>
            
            <?php if ($output !== ''): ?>
            <div class="result">
                <div class="result-label">Risultato:</div>
                <p style="font-family: monospace; word-break: break-all; max-height: 200px; overflow-y: auto;">
                    <?php echo htmlspecialchars($output); ?>
                </p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo addslashes($output); ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Converter - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🔤 Case Converter</h1>
            
            <?php
            $testo = '';
            $risultato = '';
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $testo = $_POST['testo'] ?? '';
                $azione = $_POST['azione'] ?? 'upper';

                switch ($azione) {
                    case 'upper':
                        $risultato = strtoupper($testo);
                        break;
                    case 'lower':
                        $risultato = strtolower($testo);
                        break;
                    case 'title':
                        $risultato = ucwords(strtolower($testo));
                        break;
                    case 'sentence':
                        $risultato = ucfirst(strtolower($testo));
                        break;
                    case 'inverse':
                        $risultato = '';
                        for ($i = 0; $i < strlen($testo); $i++) {
                            $c = $testo[$i];
                            $risultato .= ctype_upper($c) ? strtolower($c) : strtoupper($c);
                        }
                        break;
                    case 'snake':
                        $risultato = strtolower(str_replace(' ', '_', trim($testo)));
                        break;
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="testo">Inserisci testo</label>
                    <textarea id="testo" name="testo" placeholder="Scrivi o incolla il tuo testo qui..."><?php echo htmlspecialchars($testo); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="azione">Tipo di conversione</label>
                    <select id="azione" name="azione">
                        <option value="upper" <?php echo ($azione ?? 'upper') === 'upper' ? 'selected' : ''; ?>>MAIUSCOLO</option>
                        <option value="lower" <?php echo ($azione ?? '') === 'lower' ? 'selected' : ''; ?>>minuscolo</option>
                        <option value="title" <?php echo ($azione ?? '') === 'title' ? 'selected' : ''; ?>>Iniziali Maiuscole</option>
                        <option value="sentence" <?php echo ($azione ?? '') === 'sentence' ? 'selected' : ''; ?>>Prima lettera maiuscola</option>
                        <option value="inverse" <?php echo ($azione ?? '') === 'inverse' ? 'selected' : ''; ?>>Inverti (aBcDeF)</option>
                        <option value="snake" <?php echo ($azione ?? '') === 'snake' ? 'selected' : ''; ?>>snake_case (minuscolo + _)</option>
                    </select>
                </div>
                
                <button type="submit">Converti</button>
            </form>
            
            <?php if ($risultato !== ''): ?>
            <div class="result">
                <div class="result-label">Risultato:</div>
                <p><?php echo htmlspecialchars($risultato); ?></p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo addslashes($risultato); ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

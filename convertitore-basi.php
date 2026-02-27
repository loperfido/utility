<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertitore Basi Numeriche - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🔢 Convertitore Decimale ↔ Binario ↔ Esadecimale</h1>
            
            <?php
            $valore = '';
            $base_origine = 'dec';
            $risultati = null;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $valore = trim($_POST['valore'] ?? '');
                $base_origine = $_POST['base_origine'] ?? 'dec';
                
                if ($valore !== '') {
                    $decimale = null;
                    
                    // Converte in decimale in base alla base di origine
                    if ($base_origine === 'dec') {
                        if (ctype_digit($valore)) {
                            $decimale = intval($valore);
                        }
                    } elseif ($base_origine === 'bin') {
                        if (preg_match('/^[01]+$/', $valore)) {
                            $decimale = bindec($valore);
                        }
                    } elseif ($base_origine === 'hex') {
                        $valore_pulito = preg_replace('/[^0-9a-fA-F]/', '', $valore);
                        if ($valore_pulito !== '') {
                            $decimale = hexdec($valore_pulito);
                        }
                    }
                    
                    if ($decimale !== null) {
                        $risultati = [
                            'decimale' => $decimale,
                            'binario' => decbin($decimale),
                            'esadecimale' => strtoupper(dechex($decimale)),
                            'ottale' => decoct($decimale)
                        ];
                    }
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="valore">Valore</label>
                    <input type="text" id="valore" name="valore" 
                           value="<?php echo htmlspecialchars($valore); ?>" 
                           placeholder="Inserisci il numero..." required>
                </div>
                
                <div class="form-group">
                    <label for="base_origine">Base di origine</label>
                    <select id="base_origine" name="base_origine">
                        <option value="dec" <?php echo $base_origine === 'dec' ? 'selected' : ''; ?>>Decimale (0-9)</option>
                        <option value="bin" <?php echo $base_origine === 'bin' ? 'selected' : ''; ?>>Binario (0-1)</option>
                        <option value="hex" <?php echo $base_origine === 'hex' ? 'selected' : ''; ?>>Esadecimale (0-9, A-F)</option>
                    </select>
                </div>
                
                <button type="submit">Converti</button>
            </form>
            
            <?php if ($risultati): ?>
            <div class="result">
                <div class="result-label">Decimale:</div>
                <p style="font-family: monospace; font-size: 1.2rem;"><?php echo number_format($risultati['decimale']); ?></p>
            </div>
            
            <div class="result">
                <div class="result-label">Binario:</div>
                <p style="font-family: monospace; font-size: 1.1rem; word-break: break-all;"><?php echo $risultati['binario']; ?></p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo $risultati['binario']; ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            
            <div class="result">
                <div class="result-label">Esadecimale:</div>
                <p style="font-family: monospace; font-size: 1.1rem;"><?php echo $risultati['esadecimale']; ?></p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo $risultati['esadecimale']; ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            
            <div class="result">
                <div class="result-label">Ottale:</div>
                <p style="font-family: monospace; font-size: 1.1rem;"><?php echo $risultati['ottale']; ?></p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo $risultati['ottale']; ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            <?php elseif ($valore !== ''): ?>
            <div class="result">
                <p style="color: #666;">Valore non valido per la base selezionata.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

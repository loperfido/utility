<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numeri Casuali - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🎲 Generatore Numeri Casuali</h1>
            
            <?php
            $min = 1;
            $max = 100;
            $quantita = 1;
            $numeri = [];
            $univoci = false;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $min = intval($_POST['min'] ?? 1);
                $max = intval($_POST['max'] ?? 100);
                $quantita = intval($_POST['quantita'] ?? 1);
                $univoci = isset($_POST['univoci']);
                
                // Validazioni
                if ($min >= $max) {
                    $max = $min + 1;
                }
                $quantita = max(1, min(100, $quantita));
                
                // Verifica se è possibile avere numeri univoci
                $max_univoci = $max - $min + 1;
                if ($univoci && $quantita > $max_univoci) {
                    $quantita = $max_univoci;
                }
                
                if ($univoci) {
                    // Genera numeri univoci
                    $pool = range($min, $max);
                    shuffle($pool);
                    $numeri = array_slice($pool, 0, $quantita);
                    sort($numeri);
                } else {
                    // Genera numeri con possibili duplicati
                    for ($i = 0; $i < $quantita; $i++) {
                        $numeri[] = random_int($min, $max);
                    }
                }
            }
            ?>
            
            <form method="POST">
                <div class="inline-group">
                    <div class="form-group" style="flex: 1;">
                        <label for="min">Numero minimo</label>
                        <input type="number" id="min" name="min" 
                               value="<?php echo $min; ?>" required>
                    </div>
                    
                    <div class="form-group" style="flex: 1;">
                        <label for="max">Numero massimo</label>
                        <input type="number" id="max" name="max" 
                               value="<?php echo $max; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="quantita">Quantità numeri da generare</label>
                    <input type="number" min="1" max="100" id="quantita" name="quantita" 
                           value="<?php echo $quantita; ?>">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="univoci" <?php echo $univoci ? 'checked' : ''; ?>>
                        Solo numeri univoci (senza duplicati)
                    </label>
                </div>
                
                <button type="submit">Genera</button>
            </form>
            
            <?php if (!empty($numeri)): ?>
            <div class="result">
                <div class="result-label">Numeri generati:</div>
                <p style="font-size: 1.5rem; color: #00d9ff; margin: 15px 0;">
                    <strong><?php echo implode(', ', $numeri); ?></strong>
                </p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo implode(', ', $numeri); ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatore Sconto - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🏷️ Calcolatore Sconto</h1>
            
            <?php
            $risultato = null;
            $prezzo = '';
            $sconto1 = '';
            $sconto2 = '';
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $prezzo = floatval($_POST['prezzo'] ?? 0);
                $sconto1 = floatval($_POST['sconto1'] ?? 0);
                $sconto2 = isset($_POST['sconto2']) ? floatval($_POST['sconto2']) : null;
                
                if ($prezzo > 0) {
                    $importo_sconto1 = $prezzo * ($sconto1 / 100);
                    $prezzo_intermedio = $prezzo - $importo_sconto1;
                    
                    $dati = [
                        'prezzo_iniziale' => number_format($prezzo, 2),
                        'sconto1' => $sconto1,
                        'importo_sconto1' => number_format($importo_sconto1, 2),
                        'prezzo_intermedio' => number_format($prezzo_intermedio, 2)
                    ];
                    
                    if ($sconto2 !== null && $sconto2 > 0) {
                        $importo_sconto2 = $prezzo_intermedio * ($sconto2 / 100);
                        $prezzo_finale = $prezzo_intermedio - $importo_sconto2;
                        $dati['sconto2'] = $sconto2;
                        $dati['importo_sconto2'] = number_format($importo_sconto2, 2);
                        $dati['prezzo_finale'] = number_format($prezzo_finale, 2);
                        $dati['sconto_totale'] = number_format(((1 - ($prezzo_finale / $prezzo)) * 100), 2);
                    } else {
                        $prezzo_finale = $prezzo_intermedio;
                        $dati['prezzo_finale'] = number_format($prezzo_finale, 2);
                    }
                    
                    $risultato = $dati;
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="prezzo">Prezzo iniziale (€)</label>
                    <input type="number" step="0.01" id="prezzo" name="prezzo" 
                           value="<?php echo htmlspecialchars($prezzo); ?>" required placeholder="Es. 100.00">
                </div>
                
                <div class="form-group">
                    <label for="sconto1">Primo sconto (%)</label>
                    <input type="number" step="0.1" id="sconto1" name="sconto1" 
                           value="<?php echo htmlspecialchars($sconto1); ?>" required placeholder="Es. 20">
                </div>
                
                <div class="form-group">
                    <label for="sconto2">Secondo sconto aggiuntivo (%) <span style="color:#888; font-weight:400;">(opzionale)</span></label>
                    <input type="number" step="0.1" id="sconto2" name="sconto2" 
                           value="<?php echo htmlspecialchars($sconto2 ?? ''); ?>" placeholder="Es. 10">
                </div>
                
                <button type="submit">Calcola</button>
            </form>
            
            <?php if ($risultato): ?>
            <div class="result">
                <div class="result-label">Dettaglio calcolo:</div>
                <p>Prezzo iniziale: <strong>€ <?php echo $risultato['prezzo_iniziale']; ?></strong></p>
                <p>Primo sconto (<?php echo $risultato['sconto1']; ?>%): <strong>- € <?php echo $risultato['importo_sconto1']; ?></strong></p>
                <p style="color:#666; font-size:0.9rem;">Prezzo intermedio: € <?php echo $risultato['prezzo_intermedio']; ?></p>
                <?php if (isset($risultato['sconto2'])): ?>
                <p>Secondo sconto (<?php echo $risultato['sconto2']; ?>%): <strong>- € <?php echo $risultato['importo_sconto2']; ?></strong></p>
                <p style="margin-top:10px; font-size:1.2rem;">
                    Prezzo finale: <strong>€ <?php echo $risultato['prezzo_finale']; ?></strong>
                </p>
                <p style="color:#666; font-size:0.9rem;">Sconto totale effettivo: <?php echo $risultato['sconto_totale']; ?>%</p>
                <?php else: ?>
                <p style="margin-top:10px; font-size:1.2rem;">
                    Prezzo finale: <strong>€ <?php echo $risultato['prezzo_finale']; ?></strong>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

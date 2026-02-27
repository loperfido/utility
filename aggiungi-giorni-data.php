<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Giorni a una Data - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>📅 Aggiungi Giorni a una Data</h1>
            
            <?php
            $data_iniziale = '';
            $giorni = 0;
            $risultato = null;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data_iniziale = $_POST['data_iniziale'] ?? '';
                $giorni = intval($_POST['giorni'] ?? 0);
                
                if ($data_iniziale !== '') {
                    $dt = new DateTime($data_iniziale);
                    $dt->modify(($giorni >= 0 ? '+' : '') . $giorni . ' days');
                    
                    $diff = new DateTime($data_iniziale);
                    $diff = $diff->diff($dt);
                    
                    $risultato = [
                        'data_iniziale' => (new DateTime($data_iniziale))->format('d/m/Y'),
                        'data_finale' => $dt->format('d/m/Y'),
                        'giorni_settimana' => $dt->format('l'),
                        'giorni_aggiunti' => $giorni
                    ];
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="data_iniziale">Data di partenza</label>
                    <input type="date" id="data_iniziale" name="data_iniziale" 
                           value="<?php echo htmlspecialchars($data_iniziale); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="giorni">Giorni da aggiungere (negativo per sottrarre)</label>
                    <input type="number" id="giorni" name="giorni" 
                           value="<?php echo htmlspecialchars($giorni !== '' ? $giorni : 0); ?>" 
                           placeholder="Es. 30 o -15">
                </div>
                
                <button type="submit">Calcola</button>
            </form>
            
            <?php if ($risultato): ?>
            <div class="result">
                <div class="result-label">Risultato:</div>
                <p style="font-size: 0.9rem; color: #666;">
                    Da: <?php echo $risultato['data_iniziale']; ?>
                </p>
                <p style="font-size: 1.5rem; margin: 10px 0;">
                    <?php echo $risultato['giorni_aggiunti'] >= 0 ? '+' : ''; ?><?php echo $risultato['giorni_aggiunti']; ?> giorni = 
                    <strong style="color: #1a1a1a;"><?php echo $risultato['data_finale']; ?></strong>
                </p>
                <p style="color: #666; font-size: 0.9rem;"><?php echo $risultato['giorni_settimana']; ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giorni Mancanti - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>⏳ Giorni Mancanti</h1>
            
            <?php
            $data_evento = '';
            $risultato = null;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data_evento = $_POST['data_evento'] ?? '';
                
                if ($data_evento !== '') {
                    $oggi = new DateTime();
                    $evento = new DateTime($data_evento);
                    $diff = $oggi->diff($evento);
                    
                    $giorni_mancanti = $diff->days;
                    $passato = $evento < $oggi;
                    
                    $risultato = [
                        'giorni' => $giorni_mancanti,
                        'passato' => $passato,
                        'data' => $evento->format('d/m/Y')
                    ];
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="data_evento">Data dell'evento</label>
                    <input type="date" id="data_evento" name="data_evento" 
                           value="<?php echo htmlspecialchars($data_evento); ?>" required>
                </div>
                
                <button type="submit">Calcola</button>
            </form>
            
            <?php if ($risultato): ?>
            <div class="result">
                <?php if ($risultato['passato']): ?>
                    <div class="result-label">L'evento è passato!</div>
                    <p style="font-size: 1.3rem; margin: 15px 0;">
                        Sono passati <strong><?php echo $risultato['giorni']; ?> giorni</strong>
                        dal <?php echo $risultato['data']; ?>
                    </p>
                <?php else: ?>
                    <div class="result-label">Mancano:</div>
                    <p style="font-size: 2rem; color: #1a1a1a; margin: 15px 0;">
                        <strong><?php echo $risultato['giorni']; ?> giorni</strong>
                    </p>
                    <p>
                        al <?php echo $risultato['data']; ?>
                    </p>
                    <?php if ($risultato['giorni'] == 0): ?>
                        <p style="color: #28a745; margin-top: 10px;">🎉 È oggi!</p>
                    <?php elseif ($risultato['giorni'] == 1): ?>
                        <p style="color: #28a745; margin-top: 10px;">⏰ Domani!</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

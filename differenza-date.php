<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Differenza Date - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>📅 Differenza Date</h1>
            
            <?php
            $data1 = '';
            $data2 = '';
            $mostra_dettaglio = false;
            $risultato = null;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data1 = $_POST['data1'] ?? '';
                $data2 = $_POST['data2'] ?? '';
                $mostra_dettaglio = isset($_POST['mostra_dettaglio']);
                
                if ($data1 !== '' && $data2 !== '') {
                    $dt1 = new DateTime($data1);
                    $dt2 = new DateTime($data2);
                    $diff = $dt1->diff($dt2);
                    
                    $secondi_totali = abs($dt2->getTimestamp() - $dt1->getTimestamp());
                    $minuti_totali = floor($secondi_totali / 60);
                    $ore_totali = floor($secondi_totali / 3600);
                    
                    $risultato = [
                        'giorni' => $diff->days,
                        'anni' => $diff->y,
                        'mesi' => $diff->m,
                        'giorni_totali' => $diff->days,
                        'settimane' => floor($diff->days / 7),
                        'ore' => $ore_totali,
                        'minuti' => $minuti_totali,
                        'secondi' => $secondi_totali,
                        'mostra_dettaglio' => $mostra_dettaglio
                    ];
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="data1">Prima data</label>
                    <input type="date" id="data1" name="data1" 
                           value="<?php echo htmlspecialchars($data1); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="data2">Seconda data</label>
                    <input type="date" id="data2" name="data2" 
                           value="<?php echo htmlspecialchars($data2); ?>" required>
                </div>
                
                <button type="submit">Calcola Differenza</button>
            </form>
            
            <?php if ($risultato): ?>
            <div class="result">
                <div class="result-label">Differenza tra le date:</div>
                <p style="font-size: 1.5rem; color: #1a1a1a; margin: 15px 0;">
                    <strong><?php echo $risultato['giorni_totali']; ?> giorni</strong>
                </p>
                <p>
                    <?php 
                    if ($risultato['anni'] > 0) echo $risultato['anni'] . ' anni, ';
                    if ($risultato['mesi'] > 0) echo $risultato['mesi'] . ' mesi, ';
                    echo $risultato['giorni'] % 30 . ' giorni';
                    ?>
                </p>
                <p style="margin-top: 10px; color: #888;">
                    Circa <?php echo $risultato['settimane']; ?> settimane | <?php echo $risultato['ore']; ?> ore
                </p>
                
                <form method="POST" style="margin-top: 15px;">
                    <input type="hidden" name="data1" value="<?php echo htmlspecialchars($data1); ?>">
                    <input type="hidden" name="data2" value="<?php echo htmlspecialchars($data2); ?>">
                    <input type="hidden" name="mostra_dettaglio" value="1">
                    <button type="submit" style="background:#f0f0f0; color:#333; padding:8px 16px; font-size:0.85rem;">
                        <?php echo $risultato['mostra_dettaglio'] ? '🙈 Nascondi' : '👁️ Mostra minuti e secondi'; ?>
                    </button>
                </form>
                
                <?php if ($risultato['mostra_dettaglio']): ?>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e8e8e8;">
                    <p style="font-size: 0.9rem; color: #666;">
                        <strong>Minuti:</strong> <?php echo number_format($risultato['minuti']); ?><br>
                        <strong>Secondi:</strong> <?php echo number_format($risultato['secondi']); ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

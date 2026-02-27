<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generatore QR Code - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>📱 Generatore QR Code</h1>
            
            <?php
            $contenuto = '';
            $mostra_qr = false;
            $dimensione = 200;
            $colore_qr = '000000';
            $colore_sfondo = 'ffffff';
            $margine = 0;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $contenuto = trim($_POST['contenuto'] ?? '');
                $dimensione = intval($_POST['dimensione'] ?? 200);
                $colore_qr = preg_replace('/[^0-9a-fA-F]/', '', $_POST['colore_qr'] ?? '000000');
                $colore_sfondo = preg_replace('/[^0-9a-fA-F]/', '', $_POST['colore_sfondo'] ?? 'ffffff');
                $margine = intval($_POST['margine'] ?? 0);
                
                if ($contenuto !== '') {
                    $mostra_qr = true;
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="contenuto">URL o testo per il QR code</label>
                    <input type="text" id="contenuto" name="contenuto" 
                           value="<?php echo htmlspecialchars($contenuto); ?>" 
                           placeholder="Es. https://www.esempio.com" required>
                </div>
                
                <div class="form-group">
                    <label for="dimensione">Dimensione: <?php echo $dimensione; ?>px</label>
                    <input type="number" min="50" max="1000" step="50" id="dimensione" name="dimensione" 
                           value="<?php echo $dimensione; ?>">
                </div>
                
                <div class="inline-group">
                    <div class="form-group" style="flex:1;">
                        <label for="colore_qr">Colore QR (HEX)</label>
                        <input type="color" id="colore_qr_picker" value="#<?php echo $colore_qr; ?>" 
                               onchange="document.getElementById('colore_qr').value = this.value.substring(1);" style="width:100%; height:40px; padding:0; border:1px solid #ddd; border-radius:6px; cursor:pointer;">
                        <input type="text" id="colore_qr" name="colore_qr" value="<?php echo $colore_qr; ?>" 
                               placeholder="000000" maxlength="6" style="margin-top:6px; text-transform:uppercase;">
                    </div>
                    
                    <div class="form-group" style="flex:1;">
                        <label for="colore_sfondo">Colore sfondo (HEX)</label>
                        <input type="color" id="colore_sfondo_picker" value="#<?php echo $colore_sfondo; ?>" 
                               onchange="document.getElementById('colore_sfondo').value = this.value.substring(1);" style="width:100%; height:40px; padding:0; border:1px solid #ddd; border-radius:6px; cursor:pointer;">
                        <input type="text" id="colore_sfondo" name="colore_sfondo" value="<?php echo $colore_sfondo; ?>" 
                               placeholder="ffffff" maxlength="6" style="margin-top:6px; text-transform:uppercase;">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="margine">Margine (pixel)</label>
                    <input type="number" min="0" max="50" id="margine" name="margine" 
                           value="<?php echo $margine; ?>">
                </div>
                
                <button type="submit">Genera QR Code</button>
            </form>
            
            <?php if ($mostra_qr): ?>
            <div class="result" style="text-align: center;">
                <div class="result-label">Il tuo QR Code:</div>
                <div class="qr-container">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=<?php echo $dimensione; ?>x<?php echo $dimensione; ?>&data=<?php echo urlencode($contenuto); ?>&color=<?php echo $colore_qr; ?>&bgcolor=<?php echo $colore_sfondo; ?>&margin=<?php echo $margine; ?>" 
                         alt="QR Code" style="display: block; max-width: 100%;">
                </div>
                <p style="margin-top: 15px; font-size: 0.9rem; color: #888;">
                    Fai clic destro sull'immagine e scegli "Salva immagine con nome" per scaricarlo
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

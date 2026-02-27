<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generatore UUID - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🔑 Generatore UUID</h1>
            
            <?php
            $quantita = 1;
            $versione = 'v4';
            $uuids = [];
            
            function generaUUIDv4() {
                $data = random_bytes(16);
                $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
                $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
                return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
            }
            
            function generaUUIDv7() {
                $timestamp = floor(microtime(true) * 1000);
                $random = random_bytes(10);
                
                $time_hex = str_pad(dechex($timestamp), 12, '0', STR_PAD_LEFT);
                $random_hex = bin2hex($random);
                
                $uuid = substr($time_hex, 0, 8) . '-' .
                        substr($time_hex, 8, 4) . '-7' .
                        substr($time_hex, 11, 3) . '-' .
                        substr($random_hex, 0, 4) . '-' .
                        substr($random_hex, 4, 12);
                
                return $uuid;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $quantita = intval($_POST['quantita'] ?? 1);
                $quantita = max(1, min(100, $quantita));
                $versione = $_POST['versione'] ?? 'v4';
                
                for ($i = 0; $i < $quantita; $i++) {
                    if ($versione === 'v7') {
                        usleep(1000);
                        $uuids[] = generaUUIDv7();
                    } else {
                        $uuids[] = generaUUIDv4();
                    }
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="versione">Versione UUID</label>
                    <select id="versione" name="versione">
                        <option value="v4" <?php echo $versione === 'v4' ? 'selected' : ''; ?>>UUID v4 (Casuale)</option>
                        <option value="v7" <?php echo $versione === 'v7' ? 'selected' : ''; ?>>UUID v7 (Ordinabile per tempo)</option>
                    </select>
                    <p style="font-size: 0.85rem; color: #888; margin-top: 6px;">
                        v4: Completamente casuale | v7: Include timestamp, ordinabile cronologicamente
                    </p>
                </div>
                
                <div class="form-group">
                    <label for="quantita">Quantità UUID da generare</label>
                    <input type="number" min="1" max="100" id="quantita" name="quantita" 
                           value="<?php echo htmlspecialchars($quantita); ?>">
                </div>
                
                <button type="submit">Genera UUID</button>
            </form>
            
            <?php if (!empty($uuids)): ?>
            <div class="result">
                <div class="result-label">UUID generati (<?php echo count($uuids); ?>):</div>
                <div style="max-height: 300px; overflow-y: auto; margin-top: 10px;">
                    <?php foreach ($uuids as $uuid): ?>
                    <p style="font-family: monospace; font-size: 0.9rem; padding: 6px 0; border-bottom: 1px solid #eee;">
                        <?php echo $uuid; ?>
                    </p>
                    <?php endforeach; ?>
                </div>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo addslashes(implode("\n", $uuids)); ?>'); alert('Copiato!');">
                    📋 Copia tutti
                </button>
            </div>
            
            <div class="result" style="margin-top: 15px; background: #fff; border-color: #ddd;">
                <div class="result-label">Info:</div>
                <p style="font-size: 0.9rem; color: #666;">
                    <strong>UUID v4:</strong> Generato casualmente, 122 bit di entropia. 
                    Probabilità di collisione estremamente bassa.<br><br>
                    <strong>UUID v7:</strong> Include timestamp Unix in millisecondi. 
                    Utile per database perché ordinabile cronologicamente.
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertitore Timestamp - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🕐 Convertitore Timestamp</h1>
            
            <?php
            $timestamp = '';
            $data = '';
            $ora = '00:00';
            $timezone = 'Europe/Rome';
            $risultato = null;
            $direzione = 'to_date';
            
            $timezones = [
                'Europe/Rome' => 'Roma (CET/CEST)',
                'Europe/London' => 'Londra (GMT/BST)',
                'Europe/Paris' => 'Parigi (CET/CEST)',
                'Europe/Berlin' => 'Berlino (CET/CEST)',
                'America/New_York' => 'New York (EST/EDT)',
                'America/Los_Angeles' => 'Los Angeles (PST/PDT)',
                'America/Chicago' => 'Chicago (CST/CDT)',
                'America/Sao_Paulo' => 'San Paolo (BRT)',
                'Asia/Tokyo' => 'Tokyo (JST)',
                'Asia/Shanghai' => 'Shanghai (CST)',
                'Asia/Kolkata' => 'Mumbai (IST)',
                'Australia/Sydney' => 'Sydney (AEDT)',
                'Pacific/Auckland' => 'Auckland (NZDT)',
                'UTC' => 'UTC'
            ];
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $timestamp = trim($_POST['timestamp'] ?? '');
                $data = $_POST['data'] ?? '';
                $ora = $_POST['ora'] ?? '00:00';
                $timezone = $_POST['timezone'] ?? 'Europe/Rome';
                $direzione = $_POST['direzione'] ?? 'to_date';
                
                date_default_timezone_set($timezone);
                
                if ($direzione === 'to_date' && $timestamp !== '') {
                    if (ctype_digit($timestamp)) {
                        $ts = intval($timestamp);
                        $risultato = [
                            'data_ora' => date('d/m/Y H:i:s', $ts),
                            'data' => date('d/m/Y', $ts),
                            'ora' => date('H:i:s', $ts),
                            'giorno_settimana' => date('l', $ts),
                            'timestamp' => $ts
                        ];
                    }
                } elseif ($direzione === 'to_timestamp' && $data !== '') {
                    $datetime = DateTime::createFromFormat('Y-m-d', $data);
                    if ($datetime) {
                        list($h, $m) = explode(':', $ora);
                        $datetime->setTime(intval($h), intval($m));
                        $ts = $datetime->getTimestamp();
                        $risultato = [
                            'data_ora' => $datetime->format('d/m/Y H:i:s'),
                            'data' => $datetime->format('d/m/Y'),
                            'ora' => $datetime->format('H:i:s'),
                            'giorno_settimana' => $datetime->format('l'),
                            'timestamp' => $ts
                        ];
                    }
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="timezone">Fuso orario</label>
                    <select id="timezone" name="timezone">
                        <?php foreach ($timezones as $tz => $nome): ?>
                        <option value="<?php echo $tz; ?>" <?php echo $timezone === $tz ? 'selected' : ''; ?>>
                            <?php echo $nome; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Operazione:</label>
                    <div class="checkbox-group">
                        <label style="padding: 10px 16px; border: 1px solid <?php echo $direzione === 'to_date' ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo $direzione === 'to_date' ? '#1a1a1a' : '#fff'; ?>; color: <?php echo $direzione === 'to_date' ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="direzione" value="to_date" <?php echo $direzione === 'to_date' ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;">
                            Timestamp → Data
                        </label>
                        <label style="padding: 10px 16px; border: 1px solid <?php echo $direzione === 'to_timestamp' ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo $direzione === 'to_timestamp' ? '#1a1a1a' : '#fff'; ?>; color: <?php echo $direzione === 'to_timestamp' ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="direzione" value="to_timestamp" <?php echo $direzione === 'to_timestamp' ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;">
                            Data → Timestamp
                        </label>
                    </div>
                </div>
                
                <div id="to_date_fields" style="<?php echo $direzione === 'to_date' ? 'display:block;' : 'display:none;'; ?>">
                    <div class="form-group">
                        <label for="timestamp">Timestamp Unix</label>
                        <input type="number" id="timestamp" name="timestamp" 
                               value="<?php echo htmlspecialchars($timestamp); ?>" 
                               placeholder="Es. 1708819200">
                    </div>
                </div>
                
                <div id="to_timestamp_fields" style="<?php echo $direzione === 'to_timestamp' ? 'display:block;' : 'display:none;'; ?>">
                    <div class="form-group">
                        <label for="data">Data</label>
                        <input type="date" id="data" name="data" 
                               value="<?php echo htmlspecialchars($data); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="ora">Ora</label>
                        <input type="time" id="ora" name="ora" 
                               value="<?php echo htmlspecialchars($ora); ?>">
                    </div>
                </div>
                
                <button type="submit">Converti</button>
            </form>
            
            <?php if ($risultato): ?>
            <div class="result">
                <div class="result-label">Data e ora:</div>
                <p style="font-size: 1.3rem;"><?php echo $risultato['data_ora']; ?></p>
                <p style="color: #666; font-size: 0.9rem;"><?php echo $risultato['giorno_settimana']; ?></p>
            </div>
            
            <div class="result">
                <div class="result-label">Timestamp Unix:</div>
                <p style="font-family: monospace; font-size: 1.2rem;"><?php echo $risultato['timestamp']; ?></p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo $risultato['timestamp']; ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        document.querySelectorAll('input[name="direzione"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('to_date_fields').style.display = this.value === 'to_date' ? 'block' : 'none';
                document.getElementById('to_timestamp_fields').style.display = this.value === 'to_timestamp' ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generatore IP - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🌐 Generatore IP Casuale</h1>
            
            <?php
            $versione = 'ipv4';
            $quantita = 1;
            $privato = false;
            $ips = [];
            
            function generaIPv4($privato = false) {
                if ($privato) {
                    $reti_private = [
                        [10, 10, 0, 0, 10, 255, 255, 255],
                        [172, 16, 0, 0, 172, 31, 255, 255],
                        [192, 168, 0, 0, 192, 168, 255, 255]
                    ];
                    $rete = $reti_private[array_rand($reti_private)];
                    return sprintf('%d.%d.%d.%d',
                        random_int($rete[0], $rete[4]),
                        random_int($rete[1], $rete[5]),
                        random_int($rete[2], $rete[6]),
                        random_int($rete[3], $rete[7])
                    );
                } else {
                    do {
                        $ip = sprintf('%d.%d.%d.%d',
                            random_int(1, 223),
                            random_int(0, 255),
                            random_int(0, 255),
                            random_int(1, 254)
                        );
                        // Escludi indirizzi privati
                    } while (
                        preg_match('/^(10\.|127\.|172\.(1[6-9]|2[0-9]|3[01])\.|192\.168\.)/', $ip)
                    );
                    return $ip;
                }
            }
            
            function generaIPv6() {
                $ipv6 = [];
                for ($i = 0; $i < 8; $i++) {
                    $ipv6[] = str_pad(dechex(random_int(0, 65535)), 4, '0', STR_PAD_LEFT);
                }
                return implode(':', $ipv6);
            }
            
            function generaIPv6Privato() {
                // fc00::/7 - Unique Local Address
                $prefix = random_int(0, 1) ? 'fc' : 'fd';
                $ipv6 = [$prefix];
                for ($i = 0; $i < 7; $i++) {
                    $ipv6[] = str_pad(dechex(random_int(0, 65535)), 4, '0', STR_PAD_LEFT);
                }
                return implode(':', $ipv6);
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $versione = $_POST['versione'] ?? 'ipv4';
                $quantita = intval($_POST['quantita'] ?? 1);
                $quantita = max(1, min(100, $quantita));
                $privato = isset($_POST['privato']);
                
                for ($i = 0; $i < $quantita; $i++) {
                    if ($versione === 'ipv4') {
                        $ips[] = [
                            'ip' => generaIPv4($privato),
                            'versione' => 'IPv4',
                            'tipo' => $privato ? 'Privato' : 'Pubblico'
                        ];
                    } else {
                        $ips[] = [
                            'ip' => $privato ? generaIPv6Privato() : generaIPv6(),
                            'versione' => 'IPv6',
                            'tipo' => $privato ? 'ULA (Privato)' : 'Globale'
                        ];
                    }
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="versione">Versione IP</label>
                    <select id="versione" name="versione">
                        <option value="ipv4" <?php echo $versione === 'ipv4' ? 'selected' : ''; ?>>IPv4</option>
                        <option value="ipv6" <?php echo $versione === 'ipv6' ? 'selected' : ''; ?>>IPv6</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="quantita">Quantità indirizzi da generare</label>
                    <input type="number" min="1" max="100" id="quantita" name="quantita" 
                           value="<?php echo htmlspecialchars($quantita); ?>">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="privato" <?php echo $privato ? 'checked' : ''; ?>>
                        Solo indirizzi privati
                    </label>
                    <p style="font-size: 0.85rem; color: #888; margin-top: 6px;">
                        IPv4: 10.x.x.x, 172.16-31.x.x, 192.168.x.x | IPv6: fc00::/7 (ULA)
                    </p>
                </div>
                
                <button type="submit">Genera IP</button>
            </form>
            
            <?php if (!empty($ips)): ?>
            <div class="result">
                <div class="result-label">Indirizzi IP generati (<?php echo count($ips); ?>):</div>
                <div style="max-height: 300px; overflow-y: auto; margin-top: 10px;">
                    <?php foreach ($ips as $ip): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span style="font-family: monospace; font-size: 0.95rem;"><?php echo htmlspecialchars($ip['ip']); ?></span>
                        <span style="font-size: 0.75rem; color: #888; background: #f0f0f0; padding: 2px 8px; border-radius: 4px;">
                            <?php echo $ip['versione']; ?> • <?php echo $ip['tipo']; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo addslashes(implode("\n", array_column($ips, 'ip'))); ?>'); alert('Copiato!');">
                    📋 Copia tutti
                </button>
            </div>
            
            <div class="result" style="margin-top: 15px; background: #fff; border-color: #ddd;">
                <div class="result-label">Info:</div>
                <p style="font-size: 0.9rem; color: #666;">
                    <strong>IPv4 Pubblici:</strong> Indirizzi instradabili su Internet (esclusi range riservati).<br>
                    <strong>IPv4 Privati:</strong> Per reti locali (10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16).<br>
                    <strong>IPv6 Globali:</strong> Indirizzi pubblici per Internet.<br>
                    <strong>IPv6 ULA:</strong> Unique Local Address per reti private (fc00::/7).
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

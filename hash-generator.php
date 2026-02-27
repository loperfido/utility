<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hash Generator - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🔏 Hash Generator</h1>
            
            <?php
            $testo = '';
            $hashes = [];
            
            $algoritmi = [
                'md5' => 'MD5 (128-bit)',
                'sha1' => 'SHA1 (160-bit)',
                'sha224' => 'SHA224 (224-bit)',
                'sha256' => 'SHA256 (256-bit)',
                'sha384' => 'SHA384 (384-bit)',
                'sha512' => 'SHA512 (512-bit)',
                'ripemd160' => 'RIPEMD160 (160-bit)',
                'whirlpool' => 'Whirlpool (512-bit)',
                'tiger128,3' => 'Tiger128 (128-bit)',
                'tiger160,3' => 'Tiger160 (160-bit)',
                'tiger192,3' => 'Tiger192 (192-bit)',
                'crc32' => 'CRC32 (checksum)',
                'adler32' => 'Adler32 (checksum)',
                'fnv132' => 'FNV132 (checksum)',
                'fnv164' => 'FNV164 (checksum)',
            ];
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $testo = $_POST['testo'] ?? '';
                
                if ($testo !== '') {
                    foreach ($algoritmi as $algo => $nome) {
                        $hashes[$algo] = [
                            'nome' => $nome,
                            'hash' => hash($algo, $testo)
                        ];
                    }
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="testo">Inserisci testo da hashare</label>
                    <input type="text" id="testo" name="testo" 
                           value="<?php echo htmlspecialchars($testo); ?>" 
                           placeholder="Inserisci il testo...">
                </div>
                
                <button type="submit">Genera Hash</button>
            </form>
            
            <?php if (!empty($hashes)): ?>
            <?php foreach ($hashes as $algo => $dati): ?>
            <div class="result">
                <div class="result-label"><?php echo $dati['nome']; ?>:</div>
                <p style="font-family: monospace; font-size: 0.85rem; word-break: break-all;"><?php echo $dati['hash']; ?></p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo $dati['hash']; ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

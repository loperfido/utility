<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checksum File - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🔐 Checksum File</h1>
            
            <?php
            $hashes1 = null;
            $hashes2 = null;
            $confronto = null;
            $errore = '';
            $confronta = false;
            
            $algoritmi = [
                'md5' => 'MD5 (128-bit)',
                'sha1' => 'SHA1 (160-bit)',
                'sha256' => 'SHA256 (256-bit)',
                'sha384' => 'SHA384 (384-bit)',
                'sha512' => 'SHA512 (512-bit)',
                'crc32' => 'CRC32 (checksum)',
                'adler32' => 'Adler32 (checksum)'
            ];
            
            function calcolaHashFile($file_path, $algoritmi) {
                $hashes = [];
                foreach ($algoritmi as $algo => $nome) {
                    $hashes[$algo] = hash_file($algo, $file_path);
                }
                return $hashes;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $confronta = isset($_POST['confronta']);
                
                if ($confronta) {
                    // Confronto due file
                    $file1 = $_FILES['file1'] ?? null;
                    $file2 = $_FILES['file2'] ?? null;
                    
                    if ($file1 && $file2) {
                        $max_size = 100 * 1024 * 1024; // 100MB
                        
                        if ($file1['error'] !== UPLOAD_ERR_OK || $file2['error'] !== UPLOAD_ERR_OK) {
                            $errore = 'Errore nel caricamento di uno o entrambi i file';
                        } elseif ($file1['size'] > $max_size || $file2['size'] > $max_size) {
                            $errore = 'Uno o entrambi i file superano i 100MB';
                        } else {
                            $hashes1 = [
                                'hashes' => calcolaHashFile($file1['tmp_name'], $algoritmi),
                                'dimensione' => $file1['size'],
                                'nome_file' => $file1['name'],
                                'tipo' => $file1['type']
                            ];
                            
                            $hashes2 = [
                                'hashes' => calcolaHashFile($file2['tmp_name'], $algoritmi),
                                'dimensione' => $file2['size'],
                                'nome_file' => $file2['name'],
                                'tipo' => $file2['type']
                            ];
                            
                            // Confronta gli hash
                            $confronto = [
                                'uguali' => $hashes1['hashes']['sha256'] === $hashes2['hashes']['sha256'],
                                'dettagli' => []
                            ];
                            
                            foreach ($algoritmi as $algo => $nome) {
                                $confronto['dettagli'][$algo] = [
                                    'uguali' => $hashes1['hashes'][$algo] === $hashes2['hashes'][$algo],
                                    'hash1' => $hashes1['hashes'][$algo],
                                    'hash2' => $hashes2['hashes'][$algo]
                                ];
                            }
                        }
                    }
                } else {
                    // Single file checksum
                    $file = $_FILES['file'] ?? null;
                    
                    if ($file) {
                        $max_size = 100 * 1024 * 1024; // 100MB
                        
                        if ($file['error'] !== UPLOAD_ERR_OK) {
                            $errori_upload = [
                                UPLOAD_ERR_INI_SIZE => 'File troppo grande (php.ini)',
                                UPLOAD_ERR_FORM_SIZE => 'File troppo grande (form)',
                                UPLOAD_ERR_PARTIAL => 'Upload parziale',
                                UPLOAD_ERR_NO_FILE => 'Nessun file caricato',
                                UPLOAD_ERR_NO_TMP_DIR => 'Cartella temp mancante',
                                UPLOAD_ERR_CANT_WRITE => 'Errore di scrittura',
                                UPLOAD_ERR_EXTENSION => 'Estensione PHP bloccata'
                            ];
                            $errore = $errori_upload[$file['error']] ?? 'Errore sconosciuto';
                        } elseif ($file['size'] > $max_size) {
                            $errore = 'File troppo grande. Dimensione massima: 100MB';
                        } else {
                            $hashes1 = [
                                'hashes' => calcolaHashFile($file['tmp_name'], $algoritmi),
                                'dimensione' => $file['size'],
                                'nome_file' => $file['name'],
                                'tipo' => $file['type']
                            ];
                        }
                    }
                }
            }
            ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Modalità:</label>
                    <div class="checkbox-group">
                        <label style="padding: 10px 16px; border: 1px solid <?php echo !$confronta ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo !$confronta ? '#1a1a1a' : '#fff'; ?>; color: <?php echo !$confronta ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="confronta" value="0" <?php echo !$confronta ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;" onchange="this.form.submit()">
                            Calcola checksum singolo
                        </label>
                        <label style="padding: 10px 16px; border: 1px solid <?php echo $confronta ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo $confronta ? '#1a1a1a' : '#fff'; ?>; color: <?php echo $confronta ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="confronta" value="1" <?php echo $confronta ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;" onchange="this.form.submit()">
                            Confronta due file
                        </label>
                    </div>
                </div>
                
                <?php if ($confronta): ?>
                <div class="inline-group">
                    <div class="form-group" style="flex:1;">
                        <label for="file1">Primo file</label>
                        <input type="file" id="file1" name="file1" required style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; width: 100%;">
                    </div>
                    
                    <div class="form-group" style="flex:1;">
                        <label for="file2">Secondo file</label>
                        <input type="file" id="file2" name="file2" required style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; width: 100%;">
                    </div>
                </div>
                <?php else: ?>
                <div class="form-group">
                    <label for="file">Seleziona file</label>
                    <input type="file" id="file" name="file" required style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; width: 100%;">
                </div>
                <?php endif; ?>
                
                <p style="font-size: 0.85rem; color: #888; margin-top: 6px;">
                    Dimensione massima: 100MB per file
                </p>
                
                <button type="submit"><?php echo $confronta ? 'Confronta File' : 'Calcola Checksum'; ?></button>
            </form>
            
            <?php if ($errore): ?>
            <div class="result" style="border-color: #dc3545; background: #fff5f5;">
                <p style="color: #dc3545;">❌ <?php echo htmlspecialchars($errore); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if ($hashes1 && !$confronta): ?>
            <div class="result">
                <div class="result-label">Informazioni file:</div>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($hashes1['nome_file']); ?></p>
                <p><strong>Dimensione:</strong> <?php echo number_format($hashes1['dimensione']); ?> byte (<?php echo round($hashes1['dimensione'] / 1024, 2); ?> KB)</p>
                <p><strong>Tipo:</strong> <?php echo htmlspecialchars($hashes1['tipo']) ?: 'Non specificato'; ?></p>
            </div>
            
            <?php foreach ($algoritmi as $algo => $nome): ?>
            <div class="result">
                <div class="result-label"><?php echo $nome; ?>:</div>
                <p style="font-family: monospace; font-size: 0.85rem; word-break: break-all;"><?php echo $hashes1['hashes'][$algo]; ?></p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo $hashes1['hashes'][$algo]; ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            <?php endforeach; ?>
            
            <?php elseif ($confronto && $hashes1 && $hashes2): ?>
            <div class="result" style="<?php echo $confronto['uguali'] ? 'border-color: #28a745; background: #f0fff4;' : 'border-color: #dc3545; background: #fff5f5;'; ?>">
                <div class="result-label">Risultato confronto:</div>
                <p style="font-size: 1.5rem; font-weight: bold; color: <?php echo $confronto['uguali'] ? '#28a745' : '#dc3545'; ?>; margin: 15px 0;">
                    <?php echo $confronto['uguali'] ? '✅ I file sono IDENTICI' : '❌ I file sono DIVERSI'; ?>
                </p>
                <p style="color: #666;">
                    File 1: <?php echo htmlspecialchars($hashes1['nome_file']); ?> (<?php echo number_format($hashes1['dimensione']); ?> byte)<br>
                    File 2: <?php echo htmlspecialchars($hashes2['nome_file']); ?> (<?php echo number_format($hashes2['dimensione']); ?> byte)
                </p>
            </div>
            
            <div class="result">
                <div class="result-label">Dettaglio hash per algoritmo:</div>
                <?php foreach ($confronto['dettagli'] as $algo => $dati): ?>
                <div style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    <p style="font-weight: 500; margin-bottom: 8px;">
                        <?php echo $algoritmi[$algo]; ?>
                        <?php echo $dati['uguali'] ? '<span style="color:#28a745; margin-left:8px;">✓ Uguuali</span>' : '<span style="color:#dc3545; margin-left:8px;">✗ Diversi</span>'; ?>
                    </p>
                    <p style="font-family: monospace; font-size: 0.8rem; word-break: break-all; color: <?php echo $dati['uguali'] ? '#28a745' : '#dc3545'; ?>;">
                        File 1: <?php echo $dati['hash1']; ?>
                    </p>
                    <p style="font-family: monospace; font-size: 0.8rem; word-break: break-all; color: <?php echo $dati['uguali'] ? '#28a745' : '#dc3545'; ?>;">
                        File 2: <?php echo $dati['hash2']; ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

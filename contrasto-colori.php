<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrasto Colori - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🎨 Contrasto Colori e Colori Opposti</h1>
            
            <?php
            $colore1 = '#000000';
            $colore2 = '#ffffff';
            $risultato = null;
            
            function hexToRgb($hex) {
                $hex = str_replace('#', '', $hex);
                if (strlen($hex) === 3) {
                    $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
                }
                return [
                    'r' => hexdec(substr($hex, 0, 2)),
                    'g' => hexdec(substr($hex, 2, 2)),
                    'b' => hexdec(substr($hex, 4, 2))
                ];
            }
            
            function getLuminance($r, $g, $b) {
                $r /= 255; $g /= 255; $b /= 255;
                $r = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
                $g = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
                $b = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);
                return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
            }
            
            function getContrastRatio($hex1, $hex2) {
                $rgb1 = hexToRgb($hex1);
                $rgb2 = hexToRgb($hex2);
                $l1 = getLuminance($rgb1['r'], $rgb1['g'], $rgb1['b']);
                $l2 = getLuminance($rgb2['r'], $rgb2['g'], $rgb2['b']);
                $lighter = max($l1, $l2);
                $darker = min($l1, $l2);
                return round(($lighter + 0.05) / ($darker + 0.05), 2);
            }
            
            function getOppositeColor($hex) {
                $rgb = hexToRgb($hex);
                return sprintf('#%02x%02x%02x', 255 - $rgb['r'], 255 - $rgb['g'], 255 - $rgb['b']);
            }
            
            function getComplementaryColors($hex) {
                $rgb = hexToRgb($hex);
                $hsl = rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
                
                $h1 = ($hsl['h'] + 15) % 360;
                $h2 = ($hsl['h'] + 180) % 360;
                $h3 = ($hsl['h'] + 210) % 360;
                
                $rgb1 = hslToRgb($h1, $hsl['s'], $hsl['l']);
                $rgb2 = hslToRgb($h2, $hsl['s'], $hsl['l']);
                $rgb3 = hslToRgb($h3, $hsl['s'], $hsl['l']);
                
                return [
                    'analogico1' => rgbToHex($rgb1['r'], $rgb1['g'], $rgb1['b']),
                    'complementare' => rgbToHex($rgb2['r'], $rgb2['g'], $rgb2['b']),
                    'analogico2' => rgbToHex($rgb3['r'], $rgb3['g'], $rgb3['b'])
                ];
            }
            
            function rgbToHsl($r, $g, $b) {
                $r /= 255; $g /= 255; $b /= 255;
                $max = max($r, $g, $b); $min = min($r, $g, $b);
                $l = ($max + $min) / 2;
                
                if ($max === $min) {
                    $h = $s = 0;
                } else {
                    $d = $max - $min;
                    $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
                    switch ($max) {
                        case $r: $h = (($g - $b) / $d + ($g < $b ? 6 : 0)) / 6; break;
                        case $g: $h = (($b - $r) / $d + 2) / 6; break;
                        case $b: $h = (($r - $g) / $d + 4) / 6; break;
                    }
                }
                return ['h' => round($h * 360), 's' => round($s * 100), 'l' => round($l * 100)];
            }
            
            function hslToRgb($h, $s, $l) {
                $s /= 100; $l /= 100;
                $c = (1 - abs(2 * $l - 1)) * $s;
                $x = $c * (1 - abs(fmod($h / 60, 2) - 1));
                $m = $l - $c / 2;
                
                if ($h < 60) { $r = $c; $g = $x; $b = 0; }
                elseif ($h < 120) { $r = $x; $g = $c; $b = 0; }
                elseif ($h < 180) { $r = 0; $g = $c; $b = $x; }
                elseif ($h < 240) { $r = 0; $g = $x; $b = $c; }
                elseif ($h < 300) { $r = $x; $g = 0; $b = $c; }
                else { $r = $c; $g = 0; $b = $x; }
                
                return ['r' => round(($r + $m) * 255), 'g' => round(($g + $m) * 255), 'b' => round(($b + $m) * 255)];
            }
            
            function rgbToHex($r, $g, $b) {
                return sprintf('#%02x%02x%02x', $r, $g, $b);
            }
            
            function getBestContrastColor($hex) {
                $rgb = hexToRgb($hex);
                $luminance = getLuminance($rgb['r'], $rgb['g'], $rgb['b']);
                
                // Se lo sfondo è scuro, suggerisci bianco, altrimenti nero
                if ($luminance > 0.5) {
                    return ['#000000', 'Nero (#000000)'];
                } else {
                    return ['#ffffff', 'Bianco (#ffffff)'];
                }
            }
            
            function findOptimalContrast($hex) {
                $rgb = hexToRgb($hex);
                $luminance = getLuminance($rgb['r'], $rgb['g'], $rgb['b']);
                
                // Calcola la luminanza ottimale per massimo contrasto
                if ($luminance > 0.5) {
                    // Sfondo chiaro → testo scuro ottimale
                    $best_luminance = 0;
                    $best_color = '#000000';
                } else {
                    // Sfondo scuro → testo chiaro ottimale
                    $best_luminance = 1;
                    $best_color = '#ffffff';
                }
                
                return [$best_color, getContrastRatio($hex, $best_color)];
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $colore1 = $_POST['colore1'] ?? '#000000';
                $colore2 = $_POST['colore2'] ?? '#ffffff';
                
                $contrasto = getContrastRatio($colore1, $colore2);
                $opposto1 = getOppositeColor($colore1);
                $opposto2 = getOppositeColor($colore2);
                $complementari1 = getComplementaryColors($colore1);
                $complementari2 = getComplementaryColors($colore2);
                
                // Calcola i colori con miglior contrasto
                $miglior_contrasto_sfondo = findOptimalContrast($colore1);
                $miglior_contrasto_testo = findOptimalContrast($colore2);
                
                $livello_wcag = '';
                if ($contrasto >= 7) {
                    $livello_wcag = 'AAA (Eccellente)';
                } elseif ($contrasto >= 4.5) {
                    $livello_wcag = 'AA (Buono per testo normale)';
                } elseif ($contrasto >= 3) {
                    $livello_wcag = 'AA Grande (Solo per testo grande)';
                } else {
                    $livello_wcag = 'Insufficiente';
                }
                
                $risultato = [
                    'colore1' => $colore1,
                    'colore2' => $colore2,
                    'contrasto' => $contrasto,
                    'wcag' => $livello_wcag,
                    'opposto1' => $opposto1,
                    'opposto2' => $opposto2,
                    'complementari1' => $complementari1,
                    'complementari2' => $complementari2,
                    'miglior_contrasto_sfondo' => $miglior_contrasto_sfondo,
                    'miglior_contrasto_testo' => $miglior_contrasto_testo
                ];
            }
            ?>
            
            <form method="POST">
                <div class="inline-group">
                    <div class="form-group" style="flex:1;">
                        <label for="colore1">Colore 1 (sfondo)</label>
                        <input type="color" id="colore1" name="colore1" 
                               value="<?php echo htmlspecialchars($colore1); ?>" 
                               style="width: 100%; height: 50px; padding: 0; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                        <input type="text" value="<?php echo strtoupper($colore1); ?>" readonly 
                               style="margin-top: 6px; text-align: center; text-transform: uppercase;">
                    </div>
                    
                    <div class="form-group" style="flex:1;">
                        <label for="colore2">Colore 2 (testo)</label>
                        <input type="color" id="colore2" name="colore2" 
                               value="<?php echo htmlspecialchars($colore2); ?>" 
                               style="width: 100%; height: 50px; padding: 0; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                        <input type="text" value="<?php echo strtoupper($colore2); ?>" readonly 
                               style="margin-top: 6px; text-align: center; text-transform: uppercase;">
                    </div>
                </div>
                
                <button type="submit">Analizza Contrasto</button>
            </form>
            
            <?php if ($risultato): ?>
            <div class="result" style="text-align: center; padding: 30px; background: <?php echo $colore1; ?>; border: 1px solid #ddd;">
                <p style="font-size: 1.5rem; color: <?php echo $colore2; ?>; font-weight: bold;">
                    Anteprima Testo
                </p>
                <p style="color: <?php echo $colore2; ?>; margin-top: 10px;">
                    The quick brown fox jumps over the lazy dog
                </p>
            </div>
            
            <div class="result">
                <div class="result-label">Rapporto di contrasto:</div>
                <p style="font-size: 2rem; font-weight: bold; color: <?php echo $risultato['contrasto'] >= 4.5 ? '#28a745' : '#dc3545'; ?>;">
                    <?php echo $risultato['contrasto']; ?> : 1
                </p>
                <p style="color: #666;"><?php echo $risultato['wcag']; ?></p>
            </div>
            
            <div class="result">
                <div class="result-label">Colore opposto di <?php echo strtoupper($colore1); ?>:</div>
                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <div style="width: 50px; height: 50px; background: <?php echo $opposto1; ?>; border: 1px solid #ddd; border-radius: 6px;"></div>
                    <span style="font-family: monospace; font-size: 1.1rem;"><?php echo strtoupper($opposto1); ?></span>
                </div>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo strtoupper($opposto1); ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            
            <div class="result">
                <div class="result-label">Colore opposto di <?php echo strtoupper($colore2); ?>:</div>
                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <div style="width: 50px; height: 50px; background: <?php echo $opposto2; ?>; border: 1px solid #ddd; border-radius: 6px;"></div>
                    <span style="font-family: monospace; font-size: 1.1rem;"><?php echo strtoupper($opposto2); ?></span>
                </div>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo strtoupper($opposto2); ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            
            <div class="result" style="border-color: #28a745; background: #f0fff4;">
                <div class="result-label" style="color: #28a745;">🌟 Miglior contrasto per <?php echo strtoupper($colore1); ?> (sfondo):</div>
                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <div style="width: 60px; height: 60px; background: <?php echo $risultato['miglior_contrasto_sfondo'][0]; ?>; border: 2px solid #28a745; border-radius: 6px;"></div>
                    <div>
                        <p style="font-family: monospace; font-size: 1.3rem; font-weight: bold;"><?php echo strtoupper($risultato['miglior_contrasto_sfondo'][0]); ?></p>
                        <p style="color: #666; font-size: 0.9rem;"><?php echo $risultato['miglior_contrasto_sfondo'][1]; ?></p>
                    </div>
                    <div style="margin-left: auto;">
                        <p style="font-size: 1.5rem; font-weight: bold; color: #28a745;">
                            <?php echo $risultato['miglior_contrasto_sfondo'][1] === 'Bianco (#ffffff)' ? getContrastRatio($colore1, '#ffffff') : getContrastRatio($colore1, '#000000'); ?> : 1
                        </p>
                    </div>
                </div>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo strtoupper($risultato['miglior_contrasto_sfondo'][0]); ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            
            <div class="result" style="border-color: #28a745; background: #f0fff4;">
                <div class="result-label" style="color: #28a745;">🌟 Miglior contrasto per <?php echo strtoupper($colore2); ?> (testo):</div>
                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <div style="width: 60px; height: 60px; background: <?php echo $risultato['miglior_contrasto_testo'][0]; ?>; border: 2px solid #28a745; border-radius: 6px;"></div>
                    <div>
                        <p style="font-family: monospace; font-size: 1.3rem; font-weight: bold;"><?php echo strtoupper($risultato['miglior_contrasto_testo'][0]); ?></p>
                        <p style="color: #666; font-size: 0.9rem;"><?php echo $risultato['miglior_contrasto_testo'][1]; ?></p>
                    </div>
                    <div style="margin-left: auto;">
                        <p style="font-size: 1.5rem; font-weight: bold; color: #28a745;">
                            <?php echo $risultato['miglior_contrasto_testo'][1] === 'Bianco (#ffffff)' ? getContrastRatio($colore2, '#ffffff') : getContrastRatio($colore2, '#000000'); ?> : 1
                        </p>
                    </div>
                </div>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo strtoupper($risultato['miglior_contrasto_testo'][0]); ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            
            <div class="result">
                <div class="result-label">Schema colori per <?php echo strtoupper($colore1); ?>:</div>
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <div style="flex: 1; text-align: center;">
                        <div style="width: 100%; padding-bottom: 100%; background: <?php echo $complementari1['analogico1']; ?>; border-radius: 6px; border: 1px solid #ddd;"></div>
                        <p style="font-size: 0.75rem; margin-top: 5px;">Analogico</p>
                        <p style="font-family: monospace; font-size: 0.8rem;"><?php echo strtoupper($complementari1['analogico1']); ?></p>
                    </div>
                    <div style="flex: 1; text-align: center;">
                        <div style="width: 100%; padding-bottom: 100%; background: <?php echo $complementari1['complementare']; ?>; border-radius: 6px; border: 1px solid #ddd;"></div>
                        <p style="font-size: 0.75rem; margin-top: 5px;">Complementare</p>
                        <p style="font-family: monospace; font-size: 0.8rem;"><?php echo strtoupper($complementari1['complementare']); ?></p>
                    </div>
                    <div style="flex: 1; text-align: center;">
                        <div style="width: 100%; padding-bottom: 100%; background: <?php echo $complementari1['analogico2']; ?>; border-radius: 6px; border: 1px solid #ddd;"></div>
                        <p style="font-size: 0.75rem; margin-top: 5px;">Analogico</p>
                        <p style="font-family: monospace; font-size: 0.8rem;"><?php echo strtoupper($complementari1['analogico2']); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

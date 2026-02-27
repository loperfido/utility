<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertitore Colori - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🎨 Convertitore Colori</h1>
            
            <?php
            $hex = '#000000';
            $rgb = ['r' => 0, 'g' => 0, 'b' => 0];
            $hsl = ['h' => 0, 's' => 0, 'l' => 0];
            $colore_selezionato = '#000000';
            
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
                return [
                    'h' => round($h * 360),
                    's' => round($s * 100),
                    'l' => round($l * 100)
                ];
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
                
                return [
                    'r' => round(($r + $m) * 255),
                    'g' => round(($g + $m) * 255),
                    'b' => round(($b + $m) * 255)
                ];
            }
            
            function rgbToHex($r, $g, $b) {
                return sprintf('#%02x%02x%02x', $r, $g, $b);
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $colore_selezionato = $_POST['colore'] ?? '#000000';
                $hex = $colore_selezionato;
                $rgb = hexToRgb($hex);
                $hsl = rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="colore">Seleziona colore</label>
                    <input type="color" id="colore" name="colore" 
                           value="<?php echo htmlspecialchars($colore_selezionato); ?>" 
                           style="width: 100%; height: 60px; padding: 0; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                </div>
                
                <button type="submit">Converti</button>
            </form>
            
            <div class="result" style="text-align: center; padding: 20px; background: <?php echo $hex; ?>; border: 1px solid #ddd;">
                <div class="result-label">Anteprima colore</div>
                <p style="font-size: 0.85rem; color: <?php echo ($rgb['r']*0.299 + $rgb['g']*0.587 + $rgb['b']*0.114) > 150 ? '#000' : '#fff'; ?>;">
                    <?php echo $hex; ?>
                </p>
            </div>
            
            <div class="result">
                <div class="result-label">HEX:</div>
                <p style="font-family: monospace; font-size: 1.2rem;"><?php echo strtoupper($hex); ?></p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo strtoupper($hex); ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            
            <div class="result">
                <div class="result-label">RGB:</div>
                <p style="font-family: monospace; font-size: 1.2rem;">
                    rgb(<?php echo $rgb['r']; ?>, <?php echo $rgb['g']; ?>, <?php echo $rgb['b']; ?>)
                </p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('rgb(<?php echo $rgb['r']; ?>, <?php echo $rgb['g']; ?>, <?php echo $rgb['b']; ?>)'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            
            <div class="result">
                <div class="result-label">HSL:</div>
                <p style="font-family: monospace; font-size: 1.2rem;">
                    hsl(<?php echo $hsl['h']; ?>°, <?php echo $hsl['s']; ?>%, <?php echo $hsl['l']; ?>%)
                </p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('hsl(<?php echo $hsl['h']; ?>, <?php echo $hsl['s']; ?>%, <?php echo $hsl['l']; ?>%)'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
        </div>
    </div>
</body>
</html>

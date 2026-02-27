<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertitore Pixel - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>📐 Convertitore Pixel (px ↔ em ↔ rem ↔ pt)</h1>
            
            <?php
            $valore = 16;
            $unita_from = 'px';
            $base_font = 16;
            $base_parent = 16;
            $risultati = null;
            
            function convertToPx($valore, $unita, $base_font, $base_parent) {
                switch ($unita) {
                    case 'px': return $valore;
                    case 'em': return $valore * $base_parent;
                    case 'rem': return $valore * $base_font;
                    case 'pt': return $valore * 1.333333;
                    case 'pc': return $valore * 16;
                    case 'in': return $valore * 96;
                    case 'cm': return $valore * 37.795276;
                    case 'mm': return $valore * 3.779528;
                    default: return $valore;
                }
            }
            
            function convertFromPx($px, $unita, $base_font, $base_parent) {
                switch ($unita) {
                    case 'px': return $px;
                    case 'em': return $px / $base_parent;
                    case 'rem': return $px / $base_font;
                    case 'pt': return $px / 1.333333;
                    case 'pc': return $px / 16;
                    case 'in': return $px / 96;
                    case 'cm': return $px / 37.795276;
                    case 'mm': return $px / 3.779528;
                    default: return $px;
                }
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $valore = floatval($_POST['valore'] ?? 16);
                $unita_from = $_POST['unita_from'] ?? 'px';
                $base_font = floatval($_POST['base_font'] ?? 16);
                $base_parent = floatval($_POST['base_parent'] ?? 16);
                
                $valore_in_px = convertToPx($valore, $unita_from, $base_font, $base_parent);
                
                $risultati = [
                    'px' => round($valore_in_px, 4),
                    'em' => round(convertFromPx($valore_in_px, 'em', $base_font, $base_parent), 4),
                    'rem' => round(convertFromPx($valore_in_px, 'rem', $base_font, $base_parent), 4),
                    'pt' => round(convertFromPx($valore_in_px, 'pt', $base_font, $base_parent), 4),
                    'pc' => round(convertFromPx($valore_in_px, 'pc', $base_font, $base_parent), 4),
                    'in' => round(convertFromPx($valore_in_px, 'in', $base_font, $base_parent), 4),
                    'cm' => round(convertFromPx($valore_in_px, 'cm', $base_font, $base_parent), 4),
                    'mm' => round(convertFromPx($valore_in_px, 'mm', $base_font, $base_parent), 4)
                ];
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="valore">Valore</label>
                    <input type="number" step="any" id="valore" name="valore" 
                           value="<?php echo htmlspecialchars($valore); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="unita_from">Unità di partenza</label>
                    <select id="unita_from" name="unita_from">
                        <option value="px" <?php echo $unita_from === 'px' ? 'selected' : ''; ?>>Pixel (px)</option>
                        <option value="em" <?php echo $unita_from === 'em' ? 'selected' : ''; ?>>EM (em)</option>
                        <option value="rem" <?php echo $unita_from === 'rem' ? 'selected' : ''; ?>>REM (rem)</option>
                        <option value="pt" <?php echo $unita_from === 'pt' ? 'selected' : ''; ?>>Punti (pt)</option>
                        <option value="pc" <?php echo $unita_from === 'pc' ? 'selected' : ''; ?>>Pica (pc)</option>
                        <option value="in" <?php echo $unita_from === 'in' ? 'selected' : ''; ?>>Pollici (in)</option>
                        <option value="cm" <?php echo $unita_from === 'cm' ? 'selected' : ''; ?>>Centimetri (cm)</option>
                        <option value="mm" <?php echo $unita_from === 'mm' ? 'selected' : ''; ?>>Millimetri (mm)</option>
                    </select>
                </div>
                
                <div class="inline-group">
                    <div class="form-group" style="flex:1;">
                        <label for="base_font">Font-size root (px)</label>
                        <input type="number" step="any" id="base_font" name="base_font" 
                               value="<?php echo htmlspecialchars($base_font); ?>" placeholder="16">
                        <p style="font-size: 0.75rem; color: #888; margin-top: 4px;">Per rem (default: 16px)</p>
                    </div>
                    
                    <div class="form-group" style="flex:1;">
                        <label for="base_parent">Font-size parent (px)</label>
                        <input type="number" step="any" id="base_parent" name="base_parent" 
                               value="<?php echo htmlspecialchars($base_parent); ?>" placeholder="16">
                        <p style="font-size: 0.75rem; color: #888; margin-top: 4px;">Per em (default: 16px)</p>
                    </div>
                </div>
                
                <button type="submit">Converti</button>
            </form>
            
            <?php if ($risultati): ?>
            <div class="result">
                <div class="result-label">Risultati:</div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 15px;">
                    <div style="padding: 10px; background: #f9f9f9; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #666;">Pixel</span>
                        <p style="font-family: monospace; font-size: 1.1rem;"><strong><?php echo $risultati['px']; ?></strong> px</p>
                    </div>
                    <div style="padding: 10px; background: #f9f9f9; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #666;">EM</span>
                        <p style="font-family: monospace; font-size: 1.1rem;"><strong><?php echo $risultati['em']; ?></strong> em</p>
                    </div>
                    <div style="padding: 10px; background: #f9f9f9; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #666;">REM</span>
                        <p style="font-family: monospace; font-size: 1.1rem;"><strong><?php echo $risultati['rem']; ?></strong> rem</p>
                    </div>
                    <div style="padding: 10px; background: #f9f9f9; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #666;">Punti</span>
                        <p style="font-family: monospace; font-size: 1.1rem;"><strong><?php echo $risultati['pt']; ?></strong> pt</p>
                    </div>
                    <div style="padding: 10px; background: #f9f9f9; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #666;">Pica</span>
                        <p style="font-family: monospace; font-size: 1.1rem;"><strong><?php echo $risultati['pc']; ?></strong> pc</p>
                    </div>
                    <div style="padding: 10px; background: #f9f9f9; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #666;">Pollici</span>
                        <p style="font-family: monospace; font-size: 1.1rem;"><strong><?php echo $risultati['in']; ?></strong> in</p>
                    </div>
                    <div style="padding: 10px; background: #f9f9f9; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #666;">Centimetri</span>
                        <p style="font-family: monospace; font-size: 1.1rem;"><strong><?php echo $risultati['cm']; ?></strong> cm</p>
                    </div>
                    <div style="padding: 10px; background: #f9f9f9; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #666;">Millimetri</span>
                        <p style="font-family: monospace; font-size: 1.1rem;"><strong><?php echo $risultati['mm']; ?></strong> mm</p>
                    </div>
                </div>
            </div>
            
            <div class="result" style="margin-top: 15px;">
                <div class="result-label">Esempio CSS:</div>
                <p style="font-family: monospace; font-size: 0.9rem; margin-top: 10px;">
                    font-size: <?php echo $risultati['rem']; ?>rem;<br>
                    font-size: <?php echo $risultati['em']; ?>em;<br>
                    font-size: <?php echo $risultati['px']; ?>px;
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

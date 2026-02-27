<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertitore Unità - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>📏 Convertitore Unità di Misura</h1>
            
            <?php
            $valore = 1;
            $categoria = 'lunghezza';
            $unita_from = 'km';
            $unita_to = 'mi';
            $risultato = null;
            
            $unita = [
                'lunghezza' => [
                    'km' => 'Chilometri (km)',
                    'm' => 'Metri (m)',
                    'cm' => 'Centimetri (cm)',
                    'mm' => 'Millimetri (mm)',
                    'mi' => 'Miglia (mi)',
                    'yd' => 'Iarde (yd)',
                    'ft' => 'Piedi (ft)',
                    'in' => 'Pollici (in)',
                    'nm' => 'Nanometri (nm)',
                    'au' => 'Unità astronomiche (AU)'
                ],
                'peso' => [
                    'kg' => 'Chilogrammi (kg)',
                    'g' => 'Grammi (g)',
                    'mg' => 'Milligrammi (mg)',
                    'lb' => 'Libbre (lb)',
                    'oz' => 'Once (oz)',
                    'st' => 'Stone (st)',
                    't' => 'Tonnellate (t)'
                ],
                'temperatura' => [
                    'c' => 'Celsius (°C)',
                    'f' => 'Fahrenheit (°F)',
                    'k' => 'Kelvin (K)'
                ],
                'volume' => [
                    'l' => 'Litri (L)',
                    'ml' => 'Millilitri (mL)',
                    'gal' => 'Galloni (US)',
                    'qt' => 'Quarti (US)',
                    'pt' => 'Pinte (US)',
                    'cup' => 'Tazze (US)',
                    'floz' => 'Once fluide (US)',
                    'm3' => 'Metri cubi (m³)'
                ],
                'area' => [
                    'km2' => 'Chilometri quadrati (km²)',
                    'm2' => 'Metri quadrati (m²)',
                    'cm2' => 'Centimetri quadrati (cm²)',
                    'ha' => 'Ettari (ha)',
                    'mi2' => 'Miglia quadrate (mi²)',
                    'ac' => 'Acri (ac)',
                    'ft2' => 'Piedi quadrati (ft²)'
                ],
                'velocita' => [
                    'kmh' => 'Km/ora (km/h)',
                    'ms' => 'Metri/secondo (m/s)',
                    'mph' => 'Miglia/ora (mph)',
                    'kn' => 'Nodi (kn)',
                    'mach' => 'Mach'
                ]
            ];
            
            $fattori = [
                'lunghezza' => [
                    'km' => 1000, 'm' => 1, 'cm' => 0.01, 'mm' => 0.001,
                    'mi' => 1609.344, 'yd' => 0.9144, 'ft' => 0.3048, 'in' => 0.0254,
                    'nm' => 1e-9, 'au' => 149597870700
                ],
                'peso' => [
                    'kg' => 1000, 'g' => 1, 'mg' => 0.001,
                    'lb' => 453.59237, 'oz' => 28.349523, 'st' => 6350.293, 't' => 1000000
                ],
                'volume' => [
                    'l' => 1000, 'ml' => 1,
                    'gal' => 3785.411784, 'qt' => 946.352946, 'pt' => 473.176473,
                    'cup' => 236.588236, 'floz' => 29.573529, 'm3' => 1000000
                ],
                'area' => [
                    'km2' => 1e6, 'm2' => 1, 'cm2' => 0.0001, 'ha' => 10000,
                    'mi2' => 2589988.11, 'ac' => 4046.856, 'ft2' => 0.092903
                ],
                'velocita' => [
                    'kmh' => 0.277778, 'ms' => 1, 'mph' => 0.44704, 'kn' => 0.514444, 'mach' => 343
                ]
            ];
            
            function convertiTemperatura($valore, $from, $to) {
                if ($from === $to) return $valore;
                
                // Prima converto in Celsius
                if ($from === 'c') $c = $valore;
                elseif ($from === 'f') $c = ($valore - 32) * 5/9;
                elseif ($from === 'k') $c = $valore - 273.15;
                
                // Poi da Celsius alla destinazione
                if ($to === 'c') return $c;
                elseif ($to === 'f') return $c * 9/5 + 32;
                elseif ($to === 'k') return $c + 273.15;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $valore = floatval($_POST['valore'] ?? 1);
                $categoria = $_POST['categoria'] ?? 'lunghezza';
                $unita_from = $_POST['unita_from'] ?? 'km';
                $unita_to = $_POST['unita_to'] ?? 'mi';
                
                if ($categoria === 'temperatura') {
                    $risultato = convertiTemperatura($valore, $unita_from, $unita_to);
                } else {
                    $fattore_from = $fattori[$categoria][$unita_from];
                    $fattore_to = $fattori[$categoria][$unita_to];
                    $valore_in_base = $valore * $fattore_from;
                    $risultato = $valore_in_base / $fattore_to;
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria" onchange="this.form.submit()">
                        <option value="lunghezza" <?php echo $categoria === 'lunghezza' ? 'selected' : ''; ?>>Lunghezza</option>
                        <option value="peso" <?php echo $categoria === 'peso' ? 'selected' : ''; ?>>Peso/Massa</option>
                        <option value="temperatura" <?php echo $categoria === 'temperatura' ? 'selected' : ''; ?>>Temperatura</option>
                        <option value="volume" <?php echo $categoria === 'volume' ? 'selected' : ''; ?>>Volume</option>
                        <option value="area" <?php echo $categoria === 'area' ? 'selected' : ''; ?>>Area</option>
                        <option value="velocita" <?php echo $categoria === 'velocita' ? 'selected' : ''; ?>>Velocità</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="valore">Valore</label>
                    <input type="number" step="any" id="valore" name="valore" 
                           value="<?php echo htmlspecialchars($valore); ?>" required>
                </div>
                
                <div class="inline-group">
                    <div class="form-group" style="flex:1;">
                        <label for="unita_from">Da</label>
                        <select id="unita_from" name="unita_from">
                            <?php foreach ($unita[$categoria] as $codice => $nome): ?>
                            <option value="<?php echo $codice; ?>" <?php echo $unita_from === $codice ? 'selected' : ''; ?>>
                                <?php echo $nome; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group" style="flex:1;">
                        <label for="unita_to">A</label>
                        <select id="unita_to" name="unita_to">
                            <?php foreach ($unita[$categoria] as $codice => $nome): ?>
                            <option value="<?php echo $codice; ?>" <?php echo $unita_to === $codice ? 'selected' : ''; ?>>
                                <?php echo $nome; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <button type="submit">Converti</button>
            </form>
            
            <?php if ($risultato !== null): ?>
            <div class="result">
                <div class="result-label">Risultato:</div>
                <p style="font-size: 1.5rem; margin: 10px 0;">
                    <strong><?php echo number_format($valore, 6); ?></strong> 
                    <?php echo $unita[$categoria][$unita_from]; ?>
                </p>
                <p style="font-size: 0.9rem; color: #666;">=</p>
                <p style="font-size: 1.8rem; color: #1a1a1a;">
                    <strong><?php echo number_format($risultato, 10); ?></strong> 
                    <?php echo $unita[$categoria][$unita_to]; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

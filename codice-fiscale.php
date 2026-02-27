<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolo Codice Fiscale - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla home</a>
        
        <div class="tool-page">
            <h1>🇮🇹 Calcolo Codice Fiscale</h1>
            
            <?php
            $nome = '';
            $cognome = '';
            $anno = '';
            $mese = '';
            $giorno = '';
            $comune = '';
            $sesso = 'M';
            $codice_fiscale = '';
            
            $mesi = [
                'A' => 'Gennaio', 'B' => 'Febbraio', 'C' => 'Marzo', 'D' => 'Aprile',
                'E' => 'Maggio', 'H' => 'Giugno', 'L' => 'Luglio', 'M' => 'Agosto',
                'P' => 'Settembre', 'R' => 'Ottobre', 'S' => 'Novembre', 'T' => 'Dicembre'
            ];
            
            $comuni_italiani = [
                'A001' => 'Agrigento', 'A004' => 'Alessandria', 'A005' => 'Ancona', 'A008' => 'Aosta',
                'A010' => 'Arezzo', 'A014' => 'Ascoli Piceno', 'A016' => 'Asti', 'A017' => 'Avellino',
                'A018' => 'Bari', 'A019' => 'Barletta', 'A022' => 'Belluno', 'A023' => 'Benevento',
                'A024' => 'Bergamo', 'A025' => 'Biella', 'A026' => 'Bologna', 'A027' => 'Bolzano',
                'A028' => 'Brescia', 'A029' => 'Brindisi', 'A030' => 'Cagliari', 'A032' => 'Caltanissetta',
                'A033' => 'Campobasso', 'A035' => 'Caserta', 'A036' => 'Catania', 'A037' => 'Catanzaro',
                'A039' => 'Chieti', 'A040' => 'Como', 'A041' => 'Cosenza', 'A042' => 'Cremona',
                'A043' => 'Crotone', 'A044' => 'Cuneo', 'A045' => 'Enna', 'A046' => 'Fermo',
                'A047' => 'Ferrara', 'A048' => 'Firenze', 'A049' => 'Foggia', 'A050' => 'Forlì-Cesena',
                'A051' => 'Frosinone', 'A052' => 'Genova', 'A053' => 'Gorizia', 'A054' => 'Grosseto',
                'A055' => 'Imperia', 'A056' => 'Isernia', 'A057' => 'La Spezia', 'A058' => "L'Aquila",
                'A059' => 'Latina', 'A060' => 'Lecce', 'A061' => 'Lecco', 'A062' => 'Livorno',
                'A063' => 'Lodi', 'A064' => 'Lucca', 'A065' => 'Macerata', 'A066' => 'Mantova',
                'A067' => 'Massa-Carrara', 'A068' => 'Matera', 'A069' => 'Messina', 'A070' => 'Milano',
                'A071' => 'Modena', 'A072' => 'Monza e Brianza', 'A073' => 'Napoli', 'A074' => 'Novara',
                'A075' => 'Nuoro', 'A076' => 'Oristano', 'A077' => 'Padova', 'A078' => 'Palermo',
                'A079' => 'Parma', 'A080' => 'Pavia', 'A081' => 'Perugia', 'A082' => 'Pesaro-Urbino',
                'A083' => 'Pescara', 'A084' => 'Piacenza', 'A085' => 'Pisa', 'A086' => 'Pistoia',
                'A087' => 'Pordenone', 'A088' => 'Potenza', 'A089' => 'Prato', 'A090' => 'Ragusa',
                'A091' => 'Ravenna', 'A092' => 'Reggio Calabria', 'A093' => 'Reggio Emilia', 'A094' => 'Rieti',
                'A095' => 'Rimini', 'A096' => 'Roma', 'A097' => 'Rovigo', 'A098' => 'Salerno',
                'A099' => 'Sassari', 'A100' => 'Savona', 'A101' => 'Siena', 'A102' => 'Siracusa',
                'A103' => 'Sondrio', 'A104' => 'Sud Sardegna', 'A105' => 'Taranto', 'A106' => 'Teramo',
                'A107' => 'Terni', 'A108' => 'Torino', 'A109' => 'Trapani', 'A110' => 'Trento',
                'A111' => 'Treviso', 'A112' => 'Trieste', 'A113' => 'Udine', 'A114' => 'Varese',
                'A115' => 'Venezia', 'A116' => 'Verbano-Cusio-Ossola', 'A117' => 'Vercelli', 'A118' => 'Verona',
                'A119' => 'Vibo Valentia', 'A120' => 'Vicenza', 'A121' => 'Viterbo',
                'Z100' => 'Estero', 'Z101' => 'Nato all\'estero'
            ];
            
            function calcolaCfParte($str, $posizione) {
                $vocali = preg_replace('/[^AEIOU]/', '', strtoupper($str));
                $consonanti = preg_replace('/[^BCDFGHJKLMNPQRSTVWXYZ]/', '', strtoupper($str));
                
                if ($posizione === 'cognome') {
                    $risultato = $consonanti . $vocali . 'XXX';
                    return substr($risultato, 0, 3);
                } else {
                    if (strlen($consonanti) >= 4) {
                        return $consonanti[0] . $consonanti[2] . $consonanti[3];
                    } else {
                        $risultato = $consonanti . $vocali . 'XXX';
                        return substr($risultato, 0, 3);
                    }
                }
            }
            
            function calcolaCarattereControllo($cf) {
                $dispari = [
                    '0' => 1, '1' => 0, '2' => 5, '3' => 7, '4' => 9, '5' => 13, '6' => 15, '7' => 17, '8' => 19, '9' => 21,
                    'A' => 1, 'B' => 0, 'C' => 5, 'D' => 7, 'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21,
                    'K' => 2, 'L' => 4, 'M' => 18, 'N' => 20, 'O' => 11, 'P' => 3, 'Q' => 6, 'R' => 8, 'S' => 12, 'T' => 14,
                    'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24, 'Z' => 23
                ];
                
                $pari = [
                    '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9,
                    'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7, 'I' => 8, 'J' => 9,
                    'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19,
                    'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24, 'Z' => 25
                ];
                
                $somma = 0;
                for ($i = 0; $i < 15; $i++) {
                    $char = $cf[$i];
                    if (($i + 1) % 2 === 1) {
                        $somma += $dispari[$char];
                    } else {
                        $somma += $pari[$char];
                    }
                }
                
                $caratteri_controllo = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                return $caratteri_controllo[$somma % 26];
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nome = strtoupper(trim($_POST['nome'] ?? ''));
                $cognome = strtoupper(trim($_POST['cognome'] ?? ''));
                $anno = $_POST['anno'] ?? '';
                $mese = $_POST['mese'] ?? '';
                $giorno = intval($_POST['giorno'] ?? 0);
                $comune = $_POST['comune'] ?? '';
                $sesso = $_POST['sesso'] ?? 'M';
                
                if ($nome && $cognome && $anno && $mese && $giorno && $comune) {
                    // Calcolo codice fiscale
                    $cf = '';
                    $cf .= calcolaCfParte($cognome, 'cognome');
                    $cf .= calcolaCfParte($nome, 'nome');
                    $cf .= substr($anno, -2);
                    $cf .= $mese;
                    $cf .= ($giorno <= 31) ? str_pad($giorno, 2, '0', STR_PAD_LEFT) : str_pad($giorno - 40, 2, '0', STR_PAD_LEFT);
                    $cf .= $comune;
                    $cf .= calcolaCarattereControllo($cf);
                    
                    $codice_fiscale = $cf;
                }
            }
            ?>
            
            <form method="POST">
                <div class="inline-group">
                    <div class="form-group" style="flex:1;">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required placeholder="Mario">
                    </div>
                    
                    <div class="form-group" style="flex:1;">
                        <label for="cognome">Cognome</label>
                        <input type="text" id="cognome" name="cognome" value="<?php echo htmlspecialchars($cognome); ?>" required placeholder="Rossi">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="sesso">Sesso</label>
                    <div class="checkbox-group">
                        <label style="padding: 10px 16px; border: 1px solid <?php echo $sesso === 'M' ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo $sesso === 'M' ? '#1a1a1a' : '#fff'; ?>; color: <?php echo $sesso === 'M' ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="sesso" value="M" <?php echo $sesso === 'M' ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;">
                            Maschio
                        </label>
                        <label style="padding: 10px 16px; border: 1px solid <?php echo $sesso === 'F' ? '#1a1a1a' : '#ddd'; ?>; background: <?php echo $sesso === 'F' ? '#1a1a1a' : '#fff'; ?>; color: <?php echo $sesso === 'F' ? '#fff' : '#333'; ?>; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="sesso" value="F" <?php echo $sesso === 'F' ? 'checked' : ''; ?> style="width: auto; margin-right: 8px; accent-color: #1a1a1a;">
                            Femmina
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="anno">Anno di nascita</label>
                    <input type="number" min="1900" max="2100" id="anno" name="anno" value="<?php echo htmlspecialchars($anno); ?>" required placeholder="1990">
                </div>
                
                <div class="form-group">
                    <label for="mese">Mese di nascita</label>
                    <select id="mese" name="mese" required>
                        <option value="">Seleziona mese</option>
                        <?php foreach ($mesi as $codice => $nome_mese): ?>
                        <option value="<?php echo $codice; ?>" <?php echo $mese === $codice ? 'selected' : ''; ?>><?php echo $nome_mese; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="giorno">Giorno di nascita</label>
                    <input type="number" min="1" max="31" id="giorno" name="giorno" value="<?php echo htmlspecialchars($giorno); ?>" required placeholder="1-31">
                    <p style="font-size: 0.85rem; color: #888; margin-top: 6px;">
                        Per le donne, aggiungere 40 al giorno (es. 5 gennaio = 45)
                    </p>
                </div>
                
                <div class="form-group">
                    <label for="comune">Comune di nascita</label>
                    <select id="comune" name="comune" required>
                        <option value="">Seleziona comune</option>
                        <?php foreach ($comuni_italiani as $codice => $nome_comune): ?>
                        <option value="<?php echo $codice; ?>" <?php echo $comune === $codice ? 'selected' : ''; ?>><?php echo $nome_comune; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit">Calcola Codice Fiscale</button>
            </form>
            
            <?php if ($codice_fiscale): ?>
            <div class="result">
                <div class="result-label">Codice Fiscale:</div>
                <p style="font-family: monospace; font-size: 1.5rem; letter-spacing: 3px; margin: 15px 0;">
                    <strong style="color: #1a1a1a;"><?php echo $codice_fiscale; ?></strong>
                </p>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo $codice_fiscale; ?>'); alert('Copiato!');">
                    📋 Copia
                </button>
            </div>
            
            <div class="result" style="margin-top: 15px; background: #fff; border-color: #ddd;">
                <div class="result-label">Attenzione:</div>
                <p style="font-size: 0.9rem; color: #666;">
                    Questo è un calcolo indicativo. Il codice fiscale ufficiale viene rilasciato dall'Agenzia delle Entrate.
                    Per casi particolari (nomi speciali, comuni non italiani, ecc.) consultare un ufficio competente.
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

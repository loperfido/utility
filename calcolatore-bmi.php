<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatore BMI - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>🧮 Calcolatore BMI</h1>

            <div class="inline-group">
                <div class="form-group" style="flex: 1;">
                    <label for="weight">Peso (kg)</label>
                    <input type="number" id="weight" min="1" max="300" placeholder="70">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="height">Altezza (cm)</label>
                    <input type="number" id="height" min="1" max="250" placeholder="175">
                </div>
            </div>

            <div class="inline-group">
                <div class="form-group" style="flex: 1;">
                    <label for="age">Età</label>
                    <input type="number" id="age" min="1" max="120" placeholder="30">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="sex">Sesso</label>
                    <select id="sex">
                        <option value="male">Maschio</option>
                        <option value="female">Femmina</option>
                    </select>
                </div>
            </div>

            <button onclick="calculate()">Calcola BMI</button>

            <div class="result" id="result" style="display: none;">
                <div class="result-label">Risultato</div>
                <p id="bmi-value" style="font-size: 2rem; font-weight: bold; margin-bottom: 8px;"></p>
                <p id="bmi-category" style="margin-bottom: 16px;"></p>
                <div id="bmi-info"></div>
                <div id="bmi-details" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e0e0e0; font-size: 0.85rem; color: #666;"></div>
            </div>
        </div>
    </div>

    <script>
        function calculate() {
            const weight = parseFloat(document.getElementById('weight').value);
            const heightCm = parseFloat(document.getElementById('height').value);
            const age = parseInt(document.getElementById('age').value);
            const sex = document.getElementById('sex').value;

            if (!weight || !heightCm || weight <= 0 || heightCm <= 0) {
                alert('Inserisci peso e altezza validi');
                return;
            }

            const heightM = heightCm / 100;
            const bmi = (weight / (heightM * heightM)).toFixed(2);

            let category, info, color, details = '';

            // Interpretazione per bambini e adolescenti (2-19 anni)
            if (age && age >= 2 && age <= 19) {
                // BMI percentile approssimato per età
                const isMale = sex === 'male';
                
                if (bmi < 14) {
                    category = 'Sottopeso';
                    color = '#3498db';
                } else if (bmi < 18.5) {
                    category = 'Normopeso';
                    color = '#27ae60';
                } else if (bmi < 22) {
                    category = 'Sovrappeso';
                    color = '#f39c12';
                } else {
                    category = 'Obesità';
                    color = '#e74c3c';
                }
                
                info = 'Il BMI per bambini e adolescenti va interpretato con le tabelle percentili OMS.';
                details = '⚠️ Per una valutazione accurata in età pediatrica, consulta un pediatra che utilizzerà le tabelle percentili specifiche per età e sesso.';
            }
            // Interpretazione per adulti (20-64 anni)
            else if (!age || (age >= 20 && age <= 64)) {
                if (bmi < 18.5) {
                    category = 'Sottopeso';
                    info = 'Il tuo BMI indica che sei sottopeso. Potresti aver bisogno di aumentare l\'apporto calorico.';
                    color = '#3498db';
                } else if (bmi < 25) {
                    category = 'Normopeso';
                    info = 'Il tuo BMI è nella norma. Continua a mantenere uno stile di vita sano!';
                    color = '#27ae60';
                } else if (bmi < 30) {
                    category = 'Sovrappeso';
                    info = 'Il tuo BMI indica sovrappeso. Potresti beneficiare di una dieta più equilibrata e più attività fisica.';
                    color = '#f39c12';
                } else if (bmi < 35) {
                    category = 'Obesità di Classe I';
                    info = 'Il tuo BMI indica obesità di primo grado. È consigliabile consultare un medico.';
                    color = '#e74c3c';
                } else if (bmi < 40) {
                    category = 'Obesità di Classe II';
                    info = 'Il tuo BMI indica obesità di secondo grado. Consulta un medico per un piano alimentare adatto.';
                    color = '#c0392b';
                } else {
                    category = 'Obesità di Classe III';
                    info = 'Il tuo BMI indica obesità grave. È importante consultare immediatamente un medico.';
                    color = '#922b21';
                }

                // Dettagli aggiuntivi basati su sesso
                if (sex === 'male') {
                    details = '💡 <strong>Per uomini:</strong> Una distribuzione del grasso addominale (girovita > 102 cm) aumenta i rischi cardiovascolari.';
                } else {
                    details = '💡 <strong>Per donne:</strong> Una distribuzione del grasso addominale (girovita > 88 cm) aumenta i rischi cardiovascolari.';
                }

                if (bmi >= 25) {
                    details += ' Considera di misurare anche il girovita per una valutazione più completa.';
                }
            }
            // Interpretazione per anziani (65+ anni)
            else if (age >= 65) {
                if (bmi < 22) {
                    category = 'Sottopeso';
                    info = 'Per gli over 65, un BMI leggermente più alto è associato a migliore salute.';
                    color = '#3498db';
                } else if (bmi < 27) {
                    category = 'Normopeso';
                    info = 'Il tuo BMI è ottimale per la tua fascia d\'età. Negli anziani un BMI 22-27 è protettivo.';
                    color = '#27ae60';
                } else if (bmi < 30) {
                    category = 'Sovrappeso lieve';
                    info = 'Leggero sovrappeso, ma nella terza età può essere accettabile se non ci sono altre patologie.';
                    color = '#f39c12';
                } else {
                    category = 'Sovrappeso importante';
                    info = 'Il BMI è elevato. Consulta un medico per valutare rischi e benefici di un eventuale calo ponderale.';
                    color = '#e74c3c';
                }

                details = '💡 <strong>Per over 65:</strong> I range di BMI ottimali sono leggermente più alti rispetto agli adulti giovani.';
            }

            document.getElementById('bmi-value').textContent = 'BMI: ' + bmi;
            document.getElementById('bmi-value').style.color = color;
            document.getElementById('bmi-category').textContent = category;
            document.getElementById('bmi-category').style.color = color;
            document.getElementById('bmi-info').textContent = info;
            document.getElementById('bmi-details').innerHTML = details;
            document.getElementById('result').style.display = 'block';
        }

        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') calculate();
            });
        });
    </script>
</body>
</html>

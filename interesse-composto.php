<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatore Interesse Composto - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>📈 Calcolatore Interesse Composto</h1>

            <div class="inline-group">
                <div class="form-group" style="flex: 1;">
                    <label for="principal">Capitale Iniziale (€)</label>
                    <input type="number" id="principal" min="0" step="0.01" placeholder="10000">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="rate">Tasso di interesse annuale (%)</label>
                    <input type="number" id="rate" min="0" step="0.01" placeholder="5">
                </div>
            </div>

            <div class="inline-group">
                <div class="form-group" style="flex: 1;">
                    <label for="years">Durata (anni)</label>
                    <input type="number" id="years" min="1" max="50" placeholder="10">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="compound">Frequenza capitalizzazione</label>
                    <select id="compound">
                        <option value="1">Annuale (1 volta/anno)</option>
                        <option value="2">Semestrale (2 volte/anno)</option>
                        <option value="4">Trimestrale (4 volte/anno)</option>
                        <option value="12">Mensile (12 volte/anno)</option>
                        <option value="52">Settimanale (52 volte/anno)</option>
                        <option value="365">Giornaliera (365 volte/anno)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="contribution">Versamento mensile aggiuntivo (€)</label>
                <input type="number" id="contribution" min="0" step="0.01" placeholder="100">
                <small style="color: #888; display: block; margin-top: 6px;">Opzionale: quanto aggiungi ogni mese</small>
            </div>

            <button onclick="calculate()">Calcola</button>

            <div class="result" id="result" style="display: none;">
                <div class="result-label">Risultato</div>
                
                <div class="stats-grid" style="margin-bottom: 24px;">
                    <div class="stat-card" style="background: #e8f5e9;">
                        <div class="stat-value" id="final-amount" style="color: #2e7d32;">€ 0</div>
                        <div class="stat-label">Montante Finale</div>
                    </div>

                    <div class="stat-card" style="background: #e3f2fd;">
                        <div class="stat-value" id="total-invested" style="color: #1976d2;">€ 0</div>
                        <div class="stat-label">Totale Investito</div>
                    </div>

                    <div class="stat-card" style="background: #fff3e0;">
                        <div class="stat-value" id="total-interest" style="color: #f57c00;">€ 0</div>
                        <div class="stat-label">Interessi Guadagnati</div>
                    </div>
                </div>

                <div id="chart-container" style="margin-top: 20px;">
                    <div class="result-label">Crescita nel tempo</div>
                    <div id="yearly-breakdown"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .stat-card {
            background: #f5f5f5;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .year-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 12px;
            border-bottom: 1px solid #e8e8e8;
            font-size: 0.9rem;
        }

        .year-row:last-child {
            border-bottom: none;
        }

        .year-row.header {
            background: #f5f5f5;
            font-weight: 600;
            border-radius: 6px 6px 0 0;
        }

        .year-row:nth-child(even:not(.header)) {
            background: #fafafa;
        }

        .progress-bar {
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            margin-top: 6px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4caf50, #8bc34a);
            border-radius: 3px;
            transition: width 0.3s;
        }
    </style>

    <script>
        function calculate() {
            const principal = parseFloat(document.getElementById('principal').value) || 0;
            const rate = parseFloat(document.getElementById('rate').value) || 0;
            const years = parseInt(document.getElementById('years').value) || 0;
            const compound = parseInt(document.getElementById('compound').value) || 1;
            const contribution = parseFloat(document.getElementById('contribution').value) || 0;

            if (principal <= 0 && contribution <= 0) {
                alert('Inserisci un capitale iniziale o un versamento mensile');
                return;
            }

            const r = rate / 100;
            const n = compound;
            
            let totalInvested = principal;
            let amount = principal;
            const yearlyData = [];

            for (let year = 1; year <= years; year++) {
                // Calcola interesse composto per l'anno
                for (let period = 0; period < n; period++) {
                    amount *= (1 + r / n);
                }
                
                // Aggiungi versamenti mensili
                for (let month = 0; month < 12; month++) {
                    if (contribution > 0) {
                        totalInvested += contribution;
                        amount += contribution * (1 + r / n / 12);
                    }
                }

                yearlyData.push({
                    year: year,
                    amount: amount,
                    invested: totalInvested,
                    interest: amount - totalInvested
                });
            }

            const finalAmount = amount;
            const totalInterest = finalAmount - totalInvested;

            // Formatta numeri
            const formatMoney = (num) => '€ ' + num.toLocaleString('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            document.getElementById('final-amount').textContent = formatMoney(finalAmount);
            document.getElementById('total-invested').textContent = formatMoney(totalInvested);
            document.getElementById('total-interest').textContent = formatMoney(totalInterest);

            // Genera tabella annuale
            let tableHTML = '<div class="year-row header"><span>Anno</span><span>Investito</span><span>Interessi</span><span>Totale</span></div>';
            
            const maxAmount = yearlyData[yearlyData.length - 1].amount;
            
            yearlyData.forEach(data => {
                const percentage = (data.amount / maxAmount) * 100;
                tableHTML += `
                    <div class="year-row">
                        <div>
                            <strong>${data.year}°</strong>
                            <div class="progress-bar"><div class="progress-fill" style="width: ${percentage}%"></div></div>
                        </div>
                        <span>${formatMoney(data.invested)}</span>
                        <span style="color: #f57c00;">${formatMoney(data.interest)}</span>
                        <span style="color: #2e7d32; font-weight: 600;">${formatMoney(data.amount)}</span>
                    </div>
                `;
            });

            document.getElementById('yearly-breakdown').innerHTML = tableHTML;
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

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatore Mutuo - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>💰 Calcolatore Mutuo</h1>

            <div class="inline-group">
                <div class="form-group" style="flex: 1;">
                    <label for="amount">Importo mutuo (€)</label>
                    <input type="number" id="amount" min="1000" step="1000" placeholder="200000">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="downpayment">Anticipo / Caparra (€)</label>
                    <input type="number" id="downpayment" min="0" step="1000" placeholder="50000">
                </div>
            </div>

            <div class="inline-group">
                <div class="form-group" style="flex: 1;">
                    <label for="rate">Tasso di interesse (%)</label>
                    <input type="number" id="rate" min="0" step="0.01" placeholder="3.5">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="years">Durata (anni)</label>
                    <input type="number" id="years" min="1" max="40" placeholder="20">
                </div>
            </div>

            <div class="form-group">
                <label for="type">Tipo di mutuo</label>
                <select id="type">
                    <option value="fixed">Tasso Fisso</option>
                    <option value="variable">Tasso Variabile (stimato)</option>
                    <option value="mixed">Tasso Misto (3+2 anni)</option>
                </select>
            </div>

            <div class="inline-group">
                <div class="form-group" style="flex: 1;">
                    <label for="insurance">Assicurazione mensile (€)</label>
                    <input type="number" id="insurance" min="0" step="1" placeholder="50">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="fees">Spese istruttoria (€)</label>
                    <input type="number" id="fees" min="0" step="100" placeholder="1000">
                </div>
            </div>

            <button onclick="calculate()">Calcola Rata</button>

            <div class="result" id="result" style="display: none;">
                <div class="result-label">Rata Mensile</div>
                <p id="monthly-payment" style="font-size: 2.5rem; font-weight: bold; color: #2e7d32; margin: 16px 0;"></p>
                
                <div class="stats-grid" style="margin-bottom: 24px;">
                    <div class="stat-card">
                        <div class="stat-value" id="total-payment" style="color: #1976d2;">€ 0</div>
                        <div class="stat-label">Totale da Pagare</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value" id="total-interest" style="color: #f57c00;">€ 0</div>
                        <div class="stat-label">Totale Interessi</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value" id="total-cost" style="color: #7b1fa2;">€ 0</div>
                        <div class="stat-label">Costo Totale Mutuo</div>
                    </div>
                </div>

                <div id="amortization-container">
                    <div class="result-label">Piano di Ammortamento (primi 5 anni)</div>
                    <div id="amortization-table"></div>
                </div>

                <div style="margin-top: 20px; padding: 16px; background: #e3f2fd; border-radius: 6px; font-size: 0.85rem;">
                    💡 <strong>Consiglio:</strong> La rata non dovrebbe superare il 30-35% del reddito mensile familiare.
                </div>
            </div>
        </div>
    </div>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .stat-card {
            background: #f5f5f5;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .ammort-row {
            display: grid;
            grid-template-columns: 0.5fr 1fr 1fr 1fr 1fr;
            padding: 10px 8px;
            border-bottom: 1px solid #e8e8e8;
            font-size: 0.8rem;
        }

        .ammort-row.header {
            background: #f5f5f5;
            font-weight: 600;
            border-radius: 6px 6px 0 0;
        }

        .ammort-row:nth-child(even:not(.header)) {
            background: #fafafa;
        }

        .ammort-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #e8e8e8;
            border-radius: 6px;
            margin-top: 12px;
        }
    </style>

    <script>
        function calculate() {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const downpayment = parseFloat(document.getElementById('downpayment').value) || 0;
            const rate = parseFloat(document.getElementById('rate').value) || 0;
            const years = parseInt(document.getElementById('years').value) || 0;
            const type = document.getElementById('type').value;
            const insurance = parseFloat(document.getElementById('insurance').value) || 0;
            const fees = parseFloat(document.getElementById('fees').value) || 0;

            if (amount <= 0) {
                alert('Inserisci un importo valido per il mutuo');
                return;
            }

            const loanAmount = amount - downpayment;
            const monthlyRate = rate / 100 / 12;
            const totalMonths = years * 12;

            // Calcolo rata (formula francese)
            let monthlyPayment;
            if (monthlyRate === 0) {
                monthlyPayment = loanAmount / totalMonths;
            } else {
                monthlyPayment = loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, totalMonths)) / (Math.pow(1 + monthlyRate, totalMonths) - 1);
            }

            // Aggiunta assicurazione
            const totalMonthlyPayment = monthlyPayment + insurance;

            // Calcolo totale
            const totalPayment = monthlyPayment * totalMonths;
            const totalInterest = totalPayment - loanAmount;
            const totalCost = totalInterest + fees + (insurance * totalMonths);

            const formatMoney = (num) => '€ ' + num.toLocaleString('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            document.getElementById('monthly-payment').textContent = formatMoney(totalMonthlyPayment);
            document.getElementById('total-payment').textContent = formatMoney(totalPayment + (insurance * totalMonths));
            document.getElementById('total-interest').textContent = formatMoney(totalInterest);
            document.getElementById('total-cost').textContent = formatMoney(totalCost);

            // Genera piano di ammortamento (primi 5 anni o totale se meno)
            const displayYears = Math.min(5, years);
            let remainingBalance = loanAmount;
            
            let tableHTML = '<div class="ammort-container"><div class="ammort-row header"><span>Mese</span><span>Rata</span><span>Interessi</span><span>Capitale</span><span>Residuo</span></div>';
            
            for (let month = 1; month <= displayYears * 12; month++) {
                const interestPayment = remainingBalance * monthlyRate;
                const capitalPayment = monthlyPayment - interestPayment;
                remainingBalance -= capitalPayment;

                if (remainingBalance < 0) remainingBalance = 0;

                const year = Math.ceil(month / 12);
                const monthInYear = ((month - 1) % 12) + 1;

                tableHTML += `
                    <div class="ammort-row">
                        <span>${year}° - ${monthInYear}°</span>
                        <span>${formatMoney(monthlyPayment)}</span>
                        <span style="color: #f57c00;">${formatMoney(interestPayment)}</span>
                        <span style="color: #1976d2;">${formatMoney(capitalPayment)}</span>
                        <span style="color: #7b1fa2;">${formatMoney(remainingBalance)}</span>
                    </div>
                `;
            }

            tableHTML += '</div>';
            document.getElementById('amortization-table').innerHTML = tableHTML;
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

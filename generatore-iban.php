<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generatore IBAN - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>🔢 Generatore IBAN</h1>

            <div class="form-group">
                <label for="country">Paese</label>
                <select id="country">
                    <option value="IT">Italia (IT)</option>
                    <option value="DE">Germania (DE)</option>
                    <option value="FR">Francia (FR)</option>
                    <option value="ES">Spagna (ES)</option>
                    <option value="GB">Regno Unito (GB)</option>
                    <option value="US">Stati Uniti (US)</option>
                    <option value="CH">Svizzera (CH)</option>
                    <option value="AT">Austria (AT)</option>
                    <option value="BE">Belgio (BE)</option>
                    <option value="NL">Paesi Bassi (NL)</option>
                    <option value="PT">Portogallo (PT)</option>
                    <option value="PL">Polonia (PL)</option>
                </select>
            </div>

            <div class="inline-group">
                <div class="form-group" style="flex: 1;">
                    <label for="bank">Codice Banca (opzionale)</label>
                    <input type="text" id="bank" maxlength="6" placeholder="Es: 12345">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="branch">Codice Filiale (opzionale)</label>
                    <input type="text" id="branch" maxlength="6" placeholder="Es: 12345">
                </div>
            </div>

            <button onclick="generate()">Genera IBAN</button>

            <div class="result" id="result" style="display: none;">
                <div class="result-label">IBAN Generato</div>
                <p id="iban-output" style="font-size: 1.3rem; font-family: monospace; letter-spacing: 2px;"></p>
                <button class="copy-btn" onclick="copy()">📋 Copia</button>
            </div>

            <div style="margin-top: 20px; padding: 16px; background: #fff3cd; border-radius: 6px; font-size: 0.85rem; color: #856404;">
                ⚠️ <strong>Attenzione:</strong> Questo IBAN è generato casualmente e serve solo per test e scopi dimostrativi. Non utilizzare per transazioni reali.
            </div>
        </div>
    </div>

    <script>
        const ibanLengths = {
            'IT': 27,
            'DE': 22,
            'FR': 27,
            'ES': 24,
            'GB': 18,
            'US': 16,
            'CH': 21,
            'AT': 20,
            'BE': 16,
            'NL': 18,
            'PT': 25,
            'PL': 28
        };

        const ibanStructure = {
            'IT': { bank: 6, branch: 6, account: 12 },
            'DE': { bank: 8, account: 10 },
            'FR': { bank: 5, branch: 5, account: 11 },
            'ES': { bank: 4, branch: 4, account: 10 },
            'GB': { bank: 4, account: 8 },
            'US': { bank: 4, account: 8 },
            'CH': { bank: 5, account: 12 },
            'AT': { bank: 5, account: 11 },
            'BE': { bank: 3, account: 9 },
            'NL': { bank: 4, account: 10 },
            'PT': { bank: 4, branch: 4, account: 11 },
            'PL': { bank: 8, account: 16 }
        };

        function randomDigit() {
            return Math.floor(Math.random() * 10);
        }

        function randomDigits(length) {
            let result = '';
            for (let i = 0; i < length; i++) {
                result += randomDigit();
            }
            return result;
        }

        function generate() {
            const country = document.getElementById('country').value;
            const bankInput = document.getElementById('bank').value;
            const branchInput = document.getElementById('branch').value;

            let bban = '';
            const structure = ibanStructure[country];

            if (country === 'IT') {
                const bank = bankInput || randomDigits(6);
                const branch = branchInput || randomDigits(6);
                const account = randomDigits(structure.account);
                
                // IT: CIN (1 char) + ABI (5) + CAB (5) + Account (12)
                const cin = String.fromCharCode(65 + (bankInput ? bankInput.split('').reduce((a, b) => a + parseInt(b) || 0, 0) % 26 : randomDigit() % 26));
                bban = cin + bank.padEnd(5, '0').slice(0, 5) + branch.padEnd(5, '0').slice(0, 5) + account;
            } else if (structure.bank && structure.account && !structure.branch) {
                const bank = bankInput || randomDigits(structure.bank);
                const account = randomDigits(structure.account);
                bban = bank + account;
            } else if (structure.bank && structure.branch && structure.account) {
                const bank = bankInput || randomDigits(structure.bank);
                const branch = branchInput || randomDigits(structure.branch);
                const account = randomDigits(structure.account);
                bban = bank + branch + account;
            }

            // Generate check digits (simplified - not fully valid IBAN)
            const checkDigits = randomDigits(2);
            
            const iban = country + checkDigits + bban;

            // Format with spaces every 4 characters
            const formattedIban = iban.match(/.{1,4}/g).join(' ');

            document.getElementById('iban-output').textContent = formattedIban;
            document.getElementById('result').style.display = 'block';
        }

        function copy() {
            const text = document.getElementById('iban-output').textContent;
            navigator.clipboard.writeText(text).then(() => {
                const btn = document.querySelector('.copy-btn');
                btn.textContent = '✅ Copiato!';
                setTimeout(() => btn.textContent = '📋 Copia', 2000);
            });
        }
    </script>
</body>
</html>

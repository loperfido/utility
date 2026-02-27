<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Strength Checker - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>🔐 Password Strength Checker</h1>

            <div class="form-group">
                <label for="password">Inserisci password</label>
                <input type="text" id="password" placeholder="Incolla o scrivi la password da analizzare">
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="showPassword"> Mostra password
                </label>
            </div>

            <div class="result" id="result" style="display: none;">
                <div class="result-label">Forza Password</div>
                <div id="strength-bar" style="height: 8px; border-radius: 4px; margin: 16px 0; background: #e0e0e0;">
                    <div id="strength-fill" style="height: 100%; border-radius: 4px; width: 0%; transition: width 0.3s, background-color 0.3s;"></div>
                </div>
                <p id="strength-text" style="font-size: 1.3rem; font-weight: bold; margin-bottom: 16px;"></p>
                
                <div id="criteria" style="text-align: left; margin-top: 20px;">
                    <div class="result-label" style="margin-bottom: 12px;">Criteri valutati:</div>
                    <div id="criteria-list"></div>
                </div>

                <div id="suggestions" style="margin-top: 20px; padding: 16px; background: #fff8e1; border-radius: 6px; font-size: 0.9rem;"></div>
            </div>

            <div id="breach-info" style="display: none; margin-top: 20px; padding: 16px; background: #ffebee; border-radius: 6px; font-size: 0.9rem;">
                <strong>⚠️ Attenzione:</strong> Questa password potrebbe essere stata compromessa in data breach pubblici.
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const showPassword = document.getElementById('showPassword');
        const result = document.getElementById('result');
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');
        const criteriaList = document.getElementById('criteria-list');
        const suggestions = document.getElementById('suggestions');
        const breachInfo = document.getElementById('breach-info');

        passwordInput.addEventListener('input', analyzePassword);
        showPassword.addEventListener('change', () => {
            passwordInput.type = showPassword.checked ? 'text' : 'password';
        });

        function analyzePassword() {
            const password = passwordInput.value;

            if (!password) {
                result.style.display = 'none';
                breachInfo.style.display = 'none';
                return;
            }

            const checks = [
                { name: 'Lunghezza ≥ 8 caratteri', pass: password.length >= 8 },
                { name: 'Lunghezza ≥ 12 caratteri', pass: password.length >= 12 },
                { name: 'Contiene lettere maiuscole', pass: /[A-Z]/.test(password) },
                { name: 'Contiene lettere minuscole', pass: /[a-z]/.test(password) },
                { name: 'Contiene numeri', pass: /[0-9]/.test(password) },
                { name: 'Contiene caratteri speciali', pass: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password) },
                { name: 'Nessun carattere ripetuto (>3)', pass: !/(.)\1{3,}/.test(password) },
                { name: 'Non contiene sequenze comuni', pass: !/(123|abc|qwerty|password|admin)/i.test(password) }
            ];

            const passedCount = checks.filter(c => c.pass).length;
            const score = (passedCount / checks.length) * 100;

            let strength, color;
            if (score < 40) {
                strength = 'Molto Debole';
                color = '#e74c3c';
            } else if (score < 60) {
                strength = 'Debole';
                color = '#e67e22';
            } else if (score < 80) {
                strength = 'Media';
                color = '#f39c12';
            } else if (score < 95) {
                strength = 'Forte';
                color = '#27ae60';
            } else {
                strength = 'Molto Forte';
                color = '#229954';
            }

            strengthFill.style.width = score + '%';
            strengthFill.style.backgroundColor = color;
            strengthText.textContent = strength;
            strengthText.style.color = color;

            criteriaList.innerHTML = checks.map(check => `
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; color: ${check.pass ? '#27ae60' : '#888'};">
                    <span style="font-size: 1.1rem;">${check.pass ? '✓' : '✗'}</span>
                    <span>${check.name}</span>
                </div>
            `).join('');

            // Suggerimenti
            const suggestionList = [];
            if (password.length < 12) suggestionList.push('Aumenta la lunghezza ad almeno 12 caratteri');
            if (!/[A-Z]/.test(password)) suggestionList.push('Aggiungi lettere maiuscole');
            if (!/[0-9]/.test(password)) suggestionList.push('Aggiungi numeri');
            if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) suggestionList.push('Aggiungi caratteri speciali (!@#$%^&*)');
            if (/(.)\1{3,}/.test(password)) suggestionList.push('Evita caratteri ripetuti consecutivamente');
            if (/(123|abc|qwerty|password|admin)/i.test(password)) suggestionList.push('Evita sequenze o parole comuni');

            if (suggestionList.length > 0) {
                suggestions.innerHTML = '<strong>Suggerimenti:</strong><br>' + suggestionList.map(s => '• ' + s).join('<br>');
                suggestions.style.display = 'block';
            } else {
                suggestions.style.display = 'none';
            }

            result.style.display = 'block';
        }
    </script>
</body>
</html>

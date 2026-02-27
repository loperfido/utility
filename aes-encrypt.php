<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AES Encrypt/Decrypt - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>🔐 AES Encrypt/Decrypt</h1>

            <div class="form-group">
                <label for="mode">Modalità</label>
                <select id="mode" onchange="toggleMode()">
                    <option value="encrypt">🔒 Cripta</option>
                    <option value="decrypt">🔓 Decripta</option>
                </select>
            </div>

            <div class="form-group">
                <label for="input">Testo da <span id="action-label">criptare</span></label>
                <textarea id="input" placeholder="Inserisci il testo..." rows="6"></textarea>
            </div>

            <div class="form-group">
                <label for="password">Chiave segreta (password)</label>
                <input type="text" id="password" placeholder="Inserisci una password sicura">
                <small style="color: #888; display: block; margin-top: 6px;">Usa una password di almeno 8 caratteri</small>
            </div>

            <div class="form-group">
                <label for="algorithm">Algoritmo</label>
                <select id="algorithm">
                    <option value="AES-GCM">AES-GCM (consigliato)</option>
                    <option value="AES-CBC">AES-CBC</option>
                </select>
            </div>

            <button onclick="process()" id="process-btn">🔒 Cripta</button>

            <div class="result" id="result" style="display: none;">
                <div class="result-label">Risultato</div>
                <textarea id="output" readonly rows="6" style="font-family: monospace; font-size: 0.85rem;"></textarea>
                <button class="copy-btn" onclick="copy()">📋 Copia</button>
            </div>

            <div style="margin-top: 20px; padding: 16px; background: #fff3cd; border-radius: 6px; font-size: 0.85rem; color: #856404;">
                ⚠️ <strong>Attenzione:</strong> La crittografia avviene nel tuo browser. Conserva la password in un luogo sicuro: senza di essa non potrai recuperare il testo decriptato.
            </div>
        </div>
    </div>

    <script>
        function toggleMode() {
            const mode = document.getElementById('mode').value;
            const actionLabel = document.getElementById('action-label');
            const processBtn = document.getElementById('process-btn');
            
            if (mode === 'encrypt') {
                actionLabel.textContent = 'criptare';
                processBtn.textContent = '🔒 Cripta';
            } else {
                actionLabel.textContent = 'decriptare';
                processBtn.textContent = '🔓 Decripta';
            }
        }

        async function generateKey(password, salt) {
            const enc = new TextEncoder();
            const keyMaterial = await crypto.subtle.importKey(
                'raw',
                enc.encode(password),
                'PBKDF2',
                false,
                ['deriveBits', 'deriveKey']
            );

            return crypto.subtle.deriveKey(
                {
                    name: 'PBKDF2',
                    salt: enc.encode(salt),
                    iterations: 100000,
                    hash: 'SHA-256'
                },
                keyMaterial,
                { name: 'AES-GCM', length: 256 },
                false,
                ['encrypt', 'decrypt']
            );
        }

        async function encrypt() {
            const input = document.getElementById('input').value;
            const password = document.getElementById('password').value;

            if (!input || !password) {
                alert('Inserisci testo e password');
                return;
            }

            try {
                const enc = new TextEncoder();
                const salt = crypto.getRandomValues(new Uint8Array(16));
                const iv = crypto.getRandomValues(new Uint8Array(12));
                
                const key = await generateKey(password, 'salt-' + Array.from(salt).join(''));
                
                const encrypted = await crypto.subtle.encrypt(
                    { name: 'AES-GCM', iv: iv },
                    key,
                    enc.encode(input)
                );

                // Combine salt + iv + encrypted data
                const combined = new Uint8Array(salt.length + iv.length + encrypted.byteLength);
                combined.set(salt, 0);
                combined.set(iv, salt.length);
                combined.set(new Uint8Array(encrypted), salt.length + iv.length);

                // Convert to base64
                const base64 = btoa(String.fromCharCode(...combined));
                
                document.getElementById('output').value = base64;
                document.getElementById('result').style.display = 'block';
            } catch (error) {
                alert('Errore durante la crittografia: ' + error.message);
            }
        }

        async function decrypt() {
            const input = document.getElementById('input').value.trim();
            const password = document.getElementById('password').value;

            if (!input || !password) {
                alert('Inserisci testo criptato e password');
                return;
            }

            try {
                // Decode from base64
                const combined = Uint8Array.from(atob(input), c => c.charCodeAt(0));
                
                // Extract salt, iv and encrypted data
                const salt = combined.slice(0, 16);
                const iv = combined.slice(16, 28);
                const encrypted = combined.slice(28);

                const key = await generateKey(password, 'salt-' + Array.from(salt).join(''));
                
                const decrypted = await crypto.subtle.decrypt(
                    { name: 'AES-GCM', iv: iv },
                    key,
                    encrypted
                );

                const dec = new TextDecoder();
                document.getElementById('output').value = dec.decode(decrypted);
                document.getElementById('result').style.display = 'block';
            } catch (error) {
                alert('Errore durante la decriptazione. Password errata o dati corrotti.');
            }
        }

        function process() {
            const mode = document.getElementById('mode').value;
            if (mode === 'encrypt') {
                encrypt();
            } else {
                decrypt();
            }
        }

        function copy() {
            const output = document.getElementById('output');
            output.select();
            document.execCommand('copy');
            
            const btn = document.querySelector('.copy-btn');
            btn.textContent = '✅ Copiato!';
            setTimeout(() => btn.textContent = '📋 Copia', 2000);
        }
    </script>
</body>
</html>

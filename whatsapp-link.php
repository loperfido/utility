<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Link Generator - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>📱 WhatsApp Link Generator</h1>

            <div class="form-group">
                <label for="phone">Numero di telefono (con prefisso internazionale)</label>
                <input type="text" id="phone" placeholder="Es: 393331234567">
                <small style="color: #888; display: block; margin-top: 6px;">Inserisci il numero con prefisso paese, senza + o spazi (Es: 39 per Italia)</small>
            </div>

            <div class="form-group">
                <label for="message">Messaggio (opzionale)</label>
                <textarea id="message" placeholder="Scrivi il messaggio precompilato..." rows="4"></textarea>
            </div>

            <button onclick="generate()">Genera Link</button>

            <div class="result" id="result" style="display: none;">
                <div class="result-label">Link WhatsApp</div>
                <p id="link-output" style="font-size: 0.9rem; word-break: break-all; font-family: monospace;"></p>
                <button class="copy-btn" onclick="copy()">📋 Copia Link</button>
                <button class="copy-btn" onclick="openLink()" style="margin-left: 8px;">🔗 Apri</button>
            </div>

            <div class="result" id="qr-result" style="display: none; margin-top: 16px;">
                <div class="result-label">QR Code</div>
                <div id="qr-container"></div>
            </div>
        </div>
    </div>

    <script>
        function generate() {
            const phone = document.getElementById('phone').value.trim();
            const message = document.getElementById('message').value.trim();

            if (!phone) {
                alert('Inserisci un numero di telefono');
                return;
            }

            // Remove any non-digit characters except +
            const cleanPhone = phone.replace(/[^0-9]/g, '');
            
            // Build URL
            let url = 'https://wa.me/' + cleanPhone;
            
            if (message) {
                const encodedMessage = encodeURIComponent(message);
                url += '?text=' + encodedMessage;
            }

            document.getElementById('link-output').textContent = url;
            document.getElementById('result').style.display = 'block';

            // Generate QR Code
            generateQR(url);
        }

        function generateQR(url) {
            const qrContainer = document.getElementById('qr-container');
            const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(url);
            
            qrContainer.innerHTML = '<div class="qr-container"><img src="' + qrUrl + '" alt="QR Code" style="width: 200px; height: 200px;"></div>';
            document.getElementById('qr-result').style.display = 'block';
        }

        function copy() {
            const text = document.getElementById('link-output').textContent;
            navigator.clipboard.writeText(text).then(() => {
                const btn = document.querySelector('.copy-btn');
                btn.textContent = '✅ Copiato!';
                setTimeout(() => btn.textContent = '📋 Copia Link', 2000);
            });
        }

        function openLink() {
            const url = document.getElementById('link-output').textContent;
            window.open(url, '_blank');
        }

        document.querySelectorAll('input, textarea').forEach(el => {
            el.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && e.target.id !== 'message') {
                    generate();
                }
            });
        });
    </script>
</body>
</html>

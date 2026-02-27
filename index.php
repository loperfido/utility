<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utility Hub - Strumenti Online Gratuiti</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>🛠️ Utility Hub</h1>

        <div class="search-filters">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cerca strumento..." onkeyup="filterTools()">
            </div>
            <div class="sort-buttons">
                <button onclick="sortTools('az')" class="sort-btn">A-Z</button>
                <button onclick="sortTools('za')" class="sort-btn">Z-A</button>
                <button onclick="sortTools('reset')" class="sort-btn active">Ordine</button>
            </div>
        </div>

        <div class="tools-grid" id="toolsGrid">
            <a href="convertitore-basi.php" class="tool-card">
                <h2>🔢 Convertitore Basi</h2>
            </a>

            <a href="convertitore-colori.php" class="tool-card">
                <h2>🎨 Convertitore Colori</h2>
            </a>

            <a href="convertitore-unita.php" class="tool-card">
                <h2>📏 Convertitore Unità</h2>
            </a>

            <a href="convertitore-pixel.php" class="tool-card">
                <h2>📐 Convertitore Pixel</h2>
            </a>

            <a href="convertitore-timestamp.php" class="tool-card">
                <h2>🕐 Convertitore Timestamp</h2>
            </a>

            <a href="case-converter.php" class="tool-card">
                <h2>🔤 Case Converter</h2>
            </a>

            <a href="inverti-testo.php" class="tool-card">
                <h2>🔃 Inverti Testo</h2>
            </a>

            <a href="base64.php" class="tool-card">
                <h2>🔄 Base64</h2>
            </a>

            <a href="differenza-date.php" class="tool-card">
                <h2>📅 Differenza Date</h2>
            </a>

            <a href="aggiungi-giorni-data.php" class="tool-card">
                <h2>📅 Aggiungi Giorni</h2>
            </a>

            <a href="generatore-password.php" class="tool-card">
                <h2>🔐 Generatore Password</h2>
            </a>

            <a href="generatore-uuid.php" class="tool-card">
                <h2>🔑 Generatore UUID</h2>
            </a>

            <a href="generatore-ip.php" class="tool-card">
                <h2>🌐 Generatore IP</h2>
            </a>

            <a href="generatore-qr.php" class="tool-card">
                <h2>📱 Generatore QR Code</h2>
            </a>

            <a href="numeri-casuali.php" class="tool-card">
                <h2>🎲 Numeri Casuali</h2>
            </a>

            <a href="hash-generator.php" class="tool-card">
                <h2>🔏 Hash Generator</h2>
            </a>

            <a href="cifrario-cesare.php" class="tool-card">
                <h2>🔐 Cifrario di Cesare</h2>
            </a>

            <a href="checksum-file.php" class="tool-card">
                <h2>📄 Checksum File</h2>
            </a>

            <a href="calcolo-sconto.php" class="tool-card">
                <h2>🏷️ Calcolatore Sconto</h2>
            </a>

            <a href="contrasto-colori.php" class="tool-card">
                <h2>🌈 Contrasto Colori</h2>
            </a>

            <a href="codice-fiscale.php" class="tool-card">
                <h2>🇮🇹 Codice Fiscale</h2>
            </a>

            <a href="estrai-email.php" class="tool-card">
                <h2>📧 Estrai Email</h2>
            </a>

            <a href="lorem-ipsum.php" class="tool-card">
                <h2>📝 Lorem Ipsum</h2>
            </a>

            <a href="calcolatore-bmi.php" class="tool-card">
                <h2>🧮 Calcolatore BMI</h2>
            </a>

            <a href="timezone-converter.php" class="tool-card">
                <h2>🌍 Timezone Converter</h2>
            </a>

            <a href="password-strength.php" class="tool-card">
                <h2>🔐 Password Strength</h2>
            </a>

            <a href="contatore-parole.php" class="tool-card">
                <h2>📝 Contatore Parole</h2>
            </a>

            <a href="generatore-iban.php" class="tool-card">
                <h2>🔢 Generatore IBAN</h2>
            </a>

            <a href="whatsapp-link.php" class="tool-card">
                <h2>📱 WhatsApp Link</h2>
            </a>

            <a href="interesse-composto.php" class="tool-card">
                <h2>📈 Interesse Composto</h2>
            </a>

            <a href="calcolatore-mutuo.php" class="tool-card">
                <h2>💰 Calcolatore Mutuo</h2>
            </a>

            <a href="aes-encrypt.php" class="tool-card">
                <h2>🔐 AES Encrypt/Decrypt</h2>
            </a>
        </div>

        <footer>
            <p>
                <a href="https://github.com/loperfido/utility" target="_blank" rel="noopener">GitHub</a> ·
                <a href="https://loperfido.altervista.org" target="_blank" rel="noopener">Cristian Loperfido</a> ·
                <span>Versione 1.0.0</span>
            </p>
        </footer>
    </div>

    <script>
        function filterTools() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const grid = document.getElementById('toolsGrid');
            const cards = grid.getElementsByClassName('tool-card');

            for (let i = 0; i < cards.length; i++) {
                const title = cards[i].getElementsByTagName('h2')[0];
                const txtValue = title.textContent || title.innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    cards[i].style.display = '';
                } else {
                    cards[i].style.display = 'none';
                }
            }
        }

        function sortTools(order) {
            const grid = document.getElementById('toolsGrid');
            const cards = Array.from(grid.getElementsByClassName('tool-card'));

            // Update active button
            document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            cards.sort((a, b) => {
                const aText = a.getElementsByTagName('h2')[0].textContent.trim();
                const bText = b.getElementsByTagName('h2')[0].textContent.trim();

                if (order === 'az') {
                    return aText.localeCompare(bText, 'it');
                } else if (order === 'za') {
                    return bText.localeCompare(aText, 'it');
                } else {
                    // Reset - original order based on href
                    const aHref = a.getAttribute('href');
                    const bHref = b.getAttribute('href');
                    return aHref.localeCompare(bHref);
                }
            });

            cards.forEach(card => grid.appendChild(card));
        }
    </script>
</body>
</html>

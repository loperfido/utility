<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatore Parole e Caratteri - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>📝 Contatore Parole e Caratteri</h1>

            <div class="form-group">
                <label for="text">Inserisci testo</label>
                <textarea id="text" placeholder="Scrivi o incolla il tuo testo qui..." rows="12"></textarea>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="words">0</div>
                    <div class="stat-label">Parole</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value" id="characters">0</div>
                    <div class="stat-label">Caratteri</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value" id="characters-no-spaces">0</div>
                    <div class="stat-label">Caratteri (no spazi)</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value" id="sentences">0</div>
                    <div class="stat-label">Frasi</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value" id="paragraphs">0</div>
                    <div class="stat-label">Paragrafi</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value" id="lines">0</div>
                    <div class="stat-label">Righe</div>
                </div>
            </div>

            <div class="result" id="reading-time" style="display: none;">
                <div class="result-label">Tempo di lettura stimato</div>
                <p id="reading-value"></p>
            </div>
        </div>
    </div>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
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
            font-size: 2rem;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>

    <script>
        const textArea = document.getElementById('text');
        const wordsEl = document.getElementById('words');
        const charactersEl = document.getElementById('characters');
        const charactersNoSpacesEl = document.getElementById('characters-no-spaces');
        const sentencesEl = document.getElementById('sentences');
        const paragraphsEl = document.getElementById('paragraphs');
        const linesEl = document.getElementById('lines');
        const readingTimeEl = document.getElementById('reading-time');
        const readingValueEl = document.getElementById('reading-value');

        textArea.addEventListener('input', countStats);

        function countStats() {
            const text = textArea.value;

            // Caratteri
            const characters = text.length;
            const charactersNoSpaces = text.replace(/\s/g, '').length;

            // Parole (split su spazi multipli e newline)
            const wordsArray = text.trim().split(/\s+/).filter(word => word.length > 0);
            const words = wordsArray.length;

            // Frasi (conteggio approssimativo basato su . ! ?)
            const sentencesArray = text.split(/[.!?]+/).filter(s => s.trim().length > 0);
            const sentences = sentencesArray.length;

            // Paragrafi (separati da doppi newline o più)
            const paragraphsArray = text.split(/\n\s*\n/).filter(p => p.trim().length > 0);
            const paragraphs = paragraphsArray.length;

            // Righe
            const lines = text.length > 0 ? text.split(/\n/).length : 0;

            // Aggiorna UI
            wordsEl.textContent = words;
            charactersEl.textContent = characters;
            charactersNoSpacesEl.textContent = charactersNoSpaces;
            sentencesEl.textContent = sentences || (text.length > 0 ? 1 : 0);
            paragraphsEl.textContent = paragraphs || (text.length > 0 ? 1 : 0);
            linesEl.textContent = lines;

            // Tempo di lettura (media: 200 parole/minuto)
            if (words > 0) {
                const minutes = Math.ceil(words / 200);
                const seconds = Math.round((words / 200) * 60) % 60;
                
                if (minutes > 0) {
                    readingValueEl.textContent = `${minutes} min ${seconds > 0 ? seconds + ' sec' : ''}`;
                } else {
                    readingValueEl.textContent = `${seconds} secondi`;
                }
                readingTimeEl.style.display = 'block';
            } else {
                readingTimeEl.style.display = 'none';
            }
        }
    </script>
</body>
</html>

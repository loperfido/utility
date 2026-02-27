<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lorem Ipsum Generator - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>📝 Lorem Ipsum Generator</h1>

            <div class="form-group">
                <label for="type">Tipo di output</label>
                <select id="type">
                    <option value="paragraphs">Paragrafi</option>
                    <option value="sentences">Frasi</option>
                    <option value="words">Parole</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Quantità</label>
                <input type="number" id="amount" min="1" max="100" value="3">
            </div>

            <button onclick="generate()">Genera</button>

            <div class="result" id="result" style="display: none;">
                <div class="result-label">Output</div>
                <p id="output"></p>
                <button class="copy-btn" onclick="copy()">Copia</button>
            </div>
        </div>
    </div>

    <script>
        const words = [
            'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit',
            'sed', 'do', 'eiusmod', 'tempor', 'incididunt', 'ut', 'labore', 'et', 'dolore',
            'magna', 'aliqua', 'enim', 'ad', 'minim', 'veniam', 'quis', 'nostrud',
            'exercitation', 'ullamco', 'laboris', 'nisi', 'aliquip', 'ex', 'ea', 'commodo',
            'consequat', 'duis', 'aute', 'irure', 'in', 'reprehenderit', 'voluptate',
            'velit', 'esse', 'cillum', 'fugiat', 'nulla', 'pariatur', 'excepteur', 'sint',
            'occaecat', 'cupidatat', 'non', 'proident', 'sunt', 'culpa', 'qui', 'officia',
            'deserunt', 'mollit', 'anim', 'id', 'est', 'laborum'
        ];

        function getRandomWord() {
            return words[Math.floor(Math.random() * words.length)];
        }

        function generateSentence() {
            const length = Math.floor(Math.random() * 8) + 5;
            let sentence = '';
            for (let i = 0; i < length; i++) {
                sentence += getRandomWord() + ' ';
            }
            sentence = sentence.trim();
            return sentence.charAt(0).toUpperCase() + sentence.slice(1) + '.';
        }

        function generateParagraph() {
            const sentences = Math.floor(Math.random() * 3) + 3;
            let paragraph = '';
            for (let i = 0; i < sentences; i++) {
                paragraph += generateSentence() + ' ';
            }
            return paragraph.trim();
        }

        function generate() {
            const type = document.getElementById('type').value;
            const amount = parseInt(document.getElementById('amount').value);
            let output = '';

            if (type === 'paragraphs') {
                const paragraphs = [];
                for (let i = 0; i < amount; i++) {
                    paragraphs.push(generateParagraph());
                }
                output = paragraphs.join('\n\n');
            } else if (type === 'sentences') {
                for (let i = 0; i < amount; i++) {
                    output += generateSentence() + ' ';
                }
            } else if (type === 'words') {
                for (let i = 0; i < amount; i++) {
                    output += getRandomWord() + ' ';
                }
            }

            document.getElementById('output').textContent = output.trim();
            document.getElementById('result').style.display = 'block';
        }

        function copy() {
            const text = document.getElementById('output').textContent;
            navigator.clipboard.writeText(text).then(() => {
                const btn = document.querySelector('.copy-btn');
                btn.textContent = 'Copiato!';
                setTimeout(() => btn.textContent = 'Copia', 2000);
            });
        }
    </script>
</body>
</html>

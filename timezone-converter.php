<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timezone Converter - Utility Hub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Torna alla Home</a>

        <div class="tool-page">
            <h1>🌍 Timezone Converter</h1>

            <div class="form-group">
                <label for="datetime">Data e Ora</label>
                <input type="datetime-local" id="datetime">
            </div>

            <div class="inline-group">
                <div class="form-group" style="flex: 1;">
                    <label for="fromTz">Fuso di partenza</label>
                    <select id="fromTz"></select>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="toTz">Fuso di arrivo</label>
                    <select id="toTz"></select>
                </div>
            </div>

            <button onclick="convert()">Converti</button>

            <div class="result" id="result" style="display: none;">
                <div class="result-label">Risultato</div>
                <p id="output" style="font-size: 1.2rem;"></p>
                <p id="diff" style="font-size: 0.9rem; color: #666; margin-top: 8px;"></p>
            </div>
        </div>
    </div>

    <script>
        const timezones = [
            { name: 'UTC', value: 'UTC' },
            { name: 'Europa - Roma (CET/CEST)', value: 'Europe/Rome' },
            { name: 'Europa - Londra (GMT/BST)', value: 'Europe/London' },
            { name: 'Europa - Parigi (CET/CEST)', value: 'Europe/Paris' },
            { name: 'Europa - Berlino (CET/CEST)', value: 'Europe/Berlin' },
            { name: 'Europa - Mosca (MSK)', value: 'Europe/Moscow' },
            { name: 'USA - New York (EST/EDT)', value: 'America/New_York' },
            { name: 'USA - Los Angeles (PST/PDT)', value: 'America/Los_Angeles' },
            { name: 'USA - Chicago (CST/CDT)', value: 'America/Chicago' },
            { name: 'USA - Denver (MST/MDT)', value: 'America/Denver' },
            { name: 'Canada - Toronto (EST/EDT)', value: 'America/Toronto' },
            { name: 'Canada - Vancouver (PST/PDT)', value: 'America/Vancouver' },
            { name: 'Brasile - São Paulo (BRT)', value: 'America/Sao_Paulo' },
            { name: 'Argentina - Buenos Aires (ART)', value: 'America/Argentina/Buenos_Aires' },
            { name: 'Messico - Città del Messico (CST)', value: 'America/Mexico_City' },
            { name: 'Asia - Tokyo (JST)', value: 'Asia/Tokyo' },
            { name: 'Asia - Shanghai (CST)', value: 'Asia/Shanghai' },
            { name: 'Asia - Hong Kong (HKT)', value: 'Asia/Hong_Kong' },
            { name: 'Asia - Singapore (SGT)', value: 'Asia/Singapore' },
            { name: 'Asia - Dubai (GST)', value: 'Asia/Dubai' },
            { name: 'Asia - Mumbai (IST)', value: 'Asia/Kolkata' },
            { name: 'Asia - Bangkok (ICT)', value: 'Asia/Bangkok' },
            { name: 'Asia - Seoul (KST)', value: 'Asia/Seoul' },
            { name: 'Australia - Sydney (AEST/AEDT)', value: 'Australia/Sydney' },
            { name: 'Australia - Melbourne (AEST/AEDT)', value: 'Australia/Melbourne' },
            { name: 'Australia - Perth (AWST)', value: 'Australia/Perth' },
            { name: 'Nuova Zelanda - Auckland (NZST/NZDT)', value: 'Pacific/Auckland' },
            { name: 'Pacifico - Honolulu (HST)', value: 'Pacific/Honolulu' },
            { name: 'Sudafrica - Johannesburg (SAST)', value: 'Africa/Johannesburg' },
            { name: 'Egitto - Il Cairo (EET)', value: 'Africa/Cairo' },
            { name: 'Nigeria - Lagos (WAT)', value: 'Africa/Lagos' },
            { name: 'Kenya - Nairobi (EAT)', value: 'Africa/Nairobi' }
        ];

        function populateSelects() {
            const fromSelect = document.getElementById('fromTz');
            const toSelect = document.getElementById('toTz');

            timezones.forEach((tz, index) => {
                const option1 = document.createElement('option');
                option1.value = tz.value;
                option1.textContent = tz.name;
                fromSelect.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = tz.value;
                option2.textContent = tz.name;
                toSelect.appendChild(option2);
            });

            // Imposta valori predefiniti
            fromSelect.value = 'Europe/Rome';
            toSelect.value = 'America/New_York';
        }

        function convert() {
            const datetimeInput = document.getElementById('datetime').value;
            const fromTz = document.getElementById('fromTz').value;
            const toTz = document.getElementById('toTz').value;

            if (!datetimeInput) {
                alert('Seleziona una data e ora');
                return;
            }

            const date = new Date(datetimeInput);
            
            // Formatta data nel fuso di partenza
            const fromOptions = { 
                timeZone: fromTz,
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            };
            
            // Formatta data nel fuso di arrivo
            const toOptions = { 
                timeZone: toTz,
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            };

            const fromFormatter = new Intl.DateTimeFormat('it-IT', fromOptions);
            const toFormatter = new Intl.DateTimeFormat('it-IT', toOptions);

            const fromDate = fromFormatter.format(date);
            const toDate = toFormatter.format(date);

            // Calcola differenza ore
            const fromTime = new Date(date.toLocaleString('en-US', { timeZone: fromTz }));
            const toTime = new Date(date.toLocaleString('en-US', { timeZone: toTz }));
            const diffHours = (toTime - fromTime) / (1000 * 60 * 60);
            const diffSign = diffHours >= 0 ? '+' : '';
            const diffText = diffHours !== 0 ? `${diffSign}${diffHours.toFixed(1)} ore` : 'stesso orario';

            document.getElementById('output').innerHTML = `
                <strong>Da:</strong> ${fromDate} (${fromTz})<br><br>
                <strong>A:</strong> ${toDate} (${toTz})
            `;
            document.getElementById('diff').textContent = `Differenza: ${diffText}`;
            document.getElementById('result').style.display = 'block';
        }

        // Imposta data/ora corrente come default
        function setDefaultDateTime() {
            const now = new Date();
            const offset = now.getTimezoneOffset();
            const local = new Date(now.getTime() - offset * 60 * 1000);
            document.getElementById('datetime').value = local.toISOString().slice(0, 16);
        }

        populateSelects();
        setDefaultDateTime();
    </script>
</body>
</html>

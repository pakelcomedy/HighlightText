<?php
require 'db_config.php';

// Mengambil data teks yang disimpan dari database
$stmt = $pdo->query("SELECT text, indices FROM highlighted_texts");
$highlightedTexts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Teks yang akan ditampilkan
$mainText = "to This to is a sample to text. You can highlight words in this text to save them to the database.to";

// Menyoroti teks yang ada di database
foreach ($highlightedTexts as $row) {
    $text = $row['text'];
    $indices = explode(',', $row['indices']);

    // Soroti setiap kemunculan
    foreach ($indices as $index) {
        // Gunakan substr untuk menyoroti teks berdasarkan indeks
        $mainText = substr_replace($mainText, '<span class="highlighted-text">' . $text . '</span>', $index, strlen($text));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Highlight Text Project</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .highlighted-text {
            background-color: #ffffcc; /* Warna sorotan */
            padding: 2px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        #notification {
            margin-top: 20px;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Highlight Text</h1>
    <div id="textContainer">
        <p>
            <?= $mainText; ?>
        </p>
    </div>
    <button id="saveTextBtn">Save Highlighted Text</button>
    <div id="notification"></div>

    <script>
        document.getElementById('saveTextBtn').addEventListener('click', function() {
            const selection = window.getSelection();
            const highlightedText = selection.toString().trim();
            const fullText = document.getElementById('textContainer').innerText;

            if (highlightedText === '') {
                showNotification('Please highlight some text before saving.', true);
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_text.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showNotification('Text saved successfully!', false);
                        
                        // Menyisipkan highlight baru di tempat kursor berada
                        const range = selection.getRangeAt(0);
                        const newHighlight = document.createElement('span');
                        newHighlight.className = 'highlighted-text';
                        newHighlight.textContent = highlightedText;

                        range.deleteContents(); // Menghapus teks yang disorot
                        range.insertNode(newHighlight); // Menyisipkan teks yang disorot baru
                    } else {
                        showNotification('Failed to save text: ' + response.error, true);
                    }
                }
            };
            xhr.send('text=' + encodeURIComponent(highlightedText) + '&fullText=' + encodeURIComponent(fullText));
        });

        function showNotification(message, isError) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.color = isError ? 'red' : 'green';
            notification.style.display = 'block';

            // Sembunyikan pemberitahuan setelah 3 detik
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>

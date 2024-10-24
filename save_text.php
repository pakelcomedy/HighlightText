<?php
require 'db_config.php';

// Ambil teks yang disorot
$highlightedText = $_POST['text'] ?? '';
$textContent = $_POST['fullText'] ?? '';

if ($highlightedText && $textContent) {
    // Temukan semua indeks kemunculan kata yang disorot
    $indices = [];
    $offset = 0;

    while (($pos = stripos($textContent, $highlightedText, $offset)) !== false) {
        $indices[] = $pos;
        $offset = $pos + 1; // Lanjutkan pencarian dari posisi berikutnya
    }

    if (count($indices) > 0) {
        // Ubah indeks menjadi string yang dipisahkan koma
        $indicesStr = implode(',', $indices);
        
        // Siapkan query untuk menyimpan data
        $stmt = $pdo->prepare("INSERT INTO highlighted_texts (text, indices) VALUES (?, ?)");
        if ($stmt->execute([$highlightedText, $indicesStr])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Text not found in full text']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No text provided']);
}

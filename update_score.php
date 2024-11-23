<?php
$dsn = 'mysql:host=localhost;dbname=mcodi3286_clickdb;charset=utf8mb4';
$username = 'mcodi3286_clickdb';
$password = 'Marcellfd321';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Ambil ip client
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function getUserCountry($ip) {
    // Ngambil api buat nentuin negaranya
    $response = @file_get_contents("http://ip-api.com/json/{$ip}");
    $data = json_decode($response, true);

    if ($response && isset($data['country'])) {
        return $data['country'];
    }

    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $countryByLang = getCountryByLanguage($lang);
        if ($countryByLang) {
            return $countryByLang;
        }
    }

    return 'Default Country';
}

function getCountryByLanguage($lang) {
    $languageMap = [
        'en' => 'United States',
        'es' => 'Spain',
        'fr' => 'France',
        'de' => 'Germany',
        'zh' => 'China',
        'ja' => 'Japan',
        'id' => 'Indonesia',
        'pt' => 'Brazil',
        'ar' => 'Saudi Arabia',
    ];
    return $languageMap[$lang] ?? null;
}

$input = json_decode(file_get_contents('php://input'), true);
$score = 1;
$ip = getClientIP();
$country = getUserCountry($ip);

//cek apakah negara nya sudah ada jika belum akan memasukan data baru
try {
    $stmt = $pdo->prepare('
        INSERT INTO leaderboard (country, score)
        VALUES (:country, :score)
        ON DUPLICATE KEY UPDATE score = score + :score
    ');
    $stmt->execute([':country' => $country, ':score' => $score]);

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} 
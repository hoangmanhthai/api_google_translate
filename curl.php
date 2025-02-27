<?php
// Check if all required parameters are provided
if (!isset($_GET['text']) || !isset($_GET['from']) || !isset($_GET['to'])) {
    die(json_encode(['error' => 'Missing required parameters']));
}

$text = $_GET['text']; // Content to translate
$from = $_GET['from']; // Source language
$to = $_GET['to'];     // Target language

/**
 * Translates text using Google Translate API
 * 
 * @param string $text Text to translate
 * @param string $from Source language code
 * @param string $to Target language code
 * @return string|false Translated text or false on failure
 */
function translate($text, $from, $to) {
    // Validate input
    if (empty($text) || empty($from) || empty($to)) {
        return false;
    }
    
    $url = "http://translate.google.com/translate_a/single?client=gtx&dt=t&ie=UTF-8&oe=UTF-8&sl=$from&tl=$to&q=" . urlencode($text);
    
    // Initialize cURL with optimal settings
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36',
        CURLOPT_HEADER => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 20
    ]);
    
    // Execute request
    $result = curl_exec($ch);
    
    // Check for errors
    if (curl_errno($ch)) {
        curl_close($ch);
        return false;
    }
    
    curl_close($ch);
    
    // Parse JSON response
    $result = json_decode($result, true);
    
    // Extract translated text
    if (!empty($result) && isset($result[0])) {
        $translation = '';
        foreach ($result[0] as $segment) {
            if (isset($segment[0])) {
                $translation .= $segment[0];
            }
        }
        return $translation;
    }
    
    return false;
}

// Get translation and handle response
$translation = translate($text, $from, $to);

// Set content type header
header('Content-Type: application/json; charset=utf-8');

// Return response
if ($translation !== false) {
    echo json_encode(['success' => true, 'translation' => $translation]);
} else {
    echo json_encode([
        'success' => false, 
        'error' => 'Translation failed',
        'example' => 'https://yourdomain.com/api.php?text=hello&from=en&to=vi'
    ]);
}

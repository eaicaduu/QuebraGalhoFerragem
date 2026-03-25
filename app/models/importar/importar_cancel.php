<?php
session_start();
header('Content-Type: application/json');

unset(
    $_SESSION['import_headers'],
    $_SESSION['import_rows'],
    $_SESSION['import_preview_produtos'],
    $_SESSION['import_arquivo_nome']
);

echo json_encode([
    'status' => true
]);
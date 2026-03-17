<?php
session_start();

unset(
    $_SESSION['import_headers'],
    $_SESSION['import_rows'],
    $_SESSION['import_preview_produtos'],
    $_SESSION['import_arquivo_nome'],
    $_SESSION['import_erro']
);

header('Location: ../../../admin.php?page=importar&acao=importar');
exit;
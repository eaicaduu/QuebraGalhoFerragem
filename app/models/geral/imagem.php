<?php

function obterImagem(
    ?string $caminho,
    string $imagemPadrao,
    string $pastaBase = 'app/',
    array $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp']
): string {
    if (empty($caminho) || !is_string($caminho)) {
        return $imagemPadrao;
    }

    $caminho = trim(str_replace('\\', '/', $caminho));
    $imagemPadrao = trim(str_replace('\\', '/', $imagemPadrao));
    $pastaBase = trim(str_replace('\\', '/', $pastaBase));

    if ($pastaBase === '') {
        return $imagemPadrao;
    }

    if (!str_ends_with($pastaBase, '/')) {
        $pastaBase .= '/';
    }

    if (preg_match('/^(https?:)?\/\//i', $caminho)) {
        return $imagemPadrao;
    }

    if (str_contains($caminho, "\0") || str_contains($caminho, '..')) {
        return $imagemPadrao;
    }

    $caminho = ltrim($caminho, '/');
    $caminho = preg_replace('#/+#', '/', $caminho);

    if ($caminho === '' || $caminho === $imagemPadrao) {
        return $imagemPadrao;
    }

    $extensao = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
    if ($extensao === '' || !in_array($extensao, $extensoesPermitidas, true)) {
        return $imagemPadrao;
    }

    $caminhoPublico = str_starts_with($caminho, $pastaBase)
        ? $caminho
        : $pastaBase . $caminho;

    $caminhoPublico = preg_replace('#/+#', '/', $caminhoPublico);

    $raizProjeto = realpath(__DIR__ . '/../../../');

    if ($raizProjeto === false) {
        return $imagemPadrao;
    }

    $caminhoFisico = $raizProjeto . '/' . $caminhoPublico;

    if (!is_file($caminhoFisico)) {
        return $imagemPadrao;
    }

    return $caminhoPublico;
}
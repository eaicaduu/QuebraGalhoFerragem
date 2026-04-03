<?php
$nomeSite = "Quebra Galho Ferragem";
$urlSite = "https://quebragalhoferragem.com";
$emailContato = "quebragalhoferragem.sc@gmail.com";
$dataAtualizacao = "03/04/2026";
?>

<style>

    h1,
    h2 {
        color: #222;
    }

    h1 {
        margin-bottom: 10px;
    }

    h2 {
        margin-top: 30px;
    }

    p {
        color: #555;
        line-height: 1.6;
    }

    ul {
        color: #555;
    }

    .data {
        font-size: 14px;
        color: #888;
        margin-bottom: 20px;
    }

    .destaque {
        background: #f1f3f5;
        padding: 15px;
        border-left: 4px solid #000;
        border-radius: 6px;
    }
</style>

<h1>Termos de Uso</h1>
<div class="data">Última atualização: <?= $dataAtualizacao ?></div>

<p>
    Bem-vindo ao <strong><?= $nomeSite ?></strong>. Ao acessar e utilizar este site, você concorda com os presentes Termos de Uso. Caso não concorde com qualquer condição, recomendamos que não utilize nossos serviços.
</p>

<h2>1. Sobre o site</h2>
<p>
    O <?= $nomeSite ?> é uma plataforma destinada à apresentação e comercialização de produtos. As informações exibidas têm caráter informativo e podem ser alteradas sem aviso prévio.
</p>

<h2>2. Cadastro e responsabilidade do usuário</h2>
<p>
    Ao se cadastrar em nosso site, você se compromete a fornecer informações verdadeiras e atualizadas.
</p>

<ul>
    <li>Manter a confidencialidade de seus dados de acesso;</li>
    <li>Não compartilhar sua conta com terceiros;</li>
    <li>Ser responsável por todas as atividades realizadas em sua conta.</li>
</ul>

<h2>3. Produtos e informações</h2>
<p>
    Nos esforçamos para manter todas as informações corretas, porém não garantimos que descrições, preços ou imagens estejam sempre livres de erros.
</p>

<div class="destaque">
    Os produtos apresentados podem sofrer alterações de preço, estoque ou especificações sem aviso prévio.
</div>

<h2>4. Uso permitido</h2>
<p>Você concorda em utilizar o site apenas para fins legais, sendo proibido:</p>

<ul>
    <li>Praticar atividades ilegais;</li>
    <li>Tentar acessar áreas restritas do sistema;</li>
    <li>Comprometer a segurança do site;</li>
    <li>Utilizar o sistema para fraudes.</li>
</ul>

<h2>5. Propriedade intelectual</h2>
<p>
    Todo o conteúdo do site, incluindo imagens, textos, logotipos e código, é protegido por direitos autorais e não pode ser utilizado sem autorização.
</p>

<h2>6. Limitação de responsabilidade</h2>
<p>
    O <?= $nomeSite ?> não se responsabiliza por:
</p>

<ul>
    <li>Erros técnicos ou indisponibilidade do sistema;</li>
    <li>Danos causados por uso indevido da plataforma;</li>
    <li>Decisões tomadas com base nas informações do site.</li>
</ul>

<h2>7. Privacidade</h2>
<p>
    Seus dados são tratados conforme nossa política de privacidade. Ao utilizar o site, você concorda com a coleta e uso de informações conforme descrito.
</p>

<h2>8. Alterações nos termos</h2>
<p>
    Podemos atualizar estes termos a qualquer momento. Recomendamos que você revise esta página periodicamente.
</p>

<h2>9. Contato</h2>
<p>
    Em caso de dúvidas, entre em contato conosco:
</p>

<ul>
    <li>Email: <?= $emailContato ?></li>
    <li>Site: <?= $urlSite ?></li>
</ul>
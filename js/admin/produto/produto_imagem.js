document.addEventListener('DOMContentLoaded', function () {
    const btnRemover = document.getElementById('btnRemoverImagem');
    const inputFile = document.getElementById('imagem');
    const inputUrl = document.getElementById('imagem_url');
    const preview = document.getElementById('previewImagem');

    const imagemPadrao = 'images/default.png';

    if (!preview) return;

    function atualizarBotaoRemover() {
        if (!btnRemover) return;

        const temImagem = preview.src && !preview.src.includes(imagemPadrao);

        btnRemover.disabled = !temImagem;

        btnRemover.classList.toggle('btn-danger', temImagem);
        btnRemover.classList.toggle('btn-secondary', !temImagem);
    }

    inputFile?.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];

            if (!file.type.startsWith('image/')) {
                preview.src = imagemPadrao;
                alert('Arquivo inválido. Envie uma imagem.');
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                atualizarBotaoRemover();
            };

            reader.readAsDataURL(file);
        }
    });

    inputUrl?.addEventListener('input', function () {
        const url = this.value.trim();

        if (url === '') {
            return;
        }

        try {
            new URL(url);
        } catch (e) {
            preview.src = imagemPadrao;
            atualizarBotaoRemover();
            return;
        }

        const img = new Image();

        img.onload = function () {
            preview.src = url;
            atualizarBotaoRemover();
        };

        img.onerror = function () {
            preview.src = imagemPadrao;
            atualizarBotaoRemover();

            preview.alt = 'Imagem inválida';
        };

        const erroImagem = document.getElementById('erroImagem');

        img.onerror = function () {
            preview.src = imagemPadrao;
            erroImagem.style.display = 'block';
        };

        img.onload = function () {
            preview.src = url;
            erroImagem.style.display = 'none';
        };


        img.src = url;
    });

    btnRemover?.addEventListener('click', function () {
        preview.src = imagemPadrao;
        inputFile.value = '';
        inputUrl.value = '';
        document.getElementById('remover_imagem').value = "1";

        atualizarBotaoRemover();
    });

    preview.onerror = function () {
        preview.src = imagemPadrao;
        preview.alt = 'Imagem inválida';
        atualizarBotaoRemover();
    };

    atualizarBotaoRemover();
});
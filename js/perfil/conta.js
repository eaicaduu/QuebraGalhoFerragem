document.addEventListener('DOMContentLoaded', () => {
    const campos = ['nome', 'email', 'telefone'];

    const telefoneInput = document.getElementById('telefone');

    if (telefoneInput) {
        telefoneInput.addEventListener('input', (e) => {
            e.target.value = formatarTelefone(e.target.value);
        });
    }

    campos.forEach(campo => {
        const input = document.getElementById(campo);

        if (!input) return;

        const checkIcon = input.parentElement.querySelector('.check-icon');
        const valorOriginal = input.value;

        input.dataset.originalValue = valorOriginal;

        input.addEventListener('input', () => {
            if (input.value.trim() !== input.dataset.originalValue) {
                checkIcon.classList.add('show');
            } else {
                checkIcon.classList.remove('show');
            }
        });
    });
});

function salvarCampo(campo) {
    const input = document.getElementById(campo);
    const status = document.getElementById('statusUpdate');
    let valor = input.value.trim();

    if ((campo === 'nome' || campo === 'email') && !valor) {
        input.classList.add('is-invalid');
        status.style.display = 'block';
        status.classList.remove('text-success');
        status.classList.add('text-danger');
        status.textContent = `${window.formatarMaiusucla(campo)} não pode ficar vazio.`;
        return;
    } else {
        input.classList.remove('is-invalid');
    }

    const dados = {};
    dados[campo] = valor;

    fetch('./app/models/perfil/conta.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(dados),
        credentials: 'same-origin'
    })
        .then(res => res.json())
        .then(data => {
            status.style.display = 'block';
            const checkIcon = input.parentElement.querySelector('.check-icon');

            if (data.success) {
                status.classList.remove('text-danger');
                status.classList.add('text-success');
                status.textContent = `${window.formatarMaiusucla(campo)} atualizado com sucesso!`;

                input.dataset.originalValue = valor;
                checkIcon.classList.remove('show');
            } else {
                status.classList.remove('text-success');
                status.classList.add('text-danger');
                status.textContent = data.message || `Erro ao atualizar ${window.formatarMaiusucla(campo)}.`;
            }

            setTimeout(() => { status.style.display = 'none'; }, 2500);
        })
        .catch(err => {
            status.style.display = 'block';
            status.classList.remove('text-success');
            status.classList.add('text-danger');
            status.textContent = `Erro na requisição: ${err}`;
            console.error(err);
        });
}
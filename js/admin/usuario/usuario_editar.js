async function editarUsuario(btn) {
    const id = btn.getAttribute('data-id');
    const tipoAtual = btn.getAttribute('data-tipo') || '1';

    const result = await Swal.fire({
        html: `
            <div style="display:flex; justify-content:space-between; align-items:center; width:100%; margin-bottom:15px;">
                <h4 class="mb-0">Alterar</h4>
                <button type="button" onclick="Swal.close()" style="background:none; border:0; font-size:20px; cursor:pointer;">
                    <i class="fa fa-times text-dark" aria-hidden="true"></i>
                </button>
            </div>

            <div class="text-dark text-start" style="max-width:400px; margin:auto;">
                <div class="mb-3">
                    <label for="swalTipoUsuario" class="form-label">Tipo de usuário</label>
                    <select id="swalTipoUsuario" class="form-select">
                        <option value="1" ${String(tipoAtual) === '1' ? 'selected' : ''}>Cliente</option>
                        <option value="2" ${parseInt(tipoAtual, 10) >= 2 ? 'selected' : ''}>Admin</option>
                    </select>
                </div>

                <div id="statusEditarUsuario" class="mt-2 small text-center d-none"></div>
            </div>
        `,
        background: '#ffffff',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Salvar',
        cancelButtonText: 'Cancelar',
        focusConfirm: false,
        didOpen: () => {
            const popup = Swal.getPopup();
            const title = popup?.querySelector('.swal2-title');
            if (title) {
                title.style.display = 'none';
            }
        },
        preConfirm: async () => {
            const selectTipo = document.getElementById('swalTipoUsuario');
            const status = document.getElementById('statusEditarUsuario');

            if (!selectTipo || !selectTipo.value) {
                if (status) {
                    status.className = 'mt-2 small text-center text-danger';
                    status.textContent = 'Selecione o tipo de usuário.';
                }
                return false;
            }

            try {
                const response = await fetch('./app/models/usuario/usuario_editar.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        id: id,
                        tipo_usuario: selectTipo.value
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.status) {
                    throw new Error(data?.mensagem || 'Erro ao atualizar usuário.');
                }

                return data;

            } catch (error) {
                if (status) {
                    status.className = 'mt-2 small text-center text-danger';
                    status.textContent = error.message || 'Erro ao atualizar usuário.';
                }
                return false;
            }
        }
    });

    if (result.isConfirmed && result.value) {
        await Swal.fire({
            icon: 'success',
            title: 'Atualizado',
            text: result.value.mensagem || 'Tipo de usuário atualizado com sucesso.'
        });

        window.location.reload();
    }
}
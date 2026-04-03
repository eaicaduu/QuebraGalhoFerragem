window.abrirLogoutSwal = function () {
    Swal.fire({
        html: `
            <div style="display:flex; justify-content:space-between; align-items:center; width:100%; margin-bottom:15px;">
                <h4 class="mb-0">Sair da Conta</h4>
                <button type="button" onclick="Swal.close()" style="background:none; border: 0; font-size:20px; cursor:pointer;">
                    <i class="fa fa-times text-dark" aria-hidden="true"></i>
                </button>
            </div>
            <h5 class="p-4 text-center">
                Tem certeza que deseja sair da sua conta?
            </h5>
            <div class="d-flex gap-2 justify-content-center">
                <button id="btnCancelarLogout" class="btn btn-secondary">
                    <i class="fa fa-ban me-1"></i> Cancelar
                </button>
                <button id="btnConfirmLogout" class="btn btn-danger">
                    <i class="fa fa-right-from-bracket me-1"></i> Sair
                </button>
            </div>
        `,
        background: '#ffffff',
        showConfirmButton: false,
        showCancelButton: false,
        didOpen: () => {
            const btnCancelar = Swal.getPopup().querySelector('#btnCancelarLogout');
            const btnConfirm = Swal.getPopup().querySelector('#btnConfirmLogout');

            btnCancelar.addEventListener('click', () => {
                Swal.close();
            });

            btnConfirm.addEventListener('click', () => {
                window.location.href = './app/models/perfil/logout.php';
            });
        }
    });
}
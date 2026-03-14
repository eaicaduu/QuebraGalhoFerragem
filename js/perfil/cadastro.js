window.abrirCadastroSwal = function () {
    Swal.fire({
        html: `
            <div style="display:flex; justify-content:space-between; align-items:center; width:100%; margin-bottom:15px;">
                <h4 class="mb-0">Cadastrar Conta</h4>
                <button type="button" onclick="Swal.close()" style="background:none; border:none; font-size:20px; cursor:pointer;">
                    <i class="fa fa-times text-dark" aria-hidden="true"></i>
                </button>
            </div>
            <form id="cadastroForm" class="d-flex flex-column align-items-center px-4 text-dark" style="max-width:400px; margin:auto; border-radius:12px;">
                <div class="mb-3 w-100">
                    <label for="nome" class="form-label">Nome completo</label>
                    <input type="text" id="nome" class="form-control rounded-4" placeholder="Informe seu nome">
                </div>

                <div class="mb-3 w-100">
                    <label for="emailCadastro" class="form-label">Email</label>
                    <input type="email" id="emailCadastro" class="form-control rounded-4" placeholder="Informe seu email">
                </div>

                <label for="senhaCadastro" class="form-label">Senha</label>
                <div class="mb-3 w-100 position-relative">
                    <input type="password" id="senhaCadastro" class="form-control rounded-4" placeholder="Informe sua senha" style="padding-right:40px;">
                    <span id="toggleSenhaCadastro" style="position: absolute; top:0; bottom:0; right:12px; display:flex; align-items:center; cursor:pointer;">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </span>
                </div>

                <button type="button" id="btnCadastrar" class="btn btn-warning fw-bold w-100 rounded-4" style="border:none; padding:10px;">Cadastrar</button>

                <p style="margin-top:10px; font-size:1rem;">Já possui uma conta? <a onclick="abrirLoginSwal()" style="cursor:pointer;" class="text-primary">Acesse</a></p>
                <p style="font-size:0.6rem; color:gray;">Ao se cadastrar, você concorda com o <a style="color:gray;">termo de uso</a></p>
            </form>
        `,
        background: '#ffffff',
        showConfirmButton: false,
        showCancelButton: false,
        didOpen: () => {
            const toggleSenhaCadastro = Swal.getPopup().querySelector('#toggleSenhaCadastro');
            const senhaCadastroInput = Swal.getPopup().querySelector('#senhaCadastro');
            const iconCadastro = toggleSenhaCadastro.querySelector('i');

            toggleSenhaCadastro.addEventListener('click', () => {
                if (senhaCadastroInput.type === 'password') {
                    senhaCadastroInput.type = 'text';
                    iconCadastro.classList.remove('fa-eye-slash');
                    iconCadastro.classList.add('fa-eye');
                } else {
                    senhaCadastroInput.type = 'password';
                    iconCadastro.classList.remove('fa-eye');
                    iconCadastro.classList.add('fa-eye-slash');
                }
            });

            const btnCadastrar = Swal.getPopup().querySelector('#btnCadastrar');
            const inputs = Swal.getPopup().querySelectorAll('#nome, #emailCadastro, #senhaCadastro');

            inputs.forEach((input, index) => {

                input.addEventListener('keydown', function (e) {

                    if (e.key === 'Enter') {

                        e.preventDefault();

                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        } else {
                            btnCadastrar.click();
                        }

                    }

                });

            });
            btnCadastrar.addEventListener('click', () => {

                if (!nome || !email || !senha) {
                    Swal.showValidationMessage('Por favor, preencha todos os campos.');
                    return;
                }

                if (!email.includes('@')) {
                    Swal.showValidationMessage('Informe um email válido.');
                    return;
                }

                if (senha.length < 4) {
                    Swal.showValidationMessage('A senha precisa ter no mínimo 4 caracteres');
                    return;
                }

                Swal.showLoading();

                fetch('./app/models/perfil/cadastro.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nome, email, senha }),
                    credentials: 'same-origin'
                })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cadastro realizado!',
                                text: 'Sua conta foi criada com sucesso.',
                            }).then(() => {
                                fetch('./app/models/perfil/login.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: new URLSearchParams({ email, senha })
                                }).then(response => response.json())
                                    .then(loginData => {
                                        if (loginData.success) {
                                            window.location.reload();
                                        }
                                    });
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro no cadastro',
                                text: data.message || 'Não foi possível concluir o cadastro.',
                            });
                        }
                    })
                    .catch(() => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro no servidor',
                            text: 'Ocorreu um erro ao enviar os dados.',
                        });
                    });
            });
        }
    });
}

window.abrirLoginSwal = function () {
    Swal.fire({
        html: `
            <div style="display:flex; justify-content:space-between; align-items:center; width:100%; margin-bottom:15px;">
                <h4 class="mb-0">Acessar Conta</h4>
                <button type="button" onclick="Swal.close()" style="background:none; border:none; font-size:20px; cursor:pointer;">
                    <i class="fa fa-times text-dark" aria-hidden="true"></i>
                </button>
            </div>
            <form id="loginForm" class="d-flex flex-column align-items-center px-4 text-dark" style="max-width:400px; margin:auto; border-radius:12px;">
                <div class="mb-3 w-100">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                    <input type="email" id="email" class="form-control rounded-4" placeholder="Informe o seu email">
                    </div>
                </div>
            </div>

            <label for="senha" class="form-label">Senha</label>
            <div class="mb-3 w-100 position-relative">
                <input type="password" id="senha" class="form-control rounded-4" placeholder="Informe a sua senha" style="padding-right:40px;">
                <span id="toggleSenha" style="position: absolute; top:0; bottom:0; right:12px; display:flex; align-items:center; cursor:pointer;">
                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                </span>
            </div>

            <a href="#" id="recuperarSenha" class="text-dark text-decoration-none text-end" style="display:block; font-size:0.9rem; margin-bottom:15px;">Esqueci a senha</a>
                
            <button type="button" id="btnAcessar" class="btn btn-warning swal2-confirm fw-bold w-100 rounded-4" style="border:none; padding:10px;">Acessar</button>

                <p style="margin-top:10px; font-size:1rem;">Não possui uma conta? <a onclick="abrirCadastroSwal()" style="cursor:pointer;" class="text-primary">Cadastre-se</a></p>
                <p style="font-size:0.6rem; color:gray;">Acessando você concorda com o <a style="color:gray;">termo de uso</a></p>
            </form>
        `,
        background: '#ffffff',
        showConfirmButton: false,
        showCancelButton: false,
        didOpen: () => {

            const emailInput = Swal.getPopup().querySelector('#email');
            const senhaInput = Swal.getPopup().querySelector('#senha');
            const toggleSenha = Swal.getPopup().querySelector('#toggleSenha');
            const icon = toggleSenha.querySelector('i');
            const btnAcessar = Swal.getPopup().querySelector('#btnAcessar');
            const recuperarSenha = Swal.getPopup().querySelector('#recuperarSenha');

            toggleSenha.addEventListener('click', (e) => {
                e.preventDefault();

                if (senhaInput.type === 'password') {
                    senhaInput.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    senhaInput.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });

            recuperarSenha.addEventListener('click', (e) => {
                e.preventDefault();
                abrirRecuperarSenhaSwal();
            });

            btnAcessar.addEventListener('click', () => {
                const email = emailInput.value.trim();
                const senha = senhaInput.value.trim();

                if (!email.includes('@')) {
                    Swal.showValidationMessage('Informe um email válido.');
                    return;
                }

                if (!email || !senha) {
                    Swal.showValidationMessage('Por favor, preencha seu número e senha.');
                    return;
                }

                Swal.showLoading();

                fetch('./app/models/perfil/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ email, senha })
                })
                    .then(response => response.json())
                    .then(data => {
                        Swal.hideLoading();
                        if (data.success) {
                            window.location.reload();
                        } else {
                            let mensagem = '';
                            switch (data.error) {
                                case 'dados_incompletos': mensagem = 'Preencha todos os campos.'; break;
                                case 'erro_conexao': mensagem = 'Erro de conexão com o banco.'; break;
                                case 'email': mensagem = 'Email não encontrado.'; break;
                                case 'senha': mensagem = 'Senha incorreta.'; break;
                                default: mensagem = 'Erro desconhecido.';
                            }
                            Swal.showValidationMessage(mensagem);

                        }
                    })
                    .catch(error => {
                        Swal.hideLoading();
                        Swal.showValidationMessage('Ocorreu um erro na requisição.');
                        console.error(error);
                    });
            });
        }
    });
/*     function abrirRecuperarSenhaSwal() {
        const numeroAdm = '5551985707136';
        const linkWhats = `https://wa.me/${numeroAdm}?text=Olá!%20Preciso%20recuperar%20minha%20senha.`

        Swal.fire({
            icon: 'info',
            title: 'Recuperar senha',
            html: `
            <p style="font-size:1rem; color:#333; margin-bottom:20px;">
                Contate o administrador para recuperar sua senha.
            </p>
            <a href="${linkWhats}" target="_blank" class="btn btn-success w-100 rounded-4 fw-bold" style="text-decoration:none; padding:10px;">
                <i class="fa-solid fa-commenting" aria-hidden="true"></i>
                 Entrar em contato via WhatsApp
            </a>
        `,
            showConfirmButton: false,
            background: '#fff',
        });
    } */
}

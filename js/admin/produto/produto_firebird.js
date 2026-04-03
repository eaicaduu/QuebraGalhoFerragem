document.addEventListener('DOMContentLoaded', function () {
    const btnAtualizar = document.getElementById('btnImportarFirebird');
    const btnFiltro = document.getElementById('btnFiltroFirebird');

    let filtroAtivo = localStorage.getItem('firebirdFiltroAtivo') || 'todos';
    let filtroPercentual = localStorage.getItem('firebirdFiltroPercentual') === '1';
    let filtroGtin = localStorage.getItem('firebirdFiltroGtin') || 'todos';
    let filtroEstoqueMin = localStorage.getItem('firebirdFiltroEstoqueMin') || '';
    let filtroEstoqueMax = localStorage.getItem('firebirdFiltroEstoqueMax') || '';

    async function carregarBancoFirebird() {
        try {
            const response = await fetch('./app/models/produto/produto_firebird.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    filtro_ativo: filtroAtivo,
                    filtro_percentual: filtroPercentual ? '1' : '0',
                    filtro_gtin: filtroGtin,
                    filtro_estoque_min: filtroEstoqueMin,
                    filtro_estoque_max: filtroEstoqueMax
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data?.erro || 'Erro ao carregar produtos do Firebird.');
            }

            window.location.reload();
        } catch (error) {
            console.error('Erro ao carregar Firebird:', error);

            await Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: error.message || 'Erro ao carregar produtos.'
            });
        }
    }

    btnAtualizar?.addEventListener('click', function (e) {
        e.preventDefault();
        carregarBancoFirebird();
    });

    btnFiltro?.addEventListener('click', async function (e) {
        e.preventDefault();

        const result = await Swal.fire({
            html: `
                <div style="display:flex; justify-content:space-between; align-items:center; width:100%; margin-bottom:15px;">
                    <h4 class="mb-0">Filtrar</h4>
                    <button type="button" onclick="Swal.close()" style="background:none; border:0; font-size:20px; cursor:pointer;">
                        <i class="fa fa-times text-dark" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="text-dark text-start" style="max-width:400px; margin:auto;">
                    <div class="mb-3">
                        <label class="form-label">Status</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filtroFirebird" id="filtroTodos" value="todos"
                                ${filtroAtivo === 'todos' ? 'checked' : ''}>
                            <label class="form-check-label" for="filtroTodos">Todos</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filtroFirebird" id="filtroAtivos" value="ativos"
                                ${filtroAtivo === 'ativos' ? 'checked' : ''}>
                            <label class="form-check-label" for="filtroAtivos">Ativos</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filtroFirebird" id="filtroInativos" value="inativos"
                                ${filtroAtivo === 'inativos' ? 'checked' : ''}>
                            <label class="form-check-label" for="filtroInativos">Inativos</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="filtroPercentual"
                                ${filtroPercentual ? 'checked' : ''}>
                            <label class="form-check-label" for="filtroPercentual">
                                Filtrar por %
                            </label>
                        </div>
                        <div class="text-muted mt-1" style="font-size: 13px;">
                            Mostra somente produtos que tenham % na descrição.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">GTIN</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filtroGtin" id="filtroGtinTodos" value="todos"
                                ${filtroGtin === 'todos' ? 'checked' : ''}>
                            <label class="form-check-label" for="filtroGtinTodos">Todos</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filtroGtin" id="filtroGtinCom" value="com_gtin"
                                ${filtroGtin === 'com_gtin' ? 'checked' : ''}>
                            <label class="form-check-label" for="filtroGtinCom">Com GTIN</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filtroGtin" id="filtroGtinSem" value="sem_gtin"
                                ${filtroGtin === 'sem_gtin' ? 'checked' : ''}>
                            <label class="form-check-label" for="filtroGtinSem">Sem GTIN</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estoque</label>

                        <div class="row g-2">
                            <div class="col-6">
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="filtroEstoqueMin"
                                    class="form-control"
                                    placeholder="Estoque mín."
                                    value="${filtroEstoqueMin}">
                            </div>

                            <div class="col-6">
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="filtroEstoqueMax"
                                    class="form-control"
                                    placeholder="Estoque máx."
                                    value="${filtroEstoqueMax}">
                            </div>
                        </div>

                        <div class="text-muted mt-1" style="font-size: 13px;">
                            Filtra produtos pela faixa de estoque atual.
                        </div>
                    </div>

                    <div id="statusFiltroFirebird" class="mt-2 small text-center d-none"></div>
                </div>
            `,
            background: '#ffffff',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: 'Aplicar',
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
                const selecionado = document.querySelector('input[name="filtroFirebird"]:checked');
                const selecionadoGtin = document.querySelector('input[name="filtroGtin"]:checked');
                const checkboxPercentual = document.getElementById('filtroPercentual');
                const inputEstoqueMin = document.getElementById('filtroEstoqueMin');
                const inputEstoqueMax = document.getElementById('filtroEstoqueMax');
                const status = document.getElementById('statusFiltroFirebird');

                if (!selecionado) {
                    if (status) {
                        status.className = 'mt-2 small text-center text-danger';
                        status.textContent = 'Selecione o status.';
                    }
                    return false;
                }

                if (!selecionadoGtin) {
                    if (status) {
                        status.className = 'mt-2 small text-center text-danger';
                        status.textContent = 'Selecione o filtro de GTIN.';
                    }
                    return false;
                }

                try {
                    filtroAtivo = selecionado.value;
                    filtroGtin = selecionadoGtin.value;
                    filtroPercentual = checkboxPercentual?.checked === true;
                    filtroEstoqueMin = inputEstoqueMin?.value?.trim() || '';
                    filtroEstoqueMax = inputEstoqueMax?.value?.trim() || '';

                    localStorage.setItem('firebirdFiltroAtivo', filtroAtivo);
                    localStorage.setItem('firebirdFiltroGtin', filtroGtin);
                    localStorage.setItem('firebirdFiltroPercentual', filtroPercentual ? '1' : '0');
                    localStorage.setItem('firebirdFiltroEstoqueMin', filtroEstoqueMin);
                    localStorage.setItem('firebirdFiltroEstoqueMax', filtroEstoqueMax);

                    const response = await fetch('./app/models/produto/produto_firebird.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            filtro_ativo: filtroAtivo,
                            filtro_gtin: filtroGtin,
                            filtro_percentual: filtroPercentual ? '1' : '0',
                            filtro_estoque_min: filtroEstoqueMin,
                            filtro_estoque_max: filtroEstoqueMax
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data?.erro || 'Erro ao aplicar filtro.');
                    }

                    return data;
                } catch (error) {
                    if (status) {
                        status.className = 'mt-2 small text-center text-danger';
                        status.textContent = error.message || 'Erro ao aplicar filtro.';
                    }
                    return false;
                }
            }
        });

        if (result.isConfirmed && result.value) {
            window.location.reload();
        }
    });

    const inputPesquisar = document.getElementById('inputPesquisarFirebird');
    const itens = document.querySelectorAll('.item-produto-firebird');

    if (!inputPesquisar || !itens.length) {
        return;
    }

    function normalizarTexto(texto) {
        return (texto || '')
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim();
    }

    function pesquisarProdutosFirebird() {
        const termo = normalizarTexto(inputPesquisar.value);
        let visiveis = 0;

        itens.forEach(function (item) {
            const radio = item.querySelector('input[type="radio"]');

            const textoBusca = normalizarTexto([
                radio?.dataset.codigo || '',
                radio?.dataset.descricao || '',
                radio?.dataset.referencia || '',
                radio?.dataset.nome || '',
                radio?.dataset.fornecedor || '',
                radio?.dataset.medida || '',
                radio?.dataset.preco || '',
                radio?.dataset.custo || '',
                radio?.dataset.estoque || '',
                radio?.dataset.estoqueMinimo || '',
                radio?.dataset.ativo === '1' ? 'ativo' : 'inativo'
            ].join(' '));

            const mostrar = termo === '' || textoBusca.includes(termo);

            item.style.display = mostrar ? '' : 'none';

            if (mostrar) {
                visiveis++;
            }
        });

        let aviso = document.getElementById('nenhumResultadoFirebird');

        if (visiveis === 0) {
            if (!aviso) {
                aviso = document.createElement('div');
                aviso.id = 'nenhumResultadoFirebird';
                aviso.className = 'alert bg-body-secondary text-center mt-2 mb-0';
                aviso.textContent = 'Nenhum produto encontrado na pesquisa.';
                inputPesquisar.closest('.card-body')?.appendChild(aviso);
            }
        } else if (aviso) {
            aviso.remove();
        }
    }

    inputPesquisar.addEventListener('input', pesquisarProdutosFirebird);
});
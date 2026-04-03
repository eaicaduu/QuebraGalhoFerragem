document.addEventListener('DOMContentLoaded', function () {
    const lista = document.getElementById('listaProdutosFirebird');
    const contador = document.getElementById('contadorFirebird');
    const textoPaginaAtual = document.getElementById('textoPaginaAtual');
    const btnAnterior = document.getElementById('btnPaginaAnterior');
    const btnProxima = document.getElementById('btnPaginaProxima');
    const inputPesquisar = document.getElementById('inputPesquisarFirebird');

    let paginaAtual = 1;
    let porPagina = 50;
    let totalPaginas = 1;
    let timeoutPesquisa = null;

    function obterFiltrosFirebird() {
        return {
            filtro_ativo: localStorage.getItem('firebirdFiltroAtivo') || 'todos',
            filtro_percentual: localStorage.getItem('firebirdFiltroPercentual') === '1' ? '1' : '0',
            filtro_gtin: localStorage.getItem('firebirdFiltroGtin') || 'todos',
            filtro_estoque_min: localStorage.getItem('firebirdFiltroEstoqueMin') || '',
            filtro_estoque_max: localStorage.getItem('firebirdFiltroEstoqueMax') || ''
        };
    }

    function obterPesquisaFirebird() {
        const termo = (inputPesquisar?.value || '').trim();
        return termo.length >= 3 ? termo : '';
    }

    function montarCard(row) {
        const codigo = row.CODIGO || '-';
        const descricao = row.DESCRICAO || '-';
        const referencia = row.REFERENCIA && row.REFERENCIA !== '' ? row.REFERENCIA : 'SEM GTIN';
        const nome = row.NOME || 'Sem categoria';
        const fornecedor = row.FORNECEDOR || 'Sem fornecedor';
        const medida = String(row.MEDIDA || 'UN').toUpperCase();
        const preco = Number(row.PRECO || 0);
        const custo = Number(row.CUSTOCOMPR || 0);
        const estoqueAtual = Number(row.QTD_ATUAL || 0);
        const estoqueMinimo = Number(row.QTD_MINIM || 0);
        const ativo = Number(row.ATIVO || 0) === 1;

        return `
            <label class="card border-0 bg-body-secondary mb-2 item-produto-firebird" style="cursor:pointer;">
                <div class="card-body py-2 px-3">

                    <div class="d-flex align-items-center gap-2">

                        <input
                            type="radio"
                            name="produtoFirebirdSelecionado"
                            class="form-check-input mt-1 flex-shrink-0"
                            value="${String(codigo).replace(/"/g, '&quot;')}">

                        <div class="flex-grow-1 overflow-hidden" style="min-width:0;">

                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="fw-semibold text-truncate">
                                    ${codigo} - ${descricao}
                                </div>

                                <div class="text-nowrap fw-semibold ms-2">
                                    R$ ${preco.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                <div class="fw-semibold text-truncate">
                                    <span class="badge ${ativo ? 'bg-success' : 'bg-danger'}">
                                        <i class="fa fa-circle me-1"></i>
                                        ${ativo ? 'Ativo' : 'Inativo'}
                                    </span>

                                    <span class="badge ${referencia !== 'SEM GTIN' ? 'bg-dark' : 'bg-danger'}">
                                        ${referencia}
                                    </span>

                                    <span class="badge bg-dark">
                                        ${medida}
                                    </span>
                                </div>

                                <div class="text-nowrap small text-muted fw-semibold ms-2">
                                    R$ ${custo.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                                </div>
                            </div>

                            <div class="small text-muted text-truncate">
                                ${fornecedor}
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                                <div class="small text-muted text-truncate" title="Categoria">
                                    ${nome}
                                </div>

                                <div class="d-flex gap-1 flex-wrap">
                                    <span class="badge ${estoqueAtual > 0 ? 'bg-primary' : 'bg-secondary'}">
                                        <i class="fa fa-box me-1"></i>
                                        ${estoqueAtual > 0
                ? estoqueAtual.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                : '0'}
                                    </span>

                                    <span class="badge ${(estoqueMinimo > 0 && estoqueAtual <= estoqueMinimo)
                ? 'bg-warning text-dark'
                : 'bg-dark'}">
                                        ${(estoqueMinimo > 0 && estoqueAtual <= estoqueMinimo)
                ? '<i class="fa fa-exclamation-triangle me-1"></i>'
                : ''}
                                        ${estoqueMinimo > 0
                ? 'Mínimo: ' + estoqueMinimo.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                : 'Mínimo: 0,00'}
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </label>
        `;
    }

    function renderizar(rows) {
        if (!lista) return;

        if (!rows || !rows.length) {
            lista.innerHTML = `
                <div class="alert bg-body-secondary text-center mb-0">
                    Nenhum produto encontrado.
                </div>
            `;
            return;
        }

        lista.innerHTML = rows.map(montarCard).join('');
    }

    async function carregarPagina(pagina) {
        try {
            const termoPesquisa = obterPesquisaFirebird();

            if (lista) {
                lista.innerHTML = `
                    <div class="alert bg-body-secondary text-center mb-0">
                        ${termoPesquisa ? 'Pesquisando produtos...' : 'Carregando produtos...'}
                    </div>
                `;
            }

            const response = await fetch('./app/models/produto/produto_firebird.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    ...obterFiltrosFirebird(),
                    pagina: String(pagina),
                    por_pagina: String(porPagina),
                    pesquisa: termoPesquisa
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data?.erro || 'Erro ao carregar produtos.');
            }

            paginaAtual = Number(data.pagina || 1);
            totalPaginas = Number(data.total_paginas || 1);

            renderizar(Array.isArray(data.rows) ? data.rows : []);

            if (contador) {
                contador.textContent = `(${Number(data.total_banco || 0)})`;
            }

            if (textoPaginaAtual) {
                if (termoPesquisa) {
                    textoPaginaAtual.textContent = `Pesquisa: página ${paginaAtual} de ${totalPaginas}`;
                } else {
                    textoPaginaAtual.textContent = `Página ${paginaAtual} de ${totalPaginas}`;
                }
            }

            if (btnAnterior) {
                btnAnterior.disabled = paginaAtual <= 1;
            }

            if (btnProxima) {
                btnProxima.disabled = paginaAtual >= totalPaginas;
            }
        } catch (error) {
            console.error('Erro ao carregar página Firebird:', error);

            if (lista) {
                lista.innerHTML = `
                    <div class="alert alert-danger text-center mb-0">
                        ${error.message || 'Erro ao carregar produtos.'}
                    </div>
                `;
            }
        }
    }

    btnAnterior?.addEventListener('click', function () {
        if (paginaAtual > 1) {
            carregarPagina(paginaAtual - 1);
        }
    });

    btnProxima?.addEventListener('click', function () {
        if (paginaAtual < totalPaginas) {
            carregarPagina(paginaAtual + 1);
        }
    });

    inputPesquisar?.addEventListener('input', function () {
        clearTimeout(timeoutPesquisa);

        timeoutPesquisa = setTimeout(() => {
            carregarPagina(1);
        }, 400);
    });

    window.addEventListener('firebird:filtros-atualizados', function () {
        carregarPagina(1);
    });

    carregarPagina(1);
});
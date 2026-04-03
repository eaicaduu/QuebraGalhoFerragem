<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-2">

    <div>
        <div class="d-flex align-items-end justify-content-start">
            <h3 class="mb-0">Firebird</h3>
            <span id="contadorFirebird" class="text-muted small ms-2"></span>
        </div>
        <small class="text-muted text-nowrap">Visualize os produtos do firebird</small>
    </div>

    <div class="w-100 w-lg-auto text-end d-flex justify-content-end gap-2 flex-wrap">
        <a id="btnImportarFirebird" class="btn btn-dark btn-sm">
            <i class="fa fa-sync me-1"></i>Atualizar
        </a>

        <a href="admin.php?page=produto&acao=todos produtos" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i>Voltar
        </a>
    </div>

</div>

<div class="d-flex flex-column gap-3">

    <div class="shadow-sm rounded overflow-auto" style="max-height: 50vh;">
        <div class="card-body p-3">

            <div class="position-sticky top-0 z-3 bg-white pt-2 pb-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="position-relative flex-grow-1">
                        <i class="fa fa-search position-absolute top-50 translate-middle-y text-muted"
                            style="left: 14px;"></i>

                        <input type="text"
                            id="inputPesquisarFirebird"
                            data-contexto="admin"
                            class="form-control ps-5 form-control-sm"
                            placeholder="Pesquisar produtos.">
                    </div>

                    <button id="btnFiltroFirebird" class="btn btn-dark btn-sm">
                        <i class="fa fa-filter"></i>
                    </button>

                </div>
            </div>

            <div id="listaProdutosFirebird" class="d-flex flex-column gap-2 mt-2"></div>

        </div>
    </div>

    <div class="d-flex flex-column flex-lg-row justify-content-center justify-content-lg-end align-items-center mt-2 gap-2">

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-secondary btn-sm" id="btnPaginaAnterior">
                <i class="fa fa-chevron-left me-1"></i>Anterior
            </button>

            <span class="btn btn-dark btn-sm disabled" id="textoPaginaAtual">
                Página 1
            </span>

            <button type="button" class="btn btn-secondary btn-sm" id="btnPaginaProxima">
                Próxima<i class="fa fa-chevron-right ms-1"></i>
            </button>
        </div>
    </div>

</div>
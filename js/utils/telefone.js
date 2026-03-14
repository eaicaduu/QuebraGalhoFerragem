window.formatarTelefone = function (numero) {
    if (!numero) return "";
    numero = numero.replace(/\D/g, "");

    numero = numero.substring(0, 11);

    if (numero.length === 11) {
        return numero.replace(/^(\d{2})(\d)(\d{4})(\d{4})$/, "($1)$2 $3-$4");
    }

    return numero;
};

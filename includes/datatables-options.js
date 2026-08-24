let dtOptions = {
    retrieve: true,
    order: [[1, 'desc']],
    language: {
        "sProcessing":    "Processando...",
        "sLengthMenu":    "Mostrar _MENU_ registros",
        "sZeroRecords":   "Nenhum registro encontrado",
        "sEmptyTable":    "Nenhum dado disponível nesta tabela",
        "sInfo":          "Mostrando registros de _START_ a _END_ de um total de _TOTAL_ registros",
        "sInfoEmpty":     "Mostrando registros de 0 a 0 de um total de 0 registros",
        "sInfoFiltered": "(filtrado de um total de _MAX_ registros)",
        "sInfoPostFix":   "",
        "sSearch":        "Pesquisar:",
        "sUrl":           "",
        "sInfoThousands": ".",
        "sLoadingRecords": "Carregando...",
        "oPaginate": {
            "sFirst":    "Primeiro",
            "sLast":    "Último",
            "sNext":    "Próximo",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Ativar para classificar a coluna em ordem crescente",
            "sSortDescending": ": Ativar para classificar a coluna em ordem decrescente"
        }
    }
}

$(document).ready(function() {
    var table = $('#userTable').DataTable(dtOptions);
});
$(document).ready(function () {
    var tableQuot = $("#tquotations").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            {className: "text-right"},
            {className: "text-center"},
            {className: "text-center"},
            {className: "text-center"},
            {className: "text-right"},
            {className: "text-right"},
            {className: "text-right"},
            {className: "text-center"},
            {width: "80px", className: "text-center"}],
        'order': [[0, 'desc']],
        'processing': true,
        'language': {
            'processing': '<i class="fa fa-spinner fa-spin"></i>'
        },
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'finance/ajax.getServerOC.php',
            type: 'GET',
            length: 20
        }
    });
});
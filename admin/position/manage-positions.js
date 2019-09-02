$(document).ready(function () {
    var tableUsr = $("#tusers").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            null,
            null,
            {"orderable": false, width: "30px", className: "text-center"}],
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'admin/position/ajax.getServerPositions.php',
            type: 'GET',
            length: 20
        }
    });
});
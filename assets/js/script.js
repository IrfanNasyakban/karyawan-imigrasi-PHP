// Initialize DataTables
$(document).ready(function() {
    $('#tableKaryawan').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
        },
        "pageLength": 10,
        "ordering": true,
        "searching": true
    });
});

// Auto hide alerts after 5 seconds
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
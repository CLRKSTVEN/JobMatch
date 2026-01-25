/**
* Theme:  Velonic - Responsive Bootstrap 4 Admin & Dashboard
* Author: Coderthemes
* File:   Datatables
*/

  // Default Datatable
  $(document).ready(function() {

    // Default Datatable
    $('#datatable').DataTable();

    //Buttons examples
    var table = $('#datatable-buttons').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf' ]
    });

    // Key Tables
    $('#key-datatable').DataTable({
        keys: true
    });

    // Multi Selection Datatable
    $('#selection-datatable').DataTable({
        select: {
            style: 'multi'
        }
    });

    table.buttons().container()
            .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
} );


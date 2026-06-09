(function ($) {
    "use strict";

    $(document).ready(function() {
        // Toggle row highlight on click
        $(document).on('click', 'table tbody tr', function(e) {
            // Ignore clicks on buttons, links, inputs, icons, or actions inside td
            if ($(e.target).closest('a, button, input, select, textarea, label, .btn, i').length) {
                return;
            }

            const $row = $(this);
            const $table = $row.closest('table');

            // If the clicked row is already highlighted, turn it off
            if ($row.hasClass('active-row-highlight')) {
                $row.removeClass('active-row-highlight');
                // Check if there are any other active rows in the table
                if ($table.find('tbody tr.active-row-highlight').length === 0) {
                    $table.removeClass('table-has-highlighted-row');
                }
            } else {
                // Remove highlight from other rows in this table
                $table.find('tbody tr').removeClass('active-row-highlight');
                $row.addClass('active-row-highlight');
                $table.addClass('table-has-highlighted-row');
            }
        });
    });

})(jQuery);

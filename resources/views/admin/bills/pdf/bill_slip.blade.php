<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Bill Slip</title>
<style>
    * { box-sizing: border-box; }
    
    body {
        font-family: "Helvetica", "Arial", sans-serif;
        margin: 0;
        padding: 15px; /* Creates the empty space around the PDF */
        color: #000;
        background: #fff;
    }

    /* Outer double border around the entire page, exactly like the image */
    .page-border-outer {
        border: 2px solid #000;
        padding: 3px;
        height: 100%;
    }
    .page-border-inner {
        border: 1px solid #000;
        height: 100%;
        padding: 0; /* Content fills this up */
    }

    /* The actual layout table holding both slips */
    .main-table {
        width: 100%;
        border-collapse: collapse;
        height: 100%;
    }

    /* Each slip cell */
    .slip-cell {
        width: 49.9%;
        vertical-align: top;
        padding: 25px 35px; /* Gives nice breathing room inside the slip */
    }

    /* Solid light blue divider in the exact middle */
    .divider-cell {
        width: 1px;
        background-color: #8EA9DB;
        padding: 0;
    }
</style>
</head>
<body>
    <div class="page-border-outer">
        <div class="page-border-inner">
            <table class="main-table" cellspacing="0" cellpadding="0">
                <tr>
                    {{-- ====== LEFT: House Owner Copy ====== --}}
                    <td class="slip-cell">
                        @include('admin.bills.pdf.slip_content', ['copyLabel' => 'House Owner Copy'])
                    </td>

                    {{-- ====== DIVIDER ====== --}}
                    <td class="divider-cell"></td>

                    {{-- ====== RIGHT: Challan Copy ====== --}}
                    <td class="slip-cell">
                        @include('admin.bills.pdf.slip_content', ['copyLabel' => 'Challan Copy'])
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

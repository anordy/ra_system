<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WARRANT OF DISTRESS</title>
    <style>
        body {
            margin: auto;
            padding: auto;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }



        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-6 {
            width: 50%;
            flex: 0 0 auto;
        }

        .text-white {
            color: #fff;
        }

        .company-details {
            float: right;
            text-align: right;
        }

        .heading {
            font-size: 20px;
            margin-bottom: 08px;
        }

        .sub-heading {
            color: #262626;
            margin-bottom: 05px;
        }

        table {
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr {
            border: 1px solid #111;
            background-color: #f2f2f2;
        }

        .total {
            background-color: #a3a5a8;
        }

        table td {
            vertical-align: middle !important;
            text-align: center;
        }

        table th,
        table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }

        .table-width {
            width: 10px;
        }

        .table-bordered {
            box-shadow: 0px 0px 5px 0.5px gray;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .text-right {
            text-align: end;
        }

        .w-20 {
            width: 20%;
            font-size: 80%;
            font-weight: normal;
        }

        .float-right {
            float: right;
        }

        .flex-container {
            display: flex;

        }

        .flex-container>div {
            width: 40%;
            margin: 10px;
        }

        .header {
            display: block;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            font-size: 80%;
        }

        img {
            width: 150px;
            height: 150px;
        }

        .zrb-no {
            float: right;
        }
    </style>
</head>

<body>


    <div class="header">
        <div class="logo">
            <img src="{{ public_path() }}/images/logo.png" alt="">
        </div>
        <div>
            <h2>WARRANT OF DISTRESS</h2>
            <P>Revenue and Debt Managment Unit</P>
            <p>(under sec.44 of TAPA)</p>
        </div>
    </div> <br><br>

    <div>
        <div class="head-section">
            <div class="flex-container">
                <div class="zrb-no">ZRA NO.
                    <table class="table-bordered">
                        <tr>
                            <td class="table-width">Z</td>
                            <td class="table-width"></td>
                            <td class="table-width"></td>
                            <td class="table-width"></td>
                            <td class="table-width"></td>
                            <td class="table-width"></td>
                            <td class="table-width"></td>
                            <td class="table-width"></td>
                            <td class="table-width"></td>
                            <td class="table-width"></td>
                            <td class="table-width"></td>

                        </tr>
                    </table>
                </div>

            </div><br><br><br><br>

            <div class="body-section">
                <p>Date of issue............./................./20..................</p>
                <hr style="border:0.5px solid;">
                <br>
            </div>
        </div>

        <div class="body-section">
            <small>
                <b>Mr/Mrs/Ms/Mr&Mrs
                </b>..........................................................................................................................................................
                of <br><br>
                (location)..................................................................................................
                Has been
                assessed to tax penalty,fine and interest as follows:</small>
            <br> <br>
        </div>


        <div class="table-section">

            <table class="table-bordered">
                <thead>
                    <tr>
                        <th class="w-20">Tax <br /> Type</th>
                        <th class="w-20">Period </th>
                        <th class="w-20">Amount of Tax TZS/USD</th>
                        <th class="w-20">Penality TZS/USD</th>
                        <th class="w-20">Interest TZS/USD</th>
                        <th class="w-20">Fine <br /> TZS/USD</th>
                        <th class="w-20">Total TZS/USD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>

                    {{-- extra table rows are placed for visual pruporses will be replaced by actual data looped:-> using for each etc. --}}
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">&nbsp;</td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                    <tr>
                        <td class="w-20">Total</td>
                        <td class="w-20 total"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>

                </tbody>
            </table>
        </div> <br><br>

        <div class="body-section">
            <p>
                AND WHEREAS, I ……………………………………………… COMMISSIONER certify that the sum <br><br>
                detailed remain unpaid; and that the said …………………………………………………………
                now owes <br><br> the sum of TZS/USD ………………….....…........................... In respect of tax,
                penalty, fines
                and
                interest.
            </p><br>
        </div>
    </div>

</body>

</html>

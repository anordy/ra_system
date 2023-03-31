<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEMAND NOTICE</title>
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

        .dot {
            text-decoration: underline;
            text-decoration-style: dotted;
        }
    </style>
</head>

<body>


    <div class="header">
        <div class="logo">
            <img src="{{ public_path() }}/images/logo.png" alt="">
        </div>
        <div>
            <h2>DEMAND NOTICE</h2>
            <P>Revenue and Debt Managment Unit</P>
            <p>(under sec.39 of TAPA)</p>
        </div>
    </div> <br><br>

    <div>
        <div class="row">
            <div class="head-section">
                <div class="row">
                    <div class="col-6">
                        <h2 class="heading">Ref No: ..........................................................</h2>
                    </div>
                </div>
                <div class="flex-container">
                    ZRA NO. <span class="dot">{{ $tax_return->business->zin }}{{ $tax_return->location->zin }}</span>
                    <div class="txt-area">
                        <textarea name="" id="" cols="30" rows="10"></textarea>
                    </div>

                </div><br>

                <div class="body-section">
                    <p class="zrb-no"> Issuing Office: Debt Managment Section
                    </p>
                    <p>Date of issue <span class="dot">{{ $now }}</span></p>
                    <hr style="border:0.5px solid;">
                    <br>
                </div>
            </div>

            <div class="body-section">
                <small> REF: OUTSTANDING TAX LIABILITY</small> <br><br>

                <P>
                    Examination of your tax account shows that as on <span class="dot">{{ $now }}</span> a balance of {{ $tax_return->currency }}
                    <span class="dot">{{ number_format($tax_return->outstanding_amount, 2) }}</span> was owing to Zanzibar Revenue Authority. This balance is inclusive of
                    penalty, fine and/or fine for failure to pay assessed tax, fine, penalty, interest or raised an
                    objection to any assessment or to appeal within the time allowed under the Zanzibar appeal Act No. 1
                    of
                    2006.
                </P> <br>
            </div>


            <div class="table-section">

                <table class="table-bordered">
                    <thead>
                        <tr>
                            <th class="w-20">Tax <br /> Type</th>
                            <th class="w-20">Tax Assessment No.</th>
                            <th class="w-20">Period Month/Year</th>
                            <th class="w-20">Amount of Tax {{ $tax_return->currency }}</th>
                            <th class="w-20">Penalty {{ $tax_return->currency }}</th>
                            <th class="w-20">Interest {{ $tax_return->currency }}</th>
                            <th class="w-20">Fine <br /> {{ $tax_return->currency }}</th>
                            <th class="w-20">Sub-Total {{ $tax_return->currency }}</th>
                            <th class="w-20">Amout Paid {{ $tax_return->currency }}</th>
                            <th class="w-20">Balance Outstanding {{ $tax_return->currency }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="w-20">{{ $tax_return->taxtype->name }}</td>
                            <td class="w-20">N/A</td>
                            <td class="w-20">{{ Carbon\Carbon::create($tax_return->filing_due_date)->format('m Y') }}</td>
                            <td class="w-20">{{ number_format($tax_return->principal, 2) }}</td>
                            <td class="w-20">{{ number_format($tax_return->penalty, 2) }}</td>
                            <td class="w-20">{{ number_format($tax_return->interest, 2) }}</td>
                            <td class="w-20">N/A</td>
                            <td class="w-20">{{ number_format($tax_return->total_amount, 2) }}</td>
                            <td class="w-20">{{ number_format($tax_return->total_amount - $tax_return->outstanding_amount, 2) }}</td>
                            <td class="w-20">{{ number_format($tax_return->outstanding_amount, 2) }}</td>
                        </tr>

                    </tbody>
                </table>
                <small>*Tax Administration and Procedures ACT No. 7 of 2009</small>
            </div> <br><br>

            <div class="body-section">
                <p>
                    Payment of the amount owing should be made within {{ $paid_within_days }} working days, failure of which recovery
                    proceeding
                    will be instated upon you without further notice. If you disagree with the above figure(s) you are
                    advised to contact the under signed officer immediately for reconciliation..
                </p><br>
                {{-- <p>
                    Name: ................................................................
                    Designation: ..........................................................
                </p><br>
                <p>
                    Signature: ...........................................................
                    Date: ................................................................
                </p> --}}
            </div>
        </div>

</body>

</html>

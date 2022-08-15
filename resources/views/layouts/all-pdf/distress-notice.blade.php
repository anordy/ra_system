<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTICE OF DISTRESS</title>
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

        .table-section {
            margin-left: 10%;
        }

        .w-20 {
            width: 20%;
            font-size: 80%;
            font-weight: normal;
            text-align: left;
            padding: 10px;
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
            <h2>NOTICE OF DISTRESS</h2>
            <P>Revenue and Debt Managment Unit</P>
            <p>(under sec.44 of TAPA)</p>
        </div>
    </div> <br><br>

    <div>
        <div class="row">
            <div class="head-section">
                <div class="row">

                </div>
                <div class="flex-container">
                    <div class="zrb-no">ZRB NO.
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
                    <div class="txt-area">
                        <textarea name="" id="" cols="30" rows="10"></textarea>
                    </div>

                </div><br>

                <div class="body-section">
                    <p class="zrb-no"> Issuing Office: Debt Managment Section
                    </p>
                    <p>Date of issue............./................./20..................</p>
                    <hr style="border:0.5px solid;">
                    <br>
                </div>
            </div>

            <div class="body-section">
                <P>
                    I, under signed, pursuant to the provision of the section 44 of TAPA Act 2009 give you NOTICE that,
                    on this day have distrained your goods and chattels as described for the recovery of the amount
                    mentioned below.
                </P> <br>
                <P>
                    The said goods and chattels have been secured and left in the premises known as <br> plot No.
                    …………………..... as requested by you. The said good and chattels have been impounded at <br>
                    (location) ………………………………………...............................
                </P> <br>
                <P>
                    Unless you pay the said tax together with the cost of detraining for the same, within the time
                    prescribed in the law or give sufficient security against liability, the said goods and chattels
                    will be sold according to law.
                </P> <br>
            </div>


            <div class="table-section">

                <table class="table-bordered">
                    <thead>
                        <tr>
                            <th class="w-20">Types of Cost</th>
                            <th class="w-20">Amout TZS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="w-20">Amount due as per Warrant of Distress</td>
                            <td class="w-20"></td>
                        </tr>
                        <tr>
                            <td class="w-20">Distraint Agents Charges</td>
                            <td class="w-20"></td>
                        </tr>
                        <tr>
                            <td class="w-20">Transport Charges</td>
                            <td class="w-20"></td>
                        </tr>
                        <tr>
                            <td class="w-20">Subtotal</td>
                            <td class="w-20"></td>
                        </tr>
                        <tr>
                            <td class="w-20">Further cost and disturbance</td>
                            <td class="w-20"></td>
                        </tr>
                        <tr>
                            <td class="w-20">Total</td>
                            <td class="w-20"></td>
                        </tr>


                    </tbody>
                </table>
            </div> <br><br>

            <div class="body-section">
                <p>
                    Name: ................................................................
                    Designation: ..........................................................
                </p><br>
                <p>
                    Signature: ...........................................................
                    Date: ................................................................
                </p>
            </div>
        </div>

</body>

</html>

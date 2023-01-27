<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAYMENT BY THIRD PARTY </title>
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

        .checkbox {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            /* padding: 10px; */
            /* margin: 50px; */
            height: 30px;
            width: 45px;
            background-color: #eee;
        }

        .zrb-no {
            float: right;
        }
    </style>
</head>

<body>


    <div class="header">
        <div class="logo">
            <img src="{{ public_path() }}/images/logo.jpg" alt="">
        </div>
        <div>
            <h2>PAYMENT BY THIRD PARTY </h2>
            <P>Revenue and Debt Managment Unit</P>
            <p>(under sec.42 of TAPA)</p>
        </div>
    </div> <br><br>

    <div>
        <div class="head-section">
            <div class="row">
                <div class="col-6">
                    <h2 class="heading">Ref No: ..........................................................</h2>
                </div>
            </div>
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
            <small>
                <b>RE:</b>
                ....................................................................................................................................................
            </small>
            <br><br>

            <P>
                In exercise of the power conferred upon me by section 42 of TAPA. I hereby declare you to be the payer
                of the tax of the above person and required you to pay me within seven (7) days from the day of this
                notice. The sum of TZS/USD………………………………………. Being the tax due by the person from monies:
            </P> <br>
        </div>

        <div class="body-section">
            <label class="checkbox">..
                <input type="checkbox"> Owing or may subsequently be owed to the tax debtor from you
                <span class="checkmark"></span>
            </label>
            <label class="checkbox">..
                <input type="checkbox"> Held by you or may subsequently be held by you for or on account of the debtor
                <span class="checkmark"></span>
            </label>
            <label class="checkbox">..
                <input type="checkbox"> Held by you or may subsequently be held by you for or on account of the third
                party for payment to the tax debtor
                <span class="checkmark"></span>
            </label>
            <label class="checkbox">..
                <input type="checkbox"> From you having authority from a third person to pay to the tax debtor
                <span class="checkmark"></span>
            </label>
        </div><br>

        <div class="body-section">
            <p>
                When you are unable to comply with this NOTICE before the payment date as set above you should notify me
                accordingly in writing setting out the reason for the inability to comply. However, your notification is
                subjected to acceptance or rejection.
            </p><br>
            <p>
                Your payment of the said sum under the notice shall deem you to have acted under the authority of the
                tax debtor and all other persons concerned and thereby indemnify you in respect of the payment against
                proceedings.
            </p><br>
            <p>
                In case of insufficient funds or overdraft, a certified report/statement of account to support the claim
                must be attached to your reply. Remittance should continue as and when funds accrue until the entire
                amount as per the notice served upon you is fully exhausted.
            </p><br>

            <div class="header">
                <b> COMMISSIONER</b>
            </div>
        </div><br>

        <div>
            <small> Note:Section 42 of the tax Administration Act No. 7 of 2009 </small>
            <hr>
            <p>Copy to:</p><br>
            <p>
                Name:
                ......................................................................................................
            </p><br>
            <p>
                P.O.BOX: ........................................................
            </p>
            <p>
                City/Town: .........................................................................
            </p>
        </div>
    </div>

</body>

</html>

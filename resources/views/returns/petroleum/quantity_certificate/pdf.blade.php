<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Certificate of Quantity</title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 1cm;
            margin-right: 1cm;
        }

        #watermark {
            position: fixed;
            bottom: 10cm;
            left: 6cm;
            width: 10cm;
            height: 10cm;
            z-index: -1000;
            opacity: 0.2;
        }

        .page-break {
            page-break-after: always;
        }

        .body {
            font-family: 'Segoe UI', Tahoma, Verdana, sans-serif;
        }

        .text-center {
            text-align: center;
        }

        table {
            width: 100%;
        }


        table {
            border-spacing: 0px !important;
        }


        .mt-2 {
            margin-top: 2px !important;
        }

        .mt-4 {
            margin-top: 4px !important;
        }

        .mt-6 {
            margin-top: 4px !important;
        }


        .letter-body span {
            font-weight: 900,
        }

        .border-logo {
            border-bottom: 2px solid black;
        }




        .no-border {
            border: none;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid;
            padding: 4px !important;
        }

        .header-title {
            font-family: 'Times New Roman', Times, serif !important;
            font-size: 18px !important;
            font-weight: bold;
            margin-left: 20px;
            margin-right: 20px;
        }

        .contact {
            font-size: 12px !important;
            color: rgb(79, 173, 234)
        }

        .contact a {
            color: blue;
            text-decoration: underline
        }

        .title {
            color: rgb(80, 133, 190)
        }

        .header-logo {
            width: 100px;
        }

        .header-nembo {
            padding-left: 5px;
            width: 80px !important;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="{{ public_path('/images/logo.jpg') }}" height="100%" width="100%" />
    </div>

    <div class="text-center">
        <table class="no-border">
            <td align="left" style="width: 30%">
                <div style="font-size: 12px">
                    Zanzibar Revenue Board, <br>
                    Head Office, <br>
                    P.O.Box 2072 <br>
                    Tel: 255 24 2230639/233041 <br>
                    Fax: 255 24 2233904 <br>
                    Email: zrb@zanrevenue.org <br>
                    Zanzibar
                </div>
            </td>
            <td align="center" class="header-title">
                <img class="header-nembo" src="{{ public_path('/images/logo.jpg') }}" alt="ZRB Logo">
                <div style="font-size: 12px">www.zrbrevenue.org</div>
            </td>
            <td align="right" style="width: 30%">
                <div style="text-align: left; font-size: 12px;">
                    Zanzibar Revenue Board, <br>
                    Head Office, <br>
                    P.O.Box 2072 <br>
                    Tel: 255 24 2230639/233041 <br>
                    Fax: 255 24 2233904 <br>
                    Email: zrb@zanrevenue.org <br>
                    Zanzibar
                </div>
            </td>
        </table>
    </div>
    <hr>
    <div class="text-center">
        <p>THE PETROLEUM LEVY REGULATION, 2017</p>
        <p>CERTIFICATE OF QUANTITY</p>
        <p style="text-decoration: underline">(Made under section 69 of the Tax Administration and Procedure Act No. 7
            of 2009)</p>
    </div>
    <div>
        <p><b>Certificate Number: </b> {{ $data->certificate_no ?? '' }}</p>
        <p>Name of the Importer/Market: {{ $data->business->name ?? '' }}</p>
        <p>Name of the Ship: {{ $data->ship ?? '' }}</p>
        <p>Port of Disembarkation: {{ $data->port ?? '' }}</p>
        <p>Voyage No: {{ $data->voyage_no ?? '' }}</p>
    </div>

    @foreach($data->products as $product)
    <div>
        <p>Intended Cargo discharge: {{ $product->cargo_name ?? '' }}</p>

        <p>This is to certify that the quantity of <b>{{ $product->liters_at_20 }} ltrs@20<sup>o</sup>C</b> dischared at
            {{ $data->port }}
            ex ship {{ $data->ship }} at {{ $data->port }} for account of {{ $data->business->name ?? '' }} as
            ascertained on {{ Carbon\Carbon::create($data->ascertained)->isoFormat('DD-MMMM-YYYY') }}
            under the residence officer supervision was as follows;
        </p>
        <table border="1" style="width: 300px;">
            <tr>
                <th>Liters Observed</th>
                <td>{{ number_format($product->liters_observed, 3) }}</td>
            </tr>
            <tr>
                <th>Liters at 20<sup>o</sup>C</th>
                <td>{{ number_format($product->liters_at_20, 3) }}</td>
            </tr>
            <tr>
                <th>Metric Tons in Air</th>
                <td>{{ number_format($product->metric_tons, 3) }}</td>
            </tr>
        </table>
    </div>
    @endforeach


    <div class="text-center" style="margin-top: 100px">

        <table>
            <tr>
                <td>
                    <div class="text-center">...........................................</div> <br>
                    <div class="text-center">ZRB Residence Officer</div>
                </td>
                <td>
                    <div class="text-center">...........................................</div> <br>
                    <div class="text-center">Surveyor</div>
                </td>
                <td>
                    <div class="text-center">...........................................</div> <br>
                    <div class="text-center"> Depot Representative</div>
                   
                </td>
            </tr>
        </table>
    </div>


</body>

</html>

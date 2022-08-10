@extends('layouts.master')

@section('title', 'All PDF')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card body p-4">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>DEMAND NOTICE</td>
                            <td><a href="{{ route('pdf.demand-notice', 'demand-notice') }}"> <button class="btn btn-primary">
                                        View</button></a>
                            </td>
                        </tr>
                        <tr>
                            <td>PAYMENT BY THIRD PARTY</td>
                            <td><a href="{{ route('pdf.demand-notice', 'third-party-payment') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a></td>
                        </tr>
                        <tr>
                            <td>WARRANT OF DISTRESS</td>
                            <td><a href="{{ route('pdf.demand-notice', 'distress-warant') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a>
                            </td>
                        </tr>
                        <tr>
                            <td>NOTICE OF DISTRESS</td>
                            <td><a href="{{ route('pdf.demand-notice', 'distress-notice') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a>
                            </td>
                        </tr>
                        <tr>
                            <td>INVENTORY OF GOODS AND CHATTLES UNDER DISTAINT</td>
                            <td><a href="{{ route('pdf.demand-notice', 'goods-invetory') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a>
                            </td>
                        </tr>
                        <tr>
                            <td>SCHEDULE OF GOODS AND CHATTLES DISTRAINED UPON</td>
                            <td><a href="{{ route('pdf.demand-notice', 'goods-schedule') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a>
                            </td>
                        </tr>
                        <tr>
                            <td>AGREEMENT ON PAYMENT OF TAX BY INSTALLMENTS</td>
                            <td><a href="{{ route('pdf.demand-notice', 'payment-installments') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a></td>
                        </tr>
                        <tr>
                            <td>UNDERTAKING TO KEEP THE GOODS AND CHATTLES DISTRAINED</td>
                            <td><a href="{{ route('pdf.demand-notice', 'distrained-goods') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a></td>
                        </tr>
                        <tr>
                            <td>APPLICATION OF REMISSION OF PENALTY AND INTEREST</td>
                            <td><a href="{{ route('pdf.demand-notice', 'penalty-remission') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a></td>
                        </tr>
                        <tr>
                            <td>UNCOLLECTABLE TAX FOR WRITE OFF</td>
                            <td><a href="{{ route('pdf.demand-notice', 'uncollectable-tax') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a></td>
                        </tr>
                        <tr>
                            <td>OUTSTANDING TAX FORM</td>
                            <td><a href="{{ route('pdf.demand-notice', 'outstanding-tax') }}"> <button
                                        class="btn btn-primary">
                                        View</button></a></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

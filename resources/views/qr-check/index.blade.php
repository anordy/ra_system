@extends('layouts.password')

@section('content')
<div class="container">
    <div class="row d-flex justify-content-center align-items-center pt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-center"><i class="fa fa-check-circle" aria-hidden="true"></i> Document Is Valid</div>

                <div class="card-body">
                    <div class="py-3"> </div>
                    <table class="table">
                        <tbody>
                            @foreach($code as $label => $codeName)
                                <tr>
                                    <td> <span class="text-uppercase">{{ $label }}  </span>: {{ $codeName }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
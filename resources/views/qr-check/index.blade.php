@extends('layouts.password')

@section('content')
{{-- <div class="container">
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
                                <td> <span class="text-uppercase"> <h6>{{ $label }} :</h6></span> {{ $codeName }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div> --}}

<style>
    .container {
        padding: 50px 0;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        color: #fff;
        background-color: #28a745;
        border-radius: 10px 10px 0 0;
        font-size: 24px;
    }
    .card-body {
        font-size: 18px;
    }
    .table {
        margin-bottom: 0;
    }
    .table td {
        padding: 1rem;
        font-weight: 500;
        color: #212529;
    }
    .table h6 {
        margin-bottom: 0;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header bg-success text-center">
            <i class="fa fa-check-circle" aria-hidden="true"></i> Document Is Valid
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <tbody>
                  @foreach($code as $label => $codeName)
                    <tr>
                      <td>
                        <span class="text-uppercase"><h6>{{ $label }}:</h6></span> {{ $codeName }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  

@endsection
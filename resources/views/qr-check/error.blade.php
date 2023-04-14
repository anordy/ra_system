@extends('layouts.password')

@section('content')
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
  <div class="row justify-content-center text-center">
    <div class="col-md-8">
        <div class="container">
          <div class="alert alert-danger">
              <h4>Document not found.!!</h4>
              <p>The document you are looking for could not be found. Please Vist ZRA Ofice for verification.</p>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
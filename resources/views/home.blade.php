@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <h1 class="text-black-50">You are logged in!</h1>
    </div>

  

        <div class="container">
          <div class="card">
            <div class="card-header text-white bg-primary text-center"><h5>VAT PAYMENT REPORT</h5></div>
             <div class="col-lg-12"><div class="card-body"><form method="POST" action="">
               <div class="form-group"><label for="vat_id">VAT REGISTRATION ID</label> <input type="text" name="vat_id" placeholder="Vat Registration ID" class="form-control"></div> 
               <div class="row justify-content-center"><button type="submit" class="btn btn-primary col-md-6">Submit</button></div></form></div></div></div></div>
        


      
@endsection

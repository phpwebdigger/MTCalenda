@extends('layouts.base')

@section('content')
<div style="margin-top: 10px;"></div>
<div style="margin-left: 20px;"></div>
<style>
	body{
		
  		height: 10% !important;
	}
	.container-fluid {
    width: 116% !important;
	}
  .wrapper > ul#results li {
  margin-bottom: 1px;
  background: #f9f9f9;
  padding: 20px;
  list-style: none;
 
}
.ajax-loading{
  text-align: center;
}
.container{

}
</style>

<div class="container">

    
   <div class="row"> 
<div class="col-md-8">
   <div class="wrapper">
   	<div class="hours">Hours wise Data</div>
  <ul id="results"><!-- results appear here --></ul>
  <div class="Days">Days wise Data</div>
  <ul id="results_days"><!-- results appear here --></ul>
     <div class="ajax-loading"><img src="{{ asset('dist/img/loading.gif') }}" /></div>
 </div>
 
 </div>
</div>
<div style="padding:120px;"></div>

</div>
@endsection

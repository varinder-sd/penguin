@extends('voyager::master')

@section('css')
<style>
.st{    -webkit-appearance: radio !important;}
 </style>
@stop

@section('content')
	<div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-dollar"></i>{{ $title }}
        </h1>
		<a href="{{ route('admin.plan.create')}}" class="btn btn-success btn-add-new">
		<i class="voyager-plus"></i> <span>Add New</span>
		</a>
	</div>
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
		@if(Session::has('flash_message'))
		<div class="alert alert-success">
            <div>
                {{ Session::get('flash_message') }}
            </div>
		</div>
        @endif
 
        @if($errors->any())
	<div class="alert alert-danger">	
            <div>
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            </div>
        @endif
		
		
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
									
		<form action="{{route('admin.plan.store')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="plan name">Plan Name:</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Plan Name">
                </div>
                <div class="form-group">
                    <label for="cost">Cost:</label>
                    <input type="text" class="form-control" name="cost" placeholder="Enter Cost">
                </div>
                <div class="form-group">
                    <label for="cost">Plan Description:</label>
                    <input type="text" class="form-control" name="description" placeholder="Enter Description">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
@stop

@section('css')

@stop

@section('javascript')
<script>
 
jQuery(document).ready(function($){

	
});
</script>
   
@stop

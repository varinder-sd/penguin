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
									
					<form class="form-edit-add" role="form" action="@if($title =='Edit Plan')         
      {{ route('admin.plan.update',$plan->id) }}        
@else
     {{ route('admin.plan.store') }}     
@endif" method="POST" enctype="multipart/form-data" autocomplete="off">
						<!-- PUT Method if we are editing -->
						 {{ csrf_field() }}
						@if($title =='Edit Plan')
						@method('PATCH')
						@endif
						<div class="row">
							<div class="col-md-8">
								<div class="panel panel-bordered">
								
									
									<div class="panel-body">
										<div class="form-group">
											<label for="name">Name</label>
											<input type="text" class="form-control" id="name" name="name" placeholder="Name" value="@isset($plan->name){{ $plan->name }}@endisset">
										</div>

										<div class="form-group">
											<label for="Price">Price</label>
											<input type="text" class="form-control" id="Price"  placeholder="Price" value="@isset($plan->cost){{$plan->cost}}@endisset" readonly>
										</div>
										<div class="form-group">
											<label for="currency">Currency</label>
										<input type="text" class="form-control"   value="@isset($plan->currency){{$plan->currency}}@endisset" readonly>
										</div>
										<div class="form-group">
											<label for="currency">Billing Period</label>
										<input type="text" class="form-control"   value="@isset($plan->billing_period){{$plan->billing_period}}@endisset" readonly>
										</div>
										<div class="form-group">
											<label for="description">Description</label>
											
			
											
											<textarea name="description" class="form-control">@isset($plan->description){{ $plan->description }}
											@endisset</textarea>
											
										</div>

										<div class="form-group"> 
											<label for="active">Active
											<input type="radio" class="form-control st" id="active" name="status" value="1" @if(isset($plan->status) && $plan->status == 1) {{ "checked" }} @endif></label>
											<label for="inactive">
											inactive<input type="radio" class="form-control st" id="inactive" name="status"value="0" @if(isset($plan->status) && $plan->status == 0) {{ "checked" }} @endif></label>
										</div>
											
						
											
									</div>
								</div>
							</div>

						
						</div>
						
						<button type="submit" class="btn btn-primary pull-right save">
							Save
						</button>
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

<?php

namespace App\Http\Controllers;

//use Request;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Session;
class PlanController extends Controller
{
    //
	public function index(Request $request){
		
		
		$plans = Plan::All();		
		
		if ($request->wantsJson()) {
			$plans = $plans->filter(function ($plans) {
				return $plans->status == 1;
			});
			return response()->json([
			'status_code' => 200,
			'plans' => $plans,
			]);
		} else {
			return view('vendor.voyager.plans.plans', compact("plans"));
		}
		
		
	}
	
	public function allplans(){
		
        $plans = Plan::all();

        return view('plans.index', compact('plans'));
    }

    /**
     * Show the Plan.
     *
     * @return mixed
     */
    public function show(Plan $plan, Request $request)
    {   
        $paymentMethods = $request->user()->paymentMethods();

        $intent = $request->user()->createSetupIntent();
        
        return view('plans.show', compact('plan', 'intent'));
    }
	
	public function create(){
		
		return view('vendor.voyager.plans.plansCreate', array('title' => 'Add Plan'));
	}
	
	public function store(Request $request)
    {
      
        $request->validate([
			 'name' => 'required',
			'price' => 'required',
			]);
			
        $input = $request->except('_token');;
			// dd($input); // dd() helper function is print_r alternative
			// die;
        Plan::create($input);
        
        Session::flash('flash_message', 'News added successfully!');
 
        //return redirect()->back();
        //return redirect('news');
        return redirect()->route('voyager.plans.index');
    }
	
	public function edit($id){
		$plan = Plan::findOrFail($id);
		//print_r($plan);
		return view('vendor.voyager.plans.plansCreate', array('plan' => $plan, 'title' => 'Edit Plan'));
	}
	
	 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function update(Request $request, $id){
		
        $plan = Plan::findOrFail($id);
 
            $request->validate([
				'name' => 'required',
			]);
 
        $input = $request->all();
 
        $input = $request->except('_token');

		if($plan->update($input)){

		Session::flash('flash_message', 'Plan updated successfully!');	
         
		}else{
         Session::flash('message', 'Data not updated!');
		}


        
 
        return redirect()->back();
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plans = Plan::findOrFail($id);
 
        $plans->delete();
 
        Session::flash('flash_message', 'Plans deleted successfully!');
 
        return redirect()->route('voyager.plans.index');
    }
	
	public function change_status(Request $request){
		
		$plan = Plan::findOrFail($request->id);

 
        $input = $request->status;
 
		if($plan->update(array('status'=>$input))){

         return 1;
        
		}else{
          return 0;
		}


	}
	
}

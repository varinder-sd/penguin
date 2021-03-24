<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Session;
class SubscriptionController extends Controller
{   
    protected $stripe;

    public function __construct() 
    {
		 $key = \config('services.stripe.secret');
        $this->stripe = new \Stripe\StripeClient($key);
    }

	public function userSubscription(Request $request) {
		$user = $request->user();
		
      	$is_cancelled = 0;
        
      	if ($user->subscription('default')->cancelled()) {
   
			$is_cancelled = 1;
		}
	
		if ($user->subscribed()) {
          
          if ($user->hasPaymentMethod()) {
         	 $card_digits = $user->card_last_four;
          
          }else{
			 $card_digits = 0;	
          }
		
          $sub = $user->subscription('default')->asStripeSubscription();
    
          $planId = $sub['items']['data'][0]->plan->id; 
         
          	$plan = Plan::where('stripe_plan',$planId)->first();
			$plan['card_digits'] =  $card_digits;
			return response()->json([
			'status_code' => 200,
			'message' => "your subscription data",
			'subscription' => $sub,
            'is_cancelled'=>$is_cancelled,
            'plan'=>$plan,                        
			]);
			
		}else{
			return response()->json([
			'status_code' => 400,
			'message' => "You have no active plan yet.",
			]);
		}
	}
	
	
	public function planUpgrade(Request $request) {
		$user = $request->user();
		
       $plan = $request->plan_id;
		if ($user->subscribed()) {	
		
			$user->subscription('default')->swapAndInvoice($plan);

			return response()->json([
			'status_code' => 200,
			'message' => "your subscription plan upgraded",
			]);
			
		}else{
			return response()->json([
			'status_code' => 500,
			'message' => "You have no active plan yet.",
			]);
		}
	}
	
	public function retrievePlans() {
       /* $key = \config('services.stripe.secret');
       $stripe = new \Stripe\StripeClient($key);
       $plansraw = $stripe->plans->all();
       $plans = $plansraw->data;
		$plan =  $stripe->plans->retrieve(
		  'plan_J8DbbUswidVpYN',
		  []
		);
		$stripe->plans->update(
		  'plan_J8DbbUswidVpYN',
		  ['amount' => 2000]
		);
		print_r($plan); die;	   
		$plan =  $stripe->products->retrieve(
		  'prod_J8DbR9rxQY7Ihc',
		  []
		);
	   
	   print_r($plan);
       return $plans; */
   }
	
	
    public function create(Request $request, Plan $plan)
    {
		$user = $request->user();
		
		if ($user->subscribed()) {
			return response()->json([
			'status_code' => 500,
			'message' => "You have already subscribed",
			]);
		}
		
        $plan = Plan::findOrFail($request->get('plan'));
        		
        $paymentMethod = $request->paymentMethod;
		
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);
		
			
        $user->newSubscription('default', $plan->stripe_plan)
            ->create($paymentMethod, [
                'email' => $user->email,
            ]);
        
		if ($request->wantsJson()) {
			
			return response()->json([
			'status_code' => 200,
			'message' => "Your plan subscribed successfully",
			]);
		} else {
			
			return redirect()->route('home')->with('success', 'Your plan subscribed successfully');
		}
		
        
    }


    public function createPlan()
    {
        return view('vendor.voyager.plans.create', array('title' => 'Add Plan'));
    }

    public function storePlan(Request $request)
    {   
        $data = $request->except('_token');

        $data['slug'] = strtolower($data['name']);
        $price = $data['cost'] *100; 

        //create stripe product
        $stripeProduct = $this->stripe->products->create([
            'name' => $data['name'],
        ]);
        
        //Stripe Plan Creation
        $stripePlanCreation = $this->stripe->plans->create([
            'amount' => $price,
            'currency' => 'inr',
            'interval' => 'month', //  it can be day,week,month or year
            'product' => $stripeProduct->id,
        ]);

        $data['stripe_plan'] = $stripePlanCreation->id;

        Plan::create($data);

        Session::flash('flash_message', 'News added successfully!');
 
        //return redirect()->back();
        //return redirect('news');
        return redirect()->route('voyager.plans.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;




//use Request;
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

    public function create(Request $request, Plan $plan)
    {
        $plan = Plan::findOrFail($request->get('plan'));
        
        $user = $request->user();
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
			'subscription' => $subscriptions,
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

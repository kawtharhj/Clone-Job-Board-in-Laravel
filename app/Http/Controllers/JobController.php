<?php

namespace App\Http\Controllers;


use App\Models\Listing;
use App\Models\User;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobController extends Controller
{

    public function create($id)
    {
        $job = Job::findOrFail($id);

        return view('\listings.show', compact('job'));
    }


   /* public function index($id)
{
    $listing = Listing::find($id);

}*/

    public function store(Request $request, $id)
    {
        
        $formfield = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'cv' => 'required|mimes:pdf',
            
        ]);
        
        $job = new Job;
        $job->listings_id = $id;
    if($request->hasFile('cv')){
        $filename = $request->file('cv')->getClientOriginalName();
        $path='cvs';
        $request->file('cv')->move($path,$filename);
     //   $request->file('cv')->storeAs('cvs', $filename);
        $formfield['cv'] = $filename;
    }
    $formfield['listings_id'] = $id;
   
    $job->storeApplication(array_merge($formfield, ['listing_ids' => $id]));
    


        return redirect()->route('listings.show', $id)->with('message', 'Application submitted successfully.');
    }
    

}
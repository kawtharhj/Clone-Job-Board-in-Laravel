<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index(){
      
        return view('listings.index',[
            'listings'=>Listing::latest()->filter(request(['tag','search']))->paginate(6)
        ]);
    
    }

    public function show(Listing $listing){
        return view('listings.show',[
            'listing'=>$listing
        ]); 
    }

    public function create(){
        return view('listings.create');
    }

    public function store( Request $request){
        $formField = $request->validate([
            'title'=>'required',
            'company'=>['required',Rule::unique('listings','company')],
            'location'=>'required',
            'website'=>'required',
            'email'=>['required','email'],
            'tags'=>'required',
            'description'=>'required'
        ]);

        if($request->hasFile('logo')){
            $formField['logo']=$request->file('logo')->store('logos','public');
        }


        $formField['user_id'] = auth()->id();

        Listing::create($formField);
        return redirect('/')->with('message', 'Listing created successfully!');
    }

    public function edit(Listing $listing){

        return view('listings.edit',['listing'=>$listing]);
    }

    public function update( Request $request,Listing $listing){
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        $formField = $request->validate([
            'title'=>'required',
            'company'=>'required',
            'location'=>'required',
            'website'=>'required',
            'email'=>['required','email'],
            'tags'=>'required',
            'description'=>'required'
        ]);

        if($request->hasFile('logo')){
            $formField['logo']=$request->file('logo')->store('logos','public');
        }

        $listing->update($formField);
        return back()->with('message', 'Listing Updated successfully!');
    }

    public function destroy(Listing $listing){

        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        $listing->delete();
        return redirect('/')->with('message','The List is removed successfully');
    }

    public function manage(){
        return view('listings.manage', ['listings'=>auth()->user()->listings()->get()]);
     }

    
     public function apply($id)
{
    $listing = Listing::findOrFail($id);
    return view('listings.apply', compact('listing'));
}


public function cstore(Request $request, $id)
{
    $formfield = $request->validate([
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'cv' => 'required|mimes:pdf',
    ]);


    $listing_id = $request->input('listing_id');
    $job = new Job;
    $job->listing_id = $listing_id;
    $job->storeApplication(array_merge($formfield, ['listing_id' => $id]));

    return redirect()->route('listings.show', $id)->with('success', 'Application submitted successfully.');
}

}











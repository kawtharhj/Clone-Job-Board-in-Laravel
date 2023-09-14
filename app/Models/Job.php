<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $table = 'jobs';
    protected $fillable  = [
        'listings_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'cv'
    ];


    public function create($id)
    {
        $job = Job::findOrFail($id);
    
        return view('\listings.show', compact('job'));
    }
    

    public function storeApplication($data)
    {
        $fillable  = new Job;
        $fillable->listings_id=$data['listings_id'];
        $fillable ->firstname = $data['firstname'];
        $fillable ->lastname = $data['lastname'];
        $fillable ->email = $data['email'];
        $fillable ->phone = $data['phone'];
        $fillable ->cv = $data['cv'];
        $fillable ->save();
      
    }

    public function listings() {
        return $this->hasOne(Listing::class, 'listings_id');
    }
}

?>
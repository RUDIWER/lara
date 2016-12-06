<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Http\Controllers\Controller;

use App\Models\PsCustomer;

class MailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function test(){

        $user = PsCustomer::findOrFail(1);

        Mail::send('emails.reminder', ['user' => $user], function ($m) use ($user) {
           $m->from('info@cool-zawadi.com', 'Dienst Dispatching cool-zawadi');
           $m->to($user->email, $user->firstname)->subject('Belangrijke info betreffende uw order bij cool-zawadi.com !');
       });
    }
}

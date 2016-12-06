<?php

namespace App\Http\Controllers;
use App\Models\CzParameter;

use Illuminate\Http\Request;

use App\Http\Requests;

class CzParamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function parameters()
    {
        $param = CzParameter::find(1);
        return view('parameters.edit', compact('param'));
    }

    public function save(Request $request, $id_cz_parameter)
    {
        $data = $request->except('_token');
        $param = CzParameter::find(1);
        $param->fill($data);
        $param->save();
        $notification = array(
            'message' => 'Parameters succesvol Opgeslagen !',
            'alert-type' => 'success'
        );
        return redirect('/home')->with($notification);
    }

}

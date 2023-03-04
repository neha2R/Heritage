<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\State;

class StatesController extends Controller
{
    public function index()
    {
        $data['countries'] = Country::get(["name", "id"]);
        return response()->json($data);
    }

    public function fetchState($country_id)
    {

        $data['states'] = State::where("country_id", $country_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function fetchCity($state_id)
    {
    
        $data['cities'] = City::where("state_id", $state_id)->get(["name", "id"])->unique('name');
        return response()->json($data);
    }
}

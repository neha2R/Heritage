<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Subdomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Theme;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $subdomains = Subdomain::OrderBy('id', 'DESC')->get();
        $themes = Theme::OrderBy('id', 'DESC')->get();

        return view('domain.list', compact('themes','domains', 'subdomains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:domains',
            'theme_id' => 'required',
        ]);
        $data = new Domain;
        $data->name = $request->name;
        $data->status = '1';
        $data->themes_id = implode(',',$request->theme_id);
        $data->save();

        if ($data->id) {
            return redirect('admin/domain')->with(['success' => 'Domain saved Successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        if ($domain->status == '1') {
            $domain->status = '0';
        } else {
            $domain->status = '1';

        }
        $domain->save();

        if ($domain->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function edit(Domain $domain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Domain $domain)
    {

        $domain->name = $request->name;
        $domain->themes_id = implode(',',$request->theme_id);

        $domain->save();
        if ($domain->id) {
            return redirect()->back()->with(['success' => 'Domain Updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {

        $domain->delete();
        if ($domain->id) {
            return redirect()->back()->with(['success' => 'Domain Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    public function addsubdomain(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'domain_id' => 'required',
            'subdomain_name' => 'required',
        ]);
        $data = new Subdomain;
        $data->name = $request->subdomain_name;
        $data->domain_id = $request->domain_id;
        $data->status = '1';
        $data->save();

        if ($data->id) {
            return redirect('admin/domain')->with(['success' => 'Sub Domain saved Successfully', 'submodel' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    public function changeSubDomainStatus($subdomain)
    {
        $subdomain = Subdomain::find($subdomain);
        if ($subdomain->status == '1') {
            $subdomain->status = '0';
        } else {
            $subdomain->status = '1';

        }
        $subdomain->save();

        if ($subdomain->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    public function updatesubdomain(Request $request, $subdomain)
    {
        $subdomain = Subdomain::find($subdomain);
        $subdomain->name = $request->name;
        $subdomain->save();
        if ($subdomain->id) {
            return redirect()->back()->with(['success' => 'Sub Domain Updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    public function deletesubdomain($subdomain)
    {
        $subdomain = Subdomain::find($subdomain);
        $subdomain->delete();
        if ($subdomain->id) {
            return redirect()->back()->with(['success' => 'Sub Domain Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    // Get all domains
    public function domains(Request $request)
    {
         if($request->theme_id){
            $domains = Domain::OrderBy('id', 'DESC')->where('themes_id',$request->theme_id)->where('status', '1')->get();
  
         }else{
            $domains = Domain::OrderBy('id', 'DESC')->where('status', '1')->get();

         }
        $domains = $domains->toArray();
        return response()->json(['status' => 200, 'message' => 'Domain data', 'data' => $domains]);

    }


    // Domain filter according to themes
    public function getDomainAccordingTheme(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'theme_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => '', 'message' => $validator->errors()]);
        }
              $query = Domain::select('id','name');
         $ids = explode(',', $request->theme_id);
         foreach($ids as $myid){
         $query->where('themes_id',$myid);
         $query->orWhere('themes_id','like', '%'.$myid.'%');
         }

        $id = $request->theme_id;
       $domains = $query->where('status','1');
        // $domains = $query->orWhere('themes_id','like', '%'.$id.'%')->get();
        // $domains = Domain::select('id','name')->get();
        $domains = $domains->toArray();
        if(empty($domains)){
            return response()->json(['status' => 200, 'message' => 'Domain not found', 'data' => '']);
        }
        return response()->json(['status' => 200, 'message' => 'Domain data', 'data' => $domains]);

    }

}

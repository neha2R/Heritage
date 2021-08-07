<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Subdomain;
use Illuminate\Http\Request;

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

        return view('domain.list', compact('domains', 'subdomains'));
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
        ]);
        $data = new Domain;
        $data->name = $request->name;
        $data->status = '1';
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

    public function domains()
    {

        $domains = Domain::OrderBy('id', 'DESC')->get();
        $domains = $domains->toArray();
        return response()->json(['status' => 200, 'message' => 'Domain data', 'data' => $domains]);

    }

}

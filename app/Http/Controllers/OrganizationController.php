<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct(private OrganizationRepository $repo)
    {
    }

    public function index()
    {
        return view('organizations.index', ['organizations' => $this->repo->paginate(15)]);
    }

    public function create()
    {
        return view('organizations.create');
    }

    public function store(StoreOrganizationRequest $request)
    {
        $org = $this->repo->create($request->validated());
        return redirect()->route('organizations.show', $org->id);
    }

    public function show($id)
    {
        $org = $this->repo->find($id);
        abort_if(!$org, 404);
        return view('organizations.show', ['organization' => $org]);
    }
}

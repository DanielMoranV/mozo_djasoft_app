<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Models\Provider;
use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdateProviderRequest;
use App\Http\Resources\ProviderResource;
use App\Interfaces\ProviderRepositoryInterface;

class ProviderController extends Controller
{
    private ProviderRepositoryInterface $providerRepositoryInterface;
    public function __construct(ProviderRepositoryInterface $providerRepositoryInterface)
    {
        $this->providerRepositoryInterface = $providerRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->providerRepositoryInterface->getAll();
        return ApiResponseHelper::sendResponse(ProviderResource::collection(($data), '', 200));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProviderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProviderRequest $request, Provider $provider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        //
    }
}

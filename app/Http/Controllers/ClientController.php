<?php

namespace App\Http\Controllers;

use App\Application\UseCases\Client\CreateClient;
use App\Application\UseCases\Client\DeleteClient;
use App\Application\UseCases\Client\GetAllClient;
use App\Application\UseCases\Client\UpdateClient;
use App\Domain\DTOs\Client\ClientInputDTO;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllClient $useCase)
    {
        return response()->json($useCase->execute());
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
    public function store(Request $request,CreateClient $useCase)
    {
        $dto = new ClientInputDTO($request->nom,$request->prenom,$request->tel,$request->email,$request->cin);
        return response()->json($useCase->execute($dto));       

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, UpdateClient $useCase)
    {
        $dto = new ClientInputDTO($request->nom,$request->prenom,$request->tel,$request->email,$request->cin);
        return response()->json($useCase->execute($id,$dto));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,DeleteClient $useCase)
    {
        return response()->json(['delete' => $useCase->execute($id)]);
    }
}

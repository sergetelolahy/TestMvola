<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Domain\DTOs\TypeChambre\TypeChambreInputDTO;
use App\Application\UseCases\TypeChambre\CreateTypeChambre;
use App\Application\UseCases\TypeChambre\DeleteTypeChambre;
use App\Application\UseCases\TypeChambre\GetAllTypeChambre;
use App\Application\UseCases\TypeChambre\UpdateTypeChambre;

class TypeChambreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllTypeChambre $useCase)
    {
        return response()->json($useCase->execute());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,CreateTypeChambre $useCase)
    {
        $dto = new TypeChambreInputDTO($request->nom, $request->nbrLit ,$request->maxPersonnes,$request->description);
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
    public function update(Request $request, string $id,UpdateTypeChambre $useCase)
    {
        $dto = new TypeChambreInputDTO($request->nom, $request->nbrLit, $request->maxPersonnes, $request->description);
        return response()->json($useCase->execute($id,$dto));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,DeleteTypeChambre $useCase)
    {
        return response()->json(['delete' => $useCase->execute($id)]);
    }
}

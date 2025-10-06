<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Domain\DTOs\TypeChambreDTO;
use App\Application\UseCases\TypeChambre\CreateTypeChambre;

class TypeChambreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $dto = new TypeChambreDTO($request->nom, $request->nbrLit ,$request->maxPersonnes,$request->description);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

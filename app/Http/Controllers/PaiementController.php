<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\DTOs\Paiement\PaiementInputDTO;
use App\Application\UseCases\Paiement\CreatePaiement;
use App\Application\UseCases\Paiement\GetAllPaiement;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllPaiement $useCase)
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
    public function store(Request $request,CreatePaiement $useCase)
    {
        $dto = new PaiementInputDTO($request->id_reservation,$request->montant,$request->date_paiement,$request->mode_paiement,$request->status);
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

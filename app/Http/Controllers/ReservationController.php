<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\DTOs\Reservation\ReservationInputDTO;
use App\Application\UseCases\Reservation\CreateReservation;
use App\Application\UseCases\Reservation\DeleteReservation;
use App\Application\UseCases\Reservation\GetAllReservation;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllReservation $useCase)
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
    public function store(Request $request,CreateReservation $useCase)
    {
        $dto = new ReservationInputDTO(
            $request->id_client,
            $request->id_chambre,
            $request->date_debut,
            $request->date_fin,
            $request->tarif_template
        );
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
    public function destroy(string $id,DeleteReservation $useCase)
    {
        return response()->json(['delete' => $useCase->execute($id)]);
    }
}

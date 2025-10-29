<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\DTOs\ChambreDTO;
use Illuminate\Http\JsonResponse;
use App\Domain\DTOs\Chambre\ChambreInputDTO;
use App\Application\UseCases\Chambre\CreateChambre;
use App\Application\UseCases\Chambre\DeleteChambre;
use App\Application\UseCases\Chambre\GetAllChambre;
use App\Application\UseCases\Chambre\UpdateChambre;
use App\Application\UseCases\Chambre\GetChambreDisponible;

class ChambreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllChambre $useCase)
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
    public function store(Request $request,CreateChambre $useCase)
    {
       
        $dto = new ChambreInputDTO(
            $request->numero,
            $request->prix,
            $request->typechambre_id,
            $request->services ?? [] // 🔹 services facultatifs
        );
    
        // Exécuter le use case
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
    public function update(Request $request, string $id,UpdateChambre $useCase)
    {
        $dto = new ChambreInputDTO($request->numero,$request->prix,$request->typechambre_id,$request->services ?? []);
        return response()->json($useCase->execute($id,$dto));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,DeleteChambre $useCase)
    {
        return response()->json(['delete' => $useCase->execute($id)]); 
    }

    public function chambresDisponiblesAujourdhui(GetChambreDisponible $useCase): JsonResponse
    {
        try {
            $chambres = $useCase->executeAujourdhui();

            return response()->json([
                'success' => true,
                'date_recherche' => now()->format('Y-m-d'),
                'chambres_disponibles' => $chambres,
                'total' => count($chambres)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des chambres disponibles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

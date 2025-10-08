<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\DTOs\ServiceDTO;
use App\Domain\DTOs\Service\ServiceInputDTO;
use App\Application\UseCases\Service\CreateService;
use App\Application\UseCases\Service\DeleteService;
use App\Application\UseCases\Service\GetAllService;
use App\Application\UseCases\Service\UpdateService;
use App\Application\UseCases\TypeChambre\CreateTypeChambre;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllService $useCase)
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
    public function store(Request $request,CreateService $useCase)
    {
       $dto = new ServiceInputDTO($request->nom , $request->description);

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
    public function update(Request $request, string $id,UpdateService $useCase)
    {
      $dto = new ServiceInputDTO($request->nom,$request->description);
      return response()->json($useCase->execute($id,$dto));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,DeleteService $useCase)
    {
        return response()->json(['delete' => $useCase->execute($id)]);
    }
}

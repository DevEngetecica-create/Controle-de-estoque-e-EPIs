<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NFeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    protected $nfeService;

    public function __construct(NFeService $nfeService)
    {
        $this->nfeService = $nfeService;
    }

    public function consultarNFe($chave)
    {
        Log::info("Consultando NFe com a chave: $chave");
        $data = $this->nfeService->consultarNFe($chave);
        
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Nota Fiscal não encontrada'], 404);
        }
    }
}

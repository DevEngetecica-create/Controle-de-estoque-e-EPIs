<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NFeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NFeController extends Controller
{
    protected $nfeService;

    public function __construct(NFeService $nfeService)
    {
        $this->nfeService = $nfeService;
    }

    public function emitir(Request $request)
    {
        $data = $request->all();
        $response = $this->nfeService->emitirNFe($data);
        
        if ($response) {
            return response()->json(['success' => true, 'response' => $response]);
        } else {
            return response()->json(['success' => false, 'error' => 'Erro ao emitir NFe'], 500);
        }
    }

    public function consultar($chave)
    {
        $data = $this->nfeService->consultarNFe($chave);
        
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Nota Fiscal não encontrada'], 404);
        }
    }
}

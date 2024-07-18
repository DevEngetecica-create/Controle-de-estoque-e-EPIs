<?php

namespace App\Services;

use NFePHP\NFe\Tools;
use NFePHP\NFe\Make;
use NFePHP\Common\Certificate;
use Illuminate\Support\Facades\Log;

class NFeService
{
    protected $tools;

    public function __construct()
    {
        try {
            $pfxPath = config('nfe.certs.pfx');
            $passphrase = config('nfe.certs.passphrase');

            if (!file_exists($pfxPath)) {
                throw new \Exception("Certificado PFX não encontrado em: $pfxPath");
            }

            Log::info("Lendo o certificado PFX...");
            $pfxcontent = file_get_contents($pfxPath);
            $certificate = Certificate::readPfx($pfxcontent, $passphrase);

            $this->tools = new Tools(config('nfe.configJson'), $certificate);
            $this->tools->model('65');
        } catch (\Exception $e) {
            Log::error("Erro ao inicializar o NFeService: " . $e->getMessage());
            throw $e;
        }
    }

    public function emitirNFe($data)
    {
        try {
            $make = new Make();

            // Configuração da NFe (baseado no seu exemplo)
            Log::info("Configurando a NFe...");

            $std = new \stdClass();
            $std->versao = '4.00';
            $std->Id = null;
            $std->pk_nItem = null;
            $make->taginfNFe($std);

            $std = new \stdClass();
            $std->cUF = 41;
            $std->cNF = '03701267';
            $std->natOp = 'VENDA CONSUMIDOR';
            $std->mod = 65;
            $std->serie = 1;
            $std->nNF = 100;
            $std->dhEmi = (new \DateTime())->format('Y-m-d\TH:i:sP');
            $std->tpNF = 1;
            $std->idDest = 1;
            $std->cMunFG = 4106902;
            $std->tpImp = 1;
            $std->tpEmis = 1;
            $std->cDV = 2;
            $std->tpAmb = 2;
            $std->finNFe = 1;
            $std->indFinal = 1;
            $std->indPres = 1;
            $std->procEmi = 3;
            $std->verProc = '4.13';
            $make->tagide($std);

            // Emitente
            $std = new \stdClass();
            $std->xNome = 'SUA RAZAO SOCIAL LTDA';
            $std->xFant = 'RAZAO';
            $std->IE = '111111111';
            $std->CNAE = '4642701';
            $std->CRT = 1;
            $std->CNPJ = '99999999999999';
            $make->tagemit($std);

            // Endereço do Emitente
            $std = new \stdClass();
            $std->xLgr = 'Avenida Getúlio Vargas';
            $std->nro = '5022';
            $std->xCpl = 'LOJA 42';
            $std->xBairro = 'CENTRO';
            $std->cMun = 4106902;
            $std->xMun = 'Cidade Teste';
            $std->UF = 'PR';
            $std->CEP = '80000000';
            $std->cPais = 1058;
            $std->xPais = 'Brasil';
            $std->fone = '4112345678';
            $make->tagenderEmit($std);

            // Destinatário
            $std = new \stdClass();
            $std->xNome = 'Cliente Teste';
            $std->CNPJ = '01234123456789';
            $std->indIEDest = 9;
            $std->email = 'cliente@teste.com';
            $make->tagdest($std);

            // Endereço do Destinatário
            $std = new \stdClass();
            $std->xLgr = 'Avenida Sebastião Diniz';
            $std->nro = '458';
            $std->xBairro = 'CENTRO';
            $std->cMun = 4106902;
            $std->xMun = 'Cidade Teste';
            $std->UF = 'PR';
            $std->CEP = '80000000';
            $std->cPais = 1058;
            $std->xPais = 'Brasil';
            $std->fone = '4112345678';
            $make->tagenderDest($std);

            // Produtos
            $std = new \stdClass();
            $std->item = 1;
            $std->cProd = '1111';
            $std->cEAN = "SEM GTIN";
            $std->xProd = 'CAMISETA REGATA GG';
            $std->NCM = '61052000';
            $std->CFOP = '5101';
            $std->uCom = 'UNID';
            $std->qCom = 1;
            $std->vUnCom = 100.00;
            $std->vProd = 100.00;
            $std->cEANTrib = "SEM GTIN";
            $std->uTrib = 'UNID';
            $std->qTrib = 1;
            $std->vUnTrib = 100.00;
            $std->indTot = 1;
            $make->tagprod($std);

            // Imposto
            $std = new \stdClass();
            $std->item = 1;
            $std->vTotTrib = 25.00;
            $make->tagimposto($std);

            // ICMS
            $std = new \stdClass();
            $std->item = 1;
            $std->orig = 0;
            $std->CSOSN = '102';
            $make->tagICMSSN($std);

            // PIS
            $std = new \stdClass();
            $std->item = 1;
            $std->CST = '99';
            $std->vBC = 0.00;
            $std->pPIS = 0.00;
            $std->vPIS = 0.00;
            $make->tagPIS($std);

            // COFINS
            $std = new \stdClass();
            $std->item = 1;
            $std->CST = '99';
            $std->vBC = 0.00;
            $std->pCOFINS = 0.00;
            $std->vCOFINS = 0.00;
            $make->tagCOFINS($std);

            // Total da NF
            $std = new \stdClass();
            $make->tagICMSTot($std);

            // Transporte
            $std = new \stdClass();
            $std->modFrete = 0;
            $make->tagtransp($std);

            // Pagamento
            $std = new \stdClass();
            $std->vTroco = 0;
            $make->tagpag($std);

            // Detalhamento do Pagamento
            $std = new \stdClass();
            $std->indPag = 1;
            $std->tPag = '01';
            $std->vPag = 100.00;
            $make->tagdetPag($std);

            // Informações Adicionais
            $std = new \stdClass();
            $std->infAdFisco = '';
            $std->infCpl = '';
            $make->taginfadic($std);

            $make->monta();
            $xml = $make->getXML();

            Log::info("Assinando a NFe...");
            $xml = $this->tools->signNFe($xml);

            Log::info("NFe emitida com sucesso.");
            return $xml;
        } catch (\Exception $e) {
            Log::error("Erro ao emitir NFe: " . $e->getMessage());
            return null;
        }
    }

    public function consultarNFe($chave)
    {
        try {
            $response = $this->tools->sefazConsultaChave($chave);
            Log::info("Resposta da SEFAZ: $response");
            $stdCl = new \stdClass();
            $stdCl = json_decode($response);
            return $stdCl;
        } catch (\Exception $e) {
            Log::error("Erro ao consultar NFe: " . $e->getMessage());
            return null;
        }
    }
}

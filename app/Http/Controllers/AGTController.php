<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\FacturaOriginal;
use App\Models\Imposto;
use App\Models\NotaCredito;
use App\Models\Produto;
use App\Models\Recibo;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DateTime;
use DOMDocument;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use phpseclib\Crypt\RSA;


class AGTController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => "Administração Geral Tributária",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.agt.index', $head); 
    }

    public function exportar()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Administração Geral Tributária",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.agt.exportar', $head); 
    }

    public function refazer()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Administração Geral Tributária",
            "descricao" => "Refazer",
            "empresa" => $entidade,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.agt.refazer', $head); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $StartDate = date("Y-m-d", strtotime($request->data_inicio));
        // $EndDate = date("Y-m-d", strtotime($request->data_final));

        $StartDate = str_replace("T", " ", $request->data_inicio) . " 00:00";;
        $EndDate = str_replace("T", " ", $request->data_final) . " 00:59";

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;


        $root = $dom->createElement('AuditFile');
        $root->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
        $root->setAttribute('xsi:schemaLocation', "urn:OECD:StandardAuditFile-Tax:AO_1.01_01 SAFTAO1.01_01.xsd");
        $root->setAttribute('xmlns', "urn:OECD:StandardAuditFile-Tax:AO_1.01_01");
        $dom->appendChild($root);

        $header = $dom->createElement('Header');
        $header->appendChild($dom->createElement('AuditFileVersion', '1.01_01'));

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $header->appendChild($dom->createElement('CompanyID', $entidade->empresa->nif));
        $header->appendChild($dom->createElement('TaxRegistrationNumber', $entidade->empresa->nif));
        $header->appendChild($dom->createElement('TaxAccountingBasis', 'F'));
        $header->appendChild($dom->createElement('CompanyName', $entidade->empresa->nome));
        $header->appendChild($dom->createElement('BusinessName', $entidade->empresa->nome));
        //create companyAddress
        $companyAddress = $dom->createElement('CompanyAddress');
        $companyAddress->appendChild($dom->createElement('AddressDetail', $entidade->empresa->morada));
        $companyAddress->appendChild($dom->createElement('City', $entidade->empresa->cidade));
        $companyAddress->appendChild($dom->createElement('Country', 'AO'));
        $header->appendChild($companyAddress);
        $header->appendChild($dom->createElement('FiscalYear', Carbon::parse(Carbon::now())->format('Y')));
        $header->appendChild($dom->createElement('StartDate', date_format(date_create($StartDate), "Y-m-d")));
        $header->appendChild($dom->createElement('EndDate', date_format(date_create($EndDate), "Y-m-d")));
        $header->appendChild($dom->createElement('CurrencyCode', 'AOA'));

        $dateNow = date_format(new DateTime(Carbon::now()->addHour()->toDateTimeString()), 'Y-m-d');
        $header->appendChild($dom->createElement('DateCreated', $dateNow));
        $header->appendChild($dom->createElement('TaxEntity', 'Global'));
        $header->appendChild($dom->createElement('ProductCompanyTaxID', $entidade->empresa->nif));
        $header->appendChild($dom->createElement('SoftwareValidationNumber', '469/AGT/2024'));
        $header->appendChild($dom->createElement('ProductID', 'EA VIEGAS/EA VIEGAS - COMERCIO GERAL E PRESTAÇAO DE SERVIÇOS , LDA'));
        $header->appendChild($dom->createElement('ProductVersion', '1.0.0'));
        $header->appendChild($dom->createElement('Telephone', $entidade->empresa->telefone));
        $header->appendChild($dom->createElement('Email', $entidade->empresa->novidade_email));
        $header->appendChild($dom->createElement('Website', $entidade->empresa->website));
        $root->appendChild($header);
    
        //MasterFiles
        $masterFiles = $dom->createElement('MasterFiles');

        $consumidor_final = Cliente::where('entidade_id', $entidade->empresa->id)->where('nome', 'CONSUMIDOR FINAL')->first();
        $clientes = Cliente::where('entidade_id', $entidade->empresa->id)->get();
       
        foreach ($clientes as $key => $cliente) {
        
            if ($cliente->nif == '999999999') {
                $CustomerID = $consumidor_final->id;
                $AccountID = "Desconhecido";
                $CustomerTaxID = $consumidor_final->nif;
                $CompanyName = "Consumidor Final";
                $AddressDetail = "Desconhecido";
                $City = "Desconhecido";
                $PostalCode = "Desconhecido";
                $Country = "Desconhecido";
                ++$key;
                if ($key > 1) {
                    continue;
                }
            } else {
                $CustomerID = $cliente->id;
                $AccountID =  $cliente->conta_corrente;
                $CustomerTaxID = "999999999"; // $cliente->nif;
                $CompanyName = $cliente->nome ?? "Desconhecido";
                $AddressDetail = $cliente->morada ?? "Desconhecido";
                $City = $cliente->localidade ??  "Desconhecido";
                $PostalCode = "*";
                $Country = "AO";
            }
            
            // dd($AccountID);
           // dd($CompanyName);

            $customer = $dom->createElement('Customer');
            $customer->appendChild($dom->createElement('CustomerID', $CustomerID));
            $customer->appendChild($dom->createElement('AccountID', $AccountID));
            $customer->appendChild($dom->createElement('CustomerTaxID', $CustomerTaxID));
            $customer->appendChild($dom->createElement('CompanyName', $CompanyName ?? "Desconhecido"));
            //BillingAddress
            $billingAddress = $dom->createElement('BillingAddress');
            $billingAddress->appendChild($dom->createElement('AddressDetail', $AddressDetail));
            $billingAddress->appendChild($dom->createElement('City', $City));
            $billingAddress->appendChild($dom->createElement('PostalCode', $PostalCode));
            $billingAddress->appendChild($dom->createElement('Country', $Country));
            $customer->appendChild($billingAddress);
            $customer->appendChild($dom->createElement('SelfBillingIndicator', 0));
            $masterFiles->appendChild($customer);
        }
        
        $root->appendChild($masterFiles);

        $produtos = Produto::where('entidade_id', $entidade->empresa->id)->get();

        foreach ($produtos as $key => $produto) {
            $product = $dom->createElement('Product');
            $product->appendChild($dom->createElement('ProductType', $produto->tipo));
            $product->appendChild($dom->createElement('ProductCode', $produto->id));
            $product->appendChild($dom->createElement('ProductGroup', 'N/A'));
            $product->appendChild($dom->createElement('ProductDescription', $produto->nome));
            $product->appendChild($dom->createElement('ProductNumberCode', $produto->id));
            $masterFiles->appendChild($product);
        }

        $taxas = $this->listarTaxas($produtos);
  
        $taxTable = $dom->createElement('TaxTable');

        foreach ($taxas as $tipoTaxa) {
            
            $imposto = Imposto::findOrFail($tipoTaxa);
            
            $taxTableEntry = $dom->createElement('TaxTableEntry');
            $taxTableEntry->appendChild($dom->createElement('TaxType', "IVA"));
            // $taxTableEntry->appendChild($dom->createElement('TaxType', $imposto->tax_type));
            $taxTableEntry->appendChild($dom->createElement('TaxCountryRegion', 'AO'));
            $taxTableEntry->appendChild($dom->createElement('TaxCode', $imposto->codigo));
            $taxTableEntry->appendChild($dom->createElement('Description', $imposto->text));
            $taxTableEntry->appendChild($dom->createElement('TaxPercentage', number_format($imposto->valor, 1, ".", "")));
            $taxTable->appendChild($taxTableEntry);
        }  

        $masterFiles->appendChild($taxTable);

        // QDT FT E FR (STARTDATE E ENDDATE)
        $quantFtFr = Venda::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('entidade_id', $entidade->empresa->id)
            ->where(function ($query) {
                $query->where('factura', 'FT')
                    ->orWhere('factura', 'FR');
            })
            ->count();
        
        //OBS: adicionar aqui Qtds notas de creditos (facturas e facturas recibos anulados ou retificados)

        $TotalCredit = Venda::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('entidade_id', $entidade->empresa->id)
            ->where('anulado', 'N')
            ->where('retificado', 'N')
            ->where(function ($query) {
                $query->where('factura', 'FT')
                    ->orWhere('factura', 'FR');
            })
            ->sum('total_incidencia');

        $TotalCreditRetificada = FacturaOriginal::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('entidade_id', $entidade->empresa->id)
            ->where('anulado', 'N')
            ->where('retificado', 'N')
            ->where(function ($query) {
                $query->where('factura', 'FT')
                    ->orWhere('factura', 'FR');
            })
            ->sum('total_incidencia');


        $TotalCredit = $TotalCredit + $TotalCreditRetificada;

        $sourceDocuments = $dom->createElement('SourceDocuments');

        //LISTAR FACTURAS E FACTURAS RECIBOS
        $facturas = Venda::with(['items', 'items.produto', 'items.produto.motivo'])
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('entidade_id', $entidade->empresa->id)
            ->where(function ($query) {
                $query->where('factura', 'FT')
                    ->orWhere('factura', 'FR');
            })->get();

        if(count($facturas) > 0){
            $salesInvoices = $dom->createElement('SalesInvoices');
        }


        $clienteDiverso = Cliente::where('entidade_id', $entidade->empresa->id)->first();

        $notaCreditoIDs = [];

        $invoiceFTEFRs = [];


        foreach ($facturas as $key => $factura) {

            if ($factura->anulado == 'Y') {
                array_push($notaCreditoIDs, $factura->id);
            }

            if ($factura->retificado == 'Y') {

                $factura = FacturaOriginal::with(['items', 'items.produto', 'items.produto.motivo'])
                    ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
                    ->where('entidade_id', $entidade->empresa->id)
                    ->where('id', $factura->id)->first();

                array_push($notaCreditoIDs, $factura->id);
            }

            $InvoiceNo = $factura->factura_next;
            $InvoiceStatusDate = $factura->created_at;
            $SourceID = $factura->user_id;
            $Hash = $factura->hash;
            $InvoiceDate = Carbon::parse($factura->created_at)->format('Y-m-d');
            $InvoiceType = $factura->factura;
            $SystemEntryDate = str_replace(' ', 'T', $factura->created_at);
            $CustomerID = $factura->nif_cliente == '999999999' ? $clienteDiverso->id : $factura->cliente_id;

            $invoice = $dom->createElement('Invoice');
            $invoice->appendChild($dom->createElement('InvoiceNo', $InvoiceNo));
            $documentStatus = $dom->createElement('DocumentStatus');
            $InvoiceStatus = $factura->anulado == 'Y' ? "A" : "N";
            $documentStatus->appendChild($dom->createElement('InvoiceStatus', $InvoiceStatus));
            $documentStatus->appendChild($dom->createElement('InvoiceStatusDate', (Carbon::parse($InvoiceStatusDate)->format('Y-m-d') . "T" . Carbon::parse($InvoiceStatusDate)->format("H:i:s"))));
            $documentStatus->appendChild($dom->createElement('SourceID', $SourceID));
            $documentStatus->appendChild($dom->createElement('SourceBilling', 'P'));
            $invoice->appendChild($documentStatus); //Add documentStatus no Invoice
            $invoice->appendChild($dom->createElement('Hash', $Hash));
            $invoice->appendChild($dom->createElement('HashControl', '1'));
            $invoice->appendChild($dom->createElement('Period', Carbon::parse($StartDate)->format('m')));
            $invoice->appendChild($dom->createElement('InvoiceDate', $InvoiceDate));
            $invoice->appendChild($dom->createElement('InvoiceType', $InvoiceType));
            $specialRegimes = $dom->createElement('SpecialRegimes');
            $specialRegimes->appendChild($dom->createElement('SelfBillingIndicator', 0));
            $specialRegimes->appendChild($dom->createElement('CashVATSchemeIndicator', 0));
            $specialRegimes->appendChild($dom->createElement('ThirdPartiesBillingIndicator', 0));
            $invoice->appendChild($specialRegimes); //add specialRegimes no Invoice
            $invoice->appendChild($dom->createElement('SourceID', $SourceID));
            $invoice->appendChild($dom->createElement('SystemEntryDate', $SystemEntryDate));
            $invoice->appendChild($dom->createElement('CustomerID', $CustomerID));
            //Criar Line de Invoice foreach
            foreach ($factura->items as $key => $Item) {
                
                $Item = (object) $Item;
                $line = $dom->createElement('Line');
                $line->appendChild($dom->createElement('LineNumber', $key + 1));
                $line->appendChild($dom->createElement('ProductCode', $Item->produto_id));
                $line->appendChild($dom->createElement('ProductDescription', $Item->produto->nome));
                $line->appendChild($dom->createElement('Quantity', number_format($Item->quantidade, 1, ".", "")));
                $line->appendChild($dom->createElement('UnitOfMeasure', ($Item->produto->unidade ? $Item->produto->unidade : "un")));
                $line->appendChild($dom->createElement('UnitPrice', number_format($Item->preco_unitario, 2, ".", "")));
                $line->appendChild($dom->createElement('TaxPointDate', Carbon::parse($factura->created_at)->format('Y-m-d')));
                $Description = $factura->observacao ? $factura->observacao : 'FACTURA ' . $factura->factura_next;
                $line->appendChild($dom->createElement('Description', $Description));
                $line->appendChild($dom->createElement('CreditAmount', number_format($Item->valor_base, 2, ".", "")));
                
                $imposto = Imposto::findOrFail($Item->produto->imposto_id);

                // if ($Item->produto->taxa > 0) {
                //     $TaxExemptionReason = "#";
                //     $TaxExemptionCode = "#";
                // } else {
                    $TaxExemptionReason = $Item->produto->motivo->descricao;
                    $TaxExemptionCode = $Item->produto->motivo->codigo;
                // }
                          //Criar Taixa e seus filhos
                $tax = $dom->createElement('Tax');
                // $tax->appendChild($dom->createElement('TaxType', $imposto->tax_type));
                $tax->appendChild($dom->createElement('TaxType', "IVA"));
                $tax->appendChild($dom->createElement('TaxCountryRegion', 'AO'));
                $tax->appendChild($dom->createElement('TaxCode', $imposto->codigo));
                $tax->appendChild($dom->createElement('TaxPercentage', number_format($imposto->valor, 1, ".", "")));
                $line->appendChild($tax); //Add Fax na Line
                $line->appendChild($dom->createElement('TaxExemptionReason', $TaxExemptionReason));
                $line->appendChild($dom->createElement('TaxExemptionCode', $TaxExemptionCode));
                $line->appendChild($dom->createElement('SettlementAmount', $Item->desconto_aplicado_valor));
                $invoice->appendChild($line);
            }

            // if ($factura->anulado == 1) {
            //     $TotalCredit += $factura->total_incidencia;
            // }

            //criar  DocumentTotals e seus filhos
            $documentTotals = $dom->createElement('DocumentTotals');
            $documentTotals->appendChild($dom->createElement('TaxPayable', number_format($factura->total_iva, 2, ".", "")));
            $documentTotals->appendChild($dom->createElement('NetTotal', number_format($factura->total_incidencia, 2, ".", "")));

            $GrossTotal = $factura->valor_total;
            // $GrossTotal = $factura->total_iva + $factura->total_incidencia;

            $documentTotals->appendChild($dom->createElement('GrossTotal', number_format($GrossTotal + $factura->total_retencao_fonte, 2, ".", "")));
            $invoice->appendChild($documentTotals);
            $payment = $dom->createElement('Payment');
            $PaymentMechanism = $factura->pagamento ? $factura->pagamento : "OU";

            $payment->appendChild($dom->createElement('PaymentMechanism', $PaymentMechanism));
            $payment->appendChild($dom->createElement('PaymentAmount', number_format($GrossTotal, 2, ".", "")));
            $payment->appendChild($dom->createElement('PaymentDate', Carbon::parse($factura->created_at)->format('Y-m-d')));
            $documentTotals->appendChild($payment);
            // $salesInvoices->appendChild($invoice);
            
            
            //criar  DocumentTotals e seus filhos
            $WithholdingTax = $dom->createElement('WithholdingTax');
            $WithholdingTax->appendChild($dom->createElement('WithholdingTaxType', "IRT"));
            $WithholdingTax->appendChild($dom->createElement('WithholdingTaxDescription', "Aplicação da Retenção"));
            $WithholdingTax->appendChild($dom->createElement('WithholdingTaxAmount', number_format($factura->total_retencao_fonte ?? 0, 2, ".", "")));
            $invoice->appendChild($WithholdingTax);
            
            
            // $WithholdingTax->appendChild($payment);
            
            $invoiceFTEFRs[] = $invoice;
            // $salesInvoices->appendChild($invoice);
            //Fim foreach
        }
        
        
        //Notas de crédito
        $notaCreditos = NotaCredito::with([
            'items',
            'facturas', 'items.produto',
            'items.produto.motivo'
        ])
        ->where('entidade_id', $entidade->empresa->id)
        ->whereIn('factura_id', $notaCreditoIDs)->get();


        $TotalDebit = 0;

        if ($notaCreditos) {
            $invoiceNCs = [];
            $quantNotaCredito = 0;
            foreach ($notaCreditos as $key => $notaCredito) {

                $quantNotaCredito++;

                $InvoiceNo = $notaCredito->factura_next;
                $InvoiceStatusDate = $notaCredito->created_at;
                $SourceID = $notaCredito->user_id;
                $Hash = $notaCredito->hash;
                $InvoiceDate = Carbon::parse($notaCredito->facturas->created_at)->format('Y-m-d');
                $SystemEntryDate = str_replace(' ', 'T', $notaCredito->created_at);
                $CustomerID = $notaCredito->nif_cliente == '999999999' ? $clienteDiverso->id : $notaCredito->cliente_id;

                $invoice = $dom->createElement('Invoice');
                $invoice->appendChild($dom->createElement('InvoiceNo', $InvoiceNo));
                $documentStatus = $dom->createElement('DocumentStatus');
                $documentStatus->appendChild($dom->createElement('InvoiceStatus', "N"));
                $documentStatus->appendChild($dom->createElement('InvoiceStatusDate', (Carbon::parse($InvoiceStatusDate)->format('Y-m-d') . "T" . Carbon::parse($InvoiceStatusDate)->format("H:i:s"))));
                $documentStatus->appendChild($dom->createElement('SourceID', $SourceID));
                $documentStatus->appendChild($dom->createElement('SourceBilling', 'P'));
                $invoice->appendChild($documentStatus); //Add documentStatus no Invoice
                $invoice->appendChild($dom->createElement('Hash', $Hash));
                $invoice->appendChild($dom->createElement('HashControl', '1'));
                $invoice->appendChild($dom->createElement('Period', Carbon::parse($StartDate)->format('m')));
                $invoice->appendChild($dom->createElement('InvoiceDate', $InvoiceDate));
                $invoice->appendChild($dom->createElement('InvoiceType', 'NC'));
                $specialRegimes = $dom->createElement('SpecialRegimes');
                $specialRegimes->appendChild($dom->createElement('SelfBillingIndicator', 0));
                $specialRegimes->appendChild($dom->createElement('CashVATSchemeIndicator', 0));
                $specialRegimes->appendChild($dom->createElement('ThirdPartiesBillingIndicator', 0));
                $invoice->appendChild($specialRegimes); //add specialRegimes no Invoice
                $invoice->appendChild($dom->createElement('SourceID', $SourceID));
                $invoice->appendChild($dom->createElement('SystemEntryDate', $SystemEntryDate));
                $invoice->appendChild($dom->createElement('CustomerID', $CustomerID));
                //Criar Line de Invoice foreach

                foreach ($notaCredito->items as $key => $Item) {

                    $Item = (object) $Item;
                    $line = $dom->createElement('Line');
                    $line->appendChild($dom->createElement('LineNumber', $key + 1));
                    $line->appendChild($dom->createElement('ProductCode', $Item->produto_id));
                    $line->appendChild($dom->createElement('ProductDescription', $Item->produto->nome));
                    $line->appendChild($dom->createElement('Quantity', number_format($Item->quantidade, 1, ".", "")));
                    $line->appendChild($dom->createElement('UnitOfMeasure', ($Item->produto->unidade ? $Item->produto->unidade : "un")));
                    $line->appendChild($dom->createElement('UnitPrice', number_format($Item->preco_unitario, 2, ".", "")));
                    $line->appendChild($dom->createElement('TaxPointDate', Carbon::parse($notaCredito->created_at)->format('Y-m-d')));
                    $Description = $notaCredito->observacao ? $notaCredito->observacao : 'FACTURA ANULADA OU RETIFICADA ' . $notaCredito->facturas->factura_next;
                    $References = $dom->createElement('References');
                    $References->appendChild($dom->createElement('Reference', $notaCredito->facturas->factura_next));
                    $References->appendChild($dom->createElement('Reason', $Description));
                    $line->appendChild($References);
                    $line->appendChild($dom->createElement('Description', $Description));
                    $line->appendChild($dom->createElement('DebitAmount', number_format($Item->valor_base, 2, ".", "")));

                    
                    $imposto = Imposto::findOrFail($Item->produto->imposto_id);
            
                    // if ($Item->produto->taxa > 0) {
                    //     $TaxExemptionReason = "#";
                    //     $TaxExemptionCode = "#";
                    // } else {
                    $TaxExemptionReason = $Item->produto->motivo->descricao;
                    $TaxExemptionCode = $Item->produto->motivo->codigo;
                    // }
                 
                    //Criar Taixa e seus filhos
                    $tax = $dom->createElement('Tax');
                    $tax->appendChild($dom->createElement('TaxType', "IVA"));
                    // $tax->appendChild($dom->createElement('TaxType', $imposto->tax_type));
                    $tax->appendChild($dom->createElement('TaxCountryRegion', 'AO'));
                    $tax->appendChild($dom->createElement('TaxCode', $imposto->codigo));
                    $tax->appendChild($dom->createElement('TaxPercentage', number_format($imposto->valor, 1, ".", "")));
                    $line->appendChild($tax); //Add Fax na Line
                    $line->appendChild($dom->createElement('TaxExemptionReason', $TaxExemptionReason));
                    $line->appendChild($dom->createElement('TaxExemptionCode', $TaxExemptionCode));
                    $line->appendChild($dom->createElement('SettlementAmount', $Item->desconto_aplicado_valor));
                    $invoice->appendChild($line);
                }

                $TotalDebit += $notaCredito->total_incidencia;

                //criar  DocumentTotals e seus filhos
                $documentTotals = $dom->createElement('DocumentTotals');
                $documentTotals->appendChild($dom->createElement('TaxPayable', number_format($notaCredito->total_iva, 2, ".", "")));
                $documentTotals->appendChild($dom->createElement('NetTotal', number_format($notaCredito->total_incidencia, 2, ".", "")));

                // $GrossTotal = $notaCredito->total_iva + $notaCredito->total_incidencia;
                $GrossTotal = $notaCredito->valor_total;

                $documentTotals->appendChild($dom->createElement('GrossTotal', number_format($GrossTotal, 2, ".", "")));
                $invoice->appendChild($documentTotals);
                $payment = $dom->createElement('Payment');
                $PaymentMechanism = $factura->pagamento ? $factura->pagamento : "OU";

                $payment->appendChild($dom->createElement('PaymentMechanism', $PaymentMechanism));
                $payment->appendChild($dom->createElement('PaymentAmount', number_format($GrossTotal, 2, ".", "")));
                $payment->appendChild($dom->createElement('PaymentDate', Carbon::parse($notaCredito->created_at)->format('Y-m-d')));
                $documentTotals->appendChild($payment);

                // $salesInvoices->appendChild($invoice);

                $invoiceNCs[] = $invoice;
            }
        }

        if(count($facturas) > 0){
            $NumberOfEntries =  $quantFtFr + $quantNotaCredito;
            $salesInvoices->appendChild($dom->createElement('NumberOfEntries', $NumberOfEntries));
            $salesInvoices->appendChild($dom->createElement('TotalDebit', number_format($TotalDebit, 2, ".", "")));
            $salesInvoices->appendChild($dom->createElement('TotalCredit', number_format($TotalCredit, 2, ".", "")));
            $sourceDocuments->appendChild($salesInvoices);    
        }


        //faz o array para colocar os invoices enbaixo das NumberOfEntries
        foreach ($invoiceFTEFRs as $invoiceFTEFR) {
            $salesInvoices->appendChild($invoiceFTEFR);
        }
        foreach ($invoiceNCs as $invoiceNC) {
            $salesInvoices->appendChild($invoiceNC);
        }


        //MovementOfGoods
        $movementOfGoods = $dom->createElement('MovementOfGoods');
        $movementOfGoods->appendChild($dom->createElement('NumberOfMovementLines', 0));
        $movementOfGoods->appendChild($dom->createElement('TotalQuantityIssued', 0.00));
        $sourceDocuments->appendChild($movementOfGoods);
        //fim MovementOfGoods


        //Lista apenas facturas proformas
        $countFtProforma = Venda::where('entidade_id', $entidade->empresa->id)->where('factura', 'PP')
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->count();

        $TotalDebit = 0;

        $facturas_proforma = Venda::with(['items', 'items.produto', 'items.produto.motivo'])
            ->where('entidade_id', $entidade->empresa->id)
            ->where('factura', 'PP')
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->get();

        $TotalCredit = Venda::with(['items','items.produto', 'items.produto.motivo'])
            ->where('entidade_id', $entidade->empresa->id)
            ->where('factura', 'PP')
            ->where('anulado', 'N') //Nao anulado
            ->where('retificado','N')
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->sum('total_incidencia');

        $TotalCreditRetificada = FacturaOriginal::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('entidade_id', $entidade->empresa->id)
            ->where('factura', 'PP')
            ->where('anulado', 'N') //Nao anulado
            ->where('retificado','N')
            ->sum('total_incidencia');
            
        $TotalCredit = $TotalCredit + $TotalCreditRetificada;

        $workingDocuments = $dom->createElement('WorkingDocuments');

        $workingDocuments->appendChild($dom->createElement('NumberOfEntries', $countFtProforma));
        $workingDocuments->appendChild($dom->createElement('TotalDebit', $TotalDebit));
        $workingDocuments->appendChild($dom->createElement('TotalCredit', number_format($TotalCredit, 2, ".", "")));
        $sourceDocuments->appendChild($workingDocuments);


        foreach ($facturas_proforma as $key => $facturaProforma) {

            $WorkDocument = $dom->createElement('WorkDocument');
            $DocumentNumber = $facturaProforma->factura_next;
            $WorkDocument->appendChild($dom->createElement('DocumentNumber', $DocumentNumber));
            $DocumentStatus = $dom->createElement('DocumentStatus');

            $DocumentStatus->appendChild($dom->createElement('WorkStatus', 'N'));
            $DocumentStatus->appendChild($dom->createElement('WorkStatusDate', (Carbon::parse($facturaProforma->updated_at)->format('Y-m-d') . "T" . Carbon::parse($facturaProforma->updated_at)->format("H:i:s"))));
            $DocumentStatus->appendChild($dom->createElement('Reason', '#'));
            $DocumentStatus->appendChild($dom->createElement('SourceID', $facturaProforma->user_id));
            $DocumentStatus->appendChild($dom->createElement('SourceBilling', 'P'));
            $WorkDocument->appendChild($DocumentStatus);
            $WorkDocument->appendChild($dom->createElement('Hash', $facturaProforma->hash));
            $WorkDocument->appendChild($dom->createElement('HashControl', '1'));
            $WorkDocument->appendChild($dom->createElement('Period', Carbon::parse($StartDate)->format('m')));
            $WorkDocument->appendChild($dom->createElement('WorkDate', Carbon::parse($facturaProforma->created_at)->format('Y-m-d')));
            $WorkDocument->appendChild($dom->createElement('WorkType', $facturaProforma->factura));
            $WorkDocument->appendChild($dom->createElement('SourceID', $facturaProforma->user_id));
            $WorkDocument->appendChild($dom->createElement('SystemEntryDate', (Carbon::parse($facturaProforma->created_at)->format('Y-m-d') . "T" . Carbon::parse($facturaProforma->created_at)->format("H:i:s"))));
            $WorkDocument->appendChild($dom->createElement('TransactionID', '#'));
            $WorkDocument->appendChild($dom->createElement('CustomerID', (string) ($facturaProforma->cliente->nif == '999999999' ? $clienteDiverso->id : $facturaProforma->cliente_id)));


            foreach ($facturaProforma->items as $key => $ftProformaItem) {

                $line = $dom->createElement('Line');
                $line->appendChild($dom->createElement('LineNumber', $key + 1));
                $line->appendChild($dom->createElement('ProductCode', $ftProformaItem->produto_id));
                $line->appendChild($dom->createElement('ProductDescription', $ftProformaItem->produto->nome));
                $line->appendChild($dom->createElement('Quantity', number_format($ftProformaItem->quantidade, 1, ".", "")));
                $line->appendChild($dom->createElement('UnitOfMeasure', ($ftProformaItem->produto->unidade ? $ftProformaItem->produto->unidade : "un")));
                $line->appendChild($dom->createElement('UnitPrice', number_format($ftProformaItem->preco_unitario, 2, ".", "")));
                $line->appendChild($dom->createElement('TaxPointDate', Carbon::parse($facturaProforma->created_at)->format('Y-m-d')));
                $line->appendChild($dom->createElement('Description', $facturaProforma->observacao ? $facturaProforma->observacao : 'FACTURA ' . $facturaProforma->factura_next));
                $line->appendChild($dom->createElement('CreditAmount', number_format($ftProformaItem->valor_base, 2, ".", "")));
                
                $imposto = Imposto::findOrFail($ftProformaItem->produto->imposto_id);
            
                // if ($ftProformaItem->produto->taxa > 0) {
                //     $TaxExemptionReason = "#";
                //     $TaxExemptionCode = "#";
                // } else {
                    $TaxExemptionReason = $ftProformaItem->produto->motivo->descricao;
                    $TaxExemptionCode = $ftProformaItem->produto->motivo->codigo;
                // }

                //Criar Taxa e seus filhos
                $tax = $dom->createElement('Tax');
                $tax->appendChild($dom->createElement('TaxType', "IVA"));
                // $tax->appendChild($dom->createElement('TaxType', $imposto->tax_type));
                $tax->appendChild($dom->createElement('TaxCountryRegion', 'AO'));
                $tax->appendChild($dom->createElement('TaxCode', $imposto->codigo));
                $tax->appendChild($dom->createElement('TaxPercentage', number_format($imposto->valor, 1, ".", "")));
                $line->appendChild($tax); //Add Fax na Line
                $line->appendChild($dom->createElement('TaxExemptionReason', $TaxExemptionReason));
                $line->appendChild($dom->createElement('TaxExemptionCode', $TaxExemptionCode));
                $line->appendChild($dom->createElement('SettlementAmount', $ftProformaItem->desconto_aplicado_valor));
                $WorkDocument->appendChild($line);
            }
            $workingDocuments->appendChild($WorkDocument);

            //criar  DocumentTotals e seus filhos
            $documentTotals = $dom->createElement('DocumentTotals');
            $documentTotals->appendChild($dom->createElement('TaxPayable', number_format($facturaProforma->total_iva, 2, ".", "")));
            $documentTotals->appendChild($dom->createElement('NetTotal', number_format($facturaProforma->total_incidencia, 2, ".", "")));

            $GrossTotal = $facturaProforma->valor_total;
            // $GrossTotal = $facturaProforma->total_iva + $facturaProforma->total_incidencia;

            $documentTotals->appendChild($dom->createElement('GrossTotal', number_format($GrossTotal, 2, ".", "")));
            $WorkDocument->appendChild($documentTotals);
        }


        /**
         * Preenche SourceDocuments->Payments
         */
        //Qtd de recibos(incluindo os anulados)
        $quantRecibos = Recibo::whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->where('entidade_id', $entidade->empresa->id)
            ->count();


        $TotalCredit = DB::table('recibos')
            ->where('anulado', 'N') //recibo não anulado
            ->whereBetween(DB::raw('DATE(recibos.created_at)'), array($StartDate, $EndDate))
            ->where('entidade_id', $entidade->empresa->id)->sum('valor_total');

        $TotalDebit = 0;
        $TotalDebit = number_format($TotalDebit, 2, ".", "");
        $TotalCredit = number_format($TotalCredit, 2, ".", "");


         /**
         * Preenche SourceDocuments->Payments->Payment
         */
        $recibos = Recibo::with(['cliente', 'facturas'])->where('entidade_id', $entidade->empresa->id)
            ->whereBetween(DB::raw('DATE(created_at)'), array($StartDate, $EndDate))
            ->get();

        //Payments
        $payments = $dom->createElement('Payments');
        $payments->appendChild($dom->createElement('NumberOfEntries', $quantRecibos));
        $payments->appendChild($dom->createElement('TotalDebit', $TotalDebit));
        $payments->appendChild($dom->createElement('TotalCredit', $TotalCredit));
        $sourceDocuments->appendChild($payments);

        foreach ($recibos as $key => $recibo) {


            $Payment = $dom->createElement('Payment');
            $Payment->appendChild($dom->createElement('PaymentRefNo', $recibo->factura_next));
            $Payment->appendChild($dom->createElement('Period', Carbon::parse($StartDate)->format('m')));
            $Payment->appendChild($dom->createElement('TransactionDate', Carbon::parse($recibo->created_at)->format('Y-m-d')));
            $Payment->appendChild($dom->createElement('PaymentType', 'RG'));
            
            // dd($recibo);

            $Description = $recibo->observacao ? $recibo->observacao : 'Liquidação da factura ' . ($recibo->facturas ? $recibo->facturas->factura_next : "") ;

            $Payment->appendChild($dom->createElement('Description', $Description));
            $Payment->appendChild($dom->createElement('SystemID', $recibo->id));
            $payments->appendChild($Payment);

            /**
             * Preenche SourceDocuments->Payments->Payment->DocumentStatus
             */

            $PaymentStatus = $recibo->anulado == 'N' ? "N" : "A";

            $DocumentStatus = $dom->createElement('DocumentStatus');
            $DocumentStatus->appendChild($dom->createElement('PaymentStatus', $PaymentStatus));
            $DocumentStatus->appendChild($dom->createElement('PaymentStatusDate', Carbon::parse($recibo->updated_at)->format('Y-m-d') . "T" . Carbon::parse($recibo->updated_at)->format("H:i:s")));
            $DocumentStatus->appendChild($dom->createElement('SourceID', $recibo->user_id));
            $DocumentStatus->appendChild($dom->createElement('SourcePayment', 'P'));
            $Payment->appendChild($DocumentStatus);

            /**
             * Preenche SourceDocuments->Payments->Payment->PaymentMethod
             */

            $PaymentMethod = $dom->createElement('PaymentMethod');
            $PaymentMethod->appendChild($dom->createElement('PaymentMechanism', $recibo->pagamento));
            $PaymentMethod->appendChild($dom->createElement('PaymentAmount', $TotalDebit = number_format($recibo->valor_total, 2, ".", "")));
            $PaymentMethod->appendChild($dom->createElement('PaymentDate', Carbon::parse($recibo->created_at)->format('Y-m-d')));
            $Payment->appendChild($PaymentMethod);
            $Payment->appendChild($dom->createElement('SourceID', $recibo->user_id));
            $Payment->appendChild($dom->createElement('SystemEntryDate', Carbon::parse($recibo->created_at)->format('Y-m-d') . "T" . Carbon::parse($recibo->updated_at)->format("H:i:s")));

            if ($recibo->cliente->nif == '999999999') {
                $CustomerID = $clienteDiverso->id;
            } else {
                $CustomerID = $recibo->cliente_id;
            }

            $Payment->appendChild($dom->createElement('CustomerID', $CustomerID));
            $Line = $dom->createElement('Line');
            $Line->appendChild($dom->createElement('LineNumber', ++$key));
            $SourceDocumentID = $dom->createElement('SourceDocumentID');

            // dd();
            // $factura = DB::connection('mysql2')->table('facturas')
            //     ->where('id', $recibo->recibos_items[0]['factura_id'])
            //     ->where('empresa_id', auth()->user()->empresa_id)->first();

            $SourceDocumentID->appendChild($dom->createElement('OriginatingON', ($recibo->facturas ? $recibo->facturas->factura_next : "")));
            $SourceDocumentID->appendChild($dom->createElement('InvoiceDate', Carbon::parse($recibo->facturas->created_at)->format('Y-m-d')));
            $SourceDocumentID->appendChild($dom->createElement('Description', $Description));
            $Line->appendChild($SourceDocumentID);
            $Line->appendChild($dom->createElement('CreditAmount', number_format($recibo->valor_total, 2, ".", "")));
            $Payment->appendChild($Line);

            /**
             * Preenche SourceDocuments->Payments->Payment->DocumentTotals
             */
            $TaxPayable = 0;

            $DocumentTotals = $dom->createElement('DocumentTotals');
            $DocumentTotals->appendChild($dom->createElement('TaxPayable', number_format($TaxPayable, 2, ".", "")));
            $DocumentTotals->appendChild($dom->createElement('NetTotal', number_format($recibo->valor_total, 2, ".", "")));
            $DocumentTotals->appendChild($dom->createElement('GrossTotal', number_format($recibo->valor_total, 2, ".", "")));
            $Payment->appendChild($DocumentTotals);
        }


        $purchaseInvoices = $dom->createElement('PurchaseInvoices');
        $purchaseInvoices->appendChild($dom->createElement('NumberOfEntries', 0));
        //add PurchaseInvoices em sourceDocuments
        $sourceDocuments->appendChild($purchaseInvoices);
        $root->appendChild($sourceDocuments);

        $dom = $dom->saveXML();
        $dom = str_replace("<TransactionID>#</TransactionID>", "", $dom);
        $dom = str_replace("<TaxExemptionReason>#</TaxExemptionReason>", "", $dom);
        $dom = str_replace("<TaxExemptionCode>#</TaxExemptionCode>", "", $dom);
        $dom = str_replace("<Reason>#</Reason>", "", $dom);
        
        // $filename = "saft_" . date("d") . '_' . date("m") . '_' . date("Y");
        $filename = "saft_" . date("d") . '_' . date("m") . '_' . date("Y") . ' _' . $request->data_inicio . ' - ' .$request->data_final;
      
        return response()->streamDownload(function () use ($dom) {
            echo $dom;
        }, $filename . '.xml');
        
        // return FacadesResponse::make($dom, 200)->header('Content-Type', 'application/xml');
        
        // $headers = [
        //     'Content-Type' => 'application/xml',
        //     'Content-Disposition' => 'attachment; filename="documento.xml"',
        // ];
        
        // return response($dom, 200, $headers);
        
        
        // $dom = $dom->saveXML();
        // $dom = str_replace("<TransactionID>#</TransactionID>", "", $dom);
        // $dom = str_replace("<TaxExemptionReason>#</TaxExemptionReason>", "", $dom);
        // $dom = str_replace("<TaxExemptionCode>#</TaxExemptionCode>", "", $dom);
        // $dom = str_replace("<Reason>#</Reason>", "", $dom);
        
        // $filename = "saft_" . date("d") . '_' . date("m") . '_' . date("Y") . ' _' . $request->data_inicio . ' - ' .$request->data_final;
        
        // $path = storage_path("app/public/saft_files/{$filename}");

        // // Criar diretório se não existir
        // if (!file_exists(storage_path("app/public/saft_files"))) {
        //     mkdir(storage_path("app/public/saft_files"), 0777, true);
        // }
    
        // // Salvar o ficheiro no servidor
        // file_put_contents($path, $dom);
    
        // // URL pública do arquivo
        // $url = asset("storage/saft_files/{$filename}");
    
        // // Retornar resposta JSON com a URL
        // return response()->json([
        //     'success' => true,
        //     'url' => $url,
        //     'filename' => $filename,
        // ]);

        // return response()->streamDownload(function () use ($dom) {
        //     echo $dom;
        // }, $filename . '.xml');
        
        // return response()->streamDownload(function () use ($dom) {
        //     echo $dom;
        // }, $filename, [
        //     'Content-Type' => 'application/xml',
        //     'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        // ]);
        

    }

    public function listarTaxas($produtos)
    {
        $taxas = array();
        foreach ($produtos as $produto) {
            array_push($taxas, $produto->imposto_id);
        }
        $collection = collect($taxas);
        $array = $collection->unique();
        
        return $array;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor

        try {
            DB::beginTransaction();
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
                    
            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
            // 
            $rsa = new RSA(); //Algoritimo RSA
            // Lendo a private key
            $rsa->loadKey($privatekey);
                       
            $pagamentos = Venda::where('entidade_id', Auth::user()->entidade_id)
                ->select('id', 'factura_next', 'codigo_factura', 'hash', 'total_iva', 'total_incidencia', 'created_at')
                ->whereBetween(DB::raw('DATE(created_at)'), array($request->data_inicio, $request->data_final))
                ->where('factura', $request->tipo_documento)
                ->orderBy('id', 'asc')
                ->get();
            
            $previousHash = null; // Para armazenar o hash do pagamento anterior
            
            foreach ($pagamentos as $index => $pagamento) {
                
                $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $pagamento->created_at);
            
                $n = $index + 1;
                if ($n === 1) {
                    // HASH
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                
                    $total_a_pagar = $pagamento->total_iva +  $pagamento->total_incidencia;
                    
                    $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->tipo_documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$n}" . ';' . number_format($total_a_pagar, 2, ".", "") . ';' . "";
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)                
    
                    // Para o primeiro registro, o update será normal
                    $pagamento->update([
                        'created_at' => $pagamento->created_at,
                        'codigo_factura' => $n,
                        'ano_factura' => $entidade->empresa->ano_factura,
                        'factura_next' => "{$request->tipo_documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$n}",
                        'hash' => base64_encode($signaturePlaintext),
                        'texto_hash' => $plaintext, // Exemplo de hash para o primeiro registro
                    ]);
            
                    // Armazena o hash do primeiro pagamento
                    $previousHash = $pagamento->hash;
                }
                
                if($n > 1) {
                                
                    // HASH
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                
                    $total_a_pagar = $pagamento->total_iva +  $pagamento->total_incidencia;
                    $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->tipo_documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$n}" . ';' . number_format($total_a_pagar, 2, ".", "") . ';' . $previousHash;
                    
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)                
                
                    // Para os registros a partir do segundo, usamos o hash do anterior
                    $pagamento->update([
                        'created_at' => $pagamento->created_at,
                        'codigo_factura' => $n,
                        'ano_factura' => $entidade->empresa->ano_factura,
                        'factura_next' => "{$request->tipo_documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$n}",
                        'hash' => base64_encode($signaturePlaintext), // Usa o hash do pagamento anterior
                        'texto_hash' => $plaintext,
                    ]);
            
                    // Armazena o hash atualizado para o próximo pagamento
                    $previousHash = $pagamento->hash;
                }
            }
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        Alert::success('Bom Trabalho', 'Operação realizado com sucesso!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

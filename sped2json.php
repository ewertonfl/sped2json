<?php
/**
 * sped2json.php
 *
 * Gerar JSON de um arquivo SPED. 09/12/2019.
 * Programa desenvolvido para avaliação da empresa Visor.
 * PHP version 7.
 *
 * @author     Ewerton de Oliveira Florencio <ewerton.florencio@yahoo.com.br>
 *
 * Como executar:
 * php sped2json.php <cnpj> <pasta-arquivos-origem> <pasta-arquivos-destino>
 */
 
 
echo "Programa sped2json iniciado. \n";

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 'on');
 
header('Content-type: text/html; charset=UTF-8');

require_once('class/UnidadeNegocio.php');
require_once('class/UnidadeMedida.php');
require_once('class/ClienteFornecedor.php');
require_once('class/ProdutoServico.php');
require_once('class/NotaFiscal.php');
require_once('class/ItemNotaFiscal.php');
require_once('class/TemporizacaoHelper.php');

use Sped2Json\UnidadeNegocio;
use Sped2Json\UnidadeMedida;
use Sped2Json\ClienteFornecedor;
use Sped2Json\ProdutoServico;
use Sped2Json\NotaFiscal;
use Sped2Json\ItemNotaFiscal;
use Sped2Json\TemporizacaoHelper;


$th = new TemporizacaoHelper();
$company_cnpj = strval($argv[1]);
$source_folder = strval($argv[2]);
$destination_folder = strval($argv[3]);
$path="";
$file_path="";
echo "\nParâmetros informados:\nCNPJ: $company_cnpj\nPasta origem: $source_folder\nPasta destino: $destination_folder\n\n";
echo "Validando parâmetros informados... \n";



// Valida parâmetros.
if (strlen($company_cnpj) == 0) {
	echo "Erro 11: O parâmetro <cnpj> é obrigatório!\n";
	echo "Como executar: php sped2json.php <cnpj> <pasta-arquivos-origem> <pasta-arquivos-destino>";
	exit;
}
if (strlen($source_folder) == 0) {
	echo "Erro 12: O parâmetro <pasta-arquivos-origem> é obrigatório!\n";
	echo "Como executar: php sped2json.php <cnpj> <pasta-arquivos-origem> <pasta-arquivos-destino>";
	exit;
}
if (strlen($destination_folder) == 0) {
	echo "Erro 13: O parâmetro <pasta-arquivos-destino> é obrigatório!\n";
	echo "Como executar: php sped2json.php <cnpj> <pasta-arquivos-origem> <pasta-arquivos-destino>";
	exit;
}



// Valida pasta de origem.
if (!is_dir($source_folder)) {
	echo "Erro 21: O diretório de origem informado não existe!\n";
	exit;
}



// Valida pasta de destino e cria caso não exista.
$path=$destination_folder;
if (!is_dir($path)) {
	mkdir($path);
	if (!is_dir($path)) {
		echo "Erro 31: Erro ao criar a pasta destino! Verifique se o parâmetro <pasta-arquivos-destino> é válido.";
		exit;
	}
}
$path .=  "\\$company_cnpj";
if (!is_dir($path)) {
	mkdir($path);	
	if (!is_dir($path)) {
		echo "Erro 32: Erro ao criar a pasta destino! Verifique se o parâmetro <pasta-arquivos-destino> é válido.";
		exit;
	}
}
$path .=  "\\efd-piscofins";
if (!is_dir($path)) {
	mkdir($path);
	if (!is_dir($path)) {
		echo "Erro 33: Erro ao criar a pasta destino! Verifique se o parâmetro <pasta-arquivos-destino> é válido.";
		exit;
	}
}



// Valida se existe o arquivo JSON. Se existir, deleta.
$file_path = $path . "\\$company_cnpj" . "_compras_vendas.json";
if (file_exists($file_path)) {
	unlink($file_path);
	if (file_exists($file_path)) {
		echo "Erro 35: Não foi possível apagar o arquivo já existente na pasta destino!";
		exit;
	}
}



// Valida os arquivos na pasta origem.
echo "Buscando arquivos SPED na pasta informada... \n";
$dir_files = array_diff(scandir($source_folder), array('..', '.'));
if (count($dir_files) == 0) {
	echo "Erro 41: Não existem arquivos na pasta origem!";
	exit;
}
$files1 = array();
foreach ($dir_files as $x) {
	if (substr($x,-4) == ".txt") {
		$files1[] = $x;
	}
	unset($x);
}
unset($dir_files);
if (count($files1) == 0) {
	echo "Erro 42: Não existem arquivos no formato \"txt\" na pasta origem!";
	exit;
}



// Processar arquivos da origem.
echo "Encontrados " . sizeof($files1) . " arquivos, processando dados... \n";
$companies=array();
$company_key = $client_key = $unit_key = $product_key = $order_key = $item_key = 0;
foreach ($files1 as $x) {
	$temp_path = $source_folder . "\\" . $x;
	try {
		$temp_file = fopen($temp_path, "r");
		if (!$temp_file) {
			throw new Exception('Não foi possível abrir o arquivo, acesso negado!');
		}
	}
	catch (Exception $e) {
		echo 'Erro 51: ',  $e->getMessage(), "\n";
		exit;
	}
	
	while(!feof($temp_file)) {
		$result = fgets($temp_file);
		$line_item = explode("|",$result);
		
		
		// 0140 - Tabela de Cadastro de Estabelecimentos.
		if ($line_item[1] == "0140") {
			$temp=new UnidadeNegocio;
			$temp->cnpj = $line_item[4];
			$temp->name = $line_item[3];
			
			
			// Pesquisar se já existe o estabelecimento.
			$company_key = array_search($temp->cnpj, array_column($companies,'cnpj'));
			if ($company_key === false) {
				$companies[] = $temp;
				$company_key = array_search($temp->cnpj, array_column($companies,'cnpj'));
			}
		}
		
		
		// 0150 - Tabela de Cadastro do Participante.
		if ($line_item[1] == "0150") {
			$temp=new ClienteFornecedor;
			$temp->participant = $line_item[2];
			$temp->cnpj = $line_item[5];
			$temp->name = $line_item[3];
			
			// Pesquisar se já existe o cliente/fornecedor no estabelecimento.
			$client_key = array_search($temp->participant, array_column($companies[$company_key]->clients,'participant'));			
			if ($client_key === false) {
				$companies[$company_key]->addArray('clients',$temp);
				$client_key = array_search($temp->participant, array_column($companies[$company_key]->clients,'participant'));
			}
		}
		
		
		// 0190 - Identificação das Unidades de Medida.
		if ($line_item[1] == "0190") {
			$temp=new UnidadeMedida;
			$temp->unit = $line_item[2];
			$temp->description = $line_item[3];
			
			// Pesquisar se já existe a unidade no estabelecimento.
			$unit_key = array_search($temp->unit, array_column($companies[$company_key]->units,'unit'));
			if ($unit_key === false) {
				$companies[$company_key]->addArray('units',$temp);
				$unit_key = array_search($temp->unit, array_column($companies[$company_key]->units,'unit'));
			}
		}
		
		
		// 0200 - Tabela de Identificação do Item (Produtos e Serviços).
		if ($line_item[1] == "0200") {
			$temp=new ProdutoServico;
			$temp->product = $line_item[2];
			$temp->description = $line_item[3];
			$temp->unit = $line_item[6];
			
			// Pesquisar se já existe o produto/serviço no estabelecimento.
			$product_key = array_search($temp->product, array_column($companies[$company_key]->products,'product'));
			if ($product_key === false) {
				$companies[$company_key]->addArray('products',$temp);
				$product_key = array_search($temp->product, array_column($companies[$company_key]->products,'product'));
			}
		}
		
		
		// A010 - Identificação do Estabelecimento.
		if ($line_item[1] == "A010") {
			$temp=$line_item[2];
			$company_key = array_search($temp, array_column($companies,'cnpj'));
		}
		
		
		// A100 - Documento - Nota Fiscal de Serviço.
		if ($line_item[1] == "A100") {
			$temp=new NotaFiscal;
			$temp->id = $line_item[8] . "-" . $line_item[6] . "-" . substr($line_item[10],4,4) . "-" . substr($line_item[10],2,2);
			$temp->number = $line_item[8];
			$temp->series = $line_item[6];
			$temp->operation = $line_item[2];
			$temp->emission = $line_item[10];
			$temp->participant = $line_item[4];
			$temp->year = substr($line_item[10],4,4);
			$temp->month = substr($line_item[10],2,2);
			$temp->day = substr($line_item[10],0,2);
			$temp->total = "R$ " . number_format($line_item[12] / 100,2,",",".");
			
			// Salvar a nota fiscal do estabelecimento.
			$companies[$company_key]->addArray('orders',$temp);
			$order_key = array_search($temp->id, array_column($companies[$company_key]->orders,'id'));
		}
		
		
		// A170 - Complemento do Documento - Itens do Documento
		if ($line_item[1] == "A170") {
			$temp=new ItemNotaFiscal;
			$temp->item_id = $line_item[2];
			$temp->product = $line_item[3];
			$temp->quantity = 1; //Não tem quantidade.
			$temp->price = "R$ " . number_format($line_item[5] / 100,2,",",".");
			$temp->total = "R$ " . number_format($line_item[5] / 100,2,",",".");
			
			// Pesquisar se já existe o item da nota fiscal no estabelecimento.
			$item_key = array_search($temp->item_id, array_column($companies[$company_key]->orders[$order_key]->items,'item_id'));
			if ($item_key === false) {
				$companies[$company_key]->orders[$order_key]->addArray('items',$temp);
				$item_key = array_search($temp->item_id, array_column($companies[$company_key]->orders[$order_key]->items,'item_id'));
			}
		}
		
		unset($result, $line_item, $temp);
	}
	
	fclose($temp_file);
	unset($x, $temp_file);
}



// Validar se coletou dados de arquivos SPED.
if (sizeof($companies) == 0) {
	echo "Erro 64: Não foram encontrados arquivos SPED válidos!";
	exit;
}



// Preparar dados para gerar JSON.
// Montar um array com a estrutura do JSON.
echo "Processando resultados obtidos para preparar o arquivo JSON... \n";
$main_array=array();
$purchases_array=array();
$sales_array=array();
$items_array=array();
$current_array=array();
$companies_array=array();

foreach ($companies as $a) {
	$orders = $a->orders;
	$purchases_array=array();
	$sales_array=array();
	foreach ($orders as $b) {
		$items_array=array();
		$operation_type = $b->operation;
		$period="$b->year/$b->month";
		
		
		// Buscar itens da Nota Fiscal.
		$items = $b->items;
		foreach ($items as $c) {
			$product_description=$a->products[array_search($c->item_id,array_column($a->products,'product'))]->description;
			$product_unit=$a->products[array_search($c->item_id,array_column($a->products,'product'))]->unit;
			
			$items_array[] = array(
				"Código do item" => $c->item_id,
				"Descrição" => $product_description,
				"Unidade" => $product_unit,
				"Quantidade" => $c->quantity,
				"Preço unitário" => $c->price,
				"Total do item" => $c->total,
			);
		}
		
		// Salvar em um array genérico de Compras/Vendas.
		if ($operation_type==0) {
			$current_array=$purchases_array;
		} elseif ($operation_type==1) {
			$current_array=$sales_array;
		}
		
		// Se não existir o ano, adicionar o bloco correspondente.
		$period_key = array_search($period, array_column($current_array,'Ano/Mês'));
		if ($period_key === false) {
			$current_array[] = array(
				"Ano/Mês" => "$b->year/$b->month",
				"Nota Fiscal" => array(),
			);
		}
		$period_key = array_search($period, array_column($current_array,'Ano/Mês'));
		
		
		// Salvar Nota Fiscal.
		$client_cnpj=$a->clients[array_search($b->participant,array_column($a->clients,'participant'))]->cnpj;
		$client_name=$a->clients[array_search($b->participant,array_column($a->clients,'participant'))]->name;
		$emission_date = "$b->day/$b->month/$b->year";
		$current_array[$period_key]["Nota Fiscal"][] = array(
			"Nota" => $b->number,
			"Série" => $b->series,
			"Data" => $emission_date,
			"CNPJ" => $client_cnpj,
			"Razão Social" => $client_name,
			"Valor total" => $b->total,
			"Itens" => $items_array,
		);
		
		
		// Atualizar array de Compras ou Vendas.
		if ($operation_type==0) {
			$purchases_array=$current_array;
		} elseif ($operation_type==1) {
			$sales_array=$current_array;
		}
	}
	
	// Ordenar notas por emissão em ordem crescente.
	$orders_size=sizeof($a->orders);
	for ($i=0; $i < $orders_size; $i++) {
		for ($j=$i+1; $j < $orders_size; $j++) {
			$x = $a->orders[$i]->year . $a->orders[$i]->month . $a->orders[$i]->day;
			$y = $a->orders[$j]->year . $a->orders[$j]->month . $a->orders[$j]->day;
			if ($y < $x) {
				$aux = $a->orders[$i];
				$a->replaceArray('orders',$i,$a->orders[$j]);
				$a->replaceArray('orders',$j,$aux);
			}
		}
	}
	
	// Ordenar blocos de Ano/Mês em ordem crescente.
	$orders_size=sizeof($purchases_array);
	for ($i=0; $i < $orders_size; $i++) {
		for ($j=$i+1; $j < $orders_size; $j++) {
			$x = str_replace("/","",$purchases_array[$i]["Ano/Mês"]);
			$y = str_replace("/","",$purchases_array[$j]["Ano/Mês"]);
			if ($y < $x) {
				$aux = $purchases_array[$i];
				$purchases_array[$i] = $purchases_array[$j];
				$purchases_array[$j] = $aux;
			}
		}
	}

	// Salvar a Unidade de Negócio.
	$companies_array[] = array(
		"CNPJ" => $a->cnpj,
		"Nome" => $a->name,
		"Compras" => $purchases_array,
		"Vendas" => $sales_array,
	);
}



// Validar se preparou o array corretamente.
if (sizeof($companies_array) == 0) {
	echo "Erro 66: Não foram encontrados arquivos SPED válidos!";
	exit;
}



//Gerar JSON.
echo "Gerando arquivo JSON... \n";
$main_array = array("Unidades de Negócio" => $companies_array);
$json_data = json_encode($main_array, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES );
if (strlen($json_data) == 0) {
	echo "Erro 71: Ocorreu um erro ao gerar o JSON.";
	exit;
}
try {
	$file = fopen($file_path,'w');
	if (!$file) {
		throw new Exception('Não foi possível acessar o caminho destino, acesso negado!');
    }
}
catch (Exception $e) {
    echo 'Erro 71: ',  $e->getMessage(), "\n";
	exit;
}
echo "Salvando JSON na pasta destino informada... \n";
fwrite($file, $json_data);
fclose($file);



//Validar se gerou o arquivo.
if (!file_exists($file_path)) {
	echo "Erro 73: Não foi possível gerar o arquivo JSON na pasta destino!";
	exit;
}




echo "\nArquivo JSON gerado com sucesso!\n\n";


printf("Processado em: " . $th->tempo());
echo "\n\n";
exit;

?>
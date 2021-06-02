Desafio Backend PHP
-------------------

1.A rotina desenvolvida, deverá ser ecxecutada em linha de comando, seguindo o formato: php <rotina> <cnpj> <pasta-arquivos-origem> <pasta-arquivos-destino>
2.Caso a pasta de arquivos destino não exista, deverá ser criada, com a seguinte estrutura: <pasta-arquivos-destino>/<cnpj>/efd-piscofins
3.O arquivo gerado deverá ser colocado na pasta de arquivos criada no passo 2, seguindo a seguinte estrutura de nomenclatura: <cnpj>_compras_vendas.json
4.O arquivo deverá ser gerado utilizando o formato UTF-8
5.A rotina deverá ler e processar quantos arquivos estiverem na pasta <pasta-arquivos-origem>. Os arquivos a serem lidos e processados, acompanham esse desafio.
6.Utilize no desenvolvimento de sua rotina, o máximo que puder, as práticas descritas no livro "Código Limpo"
7.Simplicidade na solução é o objetivo principal do desafio.
8.Classes de teste PHPUnit serão um diferencial, porém não obrigatório.
9.Utilize PHP 7
Os arquivos que acompanham esse desafio, são arquivos SPED EFD Contribuições. Todos os arquivos deverão ser processados. Neste desafio, deverão ser utilizados os seguintes registros:


0140 - Unidade de negócio
0150 - Clientes e Fornecedores
0190 - Unidades de medida
0200 - Produtos e serviços
A100 - Notas Fiscais
A170 - Itens de Notas Fiscais

Os detalhes de cada registro estão descritos no manual da receita federal, que acompanha esse desafio. Podem ocorrer pequenas divergências entre os formatos dos arquivos e a documentação, caso ocorram, serão mínimas e podem ser verificadas em manuais de versões anteriores da documentação. Ficando a seu cargo, identificar se há divergência e caso ocorra, procurar o manual referente à versão do arquivo. Indique se identificou alguma divergência e como solucionou, como comentário no topo da rotina desenvolvida.

Gerar JSON que represente as vendas e compras de cada unidade de negócio. Com as respectivas notas fiscais e seus itens, agrupados por ano e mês. Estrutura semelhante a:

- Unidades de Negócio (CNPJ, Nome)
	- Vendas
		- Ano/Mes
			- Nota Fiscal (data, cnpj do cliente, razao social, valor total)
				- Itens da nota fiscal (data, codigo do item, descricao, unidade, quantidade, preco unitario, total do item)
	- Compras
		- Ano/Mes
			- Nota Fiscal (data, cnpj do fornecedor, razao social, valor total)
				- Itens da nota fiscal (data, codigo do item, descricao, unidade, quantidade, preco unitario, total do item)



<?php
	//estrutura principal (multipla) de dados que vai receber as ideias
	$posts = array();

	//para cada ideia, começando de 0 e indo até o total de ideias recebidas, cria uma estrutura secundaria (singular) e adiciona a principal
	for ($i=0; $i < count($_POST["titulo"]); $i++) { 
		$posts[] = array("titulo" => $_POST["titulo"][$i], "corpo" => $_POST["corpo"][$i], "data" => $_POST["data"][$i]);
	}

	//salva os dados em formato json no arquivo base, poderia facilmente ser convertido para base de dados
	file_put_contents(__DIR__."/assets/files/data.json", json_encode($posts));

	//imprime sucesso, embora o usuário não visualiza, poderia ser tratado se transformasse em API
	echo "success";
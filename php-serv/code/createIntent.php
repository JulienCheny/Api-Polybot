<?php 

$method = $_SERVER['REQUEST_METHOD'];
// Process only when method is POST
if($method == 'POST')
{
	$contexts_intent = "";
	$context_departement = "";
	$context_annee = "";
	
	$parametres = "";
	$parametre_departement = "";
	$parametre_annee = "";
	
	$question_modele = '{"count": 0,"data": [{"text": "Questions utilisateur"}]}';
	
	$nom_intent = $_POST["nom_question"];
	$format_reponse = $_POST["format_reponse"];
	$reponse_question = $_POST["reponse_question"];
	$questions= $_POST["table-values"];
	

	if (isset($_POST["Annee"]))
	{
		$years = $_POST["Annee"];
		$context_annee = '{ "name": "annee_utilisateur", "parameters": {}, "lifespan": 999 }';
		$parametre_annee = '{"id": "5d8de817-7a48-45f8-8af9-cd5357700d3f","required": true,"dataType": "@annee","name": "annee","value": "#annee_utilisateur.annee","prompts":["Quelle est ton année ?"],"isList": false}';
		$contexts_intent = '"annee_utilisateur"';
	}
	
	if (isset($_POST["Departement"]))
	{
		$departements = $_POST["Departement"];
		$context_departement = '{ "name": "departement_utilisateur", "parameters": {}, "lifespan": 999 }';
		$parametre_departement = '{"id": "2f3c8e2a-2e3a-4f04-991f-5f5b51cda9e0","required": true,"dataType": "@departement","name": "departement","value": "#departement_utilisateur.departement","prompts":["Quel est ton département ?"],"isList": false}';
		if(empty($contexts_intent))
		{
			$contexts_intent = '"departement_utilisateur"';
		}
		else
		{
			$contexts_intent = $contexts_intent . ',"departement_utilisateur"';
		}
	}
	
	if(empty($context_departement) and empty($context_annee))
	{
		$affectedContexts = "";
		$parametres = "";
	}
	else if(empty($context_departement))
	{
		$affectedContexts = $context_annee;
		$parametres = $parametre_annee;
	}
	else if(empty($context_annee))
	{
		$affectedContexts = $context_departement;
		$parametres = $parametre_departement;
	}
	else
	{
		$affectedContexts = $context_annee . "," . $context_departement;
		$parametres = $parametre_annee . "," . $parametre_departement;
	}

	
	$questions_context = "";
	
	foreach($questions as $value)
	{
		if(empty($questions_context))
		{
			$questions_context = '{"count": 0,"data": [{"text": "'.$value.'"}]}';
		}
		else
		{
			$questions_context = $questions_context.',{"count": 0,"data": [{"text": "'.$value.'"}]}';
		}
	}
	
	$json_genere = '{"contexts": [],"events" : [],"fallbackIntent":false,"name": "'.$nom_intent.'","priority": 500000,"responses": [{"resetContexts": false,"action": "","affectedContexts": ['.$affectedContexts.'],"parameters": ['.$parametres.'],"messages": [{"type": 0,"speech": []}],"defaultResponsePlatforms": {},"speech": []}],"userSays": ['.$questions_context.'],"webhookUsed": true,"webhookForSlotFilling": false}';	
	$size = strlen($json_genere);

	$opts = array(
        'http' => array(
            'header'=>"Authorization: Bearer 1adb36b46e504aacb0d9e23393eb3a10\r\n" .
						  "Content-Type: application/json;charset=utf-8\r\n" .
						  "Content-length: $size\r\n",
            'method'  => 'POST',
            'content' => $json_genere,
        ),
    );

	$context = stream_context_create($opts);
	$result = json_decode(file_get_contents("https://api.dialogflow.com/v1/intents", false, $context));
	#var_dump($result);
	if($result)
	{
		#echo "Creation effectuée !";
		$opts = array(
		  'http'=>array(
			'method'=>"GET",
			'header'=>"Authorization: Bearer 1adb36b46e504aacb0d9e23393eb3a10\r\n" .
					  "Content-Type: application/json\r\n"
		  )
		);
		$context = stream_context_create($opts);
		$all_intents = json_decode(file_get_contents("https://api.dialogflow.com/v1/intents", false, $context));
		$found_intent = False;
		$uuid = "";
		foreach($all_intents as $single_intent)
		{
			if($nom_intent == $single_intent->name)
			{
				$uuid = $single_intent->id;
				break;
			}
		}
		/**
			Ajout de l'intent a la BDD
		*/
		$json_add_bdd = '{
		  "uuid": "'. $uuid .'",
		  "name": "'. $nom_intent.'",
		  "textResponse": "'. $format_reponse .'"
		}';
		$size = strlen($json_add_bdd);
		$opts = array(
			'http' => array(
				'header'=> "Content-Type: application/json;charset=utf-8\r\n" .
						"Content-length: $size\r\n",
				'method'  => 'POST',
				'content' => $json_add_bdd,
			),
		);
		$context = stream_context_create($opts);
		$result = json_decode(file_get_contents("http://51.38.45.203:8080/requests", false, $context));
		$request_id = '"/requests/'. $result->id . '"';
		/**
			Ajout d'une reponse a la BDD
		*/
		$dpts_ids = array();
		if(!empty($_POST["Departement"]))
		{
			foreach($_POST["Departement"] as $dpt)
			{
				$opts = array(
				  'http'=>array(
					'method'=>"GET",
					'header'=> "Content-Type: application/json\r\n"
				  )
				);
				$context = stream_context_create($opts);
				$all_dpts = json_decode(file_get_contents("http://51.38.45.203:8080/departments", false, $context));
				foreach($all_dpts->{'hydra:member'} as $single_dpt)
				{
					if($dpt == $single_dpt->name)
					{
						$id_dpt = $single_dpt->id;
						array_push($dpts_ids, "/departments/". $id_dpt);
						break;
					}
				}
			}
		}

		$years_ids = array();
		if(!empty($_POST["Annee"]))
		{
			foreach($_POST["Annee"] as $year)
			{
				$opts = array(
				  'http'=>array(
					'method'=>"GET",
					'header'=> "Content-Type: application/json\r\n"
				  )
				);
				$context = stream_context_create($opts);
				$all_years = json_decode(file_get_contents("http://51.38.45.203:8080/years", false, $context));
				foreach($all_years->{'hydra:member'} as $single_year)
				{
					if($year == $single_year->name)
					{
						$id_year = $single_year->id;
						array_push($years_ids, "/years/". $id_year);
						break;
					}
				}
			}
		}
		/*var_dump($dpts_ids);
		var_dump($years_ids);
		var_dump(count($dpts_ids));
		var_dump(count($years_ids));*/
		
		if(empty($dpts_ids) and empty($years_ids))
		{
			$json_add_reponse = 
			'{
				"text": "'.$reponse_question.'",
				"request": '.$request_id.'
			}';
			$size = strlen($json_add_reponse);
			$opts = array(
				'http' => array(
					'header'=> "Content-Type: application/json;charset=utf-8\r\n" .
							"Content-length: $size\r\n",
					'method'  => 'POST',
					'content' => $json_add_reponse,
				),
			);
			$context = stream_context_create($opts);
			$result = json_decode(file_get_contents("http://51.38.45.203:8080/answers", false, $context));
		}
		else if(empty($dpts_ids))
		{
			foreach($years_ids as $year)
			{
				$json_add_reponse = 
				'{
					"text": "'.$reponse_question.'",
					"request": '.$request_id.',
					"year": "'.$year.'"
				}';
				$size = strlen($json_add_reponse);
				$opts = array(
					'http' => array(
						'header'=> "Content-Type: application/json;charset=utf-8\r\n" .
								"Content-length: $size\r\n",
						'method'  => 'POST',
						'content' => $json_add_reponse,
					),
				);
				
				$context = stream_context_create($opts);
				$result = json_decode(file_get_contents("http://51.38.45.203:8080/answers", false, $context));
			}
		}
		
		else if(empty($years_ids))
		{
			foreach($dpts_ids as $dpt)
			{
				$json_add_reponse = 
				'{
					"text": "'.$reponse_question.'",
					"request": '.$request_id.',
					"department": "'.$dpt.'"
				}';
				$size = strlen($json_add_reponse);
				$opts = array(
					'http' => array(
						'header'=> "Content-Type: application/json;charset=utf-8\r\n" .
								"Content-length: $size\r\n",
						'method'  => 'POST',
						'content' => $json_add_reponse,
					),
				);
				
				$context = stream_context_create($opts);
				$result = json_decode(file_get_contents("http://51.38.45.203:8080/answers", false, $context));
			}
		}
		else
		{
			foreach($dpts_ids as $dpt)
			{
				foreach($years_ids as $year)
				{
					$json_add_reponse = 
					'{
						"text": "'.$reponse_question.'",
						"request": '.$request_id.',
						"department": "'.$dpt.'",
						"year": "'.$year.'"
					}';
					$size = strlen($json_add_reponse);
					$opts = array(
						'http' => array(
							'header'=> "Content-Type: application/json;charset=utf-8\r\n" .
									"Content-length: $size\r\n",
							'method'  => 'POST',
							'content' => $json_add_reponse,
						),
					);
					$context = stream_context_create($opts);
					$result = json_decode(file_get_contents("http://51.38.45.203:8080/answers", false, $context));
				}
			}
		}
	}
	else
	{
		#echo "Echec de la création";
	}
}
else
{
	#echo "Method not allowed";
}

header('Location: http://51.38.45.203:8080/adminPage/pages/admin.html');

?>
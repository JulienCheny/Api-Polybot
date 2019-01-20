<?php 

$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if($method == 'POST')
{
	
	$opts = array(
	  'http'=>array(
		'method'=>"GET",
		'header'=>"Authorization: Bearer 1adb36b46e504aacb0d9e23393eb3a10\r\n" .
				  "Content-Type: application/json\r\n"
	  )
	);
	$context = stream_context_create($opts);

	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);
	
	$annee = $json->queryResult->parameters->annee;
	$departement = $json->queryResult->parameters->departement;
	$intent = $json->queryResult->intent->displayName;
	
	$all_intents = json_decode(file_get_contents("https://api.dialogflow.com/v1/intents", false, $context));
	$found_intent = False;
	foreach($all_intents as $single_intent)
	{
		
		if($intent == $single_intent->name)
		{

			$get_intent_info = "http://51.38.45.203:8080/requests?uuid=".$single_intent->id;				
			$intent_info = file_get_contents($get_intent_info);
			$json_intent_answer = json_decode($intent_info);
			$reponse_text = $json_intent_answer->{'hydra:member'}[0]->{'textResponse'};
			
			$full_link = "http://51.38.45.203:8080/answers?request.uuid=".$single_intent->id."&department.name=".$departement."&year.name=".$annee;
			if(empty($departement) and empty($annee))
			{
				$full_link = "http://51.38.45.203:8080/answers?request.uuid=".$single_intent->id;
			}
			else if(empty($annee))
			{
				$full_link = "http://51.38.45.203:8080/answers?request.uuid=".$single_intent->id."&department.name=".$departement;
			}
			else if(empty($departement))
			{
				$full_link = "http://51.38.45.203:8080/answers?request.uuid=".$single_intent->id."&year.name=".$annee;
			}

			$my_var = file_get_contents($full_link);
			$json_answer=json_decode($my_var);
			
			if($json_answer->{'hydra:totalItems'} == 0)
			{
				$speech = "[" . $intent . "/" . $departement . "/" . $annee . "] Désolé mais cette réponse m'échappe !"; 
			}
			else
			{
				$answer = $json_answer->{'hydra:member'}[0]->{'text'};
				$speech = "[" . $intent . "/" . $departement . "/" . $annee . "] " . $reponse_text . " " . $answer;
			}
			$found_intent = True;
			break;
		}
	}
	if(!$found_intent)
	{
		$speech = "[".$intent."]Oula ! Ceci dépasse mes connaissances !";
	}
	
	$response = '{"fulfillmentText": "", "fulfillmentMessages": [{"text": { "text": ["' . $speech . '"] }}],"source": "'. $debug_info .'"}';

	echo $response;
}
else
{
	echo "Method not allowed";
}

?>
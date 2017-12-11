<?php
	try
	{
		include('<CONNEXION>');
		/*
		*	Check passphrase SECRET
		*/
		
		$secret = '<SECRET>';
 
		$headers = getallheaders();
		$hubSignature = $headers['X-Hub-Signature'];
		 
		// Split signature into algorithm and hash
		list($algo, $hash) = explode('=', $hubSignature, 2);
		 
		// Get payload
		$payload = file_get_contents('php://input');
		// Calculate hash based on payload and the secret
		$payloadHash = hash_hmac($algo, $payload, $secret);

		// Check if hashes are equivalent
		if ($hash == $payloadHash) 
		{
			$payload = json_decode($_REQUEST['payload']);
			
			$full_payload=$link->real_escape_string($_REQUEST['payload']);
			
			$GitHubEvent = $headers['X-GitHub-Event'];
			if($GitHubEvent=='push')
			{
				if ($payload->ref === 'refs/heads/master')//Choose branch to track
				{
					foreach($payload->commits as $commit)
					{
						$message_commit=$link->real_escape_string($commit->message);
						$timestamp_commit=$link->real_escape_string($commit->timestamp);
						$url_commit=$link->real_escape_string($commit->url);
						$author_commit=$link->real_escape_string($commit->author->email);
						$explode_issue=explode('#',$message_commit);
						$id_issue=trim ($explode_issue[1]);
						if($id_issue!='')
						{
								$request='INSERT INTO webhook_commits(
								commit_content,
								commit_url,
								user,
								date,
								payload,
								id_issue
							)
							VALUES(
								"'.$message_commit.'",
								"'.$url_commit.'",
								"'.$author_commit.'",
								"'.$timestamp_commit.'",
								"'.$full_payload.'",
								'.$id_issue.'
							)
							';
						}
						else
						{
							$request='INSERT INTO webhook_commits(
								commit_content,
								commit_url,
								user,
								date,
								payload
							)
							VALUES(
								"'.$message_commit.'",
								"'.$url_commit.'",
								"'.$author_commit.'",
								"'.$timestamp_commit.'",
								"'.$full_payload.'"
							)
							';
						}
						
						$send_request=$link->query($request);
						if(!$send_request)
						{
							//ERROR FUNCTION (email,..)
						}
					}
				}
			}
			
			if($GitHubEvent=='issues')//CONCERNANT LES ISSUES
			{
				if($payload->action=="opened")//OUVERTURE DE L'ISSUE
				{
					$id_issue=$payload->issue->number;
					$content=$link->real_escape_string(nl2br(strip_tags($payload->issue->body)));
					$title=$link->real_escape_string($payload->issue->title);
					
					$user=$payload->issue->user->login;
					$user=switch_username($user);
					$assigned=$payload->issue->assignee->login;
					$assigned=switch_username($assigned);
					
					$comment=$link->real_escape_string($payload->issue->assignee->login);
					
					issue_opened($id_issue,$date,$user,$content,$title,$assigned,$comment);
				}
				if($payload->action=="edited")//ÉDITION DE L'ISSUE
				{
					$id_issue=$payload->issue->number;
					$new_title=$link->real_escape_string($payload->issue->title);
					$old_title=$link->real_escape_string($payload->changes->title->from);
					
					$new_body=$link->real_escape_string(nl2br(strip_tags($payload->issue->body)));
					$old_body=$link->real_escape_string(nl2br(strip_tags($payload->changes->body->from)));
					
					$user=$payload->sender->login;
					$user=switch_username($user);
					
					issue_edited($id_issue,$new_title,$old_title,$new_body,$old_body,$user);
				}
				if($payload->action=="closed")//FERMETURE DE L'ISSUE
				{
					$id_issue=$payload->issue->number;
					
					$user=$payload->issue->user->login;
					$user=switch_username($user);
					
					issue_closed($id_issue,$user);
				}
				if($payload->action=="reopened")//RÉOUVERTURE DE L'ISSUE
				{
					$id_issue=$payload->issue->number;
					
					$user=$payload->sender->login;
					$user=switch_username($user);
					
					issue_reopened($id_issue,$user);
				}
				if($payload->action=="labeled" || $payload->action=="unlabeled")//AJOUT LABEL 
				{
					$id_issue=$payload->issue->number;
					
					$tab=array();
					
					$i=0;
					foreach($payload->issue->labels as $label)
					{
						$tab[$i]['label']=$label->name;
						$tab[$i]['color']=$label->color;
						$i++;
					}
					
					issue_add_label($id_issue,$tab);
				}
			}
			if($GitHubEvent=='issue_comment')  //CONCERNANT LES COMMENTAIRES
			{
				if($payload->action=="created")//CRÉATION DU COMMENTAIRE
				{
					$id_issue=$payload->issue->number;
					
					$id_comment=$payload->comment->id;
					
					$user=$payload->sender->login;
					$user=switch_username($user);
					
					$comment=$link->real_escape_string(nl2br(strip_tags($payload->comment->body)));
					
					comment_created($id_issue,$id_comment,$user,$comment);
				}
				if($payload->action=="edited")//ÉDITION DU COMMENTAIRE
				{
					$id_comment=$payload->comment->id;
					
					$user=$payload->sender->login;
					$user=switch_username($user);
					$id_issue=$payload->issue->number;
					
					$old_comment=$link->real_escape_string(nl2br(strip_tags($payload->changes->body->from)));
					$new_comment=$link->real_escape_string(nl2br(strip_tags($payload->comment->body)));
					
					comment_edited($id_comment,$old_comment,$new_comment,$user,$id_issue);
				}
			}
		}
		else
		{
			//ERROR FUNCTION (email,..)
		}
	}
	catch(Exception $e)
	{
		exit(0);
	}
?>
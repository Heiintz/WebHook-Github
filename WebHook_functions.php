<?php 
	
	function switch_username($user) //Switch nickname from GitHub by user email
	{
		switch($user)
		{
			case 'NICKNAME' :
				$user='EMAIL';
				break;
			default:
				$user='';
				break;
		}
		return $user;
	}
	
	function issue_opened($id_issue,$date,$user,$content,$title,$comment,$assigned) //Add issue from GITHUB
	{
		global $link;
		
		$date=date("Y-m-d H:i:s");
		
		$req='INSERT INTO webhook_issues(
			issue_number,
			etat_issue,
			date,
			user,
			content,
			title,
			assigned
			) VALUES (
				'.$id_issue.',
				1,
				"'.$date.'",
				"'.$user.'",
				"'.$content.'",
				"'.$title.'",
				"'.$assigned.'"
				
		)';
		$requete=$link->query($req);
		if(!$requete)
		{
			//ERROR FUNCTION (email,..)
		}
	}
	function issue_edited($id_issue,$new_title,$old_title,$new_body,$old_body,$user)//Edit issue check if issue exist (if not create (for import old issue))
	{
		global $link;
		
		$date=date("Y-m-d H:i:s");
		
		$req_check_issue_already_here='SELECT * FROM webhook_issues WHERE issue_number='.$id_issue;
		$requete_check_issue_already_here=$link->query($req_check_issue_already_here);
		if($requete_check_issue_already_here)
		{
			if($requete_check_issue_already_here->num_rows>0)
			{
				if($old_title!='')
				{
					$edit_title=1;
					$req='INSERT INTO webhook_issues_history(
					id_issue,
					date_modify,
					field_modify,
					issue_before,
					issue_after,
					user
					) VALUES (
						'.$id_issue.',
						"'.$date.'",
						"Titre",
						"'.$old_title.'",
						"'.$new_title.'",
						"'.$user.'"
					)';
				}
				elseif($old_body!='' || $new_body!='')
				{
					$edit_body=1;
					$req='INSERT INTO webhook_issues_history(
					id_issue,
					date_modify,
					field_modify,
					issue_before,
					issue_after,
					user
					) VALUES (
						'.$id_issue.',
						"'.$date.'",
						"Description",
						"'.$old_body.'",
						"'.$new_body.'",
						"'.$user.'"
					)';
				}
				$requete=$link->query($req);
				if($requete)
				{
					$req2='UPDATE webhook_issues SET etat_issue=2,date="'.$date.'"';
					if($edit_title==1)
					{
						$req2.=',title="'.$new_title.'"';
					}
					if($edit_body==1)
					{
						$req2.=',content="'.$new_body.'"';
					}
					$req2.=' WHERE issue_number='.$id_issue;
					$requete2=$link->query($req2);
					if(!$requete2)
					{
						//ERROR FUNCTION (email,..)
					}
				}
				else
				{
					//ERROR FUNCTION (email,..)
				}
			}
			else
			{
				$req_issue='INSERT INTO webhook_issues(
					issue_number,
					etat_issue,
					date,
					user,
					content,
					title
					) VALUES (
						'.$id_issue.',
						1,
						"'.$date.'",
						"'.$user.'",
						"'.$new_body.'",
						"'.$new_title.'"
				)';
				$requete_issue=$link->query($req_issue);
				if(!$requete_issue)
				{
					//ERROR FUNCTION (email,..)
				}
			}
		}
	}
	
	function issue_closed($id_issue,$user)//Close issue
	{
		global $link;
		
		$date=date("Y-m-d H:i:s");
		
		$req='UPDATE webhook_issues SET 
			etat_issue=3,date="'.$date.'"
		WHERE issue_number='.$id_issue;
		$requete=$link->query($req);
		if($requete)
		{
			$req2='INSERT INTO webhook_issues_history(
			id_issue,
			date_modify,
			field_modify,
			issue_before,
			issue_after,
			user
			) VALUES (
				'.$id_issue.',
				"'.$date.'",
				"État",
				"Ouverte",
				"Fermée",
				"'.$user.'"
			)';
			$requete2=$link->query($req2);
			if(!$requete2)
			{
				//ERROR FUNCTION (email,..)
			}
		}
		else
		{
			//ERROR FUNCTION (email,..)
		}
	}
	
	function issue_reopened($id_issue,$user)//Reopen issue
	{
		global $link;
		
		$date=date("Y-m-d H:i:s");
		
		$req='UPDATE webhook_issues SET 
			etat_issue=6,date="'.$date.'"
		WHERE issue_number='.$id_issue;
		$requete=$link->query($req);
		if($requete)
		{
			$req2='INSERT INTO webhook_issues_history(
			id_issue,
			date_modify,
			field_modify,
			issue_before,
			issue_after,
			user
			) VALUES (
				'.$id_issue.',
				"'.$date.'",
				"État",
				"Fermée",
				"Réouverte",
				"'.$user.'"
			)';
			$requete2=$link->query($req2);
			if(!$requete2)
			{
				//ERROR FUNCTION (email,..)
			}
		}
		else
		{
			//ERROR FUNCTION (email,..)
		}
	}
	
	function comment_created($id_issue,$id_comment,$user,$comment)//Add comment
	{
		global $link;
		
		$date=date("Y-m-d H:i:s");
		
		$req='INSERT INTO webhook_comments(
			id_issue,
			id_comment,
			comment_content,
			comment_user,
			date,
			comment_state
		) VALUES(
			'.$id_issue.',
			'.$id_comment.',
			"'.$comment.'",
			"'.$user.'",
			"'.$date.'",
			4
		)';
		$requete=$link->query($req);
		if($requete)
		{
			$req='UPDATE webhook_issues SET 
				date="'.$date.'"
			WHERE issue_number='.$id_issue;
			$requete=$link->query($req);
			if(!$requete)
			{
				//ERROR FUNCTION (email,..)
			}
		}
		else
		{
			//ERROR FUNCTION (email,..)
		}
	}
	
	function comment_edited($id_comment,$comment_before,$comment_after,$user,$id_issue)//Edit comment from issue and check if first comment exist (if not create (for import old issue))
	{
		global $link;
		
		$date=date("Y-m-d H:i:s");
		
		$req_issue='SELECT id_issue FROM webhook_comments WHERE  webhook_comments.id_comment='.$id_comment;
		$requete_issue=$link->query($req_issue);
		$row_requete_issue=$requete_issue->fetch_assoc();
		if($row_requete_issue['id_issue']!='')
		{
			$req='INSERT INTO webhook_comments_history(
				id_comment,
				date_modify,
				comment_before,
				comment_after,
				user
			) VALUES(
				'.$id_comment.',
				"'.$date.'",
				"'.$comment_before.'",
				"'.$comment_after.'",
				"'.$user.'"
			)';
			$requete=$link->query($req);
			if($requete)
			{
				$req2='UPDATE webhook_comments SET comment_content="'.$comment_after.'",date="'.$date.'" WHERE id_comment='.$id_comment;
				$requete2=$link->query($req2);
				if($requete2)
				{
					$req='UPDATE webhook_issues SET 
						date="'.$date.'"
					WHERE issue_number='.$row_requete_issue['id_issue'];
					$requete=$link->query($req);
					if(!$requete)
					{
						//ERROR FUNCTION (email,..)
					}
				}
				else
				{
					//ERROR FUNCTION (email,..)
				}
			}
			else
			{
				//ERROR FUNCTION (email,..)
			}
		}
		else
		{
			$req='INSERT INTO webhook_comments(
				id_issue,
				id_comment,
				comment_content,
				comment_user,
				comment_state,
				date
			) VALUES(
				'.$id_issue.',
				"'.$id_comment.'",
				"'.$comment_before.'",
				"'.$user.'",
				4,
				"'.$date.'"
			)';
			$requete=$link->query($req);
			if(!$requete)
			{
				//ERROR FUNCTION (email,..)
			}
		}
		
	}
	
	function issue_add_label($id_issue,$tab) //Add labels
	{
		global $link;
		$req='DELETE FROM webhook_issues_labels WHERE id_issue='.$id_issue;
		$requete=$link->query($req);
		if($requete)
		{
			for($i=0;$i<count($tab);$i++)
			{
				$req2='INSERT INTO webhook_issues_labels(
					id_issue,
					label,
					color
				) VALUES (
					'.$id_issue.',
					"'.$tab[$i]['label'].'",
					"'.$tab[$i]['color'].'"
				)';
				$requete2=$link->query($req2);
				if(!$requete2)
				{
					//ERROR FUNCTION (email,..)
				}
			}
		}
	}
?>
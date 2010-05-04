<?php
/**
 * Traq 2
 * Copyright (C) 2009, 2010 Jack Polgar
 *
 * This file is part of Traq.
 * 
 * Traq is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 only.
 * 
 * Traq is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Traq. If not, see <http://www.gnu.org/licenses/>.
 *
 * $Id$
 */

 // Fetch required files
require('common.php');
require('../inc/version.php');
include('../inc/config.php');
include('../inc/db.class.php');

$db = new Database($conf['db']['server'],$conf['db']['user'],$conf['db']['pass'],$conf['db']['dbname']);
define("DBPF",$conf['db']['prefix']);

if(!isset($_REQUEST['migrate']))
{
	head('migrate');
	?><div align="center"><?php
	// Check for migrate
	if(settings('db_revision') < $db_revision)
	{
		?>
		You need to migrate your database, click Migrate to continue.<br /><br />
		<form action="migrate.php?migrate" method="post">
			<input type="submit" value="Migrate" />
		</form>
		<?php
	}
	else
	{
		?>
		Your database is up to date.
		<?php
	}
	?></div><?php
	foot();
}
elseif(isset($_REQUEST['migrate']))
{
	// Converts the database to Traq 2.0 from 0.6
	if(settings('dbversion') == 17)
	{
		// First we need to fetch all the data from the database...
		
		// Projects
		$projects = array();
		$fetchprojects = $db->query("SELECT * FROM ".DBPF."projects");
		while($info = $db->fetcharray($fetchprojects))
			$projects[$info['id']] = $info;
		
		// Milestones
		$milestones = array();
		$fetchmilestones = $db->query("SELECT * FROM ".DBPF."milestones");
		while($info = $db->fetcharray($fetchmilestones))
			$milestones[$info['id']] = $info;
			
		// Versions
		$versions = array();
		$fetchversions = $db->query("SELECT * FROM ".DBPF."versions");
		while($info = $db->fetcharray($fetchversions))
			$versions[$info['id']] = $info;
			
		// Components
		$components = array();
		$fetchcomponents = $db->query("SELECT * FROM ".DBPF."components");
		while($info = $db->fetcharray($fetchcomponents))
			$components[$info['id']] = $info;
		
		// Tickets
		$tickets = array();
		$fetchtickets = $db->query("SELECT * FROM ".DBPF."tickets");
		while($info = $db->fetcharray($fetchtickets))
			$tickets[$info['id']] = $info;
		
		// Ticket History
		$tickethistory = array();
		$fetchtickethistory = $db->query("SELECT * FROM ".DBPF."tickethistory");
		while($info = $db->fetcharray($fetchtickethistory))
			$tickethistory[$info['id']] = $info;
		
		// Timeline
		$timeline = array();
		$fetchtimeline = $db->query("SELECT * FROM ".DBPF."timeline");
		while($info = $db->fetcharray($fetchtimeline))
			$timeline[$info['id']] = $info;
		
		// Users
		$users = array();
		$fetchusers = $db->query("SELECT * FROM ".DBPF."users");
		while($info = $db->fetcharray($fetchusers))
			$users[$info['id']] = $info;
		
		// Usergroups
		$usergroups = array();
		$fetchusergroups = $db->query("SELECT * FROM ".DBPF."usergroups");
		while($info = $db->fetcharray($fetchusergroups))
			$usergroups[$info['id']] = $info;
		
		// Attachments
		$attachments = array();
		$fetchattachments = $db->query("SELECT * FROM ".DBPF."attachments");
		while($info = $db->fetcharray($fetchattachments))
			$attachments[$info['id']] = $info;
		
		// Priorities
		$priorities = array();
		$fetchpriorities = $db->query("SELECT * FROM ".DBPF."priorities");
		while($info = $db->fetcharray($fetchpriorities))
			$priorities[$info['id']] = $info;
		
		// Severities
		$severities = array();
		$fetchseverities = $db->query("SELECT * FROM ".DBPF."severities");
		while($info = $db->fetcharray($fetchseverities))
			$severities[$info['id']] = $info;
		
		// statuses
		$statuses = array();
		$fetchstatuses = $db->query("SELECT * FROM ".DBPF."statustypes ORDER BY id ASC");
		while($info = $db->fetcharray($fetchstatuses))
			$statuses[$info['id']] = $info;
		
		$status_new = $statuses['1'];
		unset($statuses['1']);
		$oldstatuses = $statuses;
		$statuses = array();
		$statuses['1'] = $status_new;
		
		foreach($oldstatuses as $oldstatus)
			$statuses[$oldstatus['id']] = $oldstatus;
		
		// Types
		$types = array();
		$fetchtypes = $db->query("SELECT * FROM ".DBPF."types");
		while($info = $db->fetcharray($fetchtypes))
			$types[$info['id']] = $info;
		
		// And now we delete and create the new tables...
		$sql = file_get_contents('migrate/2.0.sql');
		
		// Now we split and execute the queries...
		$queries = explode(";",$sql);
		foreach($queries as $query)
			if(!empty($query))
				$db->query(str_replace('traq_',DBPF,$query));
		
		// Now to convert the old data and entr it into the database.
		
		// Projects
		foreach($projects as $project)
		{
			$db->query("INSERT INTO ".DBPF."projects
			(id,name,slug,codename,info,managers,private,next_tid,displayorder)
			VALUES(
			'".($project['id'])."',
			'".($project['name'])."',
			'".($project['slug'])."',
			'".($project['codename'])."',
			'".($project['desc'])."',
			'".($project['managers'])."',
			'0',
			'".($project['currenttid']+1)."',
			'0'
			)");
		}
		
		// Milestones
		foreach($milestones as $milestone)
		{
			$db->query("INSERT INTO ".DBPF."milestones
				(id,milestone,slug,codename,info,due,completed,locked,project_id,displayorder)
				VALUES(
				'".($milestone['id'])."',
				'".($milestone['milestone'])."',
				'".($milestone['milestone'])."',
				'".($milestone['codename'])."',
				'".($milestone['desc'])."',
				'".($milestone['due'])."',
				'".($milestone['completed'])."',
				'".($milestone['completed'] > 0 ? 1 : 0)."',
				'".($milestone['project'])."',
				'0'
				)");
		}
		
		// Versions
		foreach($versions as $version)
		{
			$db->query("INSERT INTO ".DBPF."versions
			(id,version,project_id)
			VALUES(
			'".($version['id'])."',
			'".($version['version'])."',
			'".($version['projectid'])."'
			)");
		}
		
		// Components
		foreach($components as $component)
		{
			$db->query("INSERT INTO ".DBPF."components
			(id,name,".DBPF."components.default,project_id)
			VALUES(
			'".($component['id'])."',
			'".($component['name'])."',
			'0',
			'".($component['project'])."'
			)");
		}
		
		// statuses
		$statuses[-50] = $statuses[2];
		foreach($statuses as $status)
		{
			$db->query("INSERT INTO ".DBPF."ticket_status
			(name,status)
			VALUES(
			".$statusid."
			'".$status['name']."',
			'".($status['id'] <= 0 ? 0 : 1)."',
			'1'
			)");
			$statuses[$status['id']]['newid'] = $db->insertid();
		}
		
		// Tickets
		foreach($tickets as $ticket)
		{
			$db->query("INSERT INTO ".DBPF."tickets
			(id,ticket_id,summary,body,user_id,user_name,project_id,milestone_id,version_id,component_id,type,
			status,priority,severity,assigned_to,closed,created,updated,private)
			VALUES(
			'".($ticket['id'])."',
			'".($ticket['tid'])."',
			'".($ticket['summary'])."',
			'".($ticket['body'])."',
			'".($ticket['ownerid'])."',
			'".($ticket['ownername'])."',
			'".($ticket['projectid'])."',
			'".($ticket['milestoneid'])."',
			'".($ticket['versionid'])."',
			'".($ticket['componentid'])."',
			'".($ticket['type'])."',
			'".($statuses[$ticket['status']]['newid'])."',
			'".($ticket['priority'])."',
			'".($ticket['severity'])."',
			'".($ticket['assigneeid'])."',
			'".($ticket['status'] <= 0 ? 1 : 0)."',
			'".($ticket['timestamp'])."',
			'".($ticket['update'])."',
			'0'
			)");
		}
		
		// Ticket History, oh shit...
		foreach($tickethistory as $history)
		{
			$newchanges = array();
			$changes = explode('|',$history['changes']);
			foreach($changes as $change) {
				$parts = explode(':',$change);
				$type = $parts[0];
				$values = explode(',',$parts[1]);
				$change = array();
				$change['type'] = $type;
				$change['toid'] = $values[0];
				$change['fromid'] = $values[1];
				// Check the change type
				if($type == "COMPONENT") {
					// Component Change
					$newchanges[] = array('property'=>'component','from'=>$components[$change['fromid']]['name'],'to'=>$components[$change['toid']]['name']);
				} elseif($type == "SEVERITY") {
					// Severity Change
					$newchanges[] = array('property'=>'severity','from'=>$severities[$change['fromid']]['name'],'to'=>$severities[$change['toid']]['name']);
				} else if($type == "TYPE") {
					// Type Change
					$newchanges[] = array('property'=>'type','from'=>$types[$change['fromid']]['name'],'to'=>$types[$change['toid']]['name']);
				} else if($type == "ASIGNEE") {
					// Assignee Change
					$newchanges[] = array('property'=>'assigned_to','from'=>$users[$change['fromid']]['username'],'to'=>$users[$change['toid']]['username']);
				} else if($type == "MILESTONE") {
					// Milestone Change
					$newchanges[] = array('property'=>'milestone','from'=>$milestones[$change['fromid']]['milestone'],'to'=>$milestones[$change['toid']]['milestone']);
				} else if($type == "STATUS") {
					// Status Change
					$newchanges[] = array('property'=>'status','from'=>$statuses[$change['fromid']]['name'],'to'=>$statuses[$change['toid']]['name'],'action'=>'mark');
				} else if($type == "PRIORITY") {
					// Priority Change
					$newchanges[] = array('property'=>'priority','from'=>$priorities[$change['fromid']]['name'],'to'=>$priorities[$change['toid']]['name']);
				} else if($type == "VERSION") {
					// Version Change
					$newchanges[] = array('property'=>'version','from'=>$versions[$change['fromid']]['version'],'to'=>$versions[$change['toid']]['version']);
				} else if($type == "REOPEN") {
					// Ticket Reopen
					$newchanges[] = array('property'=>'status','from'=>$statuses[$change['fromid']]['name'],'to'=>$statuses[$change['toid']]['name'],'action'=>'reopen');
				} else if($type == "CLOSE") {
					// Ticket Close
					$newchanges[] = array('property'=>'status','from'=>$statuses[$change['fromid']]['name'],'to'=>$statuses[$change['toid']]['name'],'action'=>'close');
				} else if($type == "CREATE") {
					// Ticket Create
					$newchanges[] = array('property'=>'status','from'=>'','to'=>'1','action'=>'open');
				}
			}

			$db->query("INSERT INTO ".DBPF."ticket_history
			(id,user_id,user_name,timestamp,ticket_id,project_id,changes,comment)
			VALUES(
			'".$history['id']."',
			'".$history['userid']."',
			'".$history['username']."',
			'".$history['timestamp']."',
			'".$history['ticketid']."',
			'".$tickets[$history['ticketid']]['projectid']."',
			'".json_encode($newchanges)."',
			'".$db->res(stripslashes($history['comment']))."'
			)");
		}
		
		// Timeline
		foreach($timeline as $update)
		{
			$data = explode(':',$update['data']);
			
			if($data['0'] == 'TICKETCREATE')
				$action = 'open_ticket';
			if($data['0'] == 'TICKETCLOSE')
				$action = 'close_ticket';
			if($data['0'] == 'TICKETREOPEN')
				$action = 'reopen_ticket';
				
			$db->query("INSERT INTO ".DBPF."timeline
			(id,project_id,owner_id,action,data,user_id,user_name,timestamp,date)
			VALUES(
			'".$update['id']."',
			'".$update['projectid']."',
			'".$data['1']."',
			'".$action."',
			'".$tickets[$data['1']]['tid']."',
			'".$update['userid']."',
			'".$update['username']."',
			'".$update['timestamp']."',
			'".date("Y-m-d",$update['timestamp'])."'
			)");
		}
		
		// Users
		foreach($users as $user)
		{
			$db->query("INSERT INTO ".DBPF."users
			(id,username,password,name,email,group_id,sesshash)
			VALUES(
			'".$user['id']."',
			'".$user['username']."',
			'".$user['password']."',
			'".$user['username']."',
			'".$user['email']."',
			'".$user['groupid']."',
			'".$user['hash']."'
			)");
		}
		
		// Usergroups
		foreach($usergroups as $group)
		{
			$db->query("INSERT INTO ".DBPF."usergroups
			(id,name,is_admin,create_tickets,update_tickets,delete_tickets,add_attachments)
			VALUES(
			'".$group['id']."',
			'".$group['name']."',
			'".$group['isadmin']."',
			'".$group['createtickets']."',
			'".$group['updatetickets']."',
			'".($group['id'] == 1 ? 1 : 0)."',
			'".($group['id'] > 3 ? 1 : 0)."'
			)");
		}
		
		// Attachments
		foreach($attachments as $attachment)
		{
			$db->query("INSERT INTO ".DBPF."attachments
			(id,name,contents,type,size,uploaded,owner_id,owner_name,ticket_id,project_id)
			VALUES(
			'".$attachment['id']."',
			'".$attachment['name']."',
			'".$attachment['contents']."',
			'".$attachment['type']."',
			'-1',
			'".$attachment['timestamp']."',
			'".$attachment['ownerid']."',
			'".$attachment['ownername']."',
			'".$attachment['ticketid']."',
			'".$attachment['projectid']."'
			)");
		}
		
		// Priorities
		foreach($priorities as $priority)
		{
			$db->query("INSERT INTO ".DBPF."priorities
			(id,name)
			VALUES(
			'".$priority['id']."',
			'".$priority['name']."'
			)");
		}
		
		// Severities
		foreach($severities as $severity)
		{
			$db->query("INSERT INTO ".DBPF."severities
			(id,name)
			VALUES(
			'".$severity['id']."',
			'".$severity['name']."'
			)");
		}
		
		// Types
		foreach($types as $type)
		{
			$db->query("INSERT INTO ".DBPF."ticket_types
			(id,name,bullet)
			VALUES(
			'".$type['id']."',
			'".$type['name']."',
			'*',
			'1'
			)");
		}
	}
	
	$db->query("UPDATE ".DBPF."settings SET value='18' WHERE setting='db_revision' LIMIT 1");
	
	if(file_exists('upgrade.php')) header("Location: upgrade.php?upgrade");
	
	head('migrate');
	?>
	<div align="center">
		Your database has been converted, Welcome to Traq 2.0!
	</div>
	<?php
	foot();
}
?>
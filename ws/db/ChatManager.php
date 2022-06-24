<?php
class ChatManager {
	private $userType;
	private $requestPageID;
	private $senderID;
	private $msg;
	private $createdOn;
	private $dbconn;
	private $scope;
	// for mentor room
	private $roomID;

	public function __construct($requestPageID = null, $dbpath, $scope, $roomID = null) {
		require_once "$dbpath";
		$this->dbconn = connect();
		if ($requestPageID != null)
			$this->requestPageID = (int) $requestPageID;
		else
			$this->roomID = (int) $roomID; // for mentor room
		$this->scope = $scope;
	}

	public function setUserType($userType) { $this->userType = $userType; }
	public function setRequestPageID($requestPageID) { $this->requestPageID = (int) $requestPageID; }
	public function setSenderID($senderID) { $this->senderID = (int) $senderID; }
	public function setMsg($msg) { $this->msg = $msg; }
	public function setCreatedOn($createdOn) { $this->createdOn = $createdOn; }

	public function save() {
		$escMsg = $this->dbconn->real_escape_string($this->msg);

		if ($this->scope == "ir") {
			$query = "
			INSERT INTO ir_chatdata
			VALUES
			(null, {$this->requestPageID}, '{$this->userType}', {$this->senderID}, '{$escMsg}', '{$this->createdOn}')";
		} else if ($this->scope == "or") {
			$query = "
			INSERT INTO or_chatdata
			VALUES
			(null, {$this->requestPageID}, '{$this->userType}', {$this->senderID}, '{$escMsg}', '{$this->createdOn}')";
		} else {
			// for mentor room
			$query = "
			INSERT INTO mr_chatdata
			VALUES
			(null, {$this->roomID}, '{$this->userType}', {$this->senderID}, '{$escMsg}', '{$this->createdOn}')";
		}

		if ($this->dbconn->query($query))
			return true;
		else
			echo $this->dbconn->error;
	}

	public function loadChatData() {
		if ($this->scope == "ir") {
			$query = "
			SELECT cd.*, bo.name FROM ir_chatdata cd LEFT JOIN business_owner bo ON cd.sender_id = bo.id WHERE user_type = 'owner' AND ir_id = {$this->requestPageID}
			UNION
			SELECT cd.*, e.name FROM ir_chatdata cd LEFT JOIN expert e ON cd.sender_id = e.id WHERE user_type = 'expert' AND ir_id = {$this->requestPageID}
			ORDER BY id";
		} else if ($this->scope == "or") {
			$query = "
			SELECT cd.*, bo.name FROM or_chatdata cd LEFT JOIN business_owner bo ON cd.sender_id = bo.id WHERE user_type = 'owner' AND or_id = {$this->requestPageID}
			UNION
			SELECT cd.*, e.name FROM or_chatdata cd LEFT JOIN expert e ON cd.sender_id = e.id WHERE user_type = 'expert' AND or_id = {$this->requestPageID}
			ORDER BY id";
		} else {
			// for mentor room
			$query = "
			SELECT cd.*, bo.name FROM mr_chatdata cd LEFT JOIN business_owner bo ON cd.sender_id = bo.id WHERE user_type = 'owner' AND room_id = {$this->roomID}
			UNION
			SELECT cd.*, e.name FROM mr_chatdata cd LEFT JOIN expert e ON cd.sender_id = e.id WHERE user_type = 'expert' AND room_id = {$this->roomID}
			ORDER BY id";
		}
		
		$result = $this->dbconn->query($query);
		return $result;
	}

	public function clearChatData() {
		if ($this->scope == "ir")
			$query = "DELETE FROM ir_chatdata WHERE ir_id = {$this->requestPageID}";
		else if ($this->scope == "or")
			$query = "DELETE FROM or_chatdata WHERE or_id = {$this->requestPageID}";
		else
			$query = "DELETE FROM mr_chatdata WHERE room_id = {$this->roomID}";

		if ($this->dbconn->query($query))
			return true;
		else 
			echo $this->dbconn->error;
	}

	public function who() {
		if ($this->scope == "ir") {
			$query = "
			SELECT bo.id as owner_id, bo.name as owner_name, e.id as expert_id, e.name as expert_name
			FROM interest_request ir, business_page bp, business_owner bo, general_prog_page gpp, expert e
			WHERE ir.business_page_id = bp.id AND bp.owner_id = bo.id AND ir.general_prog_page_id = gpp.id AND gpp.expert_id = e.id AND ir.ir_id = {$this->requestPageID}";
		} else if ($this->scope == "or") {
			$query = "
			SELECT bo.id as owner_id, bo.name as owner_name, expert.id as expert_id, expert.name as expert_name FROM offer_request ofr, (
			    SELECT ofr.or_id, gpp.expert_id 
			    FROM offer_request ofr, general_prog_page gpp 
			    WHERE ofr.or_id = {$this->requestPageID} AND gpp.id = ofr.general_prog_page_id
			    UNION
			    SELECT ofr.or_id, spp.expert_id 
			    FROM offer_request ofr, specified_prog_page spp 
			    WHERE ofr.or_id = {$this->requestPageID} AND spp.id = ofr.specified_prog_page_id
			) x, business_page bp, business_owner bo, expert
			WHERE ofr.business_page_id = bp.id AND bp.owner_id = bo.id AND ofr.or_id = x.or_id  AND x.expert_id = expert.id AND ofr.or_id = {$this->requestPageID}";
		} else {
			$query = "
			SELECT bo.id as owner_id, bo.name as owner_name, e.id as expert_id, e.name as expert_name
			FROM mentor_room mr, business_page bp, business_owner bo, specified_prog_page spp, expert e
			WHERE mr.business_page_id = bp.id AND bp.owner_id = bo.id AND mr.specified_prog_page_id = spp.id AND spp.expert_id = e.id AND mr.room_id = {$this->roomID}";
		}

		$result = $this->dbconn->query($query);
		if ($result->num_rows != 0) {
			$row = $result->fetch_assoc();
			return $row;
		} else {
			echo "An error occured: request page not found";
		}
	}

}
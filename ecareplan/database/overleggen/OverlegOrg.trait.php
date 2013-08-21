<?php
trait OverlegOrgTrait {
	private $uid;
	private $overlegId;
	private $organisatieFactuur;
	private $organisatieFactuurDatum;
	private $organisatieDubbel;

	/**
	 * set value for uid 
	 *
	 * type:INT,size:10,default:null,primary,unique,autoincrement
	 *
	 * @param mixed $uid
	 * @return Overlegorg
	 */
	public function &setUid($uid) {
		$this->uid=$uid;
		return $this;
	}

	/**
	 * get value for uid 
	 *
	 * type:INT,size:10,default:null,primary,unique,autoincrement
	 *
	 * @return mixed
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * set value for overleg_id 
	 *
	 * type:INT,size:10,default:null,index
	 *
	 * @param mixed $overlegId
	 * @return Overlegorg
	 */
	public function &setOverlegId($overlegId) {
		$this->overlegId=$overlegId;
		return $this;
	}

	/**
	 * get value for overleg_id 
	 *
	 * type:INT,size:10,default:null,index
	 *
	 * @return mixed
	 */
	public function getOverlegId() {
		return $this->overlegId;
	}

	/**
	 * set value for organisatie_factuur 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @param mixed $organisatieFactuur
	 * @return Overlegorg
	 */
	public function &setOrganisatieFactuur($organisatieFactuur) {
		$this->organisatieFactuur=$organisatieFactuur;
		return $this;
	}

	/**
	 * get value for organisatie_factuur 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getOrganisatieFactuur() {
		return $this->organisatieFactuur;
	}

	/**
	 * set value for organisatie_factuur_datum 
	 *
	 * type:VARCHAR,size:12,default:null,nullable
	 *
	 * @param mixed $organisatieFactuurDatum
	 * @return Overlegorg
	 */
	public function &setOrganisatieFactuurDatum($organisatieFactuurDatum) {
		$this->organisatieFactuurDatum=$organisatieFactuurDatum;
		return $this;
	}

	/**
	 * get value for organisatie_factuur_datum 
	 *
	 * type:VARCHAR,size:12,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getOrganisatieFactuurDatum() {
		return $this->organisatieFactuurDatum;
	}

	/**
	 * set value for organisatie_dubbel 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @param mixed $organisatieDubbel
	 * @return Overlegorg
	 */
	public function &setOrganisatieDubbel($organisatieDubbel) {
		$this->organisatieDubbel=$organisatieDubbel;
		return $this;
	}

	/**
	 * get value for organisatie_dubbel 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getOrganisatieDubbel() {
		return $this->organisatieDubbel;
	}

	/**
	 * return array with the field id as index and the field value as value.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'uid'=>$this->getUid(),
			'overleg_id'=>$this->getOverlegId(),
			'organisatie_factuur'=>$this->getOrganisatieFactuur(),
			'organisatie_factuur_datum'=>$this->getOrganisatieFactuurDatum(),
			'organisatie_dubbel'=>$this->getOrganisatieDubbel());
	}
        
        public function assignByHash($result){
            parent::assignByHash($result);
            $this->setUid($result['uid']);
            $this->setOverlegId($result['overleg_id']);
            $this->setOrganisatieFactuur($result['organisatie_factuur']);
            $this->setOrganisatieFactuurDatum($result['organisatie_factuur_datum']);
            $this->setOrganisatieDubbel($result['organisatie_dubbel']);
        }
        
        public static function getFieldNames(){
            return array(1=>'uid',
                2=>'overleg_id',
                3=>'organisatie_factuur',
                4=>'organisatie_factuur_datum',
                5=>'organisatie_dubbel');
        }
        
        public function insertIntoDatabaseOrg(PDO $db){
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $orgfactuur = $this->getOrganisatieFactuur();
            $orgdatum = $this->getOrganisatieFactuurDatum();
            $orgdubbel = $this->getOrganisatieDubbel();
            
            $query = 'INSERT INTO `overlegorg`
                SET `uid` = :uid, `overleg_id`= :overleg_id, `organisatie_factuur`= :organisatie_factuur, `organisatie_factuur_datum`= :organisatie_factuur_datum, `organisatie_dubbel`= :organisatie_dubbel';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':organisatie_factuur',$orgfactuur);
                $statement->bindValue(':organisatie_factuur_datum',$orgdatum);
                $statement->bindValue(':organisatie_dubbel',$orgdubbel);
                $affected = $statement->execute();
                if (false===$affected) {
			$statement->closeCursor();
			throw new Exception($statement->errorCode() . ':' . var_export($statement->errorInfo(), true), 0);
		}
		$lastInsertId=$db->lastInsertId();
		if (false!==$lastInsertId) {
			$this->setUid($lastInsertId);
		}
                $statement->closeCursor();
            } catch (PDOException $e) {
                $error_messsage = $e->getMessage();
                //display
            }
            return $affected;
        }
        
        public function updateToDatabaseOrg(PDO $db, $parent){
            if($parent) {
                parent::updateToDatabase($db);
            }
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $orgfactuur = $this->getOrganisatieFactuur();
            $orgdatum = $this->getOrganisatieFactuurDatum();
            $orgdubbel = $this->getOrganisatieDubbel();
            
            $query = 'UPDATE `overlegorg` 
                SET `overleg_id`= :overleg_id, `organisatie_factuur`= :organisatie_factuur, `organisatie_factuur_datum`= :organisatie_factuur_datum, `organisatie_dubbel`= :organisatie_dubbel
                WHERE `uid` = :uid';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':organisatie_factuur',$orgfactuur);
                $statement->bindValue(':organisatie_factuur_datum',$orgdatum);
                $statement->bindValue(':organisatie_dubbel',$orgdubbel);
                $affected = $statement->execute();
                if (false===$affected) {
			$statement->closeCursor();
			throw new Exception($statement->errorCode() . ':' . var_export($statement->errorInfo(), true), 0);
		}
		$lastInsertId=$db->lastInsertId();
		if (false!==$lastInsertId) {
			$this->setUid($lastInsertId);
		}
                $statement->closeCursor();
            } catch (PDOException $e) {
                $error_messsage = $e->getMessage();
            }
        }
        
        public function deleteFromDatabaseOrg(PDO $db, $parent){
            $uid = $this->getUid();
            $query = 'DELETE FROM `overlegorg` 
            WHERE `uid`= :uid';
            try{
                $statement=$db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->execute();
                if (false===$affected) {
			$stmt->closeCursor();
			throw new Exception($stmt->errorCode() . ':' . var_export($stmt->errorInfo(), true), 0);
		}
                $statement->closeCursor();
                if($parent) {
                    parent::deleteFromDatabase($db);
                }
            } catch (PDOException $e){
                $error_message = $e->getMessage();
            }
            return $valid;
        }
        
        public static function findByIdOrg(PDO $db, $id){
            $query = 'SELECT `uid`, `overleg_id`, `organisatie_factuur`, `organisatie_factuur_datum`, `organisatie_dubbel` 
                FROM `overlegorg` 
                WHERE `uid`= :uid';
            
            $statement = $db->prepare($query);
            $statement->bindValue(':uid', $id);
            $affected= $statement->execute();
            if (false===$affected) {
                    $statement->closeCursor();			
            }
            $result = $statement->fetch();
            $statement->closeCursor();
            if(!$result) {
                    return null;
            }
            $org = new OverlegOrg();;
            $org->assignByHash($result);
            return $org;
        }
        
        public static function findByOverlegOrg(PDO $db,$id){
            $query = 'SELECT `uid`, `overleg_id`, `organisatie_factuur`, `organisatie_factuur_datum`, `organisatie_dubbel`  
                FROM `overlegorg` 
                WHERE `overleg_id` = :overleg_id';
            
            $statement = $db->prepare($query);
            $statement->bindValue(':overleg_id', $id);
            $statement->execute();
            
            if (false===$affected) {
                    $statement->closeCursor();	
                    throw new Exception($statement->errorCode() . ':' . var_export($statement->errorInfo(), true), 0);
            }
            $result = $statement->fetch();
            $statement->closeCursor();
            if(!$result) {
                    return null;
            }
            $org = new OverlegOrg();;
            $org->assignByHash($result);
            return $org;
        }
}
?>
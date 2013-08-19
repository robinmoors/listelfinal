<?php
trait OverlegOmbTrait {
	private $uid;
	private $overlegId;
	private $ombFactuur;
	private $ombActief;
	private $ombRangorde;
	/**
	 * set value for uid 
	 *
	 * type:INT,size:10,default:null,primary,unique,autoincrement
	 *
	 * @param mixed $uid
	 * @return Overlegomb
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
	 * @return Overlegomb
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
	 * set value for omb_factuur 
	 *
	 * type:VARCHAR,size:20,default:null,nullable
	 *
	 * @param mixed $ombFactuur
	 * @return Overlegomb
	 */
	public function &setOmbFactuur($ombFactuur) {
		$this->ombFactuur=$ombFactuur;
		return $this;
	}

	/**
	 * get value for omb_factuur 
	 *
	 * type:VARCHAR,size:20,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getOmbFactuur() {
		return $this->ombFactuur;
	}

	/**
	 * set value for omb_actief 
	 *
	 * type:INT,size:10,default:0,nullable
	 *
	 * @param mixed $ombActief
	 * @return Overlegomb
	 */
	public function &setOmbActief($ombActief) {
		$this->ombActief=$ombActief;
		return $this;
	}

	/**
	 * get value for omb_actief 
	 *
	 * type:INT,size:10,default:0,nullable
	 *
	 * @return mixed
	 */
	public function getOmbActief() {
		return $this->ombActief;
	}

	/**
	 * set value for omb_rangorde 
	 *
	 * type:INT,size:10,default:0,nullable
	 *
	 * @param mixed $ombRangorde
	 * @return Overlegomb
	 */
	public function &setOmbRangorde($ombRangorde) {
		$this->ombRangorde=$ombRangorde;
		return $this;
	}

	/**
	 * get value for omb_rangorde 
	 *
	 * type:INT,size:10,default:0,nullable
	 *
	 * @return mixed
	 */
	public function getOmbRangorde() {
		return $this->ombRangorde;
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
			'omb_factuur'=>$this->getOmbFactuur(),
			'omb_actief'=>$this->getOmbActief(),
			'omb_rangorde'=>$this->getOmbRangorde());
	}
        
        public function assignByHash($result){
            parent::assignByHash($result);
            $this->setUid($result['uid']);
            $this->setOverlegId($result['overleg_id']);
            $this->setOmbFactuur($result['omb_factuur']);
            $this->setOmbActief($result['omb_actief']);
            $this->setOmbRangorde($result['omb_rangorde']);
        }
        
        public static function getFieldNames(){
            return array(1=>'uid',
                2=>'overleg_id',
                3=>'omb_factuur',
                4=>'omb_actief',
                5=>'omb_rangorde');
        }
        
        public function insertIntoDatabaseOmb(PDO $db){
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $ombfactuur = $this->getOmbFactuur();
            $ombactief = $this->getOmbActief();
            $ombrangorde = $this->getOmbRangorde();
            
            $query = 'INSERT INTO `overlegomb`
                SET `uid` = :uid, `overleg_id`= :overleg_id, `omb_factuur`= :omb_factuur, `omb_actief`= :omb_actief, `omb_rangorde`= :omb_rangorde';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':omb_factuur',$ombfactuur);
                $statement->bindValue(':omb_actief',$ombactief);
                $statement->bindValue(':omb_rangorde',$ombrangorde);
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
        
        public function updateToDatabaseOmb(PDO $db, $parent){
            echo '------------ UPDATE OMB -----------------';
            if($parent) {
                parent::updateToDatabase($db);
            }
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $ombfactuur = $this->getOmbFactuur();
            $ombactief = $this->getOmbActief();
            $ombrangorde = $this->getOmbRangorde();
            
            $query = 'UPDATE `overlegomb` 
                SET `overleg_id`= :overleg_id,`omb_factuur`= :omb_factuur,`omb_actief`= :omb_actief,`omb_rangorde`= :omb_rangorde 
                WHERE `uid` = :uid';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':omb_factuur',$ombfactuur);
                $statement->bindValue(':omb_actief',$ombactief);
                $statement->bindValue(':omb_rangorde',$ombrangorde);
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
        }
            
        public function deleteFromDatabaseOmb(PDO $db, $parent){
            $uid = $this->getUid();
            $query = 'DELETE FROM `overlegomb` 
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
                //display
            }
            return $valid;
        }
        
        public static function findById(PDO $db, $id){
            $query = 'SELECT `uid`, `overleg_id`, `omb_factuur`, `omb_actief`, `omb_rangorde` 
                FROM `overlegomb` 
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
            $omb = new OverlegMenos();
            $omb->assignByHash($result);
            return $omb;
        }
        
        public static function findByOverleg(PDO $db,$id){
            $query = 'SELECT `uid`, `overleg_id`, `omb_factuur`, `omb_actief`, `omb_rangorde` 
                FROM `overlegomb` 
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
                //echo '{"succes":"negative","message":"Oei het loopt even mis!<br/>We vonden geen overleg met het id"}';
                    return null;
            }
            $omb = new OverlegMenos();
            $omb->assignByHash($result);
            return $omb;
        }
        
}
?>
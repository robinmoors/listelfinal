<?php
trait OverlegPsyTrait {
	private $uid;
	private $overlegId;
	private $psyAlgemeen;
	private $psyDoelstellingen;

	/**
	 * set value for uid 
	 *
	 * type:INT,size:10,default:null,primary,unique,autoincrement
	 *
	 * @param mixed $uid
	 * @return Overlegpsy
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
	 * @return Overlegpsy
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
	 * set value for psy_algemeen 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @param mixed $psyAlgemeen
	 * @return Overlegpsy
	 */
	public function &setPsyAlgemeen($psyAlgemeen) {
		$this->psyAlgemeen=$psyAlgemeen;
		return $this;
	}

	/**
	 * get value for psy_algemeen 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getPsyAlgemeen() {
		return $this->psyAlgemeen;
	}

	/**
	 * set value for psy_doelstellingen 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @param mixed $psyDoelstellingen
	 * @return Overlegpsy
	 */
	public function &setPsyDoelstellingen($psyDoelstellingen) {
		$this->psyDoelstellingen=$psyDoelstellingen;
		return $this;
	}

	/**
	 * get value for psy_doelstellingen 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getPsyDoelstellingen() {
		return $this->psyDoelstellingen;
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
			'psy_algemeen'=>$this->getPsyAlgemeen(),
			'psy_doelstellingen'=>$this->getPsyDoelstellingen());
	}
        
        public function assignByHash($result){
            parent::assignByHash($result);
            $this->setUid($result['uid']);
            $this->setOverlegId($result['overleg_id']);
            $this->setPsyAlgemeen($result['psy_algemeen']);
            $this->setPsyDoelstellingen($result['psy_doelstellingen']);
        }
        
        public static function getFieldNames(){
            return array(1=>'uid',
                2=>'overleg_id',
                3=>'psy_algemeen',
                4=>'psy_doelstellingen');
        }
        
        public function insertIntoDatabasePsy(PDO $db){
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $psyalg = $this->getPsyAlgemeen();
            $psydoel = $this->getPsyDoelstellingen();
            
            
            $query = 'INSERT INTO `overlegpsy`
                SET `uid` = :uid, `overleg_id`= :overleg_id, `psy_algemeen`= :psy_algemeen, `psy_doelstellingen`= :psy_doelstellingen';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':psy_algemeen',$psyalg);
                $statement->bindValue(':psy_doelstellingen',$psydoel);
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
            return $affected;
        }
        
        public function updateToDatabasePsy(PDO $db, $parent){
            if($parent) {
                parent::updateToDatabase($db);
            }
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $psyalg = $this->getPsyAlgemeen();
            $psydoel = $this->getPsyDoelstellingen();
            
            $query = 'UPDATE `overlegpsy` 
                SET `uid` = :uid, `overleg_id`= :overleg_id, `psy_algemeen`= :psy_algemeen, `psy_doelstellingen`= :psy_doelstellingen 
                WHERE `uid` = :uid';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':psy_algemeen',$psyalg);
                $statement->bindValue(':psy_doelstellingen',$psydoel);
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
        
        public function deleteFromDatabasePsy(PDO $db, $parent){
            $uid = $this->getUid();
            $query = 'DELETE FROM `overlegpsy` 
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
        
        public static function findByIdPsy(PDO $db, $id){
            $query = 'SELECT `uid`, `overleg_id`, `psy_algemeen`, `psy_doelstellingen` 
                FROM `overlegpsy` 
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
            $psy = new OverlegPsy();
            $psy->assignByHash($result);
            return $psy;
        }
        
        public static function findByOverlegPsy(PDO $db,$id){
            $query = 'SELECT `uid`, `overleg_id`, `psy_algemeen`, `psy_doelstellingen` 
                FROM `overlegpsy` 
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
            $psy = new OverlegPsy();
            $psy->assignByHash($result);
            return $psy;
        }
}
?>
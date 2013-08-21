<?php
trait OverlegLokTrait {
	private $uid;
	private $overlegId;
	private $lokaalAlgemeen;
	private $lokaalDoelstellingen;

	/**
	 * set value for uid 
	 *
	 * type:INT,size:10,default:null,primary,unique,autoincrement
	 *
	 * @param mixed $uid
	 * @return Overleglok
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
	 * @return Overleglok
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
	 * set value for lokaal_algemeen 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @param mixed $lokaalAlgemeen
	 * @return Overleglok
	 */
	public function &setLokaalAlgemeen($lokaalAlgemeen) {
		$this->lokaalAlgemeen=$lokaalAlgemeen;
		return $this;
	}

	/**
	 * get value for lokaal_algemeen 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getLokaalAlgemeen() {
		return $this->lokaalAlgemeen;
	}

	/**
	 * set value for lokaal_doelstellingen 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @param mixed $lokaalDoelstellingen
	 * @return Overleglok
	 */
	public function &setLokaalDoelstellingen($lokaalDoelstellingen) {
		$this->lokaalDoelstellingen=$lokaalDoelstellingen;
		return $this;
	}

	/**
	 * get value for lokaal_doelstellingen 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getLokaalDoelstellingen() {
		return $this->lokaalDoelstellingen;
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
			'lokaal_algemeen'=>$this->getLokaalAlgemeen(),
			'lokaal_doelstellingen'=>$this->getLokaalDoelstellingen());
	}
        
        public function assignByHash($result) {
		$this->setUid($result['uid']);
		$this->setOverlegId($result['overleg_id']);
		$this->setLokaalAlgemeen($result['lokaal_algemeen']);
		$this->setLokaalDoelstellingen($result['lokaal_doelstellingen']);
	}
        
        public function getFieldNames(){
            return array(1=>'uid',
                2=>'overleg_id',
                3=>'lokaal_algemeen',
                4=>'lokaal_doelstellingen');
        }
        
        public function insertIntoDatabaseLok(PDO $db){
            $overlegid = $this->getOverlegId();
            $lokaalalgemeen = $this->getLokaalAlgemeen();
            $lokaaldoelstellingen = $this->getLokaalDoelstellingen();
            
            $query = 'INSERT INTO listel5.overleglok 
                SET overleg_id = :overleg_id, lokaal_algemeen = :lokaal_algemeen, lokaal_doelstellingen= :lokaal_doelstellingen';
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':lokaal_algemeen',$lokaalalgemeen);
                $statement->bindValue(':lokaal_doelstellingen',$lokaaldoelstellingen);
                $affected = $statement->execute();
                $statement->closeCursor();
            } catch (PDOException $e) {
                $error_messsage = $e->getMessage();
                //display
            }
            return $affected;
        }
        
        public function updateToDatabaseLok(PDO $db){
            $uid = $this->getUid();
            $overlegid = $this->getOverlegId();
            $lokaalalgemeen = $this->getLokaalAlgemeen();
            $lokaaldoelstellingen = $this->getLokaalDoelstellingen();
            
            $query = 'UPDATE listel5.overleglok
                SET overleg_id = :overleg_id, lokaal_algemeen = :lokaal_algemeen, lokaal_doelstellingen= :lokaal_doelstellingen
                WHERE uid = :uid';
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':lokaal_algemeen',$lokaalalgemeen);
                $statement->bindValue(':lokaal_doelstellingen',$lokaaldoelstellingen);
                $affected = $statement->execute();
                $statement->closeCursor();
            } catch (PDOException $e) {
                $error_messsage = $e->getMessage();
                //display
            }
            return $affected;
        }
        
        public function deleteFromDatabaseLok(PDO $db){
            $uid = $this->getUid();
            $query = 'DELETE FROM listel5.overleglok
                WHERE uid= :uid';
            try{
                $statement=$db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->execute();
                $valid = ($statement->rowCount() >=1);
                $statement->closeCursor();
            } catch (PDOException $e){
                $error_message = $e->getMessage();
                //display
            }
            return $valid;
        }
        
        public function findByIdLok(PDO $db){
            $uid = $this->getUid();
            $query = 'SELECT `uid`, `overleg_id`, `lokaal_algemeen`, `lokaal_doelstellingen` FROM `listel5`.`overleglok` WHERE `id`= :id';
            $statement = $db->prepare($query);
            $statement->bindValue(':uid', $uid);
            $affected= $statement->execute();
            if (false===$affected) {
                    $statement->closeCursor();			
            }
            $result = $statement->fetch();
            $statement->closeCursor();
            if(!$result) {
                    return null;
            }
            return toArray($result);
        }
        
        public function findByOverlegLok(PDO $db){
            $overlegid = $this->getOverlegId();
            $query  = 'SELECT `uid`, `overleg_id`, `lokaal_algemeen`, `lokaal_doelstellingen` FROM `listel5`.`overleglok` WHERE `overleg_id`= :overleg_id';
            $statement = $db->prepare($query);
            $statement->bindValue(':overleg_id', $overlegid);
            $statement->execute();
            
            $resultInstances=array();
            while($result=$statement->fetch(PDO::FETCH_ASSOC)) {
                $resultInstances[]=toArray($result);
            }
            $statement->closeCursor();
            return $resultInstances;
            }
}
?>
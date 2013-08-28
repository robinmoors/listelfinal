<?php
trait OverlegTpTrait {
	private $uid;
	private $overlegId;
	private $tpVerslag;
	private $tpAuteur;
	private $tpNieuwepartners;
	private $tpRechtenoc;

	/**
	 * set value for uid 
	 *
	 * type:INT,size:10,default:null,primary,unique,autoincrement
	 *
	 * @param mixed $uid
	 * @return Overlegtp
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
	 * @return Overlegtp
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
	 * set value for tp_verslag 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @param mixed $tpVerslag
	 * @return Overlegtp
	 */
	public function &setTpVerslag($tpVerslag) {
		$this->tpVerslag=$tpVerslag;
		return $this;
	}

	/**
	 * get value for tp_verslag 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getTpVerslag() {
		return $this->tpVerslag;
	}

	/**
	 * set value for tp_auteur 
	 *
	 * type:ENUM,size:3,default:null,nullable
	 *
	 * @param mixed $tpAuteur
	 * @return Overlegtp
	 */
	public function &setTpAuteur($tpAuteur) {
		$this->tpAuteur=$tpAuteur;
		return $this;
	}

	/**
	 * get value for tp_auteur 
	 *
	 * type:ENUM,size:3,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getTpAuteur() {
		return $this->tpAuteur;
	}

	/**
	 * set value for tp_nieuwepartners 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @param mixed $tpNieuwepartners
	 * @return Overlegtp
	 */
	public function &setTpNieuwepartners($tpNieuwepartners) {
		$this->tpNieuwepartners=$tpNieuwepartners;
		return $this;
	}

	/**
	 * get value for tp_nieuwepartners 
	 *
	 * type:TEXT,size:65535,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getTpNieuwepartners() {
		return $this->tpNieuwepartners;
	}

	/**
	 * set value for tp_rechtenOC 
	 *
	 * type:TINYINT,size:3,default:1,nullable
	 *
	 * @param mixed $tpRechtenoc
	 * @return Overlegtp
	 */
	public function &setTpRechtenoc($tpRechtenoc) {
		$this->tpRechtenoc=$tpRechtenoc;
		return $this;
	}

	/**
	 * get value for tp_rechtenOC 
	 *
	 * type:TINYINT,size:3,default:1,nullable
	 *
	 * @return mixed
	 */
	public function getTpRechtenoc() {
		return $this->tpRechtenoc;
	}

	/**
	 * return array with the field id as index and the field value as value.
	 *
	 * @return array
	 */
	public function toArrayTp() {
		return array(
			'uid'=>$this->getUid(),
			'overleg_id'=>$this->getOverlegId(),
			'tp_verslag'=>$this->getTpVerslag(),
			'tp_auteur'=>$this->getTpAuteur(),
			'tp_nieuwepartners'=>$this->getTpNieuwepartners(),
			'tp_rechtenOC'=>$this->getTpRechtenoc());
	}
        
        public function assignByHashTp($result){
            parent::assignByHash($result);
            $this->setUid($result['uid']);
            $this->setOverlegId($result['overleg_id']);
            $this->setTpVerslag($result['tp_verslag']);
            $this->setTpAuteur($result['tp_auteur']);
            $this->setTpNieuwepartners($result['tp_nieuwepartners']);
            $this->setTpRechtenoc($result['tp_rechtenOC']);
        }
        
        public static function getFieldNamesTp(){
            return array(1=>'uid',
                2=>'overleg_id',
                3=>'tp_verslag',
                4=>'tp_auteur',
                5=>'tp_nieuwepartners',
                6=>'tp_rechtenOC');
        }
        
        public function insertIntoDatabaseTp(PDO $db){
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $tpverslag = $this->getTpVerslag();
            $tpauteur = $this->getTpAuteur();
            $tppartner = $this->getTpNieuwepartners();
            $tprechtenoc = $this->getTpRechtenoc();
            
            $query = 'INSERT INTO `overlegtp`
                SET `uid` = :uid, `overleg_id`= :overleg_id, `tp_verslag`= :tp_verslag, `tp_auteur`= :tp_auteur, `tp_nieuwepartners`= :tp_nieuwepartners, `tp_rechtenOC`= :tp_rechtenOC';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':tp_verslag',$tpverslag);
                $statement->bindValue(':tp_auteur',$tpauteur);
                $statement->bindValue(':tp_nieuwepartners',$tppartner);
                $statement->bindValue(':tp_rechtenOC',$tprechtenoc);
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
        
        public function updateToDatabaseTp(PDO $db, $parent){
            if($parent) {
                parent::updateToDatabase($db);
            }
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $tpverslag = $this->getTpVerslag();
            $tpauteur = $this->getTpAuteur();
            $tppartner = $this->getTpNieuwepartners();
            $tprechtenoc = $this->getTpRechtenoc();
            
            $query = 'UPDATE `overlegtp` 
                SET `uid` = :uid, `overleg_id`= :overleg_id, `tp_verslag`= :tp_verslag, `tp_auteur`= :tp_auteur, `tp_nieuwepartners`= :tp_nieuwepartners, `tp_rechtenOC`= :tp_rechtenOC
                WHERE `uid` = :uid';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':tp_verslag',$tpverslag);
                $statement->bindValue(':tp_auteur',$tpauteur);
                $statement->bindValue(':tp_nieuwepartners',$tppartner);
                $statement->bindValue(':tp_rechtenOC',$tprechtenoc);
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
        
        public function deleteFromDatabaseTp(PDO $db, $parent){
            $uid = $this->getUid();
            $query = 'DELETE FROM `overlegtp` 
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
        
        public static function findByIdTp(PDO $db, $id){
            $query = 'SELECT `uid`, `overleg_id`, `tp_verslag`, `tp_auteur`, `tp_nieuwepartners`, `tp_rechtenOC` 
                FROM `overlegtp` 
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
            $tp = new OverlegTp();
            $tp->assignByHashTp($result);
            return $tp;
        }
        
        public static function findByOverlegTp(PDO $db,$id){
            $query = 'SELECT `uid`, `overleg_id`, `tp_verslag`, `tp_auteur`, `tp_nieuwepartners`, `tp_rechtenOC` 
                FROM `overlegtp` 
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
            $tp = new OverlegTp();
            $tp->assignByHashTp($result);
            return $tp;
        }
}
?>
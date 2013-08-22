<?php
trait OverlegExtendedTrait {
	private $uid;
	private $overlegId;
	private $locatieTekst;
	private $tijdstip;
	private $akkoordPatient;
	private $aanwezigPatient;
	private $vertegenwoordiger;
	private $evalNieuw;
	private $afronddatum;
	private $volgendeDatum;
	private $verklaringHuisarts;
	private $ambulant;
	private $huisartsBelangrijk;

	/**
	 * set value for uid 
	 *
	 * type:INT,size:10,default:null,primary,unique,autoincrement
	 *
	 * @param mixed $uid
	 * @return Overlegextended
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
	 * @return Overlegextended
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
	 * set value for locatieTekst 
	 *
	 * type:VARCHAR,size:200,default:null,nullable
	 *
	 * @param mixed $locatieTekst
	 * @return Overlegextended
	 */
	public function &setLocatieTekst($locatieTekst) {
		$this->locatieTekst=$locatieTekst;
		return $this;
	}

	/**
	 * get value for locatieTekst 
	 *
	 * type:VARCHAR,size:200,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getLocatieTekst() {
		return $this->locatieTekst;
	}

	/**
	 * set value for tijdstip 
	 *
	 * type:VARCHAR,size:80,default:null,nullable
	 *
	 * @param mixed $tijdstip
	 * @return Overlegextended
	 */
	public function &setTijdstip($tijdstip) {
		$this->tijdstip=$tijdstip;
		return $this;
	}

	/**
	 * get value for tijdstip 
	 *
	 * type:VARCHAR,size:80,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getTijdstip() {
		return $this->tijdstip;
	}

	/**
	 * set value for akkoord_patient 
	 *
	 * type:TINYINT,size:3,default:1,nullable
	 *
	 * @param mixed $akkoordPatient
	 * @return Overlegextended
	 */
	public function &setAkkoordPatient($akkoordPatient) {
		$this->akkoordPatient=$akkoordPatient;
		return $this;
	}

	/**
	 * get value for akkoord_patient 
	 *
	 * type:TINYINT,size:3,default:1,nullable
	 *
	 * @return mixed
	 */
	public function getAkkoordPatient() {
		return $this->akkoordPatient;
	}

	/**
	 * set value for aanwezig_patient 
	 *
	 * type:TINYINT,size:3,default:-1,nullable
	 *
	 * @param mixed $aanwezigPatient
	 * @return Overlegextended
	 */
	public function &setAanwezigPatient($aanwezigPatient) {
		$this->aanwezigPatient=$aanwezigPatient;
		return $this;
	}

	/**
	 * get value for aanwezig_patient 
	 *
	 * type:TINYINT,size:3,default:-1,nullable
	 *
	 * @return mixed
	 */
	public function getAanwezigPatient() {
		return $this->aanwezigPatient;
	}

	/**
	 * set value for vertegenwoordiger 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @param mixed $vertegenwoordiger
	 * @return Overlegextended
	 */
	public function &setVertegenwoordiger($vertegenwoordiger) {
		$this->vertegenwoordiger=$vertegenwoordiger;
		return $this;
	}

	/**
	 * get value for vertegenwoordiger 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getVertegenwoordiger() {
		return $this->vertegenwoordiger;
	}

	/**
	 * set value for eval_nieuw 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @param mixed $evalNieuw
	 * @return Overlegextended
	 */
	public function &setEvalNieuw($evalNieuw) {
		$this->evalNieuw=$evalNieuw;
		return $this;
	}

	/**
	 * get value for eval_nieuw 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getEvalNieuw() {
		return $this->evalNieuw;
	}

	/**
	 * set value for afronddatum 
	 *
	 * type:VARCHAR,size:10,default:null,nullable
	 *
	 * @param mixed $afronddatum
	 * @return Overlegextended
	 */
	public function &setAfronddatum($afronddatum) {
		$this->afronddatum=$afronddatum;
		return $this;
	}

	/**
	 * get value for afronddatum 
	 *
	 * type:VARCHAR,size:10,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getAfronddatum() {
		return $this->afronddatum;
	}

	/**
	 * set value for volgende_datum 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @param mixed $volgendeDatum
	 * @return Overlegextended
	 */
	public function &setVolgendeDatum($volgendeDatum) {
		$this->volgendeDatum=$volgendeDatum;
		return $this;
	}

	/**
	 * get value for volgende_datum 
	 *
	 * type:INT,size:10,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getVolgendeDatum() {
		return $this->volgendeDatum;
	}

	/**
	 * set value for verklaring_huisarts 
	 *
	 * type:ENUM,size:9,default:null,nullable
	 *
	 * @param mixed $verklaringHuisarts
	 * @return Overlegextended
	 */
	public function &setVerklaringHuisarts($verklaringHuisarts) {
		$this->verklaringHuisarts=$verklaringHuisarts;
		return $this;
	}

	/**
	 * get value for verklaring_huisarts 
	 *
	 * type:ENUM,size:9,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getVerklaringHuisarts() {
		return $this->verklaringHuisarts;
	}

	/**
	 * set value for ambulant 
	 *
	 * type:ENUM,size:10,default:null,nullable
	 *
	 * @param mixed $ambulant
	 * @return Overlegextended
	 */
	public function &setAmbulant($ambulant) {
		$this->ambulant=$ambulant;
		return $this;
	}

	/**
	 * get value for ambulant 
	 *
	 * type:ENUM,size:10,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getAmbulant() {
		return $this->ambulant;
	}

	/**
	 * set value for huisarts_belangrijk 
	 *
	 * type:BIT,size:0,default:null,nullable
	 *
	 * @param mixed $huisartsBelangrijk
	 * @return Overlegextended
	 */
	public function &setHuisartsBelangrijk($huisartsBelangrijk) {
		$this->huisartsBelangrijk=$huisartsBelangrijk;
		return $this;
	}

	/**
	 * get value for huisarts_belangrijk 
	 *
	 * type:BIT,size:0,default:null,nullable
	 *
	 * @return mixed
	 */
	public function getHuisartsBelangrijk() {
		return $this->huisartsBelangrijk;
	}

	/**
	 * return array with the field id as index and the field value as value.
	 *
	 * @return array
	 */
	public function toArrayExt() {
		return array(
			'uid'=>$this->getUid(),
			'overleg_id'=>$this->getOverlegId(),
			'locatieTekst'=>$this->getLocatieTekst(),
			'tijdstip'=>$this->getTijdstip(),
			'akkoord_patient'=>$this->getAkkoordPatient(),
			'aanwezig_patient'=>$this->getAanwezigPatient(),
			'vertegenwoordiger'=>$this->getVertegenwoordiger(),
			'eval_nieuw'=>$this->getEvalNieuw(),
			'afronddatum'=>$this->getAfronddatum(),
			'volgende_datum'=>$this->getVolgendeDatum(),
			'verklaring_huisarts'=>$this->getVerklaringHuisarts(),
			'ambulant'=>$this->getAmbulant(),
			'huisarts_belangrijk'=>$this->getHuisartsBelangrijk());
	}
        
        public function assignByHashExt($result){
            parent::assignByHash($result);
            $this->setUid($result['uid']);
            $this->setOverlegId($result['overleg_id']);
            $this->setLocatieTekst($result['locatieTekst']);
            $this->setTijdstip($result['tijdstip']);
            $this->setAkkoortPatient($result['akkoord_patient']);
            $this->setAanwezigPatient($result['aanwezig_patient']);
            $this->setVertegenwoordiger($result['vertegenwoordiger']);
            $this->setEvalNieuw($result['eval_nieuw']);
            $this->setAfronddatum($result['afronddatum']);
            $this->setVolgendeDatum($result['volgende_datum']);
            $this->setVerklaringHuisarts($result['verklaring_huisarts']);
            $this->setAmbulant($result['ambulant']);
            $this->setHuisartsBelangrijk($result['huisarts_belangrijk']);
        }
        
        
        public static function getFieldNamesExt(){
            return array(1=>'uid',
                2=>'overleg_id',
                3=>'locatieTekst',
                4=>'tijdstip',
                5=>'akkoord_patient',
                6=>'aanwezig_patient',
                7=>'vertegenwoordiger',
                8=>'eval_nieuw',
                9=>'afronddatum',
                10=>'volgende_datum',
                11=>'verklaring_huisarts',
                12=>'ambulant',
                13=>'huisarts_belangrijk');
        }
        
        
        public function insertIntoDatabaseExt(PDO $db){
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $locatie = $this->getLocatieTekst();
            $tijdstip = $this->getTijdstip();
            $akkoord = $this->getAkkoortPatient();
            $aanwezig  = $this->getAanwezigPatient();
            $vertegenwoordiger = $this->getVertegenwoordiger();
            $eval = $this->getEvalNieuw();
            $afrond = $this->getAfronddatum();
            $volgende  = $this->getVolgendeDatum();
            $verklaring = $this->getVerklaringHuisarts();
            $ambulant = $this->getAmbulant();
            $huisarts = $this->getHuisartsBelangrijk();
            
            $query = 'INSERT INTO `overlegomb`
                SET `uid` = :uid, `overleg_id`= :overleg_id, `locatieTekst`= :locatieTekst, `tijdstip`= :tijdstip, `akkoord_patient`= :akkoord_patient ,
                `aanwezig_patient` = :aanwezig_patient, `vertegenwoordiger`= :vertegenwoordiger, `eval_nieuw`= :eval_nieuw, `afronddatum`= :afronddatum, `volgende_datum`= :volgende_datum,
                `verklaring_huisarts` = :verklaring_huisarts, `ambulant` = :ambulant, `huisarts_belangrijk` = :huisarts_belangrijk';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':locatieTekst',$locatie);
                $statement->bindValue(':tijdstip',$tijdstip);
                $statement->bindValue(':akkoord_patient',$akkoord);
                $statement->bindValue(':aanwezig_patient',$aanwezig);
                $statement->bindValue(':vertegenwoordiger',$vertegenwoordiger);
                $statement->bindValue(':eval_nieuw',$eval);
                $statement->bindValue(':afronddatum',$afrond);
                $statement->bindValue(':volgende_datum',$volgende);
                $statement->bindValue(':verklaring_huisarts',$verklaring);
                $statement->bindValue(':ambulant',$ambulant);
                $statement->bindValue(':huisarts_belangrijk',$huisarts);
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
        
        public function updateToDatabaseExt(PDO $db, $parent){
            if($parent) {
                parent::updateToDatabase($db);
            }
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $locatie = $this->getLocatieTekst();
            $tijdstip = $this->getTijdstip();
            $akkoord = $this->getAkkoortPatient();
            $aanwezig  = $this->getAanwezigPatient();
            $vertegenwoordiger = $this->getVertegenwoordiger();
            $eval = $this->getEvalNieuw();
            $afrond = $this->getAfronddatum();
            $volgende  = $this->getVolgendeDatum();
            $verklaring = $this->getVerklaringHuisarts();
            $ambulant = $this->getAmbulant();
            $huisarts = $this->getHuisartsBelangrijk();
            
            $query = 'UPDATE `overlegext` 
                SET `overleg_id`= :overleg_id, `locatieTekst`= :locatieTekst, `tijdstip`= :tijdstip, `akkoord_patient`= :akkoord_patient ,
                `aanwezig_patient` = :aanwezig_patient, `vertegenwoordiger`= :vertegenwoordiger, `eval_nieuw`= :eval_nieuw, `afronddatum`= :afronddatum, `volgende_datum`= :volgende_datum,
                `verklaring_huisarts` = :verklaring_huisarts, `ambulant` = :ambulant, `huisarts_belangrijk` = :huisarts_belangrijk
                WHERE `uid` = :uid';
            
            try{
                $statement= $db->prepare($query);
                $statement->bindValue(':uid',$uid);
                $statement->bindValue(':overleg_id',$overlegid);
                $statement->bindValue(':locatieTekst',$locatie);
                $statement->bindValue(':tijdstip',$tijdstip);
                $statement->bindValue(':akkoord_patient',$akkoord);
                $statement->bindValue(':aanwezig_patient',$aanwezig);
                $statement->bindValue(':vertegenwoordiger',$vertegenwoordiger);
                $statement->bindValue(':eval_nieuw',$eval);
                $statement->bindValue(':afronddatum',$afrond);
                $statement->bindValue(':volgende_datum',$volgende);
                $statement->bindValue(':verklaring_huisarts',$verklaring);
                $statement->bindValue(':ambulant',$ambulant);
                $statement->bindValue(':huisarts_belangrijk',$huisarts);
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
        
        public function deleteFromDatabaseExt(PDO $db, $parent){
            $uid = $this->getUid();
            $query = 'DELETE FROM `overlegext` 
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
         
        public static function findByIdExt(PDO $db, $id){
            $query = 'SELECT `uid`, `overleg_id`, `locatieTekst`, `tijdstip`, `akkoord_patient`, `aanwezig_patient`, `vertegenwoordiger`, `eval_nieuw`, `afronddatum`, `volgende_datum`, `verklaring_huisarts`, `ambulant`, `huisarts_belangrijk`
                FROM `overlegext` 
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
            return $result;
        }
        
        public static function findByOverleg(PDO $db,$id){
            $query = 'SELECT `uid`, `overleg_id`, `locatieTekst`, `tijdstip`, `akkoord_patient`, `aanwezig_patient`, `vertegenwoordiger`, `eval_nieuw`, `afronddatum`, `volgende_datum`, `verklaring_huisarts`, `ambulant`, `huisarts_belangrijk`
                FROM `overlegext` 
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
            return $result;
        }
}
?>
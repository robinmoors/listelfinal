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
        
        /*
        public function insertIntoDatabaseExt(PDO $db){
            $uid = $this->getUid();
            $overlegid  = $this->getOverlegId();
            $ombfactuur = $this->getLocatieTekst();
            $ombactief = $this->getTijdstip();
            $ombrangorde = $this->getAkkoortPatient();
            $overlegid  = $this->getAanwezigPatient();
            $ombfactuur = $this->getVertegenwoordiger();
            $ombactief = $this->getEvalNieuw();
            $ombrangorde = $this->getAfronddatum();
            $overlegid  = $this->getVolgendeDatum();
            $ombfactuur = $this->getVerklaringHuisarts();
            $ombactief = $this->getAmbulant();
            $ombrangorde = $this->getHuisartsBelangrijk();
            
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
         * */
         
}
?>
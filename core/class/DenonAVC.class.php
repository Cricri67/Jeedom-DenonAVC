<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Variables globales********************************* */

/* * ***************************Includes********************************* */
		
require_once __DIR__  . '/../../../../core/php/core.inc.php';


class DenonAVC extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {

      }
     */


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
		$this->setCategory('multimedia', 1);      
		$this->setIsEnable(1);
		$this->setIsVisible(1);
    }

    public function postInsert() {
    }

    public function preSave() {
        
    }

    public function postSave() {
        
    }

    public function preUpdate() {
		if (empty($this->getConfiguration('AdrIP'))) {
			throw new Exception(__('L\'adresse IP ne peut pas être vide',__FILE__));
		} 
    }

    public function postUpdate() {
		
		$Src = array( "Tuner","Phono","CD","Tape","DVD","VDP","TV","DBS","VCR1","VCR2","VCR3","VAUX","Net");
		
		
		if ( $this->getIsEnable() ){
			log::add('DenonAVC', 'debug', 'Création des commandes dans le postUpdate');

			// Information Power On/Off 
			$info = $this->getCmd(null, 'Power');
			if ( ! is_object($info)) {
				$info = new DenonAVCCmd();
				$info->setName('Etat');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('Power');
				$info->setType('info');
				$info->setSubType('binary');
				$info->setIsVisible(0);
				$info->setOrder(2);
				$info->save();
			}

			// Mise sous tension (On) 
			$cmd = $this->getCmd(null, 'On');
			if ( ! is_object($cmd)) {
				$cmd = new DenonAVCCmd();
				$cmd->setName('On');
				$cmd->setEqLogic_id($this->getId());
				$cmd->setLogicalId('On');
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->setIsVisible(1);
				$cmd->setOrder(0);
				$cmd->setValue($info->getId());
				$cmd->setTemplate('dashboard', 'PowerOnOff');
				$cmd->setDisplay('parameters',array ( "color" => "green", "type" => "off", "size" =>30 ));
				$cmd->save();
			}

			// Mise hors tension (Off) 
			$cmd = $this->getCmd(null, 'Off');
			if ( ! is_object($cmd)) {
				$cmd = new DenonAVCCmd();
				$cmd->setName('Off');
				$cmd->setEqLogic_id($this->getId());
				$cmd->setLogicalId('Off');
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->setIsVisible(1);
				$cmd->setOrder(1);
				$cmd->setValue($info->getId());
				$cmd->setTemplate('dashboard', 'PowerOnOff');
				$cmd->setDisplay('parameters',array ( "color" => "green", "type" => "off", "size" =>30 ));
				$cmd->save();
			}

			// Information Mute 
			$info = $this->getCmd(null, 'MuteEtat');
			if ( ! is_object($info)) {
				$info = new DenonAVCCmd();
				$info->setName('MuteEtat');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('MuteEtat');
				$info->setType('info');
				$info->setSubType('binary');
				$info->setIsVisible(0);
				$info->setOrder(5);
				$info->save();
			}

			// Mute (le nom d'une commande On/Off doit comporter On ou Off pour que le widget fonctionne !)
			$cmd = $this->getCmd(null, 'Mute');
			if ( ! is_object($cmd)) {
				$cmd = new DenonAVCCmd();
				$cmd->setName('MuteOn');
				$cmd->setEqLogic_id($this->getId());
				$cmd->setLogicalId('Mute');
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->setIsVisible(1);
				$cmd->setOrder(3);
				$cmd->setValue($info->getId());
				$cmd->setTemplate('dashboard', 'Mute');
				$cmd->save();
			}

			// Unmute (le nom d'une commande On/Off doit comporter On ou Off pour que le widget fonctionne !)
			$cmd = $this->getCmd(null, 'Unmute');
			if ( ! is_object($cmd)) {
				$cmd = new DenonAVCCmd();
				$cmd->setName('MuteOff');
				$cmd->setEqLogic_id($this->getId());
				$cmd->setLogicalId('Unmute');
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->setIsVisible(1);
				$cmd->setOrder(4);
				$cmd->setValue($info->getId());
				$cmd->setTemplate('dashboard', 'Mute');
				$cmd->save();
			}

			// Information Volume 
			$info = $this->getCmd(null, 'Volume');
			if ( ! is_object($info)) {
				$info = new DenonAVCCmd();
				$info->setName('Volume');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('Volume');
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setIsVisible(0);
				$info->setUnite('dB');
				$info->setConfiguration('minValue', -80);
				$info->setConfiguration('maxValue', -20);
				$info->setOrder(6);
				$info->save();
			}

			// Set Volume 
			$cmd = $this->getCmd(null, 'SetVol');
			if ( ! is_object($cmd)) {
				$cmd = new DenonAVCCmd();
				$cmd->setName('SetVol');
				$cmd->setEqLogic_id($this->getId());
				$cmd->setLogicalId('SetVol');
				$cmd->setType('action');
				$cmd->setSubType('slider');
				$cmd->setIsVisible(1);
				$cmd->setUnite('dB');
				$cmd->setConfiguration('minValue', -80);
				$cmd->setConfiguration('maxValue', -20);
				$cmd->setValue($info->getId());
				$cmd->setTemplate('dashboard', 'curseur');
				$cmd->setOrder(7);
				$cmd->save();
			}

			// Information Source 
			$info = $this->getCmd(null, 'Source');
			if ( ! is_object($info)) {
				$info = new DenonAVCCmd();
				$info->setName('Source');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('Source');
				$info->setType('info');
				$info->setSubType('string');
				$info->setDisplay('showNameOndashboard',0);
				$info->setDisplay('showNameOnplan',0);
				$info->setDisplay('showNameOnview',0);
				$info->setDisplay('showNameOnmobile',0);
				$info->setOrder(8);
				$info->save();
			}

			// Information Mode 
			$info = $this->getCmd(null, 'Mode');
			if ( ! is_object($info)) {
				$info = new DenonAVCCmd();
				$info->setName('Mode');
				$info->setEqLogic_id($this->getId());
				$info->setLogicalId('Mode');
				$info->setType('info');
				$info->setSubType('string');
				$info->setDisplay('showNameOndashboard',0);
				$info->setDisplay('showNameOnplan',0);
				$info->setDisplay('showNameOnview',0);
				$info->setDisplay('showNameOnmobile',0);
				$info->setOrder(9);
				$info->save();
			}

			// Sources "TUNER;PHONO;CD;CDR/TAPE;DVD;VDP;TV;DBS;VCR-1;VCR-2;VCR-3;V.AUX;NetAudio"
			for ( $order = 0 , $index = 0 ; $index < 13 ; $index++ ){
				if ($this->getConfiguration( $Src[$index] )) {
					$cmd = $this->getCmd(null, $Src[$index]);
					if ( ! is_object($cmd)) {
						$cmd = new DenonAVCCmd();
						$cmd->setName($Src[$index]);
						$cmd->setEqLogic_id($this->getId());
						$cmd->setLogicalId($Src[$index]);
						$cmd->setType('action');
						$cmd->setSubType('other');
						$cmd->setIsVisible(1);
						$cmd->setOrder(10+$order++);
						$cmd->save();
					}
				}
			}

			// Refresh 
			$cmd = $this->getCmd(null, 'Refresh');
			if ( ! is_object($cmd)) {
				$cmd = new DenonAVCCmd();
				$cmd->setName('Refresh');
				$cmd->setEqLogic_id($this->getId());
				$cmd->setLogicalId('Refresh');
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->setIsVisible(1);
				$cmd->setOrder(10+$order);
				$cmd->save();
			}
		}
    }

    public function preRemove() {
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }

     */

    /*     * **********************Getteur Setteur*************************** */

	
	public function callAVC ( $zone = 0, $cmd = '', $param = '' ){
		
		$etat = array 
				( 
				"POWER" => 0,
				"MUTE" => 0,
				"SOURCE" => "",
				"MODE" => "",
				"VOL" => 0
				);

		$urlGetTM = 'http://' . $this->getConfiguration( 'AdrIP' ) . '/TopMenu.asp';
		$urlGetMZ = 'http://' . $this->getConfiguration( 'AdrIP' ) . '/PDA/MainZone.asp';
		$urlGetZ2 = 'http://' . $this->getConfiguration( 'AdrIP' ) . '/PDA/Zone2.asp';
		$urlGetZ3 = 'http://' . $this->getConfiguration( 'AdrIP' ) . '/PDA/Zone3.asp';
		$urlSetTM = 'http://' . $this->getConfiguration( 'AdrIP' ) . '/sendTopMenu.asp';
		$urlSetMZ = 'http://' . $this->getConfiguration( 'AdrIP' ) . '/PDA/sendMainZone.asp';
		$urlSetZ2 = 'http://' . $this->getConfiguration( 'AdrIP' ) . '/PDA/sendZone2.asp';
		$urlSetZ3 = 'http://' . $this->getConfiguration( 'AdrIP' ) . '/PDA/sendZone3.asp';
		

		if ($cmd != ''){
			/*
			**	Envoi d'une commande
			*/
			
			$ch = curl_init();
			switch ($zone){
			case 0:
				log::add('DenonAVC', 'debug', $urlSetTM."?".$cmd."=".$param); 
				curl_setopt($ch, CURLOPT_URL, $urlSetTM);
				break;
			case 2:
				log::add('DenonAVC', 'debug', $urlSetZ2."?".$cmd."=".$param); 
				curl_setopt($ch, CURLOPT_URL, $urlSetZ2);
				break;
			case 3:
				log::add('DenonAVC', 'debug', $urlSetZ3."?".$cmd."=".$param); 
				curl_setopt($ch, CURLOPT_URL, $urlSetZ3);
				break;
			default:
				log::add('DenonAVC', 'debug', $urlSetMZ."?".$cmd."=".$param); 
				curl_setopt($ch, CURLOPT_URL, $urlSetMZ);
			}
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $cmd."=".$param);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($ch);
			curl_close($ch);
			log::add('DenonAVC', 'debug', "Retour commande :"); 
			log::add('DenonAVC', 'debug', $content); 
		}else{
			/*
			**	Demande d'état
			*/

			$ch = curl_init();
			switch ($zone){
			case 0: 
				curl_setopt($ch, CURLOPT_URL, $urlGetTM);
				break;
			case 2:
				curl_setopt($ch, CURLOPT_URL, $urlGetZ2);
				break;
			case 3:
				curl_setopt($ch, CURLOPT_URL, $urlGetZ3);
				break;
			default:
				curl_setopt($ch, CURLOPT_URL, $urlGetMZ);
			}
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($ch);
			curl_close($ch);
			log::add('DenonAVC', 'debug', $content); 
		}
		if ($zone == 0){
			if ( strstr ($content, "<INPUT type='radio' name='radioSystemPower' value='ON' onClick='radioBtn()'" )[75] == '>')
				return 0;
			else
				return 1;
		}else{
			
			if ( strstr ($content, "<INPUT type='radio' name='radioPower' value='ON'" )[70] == '>')
				$etat[POWER]=0;
			else
				$etat[POWER]=1;
			
			if ( strstr ($content, "<INPUT type='hidden' name='checkMmute' value='")[48] == 'f')
				$etat[MUTE]=0;
			else
				$etat[MUTE]=1;
				
			if ( strstr ($content, "<INPUT type='radio' name='radioPower' value='ON'" )[70] == '>')
				$etat[POWER]=0;
			else
				$etat[POWER]=1;
			
			$end = strstr ($content, "selected>");
			sscanf ($end, "selected>%s ", $etat[SOURCE]);
			
			$end = substr($end, 10);
			$end = strstr ($end, "selected>");
			$etat[MODE] = substr($end,9,strpos($end,"<")-9);
			
			$end = strstr ($end, "<input type='text' name='textMas' size='6' maxlength='5' style='text-align:right;' value='" );
			$etat[VOL] = (int) substr ($end, 90, 3 );
			log::add('DenonAVC', 'debug', "Power=" .$etat[POWER].", Mute=".$etat[MUTE].", Source=".$etat[SOURCE].", Mode=".$etat[MODE].", Volume=".$etat[VOL]); 
			
			return $etat;
		}
	}
	
	/*
	**	Mise à jour de l'équipement
	*/
	public function updateEquip( $power, $mute, $source, $mode, $vol ) 
	{
		$this->checkAndUpdateCmd('Power', $power);
		$this->checkAndUpdateCmd('MuteEtat', $mute);
		$this->checkAndUpdateCmd('Source', $source);
		$this->checkAndUpdateCmd('Mode', $mode);
		$this->checkAndUpdateCmd('Volume', $vol);
	}
	
}

class DenonAVCCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
		
		$etat = array 
				( 
				"POWER" => 0,
				"MUTE" => 0,
				"SOURCE" => "",
				"MODE" => "",
				"VOL" => 0
				);

		
		$eqLogic = $this->getEqLogic(); //récupère l'éqlogic de l'équipement
		switch ($this->getLogicalId()) {	
		case 'On':
			log::add('DenonAVC', 'debug', 'Exécution de la commande On');
			$eqLogic->callAVC(0, 'radioSystemPower', 'ON');
			$eqLogic->checkAndUpdateCmd('Power', 1);
			break;
		
		case 'Off':
			log::add('DenonAVC', 'debug', 'Exécution de la commande Off.');
			$eqLogic->callAVC(0, 'radioSystemPower', 'STANDBY');
			$eqLogic->checkAndUpdateCmd('Power', 0);
			break;
		
		case 'Mute':
			log::add('DenonAVC', 'debug', 'Exécution de la commande Mute');
			$eqLogic->callAVC(1, 'checkMmute', 'on');
			$eqLogic->checkAndUpdateCmd('MuteEtat', 1);
			break;
		
		case 'Unmute':
			log::add('DenonAVC', 'debug', 'Exécution de la commande Unmute');
			$eqLogic->callAVC(1, 'checkMmute', 'off');
			$eqLogic->checkAndUpdateCmd('MuteEtat', 0);
			break;

		case 'SetVol':
			log::add('DenonAVC', 'debug', 'Exécution de la commande SetVol:'. $_options['slider'] );
			$etat=$eqLogic->callAVC(1, 'setMas=on&textMas', $_options['slider']);
			$eqLogic->checkAndUpdateCmd('Volume', $_options['slider']);
			break;

		case 'Tuner':
			log::add('DenonAVC', 'debug', 'Exécution de la commande Tuner');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'TUNER');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'Phono':
			log::add('DenonAVC', 'debug', 'Exécution de la commande Phono');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'PHONO');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'CD':
			log::add('DenonAVC', 'debug', 'Exécution de la commande CD');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'CD');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'Tape':
			log::add('DenonAVC', 'debug', 'Exécution de la commande Tape');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'CDR/TAPE');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'DVD':
			log::add('DenonAVC', 'debug', 'Exécution de la commande DVD');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'DVD');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'VDP':
			log::add('DenonAVC', 'debug', 'Exécution de la commande VDP');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'VDP');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'TV':
			log::add('DenonAVC', 'debug', 'Exécution de la commande TV');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'TV');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'DBS':
			log::add('DenonAVC', 'debug', 'Exécution de la commande DBS');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'DBS');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'VCR1':
			log::add('DenonAVC', 'debug', 'Exécution de la commande VCR1');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'VCR-1');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'VCR2':
			log::add('DenonAVC', 'debug', 'Exécution de la commande VCR2');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'VCR-2');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'VCR3':
			log::add('DenonAVC', 'debug', 'Exécution de la commande VCR3');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'VCR-3');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'VAUX':
			log::add('DenonAVC', 'debug', 'Exécution de la commande VAUX');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'V.AUX');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		case 'Net':
			log::add('DenonAVC', 'debug', 'Exécution de la commande Net');
			$etat=$eqLogic->callAVC(1, 'listInputFunction', 'AUX');
			$eqLogic->checkAndUpdateCmd('Source', $etat[SOURCE]);
			$eqLogic->checkAndUpdateCmd('Mode', $etat[MODE]);
			break;
		
		default: //refresh
			log::add('DenonAVC', 'debug', 'Exécution de la commande Refresh');
			$MainPwr = $eqLogic->callAVC(0);
			$etat = $eqLogic->callAVC(1);
			$eqLogic->updateEquip( $MainPwr, $etat[MUTE], $etat[SOURCE], $etat[MODE], $etat[VOL] );
		}
	}
}



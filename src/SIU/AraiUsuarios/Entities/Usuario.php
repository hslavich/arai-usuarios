<?php
/**
 * Created by IntelliJ IDEA.
 * User: fbohn
 * Date: 07/11/15
 * Time: 12:03.
 */

namespace SIU\AraiUsuarios\Entities;

class Usuario
{
    const LOGIN_METHOD_DEFAULT = 'uniqueIdentifier';

    private $uid;
    private $dn;
    private $cn;
    private $sn;
    private $description;
    private $seeAlso;
    private $telephoneNumber;
    private $userPassword;
    private $destinationIndicator;
    private $facsimileTelephoneNumber;
    private $internationaliSDNNumber;
    private $l;
    private $ou;
    private $physicalDeliveryOfficeName;
    private $postOfficeBox;
    private $postalAddress;
    private $postalCode;
    private $preferredDeliveryMethod;
    private $registeredAddress;
    private $st;
    private $street;
    private $teletexTerminalIdentifier;
    private $telexNumber;
    private $title;
    private $x121Address;
    private $audio;
    private $businessCategory;
    private $carLicense;
    private $departmentNumber;
    private $displayName;
    private $employeeNumber;
    private $employeeType;
    private $givenName;
    private $homePhone;
    private $homePostalAddress;
    private $initials;
    private $jpegPhoto;
    private $labeledURI;
    private $mail;
    private $manager;
    private $mobile;
    private $o;
    private $pager;
    private $photo;
    private $preferredLanguage;
    private $roomNumber;
    private $secretary;
    private $userCertificate;
    private $userPKCS12;
    private $userSMIMECertificate;
    private $x500uniqueIdentifier;
    private $eduPersonAffiliation;
    private $eduPersonEntitlement;
    private $eduPersonNickname;
    private $eduPersonOrgDN;
    private $eduPersonOrgUnitDN;
    private $eduPersonPrimaryAffiliation;
    private $eduPersonPrimaryOrgUnitDN;
    private $eduPersonPrincipalName;
    private $eduPersonPrincipalNamePrior;
    private $eduPersonScopedAffiliation;
    private $eduPersonTargetedID;
    private $eduPersonAssurance;
    private $eduPersonUniqueId;
    private $eduPersonOrcid;
    private $bloqueado;
    private $idPersona;
    private $uniqueIdentifier;

    private $login;
    private $loginMethod;
    private $gender;
    private $zoneInfo;
    private $birthDate;
    private $mailPassRecovery;
    private $mailVerified;
    private $mobileVerified;
    private $mailPassRecoveryVerified;

    private $passwordPlano;
    private $passwordActualPlano;

    private $jpegPhotoUrl;
    private $accessTo;
    private $appLauncherData;

    private $cuentas;
    private $atributos;
    
    private $memberof;
	private $grupos;

    const SEPARADORAPLICACIONCUENTA = '|';

    public function __construct()
    {
        $this->uid = null;
        $this->dn = null;
        $this->cn = null;
        $this->sn = null;
        $this->description = null;
        $this->seeAlso = null;
        $this->telephoneNumber = null;
        $this->userPassword = null;
        $this->destinationIndicator = null;
        $this->facsimileTelephoneNumber = null;
        $this->internationaliSDNNumber = null;
        $this->l = null;
        $this->ou = null;
        $this->physicalDeliveryOfficeName = null;
        $this->postOfficeBox = null;
        $this->postalAddress = null;
        $this->postalCode = null;
        $this->preferredDeliveryMethod = null;
        $this->registeredAddress = null;
        $this->st = null;
        $this->street = null;
        $this->teletexTerminalIdentifier = null;
        $this->telexNumber = null;
        $this->title = null;
        $this->x121Address = null;
        $this->audio = null;
        $this->businessCategory = null;
        $this->carLicense = null;
        $this->departmentNumber = null;
        $this->displayName = null;
        $this->employeeNumber = null;
        $this->employeeType = null;
        $this->givenName = null;
        $this->homePhone = null;
        $this->homePostalAddress = null;
        $this->initials = null;
        $this->jpegPhoto = null;
        $this->labeledURI = null;
        $this->mail = null;
        $this->manager = null;
        $this->mobile = null;
        $this->o = null;
        $this->pager = null;
        $this->photo = null;
        $this->preferredLanguage = null;
        $this->roomNumber = null;
        $this->secretary = null;
        $this->userCertificate = null;
        $this->userPKCS12 = null;
        $this->userSMIMECertificate = null;
        $this->x500uniqueIdentifier = null;
        $this->eduPersonAffiliation = null;
        $this->eduPersonEntitlement = null;
        $this->eduPersonNickname = null;
        $this->eduPersonOrgDN = null;
        $this->eduPersonOrgUnitDN = null;
        $this->eduPersonPrimaryAffiliation = null;
        $this->eduPersonPrimaryOrgUnitDN = null;
        $this->eduPersonPrincipalName = null;
        $this->eduPersonPrincipalNamePrior = null;
        $this->eduPersonScopedAffiliation = null;
        $this->eduPersonTargetedID = null;
        $this->eduPersonAssurance = null;
        $this->eduPersonUniqueId = null;
        $this->eduPersonOrcid = null;
        $this->bloqueado = null;
        $this->idPersona = null;
        $this->uniqueIdentifier = null;

        $this->login = null;
        $this->loginMethod = null;
        $this->gender = null;
        $this->zoneInfo = null;
        $this->birthDate = null;
        $this->mailPassRecovery = null;
        $this->mailVerified = null;
        $this->mailPassRecoveryVerified = null;
        $this->mobileVerified = null;

        $this->cuentas = array();
        $this->atributos = array();
        $this->memberof = array();
		$this->grupos = array();
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getDn()
    {
        return $this->dn;
    }

    /**
     * @param mixed $dn
     */
    public function setDn($dn)
    {
        $this->dn = $dn;
    }

    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * @param mixed $givenName
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;
    }

    /**
     * @return mixed
     */
    public function getSn()
    {
        return $this->sn;
    }

    /**
     * @param mixed $sn
     */
    public function setSn($sn)
    {
        $this->sn = $sn;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    /**
     * @param mixed $idPersona
     */
    public function setIdPersona($idPersona)
    {
        $this->idPersona = $idPersona;
    }

    /**
     * @return mixed
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * @param $password
     * @param string $algoritmo Si no esta definido asume que es plano o que ya esta encriptado
     */
    public function setUserPassword($password, $algoritmo = null)
    {
        $this->userPassword = $this->generarUserPassword($password, $algoritmo);
    }

    public function separarPasswordAlgoritmoEncriptado()
    {
        if (preg_match('/{([^}]+)}(.*)/', $this->userPassword, $matches)) {
            $passwordEncriptado = $matches[2];
            $algoritmo = strtolower($matches[1]);
        } else {
            $passwordEncriptado = $this->userPassword;
            $algoritmo = null;
        }

        return array(
            'password' => $passwordEncriptado,
            'algoritmo' => $algoritmo,
        );
    }

    /**
     * @return mixed
     */
    public function getPasswordPlano()
    {
        return $this->passwordPlano;
    }

    /**
     * @param mixed $passwordPlano
     */
    public function setPasswordPlano($passwordPlano)
    {
        $this->passwordPlano = $passwordPlano;
    }

    /**
     * @return mixed
     */
    public function getPasswordActualPlano()
    {
        return $this->passwordActualPlano;
    }

    /**
     * @param mixed $passwordActualPlano
     */
    public function setPasswordActualPlano($passwordActualPlano)
    {
        $this->passwordActualPlano = $passwordActualPlano;
    }

    /**
     * @return null
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param null $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @return null
     */
    public function getLoginMethod()
    {
        return $this->loginMethod;
    }

    /**
     * @param null $loginMethod
     */
    public function setLoginMethod($loginMethod): void
    {
        $this->loginMethod = $loginMethod;
    }

    /**
     * @return null
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param null $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return null
     */
    public function getZoneInfo()
    {
        return $this->zoneInfo;
    }

    /**
     * @param null $zoneInfo
     */
    public function setZoneInfo($zoneInfo): void
    {
        $this->zoneInfo = $zoneInfo;
    }

    /**
     * @return null
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param null $birthDate
     */
    public function setBirthDate($birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return null
     */
    public function getMailPassRecovery()
    {
        return $this->mailPassRecovery;
    }

    /**
     * @param null $mailPassRecovery
     */
    public function setMailPassRecovery($mailPassRecovery): void
    {
        $this->mailPassRecovery = $mailPassRecovery;
    }

    /**
     * @return null
     */
    public function getMailVerified()
    {
        return $this->mailVerified;
    }

    /**
     * @param null $mailVerified
     */
    public function setMailVerified($mailVerified): void
    {
        $this->mailVerified = $mailVerified;
    }

    /**
     * @return null
     */
    public function getMobileVerified()
    {
        return $this->mobileVerified;
    }

    /**
     * @param null $mobileVerified
     */
    public function setMobileVerified($mobileVerified): void
    {
        $this->mobileVerified = $mobileVerified;
    }

    /**
     * @return null
     */
    public function getMailPassRecoveryVerified()
    {
        return $this->mailPassRecoveryVerified;
    }

    /**
     * @param null $mailPassRecoveryVerified
     */
    public function setMailPassRecoveryVerified($mailPassRecoveryVerified): void
    {
        $this->mailPassRecoveryVerified = $mailPassRecoveryVerified;
    }

    /**
     * @return mixed
     */
    public function getBloqueado()
    {
        return $this->bloqueado;
    }

    /**
     * @param mixed $bloqueado
     */
    public function setBloqueado($bloqueado)
    {
        $this->bloqueado = $bloqueado;
    }

    /**
     * @return mixed
     */
    public function getJpegPhoto()
    {
        return $this->jpegPhoto;
    }

    /**
     * @param mixed $jpegPhoto
     */
    public function setJpegPhoto($jpegPhoto)
    {
        $this->jpegPhoto = $jpegPhoto;
    }

    /**
     * @return mixed
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @param mixed $cn
     */
    public function setCn($cn)
    {
        $this->cn = $cn;
    }

    /**
     * @return mixed
     */
    public function getJpegPhotoUrl()
    {
        return $this->jpegPhotoUrl;
    }

    /**
     * @param mixed $jpegPhotoUrl
     */
    public function setJpegPhotoUrl($jpegPhotoUrl)
    {
        $this->jpegPhotoUrl = $jpegPhotoUrl;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getSeeAlso()
    {
        return $this->seeAlso;
    }

    /**
     * @param mixed $seeAlso
     */
    public function setSeeAlso($seeAlso)
    {
        $this->seeAlso = $seeAlso;
    }

    /**
     * @return mixed
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    /**
     * @param mixed $telephoneNumber
     */
    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;
    }

    /**
     * @return mixed
     */
    public function getDestinationIndicator()
    {
        return $this->destinationIndicator;
    }

    /**
     * @param mixed $destinationIndicator
     */
    public function setDestinationIndicator($destinationIndicator)
    {
        $this->destinationIndicator = $destinationIndicator;
    }

    /**
     * @return mixed
     */
    public function getFacsimileTelephoneNumber()
    {
        return $this->facsimileTelephoneNumber;
    }

    /**
     * @param mixed $facsimileTelephoneNumber
     */
    public function setFacsimileTelephoneNumber($facsimileTelephoneNumber)
    {
        $this->facsimileTelephoneNumber = $facsimileTelephoneNumber;
    }

    /**
     * @return mixed
     */
    public function getInternationaliSDNNumber()
    {
        return $this->internationaliSDNNumber;
    }

    /**
     * @param mixed $internationaliSDNNumber
     */
    public function setInternationaliSDNNumber($internationaliSDNNumber)
    {
        $this->internationaliSDNNumber = $internationaliSDNNumber;
    }

    /**
     * @return mixed
     */
    public function getL()
    {
        return $this->l;
    }

    /**
     * @param mixed $l
     */
    public function setL($l)
    {
        $this->l = $l;
    }

    /**
     * @return mixed
     */
    public function getOu()
    {
        return $this->ou;
    }

    /**
     * @param mixed $ou
     */
    public function setOu($ou)
    {
        $this->ou = $ou;
    }

    /**
     * @return mixed
     */
    public function getPhysicalDeliveryOfficeName()
    {
        return $this->physicalDeliveryOfficeName;
    }

    /**
     * @param mixed $physicalDeliveryOfficeName
     */
    public function setPhysicalDeliveryOfficeName($physicalDeliveryOfficeName)
    {
        $this->physicalDeliveryOfficeName = $physicalDeliveryOfficeName;
    }

    /**
     * @return mixed
     */
    public function getPostOfficeBox()
    {
        return $this->postOfficeBox;
    }

    /**
     * @param mixed $postOfficeBox
     */
    public function setPostOfficeBox($postOfficeBox)
    {
        $this->postOfficeBox = $postOfficeBox;
    }

    /**
     * @return mixed
     */
    public function getPostalAddress()
    {
        return $this->postalAddress;
    }

    /**
     * @param mixed $postalAddress
     */
    public function setPostalAddress($postalAddress)
    {
        $this->postalAddress = $postalAddress;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return mixed
     */
    public function getPreferredDeliveryMethod()
    {
        return $this->preferredDeliveryMethod;
    }

    /**
     * @param mixed $preferredDeliveryMethod
     */
    public function setPreferredDeliveryMethod($preferredDeliveryMethod)
    {
        $this->preferredDeliveryMethod = $preferredDeliveryMethod;
    }

    /**
     * @return mixed
     */
    public function getRegisteredAddress()
    {
        return $this->registeredAddress;
    }

    /**
     * @param mixed $registeredAddress
     */
    public function setRegisteredAddress($registeredAddress)
    {
        $this->registeredAddress = $registeredAddress;
    }

    /**
     * @return mixed
     */
    public function getSt()
    {
        return $this->st;
    }

    /**
     * @param mixed $st
     */
    public function setSt($st)
    {
        $this->st = $st;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getTeletexTerminalIdentifier()
    {
        return $this->teletexTerminalIdentifier;
    }

    /**
     * @param mixed $teletexTerminalIdentifier
     */
    public function setTeletexTerminalIdentifier($teletexTerminalIdentifier)
    {
        $this->teletexTerminalIdentifier = $teletexTerminalIdentifier;
    }

    /**
     * @return mixed
     */
    public function getTelexNumber()
    {
        return $this->telexNumber;
    }

    /**
     * @param mixed $telexNumber
     */
    public function setTelexNumber($telexNumber)
    {
        $this->telexNumber = $telexNumber;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getX121Address()
    {
        return $this->x121Address;
    }

    /**
     * @param mixed $x121Address
     */
    public function setX121Address($x121Address)
    {
        $this->x121Address = $x121Address;
    }

    /**
     * @return mixed
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * @param mixed $audio
     */
    public function setAudio($audio)
    {
        $this->audio = $audio;
    }

    /**
     * @return mixed
     */
    public function getBusinessCategory()
    {
        return $this->businessCategory;
    }

    /**
     * @param mixed $businessCategory
     */
    public function setBusinessCategory($businessCategory)
    {
        $this->businessCategory = $businessCategory;
    }

    /**
     * @return mixed
     */
    public function getCarLicense()
    {
        return $this->carLicense;
    }

    /**
     * @param mixed $carLicense
     */
    public function setCarLicense($carLicense)
    {
        $this->carLicense = $carLicense;
    }

    /**
     * @return mixed
     */
    public function getDepartmentNumber()
    {
        return $this->departmentNumber;
    }

    /**
     * @param mixed $departmentNumber
     */
    public function setDepartmentNumber($departmentNumber)
    {
        $this->departmentNumber = $departmentNumber;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getEmployeeNumber()
    {
        return $this->employeeNumber;
    }

    /**
     * @param mixed $employeeNumber
     */
    public function setEmployeeNumber($employeeNumber)
    {
        $this->employeeNumber = $employeeNumber;
    }

    /**
     * @return mixed
     */
    public function getEmployeeType()
    {
        return $this->employeeType;
    }

    /**
     * @param mixed $employeeType
     */
    public function setEmployeeType($employeeType)
    {
        $this->employeeType = $employeeType;
    }

    /**
     * @return mixed
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * @param mixed $homePhone
     */
    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;
    }

    /**
     * @return mixed
     */
    public function getHomePostalAddress()
    {
        return $this->homePostalAddress;
    }

    /**
     * @param mixed $homePostalAddress
     */
    public function setHomePostalAddress($homePostalAddress)
    {
        $this->homePostalAddress = $homePostalAddress;
    }

    /**
     * @return mixed
     */
    public function getInitials()
    {
        return $this->initials;
    }

    /**
     * @param mixed $initials
     */
    public function setInitials($initials)
    {
        $this->initials = $initials;
    }

    /**
     * @return mixed
     */
    public function getLabeledURI()
    {
        return $this->labeledURI;
    }

    /**
     * @param mixed $labeledURI
     */
    public function setLabeledURI($labeledURI)
    {
        $this->labeledURI = $labeledURI;
    }

    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param mixed $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return mixed
     */
    public function getO()
    {
        return $this->o;
    }

    /**
     * @param mixed $o
     */
    public function setO($o)
    {
        $this->o = $o;
    }

    /**
     * @return mixed
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * @param mixed $pager
     */
    public function setPager($pager)
    {
        $this->pager = $pager;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getPreferredLanguage()
    {
        return $this->preferredLanguage;
    }

    /**
     * @param mixed $preferredLanguage
     */
    public function setPreferredLanguage($preferredLanguage)
    {
        $this->preferredLanguage = $preferredLanguage;
    }

    /**
     * @return mixed
     */
    public function getRoomNumber()
    {
        return $this->roomNumber;
    }

    /**
     * @param mixed $roomNumber
     */
    public function setRoomNumber($roomNumber)
    {
        $this->roomNumber = $roomNumber;
    }

    /**
     * @return mixed
     */
    public function getSecretary()
    {
        return $this->secretary;
    }

    /**
     * @param mixed $secretary
     */
    public function setSecretary($secretary)
    {
        $this->secretary = $secretary;
    }

    /**
     * @return mixed
     */
    public function getUserCertificate()
    {
        return $this->userCertificate;
    }

    /**
     * @param mixed $userCertificate
     */
    public function setUserCertificate($userCertificate)
    {
        $this->userCertificate = $userCertificate;
    }

    /**
     * @return mixed
     */
    public function getUserPKCS12()
    {
        return $this->userPKCS12;
    }

    /**
     * @param mixed $userPKCS12
     */
    public function setUserPKCS12($userPKCS12)
    {
        $this->userPKCS12 = $userPKCS12;
    }

    /**
     * @return mixed
     */
    public function getUserSMIMECertificate()
    {
        return $this->userSMIMECertificate;
    }

    /**
     * @param mixed $userSMIMECertificate
     */
    public function setUserSMIMECertificate($userSMIMECertificate)
    {
        $this->userSMIMECertificate = $userSMIMECertificate;
    }

    /**
     * @return mixed
     */
    public function getX500uniqueIdentifier()
    {
        return $this->x500uniqueIdentifier;
    }

    /**
     * @param mixed $x500uniqueIdentifier
     */
    public function setX500uniqueIdentifier($x500uniqueIdentifier)
    {
        $this->x500uniqueIdentifier = $x500uniqueIdentifier;
    }

    /**
     * @return mixed
     */
    public function getEduPersonAffiliation()
    {
        return $this->eduPersonAffiliation;
    }

    /**
     * @param mixed $eduPersonAffiliation
     */
    public function setEduPersonAffiliation($eduPersonAffiliation)
    {
        $this->eduPersonAffiliation = $eduPersonAffiliation;
    }

    /**
     * @return mixed
     */
    public function getEduPersonEntitlement()
    {
        return $this->eduPersonEntitlement;
    }

    /**
     * @param mixed $eduPersonEntitlement
     */
    public function setEduPersonEntitlement($eduPersonEntitlement)
    {
        $this->eduPersonEntitlement = $eduPersonEntitlement;
    }

    /**
     * @return mixed
     */
    public function getEduPersonNickname()
    {
        return $this->eduPersonNickname;
    }

    /**
     * @param mixed $eduPersonNickname
     */
    public function setEduPersonNickname($eduPersonNickname)
    {
        $this->eduPersonNickname = $eduPersonNickname;
    }

    /**
     * @return mixed
     */
    public function getEduPersonOrgDN()
    {
        return $this->eduPersonOrgDN;
    }

    /**
     * @param mixed $eduPersonOrgDN
     */
    public function setEduPersonOrgDN($eduPersonOrgDN)
    {
        $this->eduPersonOrgDN = $eduPersonOrgDN;
    }

    /**
     * @return mixed
     */
    public function getEduPersonOrgUnitDN()
    {
        return $this->eduPersonOrgUnitDN;
    }

    /**
     * @param mixed $eduPersonOrgUnitDN
     */
    public function setEduPersonOrgUnitDN($eduPersonOrgUnitDN)
    {
        $this->eduPersonOrgUnitDN = $eduPersonOrgUnitDN;
    }

    /**
     * @return mixed
     */
    public function getEduPersonPrimaryAffiliation()
    {
        return $this->eduPersonPrimaryAffiliation;
    }

    /**
     * @param mixed $eduPersonPrimaryAffiliation
     */
    public function setEduPersonPrimaryAffiliation($eduPersonPrimaryAffiliation)
    {
        $this->eduPersonPrimaryAffiliation = $eduPersonPrimaryAffiliation;
    }

    /**
     * @return mixed
     */
    public function getEduPersonPrimaryOrgUnitDN()
    {
        return $this->eduPersonPrimaryOrgUnitDN;
    }

    /**
     * @param mixed $eduPersonPrimaryOrgUnitDN
     */
    public function setEduPersonPrimaryOrgUnitDN($eduPersonPrimaryOrgUnitDN)
    {
        $this->eduPersonPrimaryOrgUnitDN = $eduPersonPrimaryOrgUnitDN;
    }

    /**
     * @return mixed
     */
    public function getEduPersonPrincipalName()
    {
        return $this->eduPersonPrincipalName;
    }

    /**
     * @param mixed $eduPersonPrincipalName
     */
    public function setEduPersonPrincipalName($eduPersonPrincipalName)
    {
        $this->eduPersonPrincipalName = $eduPersonPrincipalName;
    }

    /**
     * @return mixed
     */
    public function getEduPersonPrincipalNamePrior()
    {
        return $this->eduPersonPrincipalNamePrior;
    }

    /**
     * @param mixed $eduPersonPrincipalNamePrior
     */
    public function setEduPersonPrincipalNamePrior($eduPersonPrincipalNamePrior)
    {
        $this->eduPersonPrincipalNamePrior = $eduPersonPrincipalNamePrior;
    }

    /**
     * @return mixed
     */
    public function getEduPersonScopedAffiliation()
    {
        return $this->eduPersonScopedAffiliation;
    }

    /**
     * @param mixed $eduPersonScopedAffiliation
     */
    public function setEduPersonScopedAffiliation($eduPersonScopedAffiliation)
    {
        $this->eduPersonScopedAffiliation = $eduPersonScopedAffiliation;
    }

    /**
     * @return mixed
     */
    public function getEduPersonTargetedID()
    {
        return $this->eduPersonTargetedID;
    }

    /**
     * @param mixed $eduPersonTargetedID
     */
    public function setEduPersonTargetedID($eduPersonTargetedID)
    {
        $this->eduPersonTargetedID = $eduPersonTargetedID;
    }

    /**
     * @return mixed
     */
    public function getEduPersonAssurance()
    {
        return $this->eduPersonAssurance;
    }

    /**
     * @param mixed $eduPersonAssurance
     */
    public function setEduPersonAssurance($eduPersonAssurance)
    {
        $this->eduPersonAssurance = $eduPersonAssurance;
    }

    /**
     * @return mixed
     */
    public function getEduPersonUniqueId()
    {
        return $this->eduPersonUniqueId;
    }

    /**
     * @param mixed $eduPersonUniqueId
     */
    public function setEduPersonUniqueId($eduPersonUniqueId)
    {
        $this->eduPersonUniqueId = $eduPersonUniqueId;
    }

    /**
     * @return mixed
     */
    public function getEduPersonOrcid()
    {
        return $this->eduPersonOrcid;
    }

    /**
     * @param mixed $eduPersonOrcid
     */
    public function setEduPersonOrcid($eduPersonOrcid)
    {
        $this->eduPersonOrcid = $eduPersonOrcid;
    }

    /**
     * @return mixed
     */
    public function getUniqueIdentifier()
    {
        return $this->uniqueIdentifier;
    }

    /**
     * @param mixed $uniqueIdentifier
     */
    public function setUniqueIdentifier($uniqueIdentifier)
    {
        $this->uniqueIdentifier = $uniqueIdentifier;
    }

    /**
     * @return mixed
     */
    public function getLoginIdentifier()
    {
        return $this->getUniqueIdentifier();
    }

    /**
     * @return mixed
     */
    public function getAccessTo()
    {
        return $this->accessTo;
    }

    /**
     * @param mixed $accessTo
     */
    public function setAccessTo($accessTo)
    {
        $this->accessTo = $accessTo;
    }

    /**
     * @return mixed
     */
    public function getAppLauncherData()
    {
        return $this->appLauncherData;
    }

    /**
     * @param mixed $appLauncherData
     */
    public function setAppLauncherData($appLauncherData)
    {
        $this->appLauncherData = $appLauncherData;
    }

    /**
     * @return null|Cuenta[]
     */
    public function getCuentas()
    {
        return $this->cuentas;
    }
    
    /**
     * Setea arreglo de DN Groups
     * @param array $dnGroups
     */
    public function setMemberOf(array $dnGroups)
    {
        $this->memberof = $dnGroups;
    }
    
    /**
     * Arreglo de DN Groups
     * @return array
     */
    public function getMemberOf()
    {
        return $this->memberof;
    }

    /**
     * @param $appUniqueId
     *
     * @return Cuenta[]
     */
    public function getCuentasAplicacion($appUniqueId)
    {
        $arrCuentas = array();
        foreach ($this->cuentas as $clave => $cuenta) {
            if (substr($clave, 0, strpos($clave, self::SEPARADORAPLICACIONCUENTA)) == $appUniqueId) {
                $arrCuentas[] = $cuenta;
            }
        }

        return $arrCuentas;
    }

    /**
     * @param $appUniqueId
     *
     * @return Cuenta[]
     */
    public function tieneCuentasEnAplicacion($appUniqueId)
    {
        return !empty($this->getCuentasAplicacion($appUniqueId));
    }

    /**
     * @param $appUniqueId
     *
     * @return Cuenta
     */
    public function getCuentaDefectoAplicacion($appUniqueId)
    {
        $arrCuentas = $this->getCuentasAplicacion($appUniqueId);
        $enc = false;
        $i = 0;
        $cantidad = count($arrCuentas);
        while ($i < $cantidad && !$enc) {
            if ($arrCuentas[$i]->getDefecto()) {
                $enc = true;
            } else {
                ++$i;
            }
        }
        if ($enc) {
            return $arrCuentas[$i];
        } elseif ($cantidad > 0) { // tiene alguna cuenta pero ninguna marcada por defecto
            // Devuevle la 1ra
            return $arrCuentas[0];
        }

        return null;
    }

    /**
     * @param $appUniqueId
     * @param $cuenta
     *
     * @return Cuenta
     */
    public function getCuenta($appUniqueId, $cuenta)
    {
        if (isset($this->cuentas[$this->getIdentificadorCuenta($appUniqueId, $cuenta)])) {
            return $this->cuentas[$this->getIdentificadorCuenta($appUniqueId, $cuenta)];
        } else {
            return null;
        }
    }

    /**
     * @param Cuenta $cuenta
     */
    public function agregarCuenta(Cuenta $cuenta)
    {
        $this->cuentas[$this->getIdentificadorCuenta($cuenta->getAppUniqueId(), $cuenta->getCuenta())] = $cuenta;
    }

    /**
     * @param Cuenta $cuenta
     */
    public function quitarCuenta(Cuenta $cuenta)
    {
        unset($this->cuentas[$this->getIdentificadorCuenta($cuenta->getAppUniqueId(), $cuenta->getCuenta())]);
        unset($cuenta);
    }

    public function quitarCuentas()
    {
        $this->cuentas = array();
    }

    /**
     * @return array
     */
    public function getAtributos()
    {
        return $this->atributos;
    }

    /**
     * @param array $atributos
     */
    public function setAtributos($atributos)
    {
        /* @var $atributo Atributo */
        foreach ($atributos as $atributo) {
            $this->atributos[] = $atributo;
        }
    }

    /**
     * @return null|Atributo
     */
    public function getAtributo($claveAtributo)
    {
        if (isset($this->atributos[$this->getIdentificadorAtributo($claveAtributo)])) {
            return $this->atributos[$this->getIdentificadorAtributo($claveAtributo)];
        } else {
            return null;
        }
    }

    /**
     * @param Atributo $atributo
     */
    public function agregarAtributo(Atributo $atributo)
    {
        $this->atributos[$this->getIdentificadorAtributo($atributo->getAtributo())] = $atributo;
    }

    /**
     * @param Atributo $atributo
     */
    public function quitarAtributo(Atributo $atributo)
    {
        unset($this->atributos[$this->getIdentificadorAtributo($atributo->getAtributo())]);
        unset($atributo);
    }

    public function quitarAtributos()
    {
        $this->atributos = array();
    }

    /**
     * Devuelve los grupos del usuario
     * @return array \SIU\AraiUsuarios\Entities\Grupo
     */
    public function getGrupos()
    {
        return $this->grupos;
    }
    
    /**
     *
     * @param array $grupos
     */
    public function setGrupos(array $grupos)
    {
        $this->grupos = $grupos;
    }
    
    /**
     * Elimina las instancias de grupos en el usuario
     */
    public function quitarGrupos()
    {
        unset($this->grupos);
        $this->grupos = array();
    }
    
    /**
     * Agrega una instancia de grupo al usuario
     * @param \SIU\AraiUsuarios\Entities\Grupo $grupo
     */
    public function agregarGrupo(Grupo $grupo)
    {
        $index = $grupo->getCn();
        $this->grupos[$index] = $grupo;
    }
    
    /**
     * Quita una instancia especifica de grupo del usuario
     * @param \SIU\AraiUsuarios\Entities\Grupo $grupo
     */
    public function quitarGrupo(Grupo $grupo)
    {
        $index = $grupo->getCn();
        if (isset($this->grupos[$index])) {
            unset($this->grupos[$index]);
        }
    }
    
    /**
     * Recupera una instancia de grupo por el CN
     * @param string $cn
     * @return \SIU\AraiUsuarios\Entities\Grupo
     */
    public function getGrupo($cn)
    {
        if (isset($this->grupos[$cn])) {
            return $this->grupos[$cn];
        }
        return null;
    }
    
    /**
     * @return array
     */
    public function getArrayDatos()
    {
        $datos = array(
            'uid' => $this->getUid(),
            'cn' => $this->getCn(),
            'sn' => $this->getSn(),
            'description' => $this->getDescription(),
            'seeAlso' => $this->getSeeAlso(),
            'telephoneNumber' => $this->getTelephoneNumber(),
            'userPassword' => $this->getUserPassword(),
            'destinationIndicator' => $this->getDestinationIndicator(),
            'facsimileTelephoneNumber' => $this->getFacsimileTelephoneNumber(),
            'internationaliSDNNumber' => $this->getInternationaliSDNNumber(),
            'l' => $this->getL(),
            'ou' => $this->getOu(),
            'physicalDeliveryOfficeName' => $this->getPhysicalDeliveryOfficeName(),
            'postOfficeBox' => $this->getPostOfficeBox(),
            'postalAddress' => $this->getPostalAddress(),
            'postalCode' => $this->getPostalCode(),
            'preferredDeliveryMethod' => $this->getPreferredDeliveryMethod(),
            'registeredAddress' => $this->getRegisteredAddress(),
            'st' => $this->getSt(),
            'street' => $this->getStreet(),
            'teletexTerminalIdentifier' => $this->getTeletexTerminalIdentifier(),
            'telexNumber' => $this->getTelexNumber(),
            'title' => $this->getTitle(),
            'x121Address' => $this->getX121Address(),
            'audio' => $this->getAudio(),
            'businessCategory' => $this->getBusinessCategory(),
            'carLicense' => $this->getCarLicense(),
            'departmentNumber' => $this->getDepartmentNumber(),
            'displayName' => $this->getDisplayName(),
            'employeeNumber' => $this->getEmployeeNumber(),
            'employeeType' => $this->getEmployeeType(),
            'givenName' => $this->getGivenName(),
            'homePhone' => $this->getHomePhone(),
            'homePostalAddress' => $this->getHomePostalAddress(),
            'initials' => $this->getInitials(),
            'jpegPhoto' => $this->getJpegPhoto(),
            'jpegPhotoUrl' => $this->getJpegPhotoUrl(),
            'labeledURI' => $this->getLabeledURI(),
            'mail' => $this->getMail(),
            'manager' => $this->getManager(),
            'mobile' => $this->getMobile(),
            'o' => $this->getO(),
            'pager' => $this->getPager(),
            'photo' => $this->getPhoto(),
            'preferredLanguage' => $this->getPreferredLanguage(),
            'roomNumber' => $this->getRoomNumber(),
            'secretary' => $this->getSecretary(),
            'userCertificate' => $this->getUserCertificate(),
            'userPKCS12' => $this->getUserPKCS12(),
            'userSMIMECertificate' => $this->getUserSMIMECertificate(),
            'x500uniqueIdentifier' => $this->getX500uniqueIdentifier(),
            'eduPersonAffiliation' => $this->getEduPersonAffiliation(),
            'eduPersonEntitlement' => $this->getEduPersonEntitlement(),
            'eduPersonNickname' => $this->getEduPersonNickname(),
            'eduPersonOrgDN' => $this->getEduPersonOrgDN(),
            'eduPersonOrgUnitDN' => $this->getEduPersonOrgUnitDN(),
            'eduPersonPrimaryAffiliation' => $this->getEduPersonPrimaryAffiliation(),
            'eduPersonPrimaryOrgUnitDN' => $this->getEduPersonPrimaryOrgUnitDN(),
            'eduPersonPrincipalName' => $this->getEduPersonPrincipalName(),
            'eduPersonPrincipalNamePrior' => $this->getEduPersonPrincipalNamePrior(),
            'eduPersonScopedAffiliation' => $this->getEduPersonScopedAffiliation(),
            'eduPersonTargetedID' => $this->getEduPersonTargetedID(),
            'eduPersonAssurance' => $this->getEduPersonAssurance(),
            'eduPersonUniqueId' => $this->getEduPersonUniqueId(),
            'eduPersonOrcid' => $this->getEduPersonOrcid(),
            'bloqueado' => $this->getBloqueado(),
            'idPersona' => $this->getIdPersona(),
            'uniqueIdentifier' => $this->getUniqueIdentifier(),
            'login' => $this->getLogin(),
            'loginMethod' => $this->getLoginMethod(),
            'gender' => $this->getGender(),
            'birthDate' => $this->getBirthDate(),
            'zoneInfo' => $this->getZoneInfo(),
            'mailPassRecovery' => $this->getMailPassRecovery(),
            'mailVerified' => $this->getMailVerified(),
            'mailPassRecoveryVerified' => $this->getMailPassRecoveryVerified(),
            'mobileVerified' => $this->getMobileVerified(),
            'accessTo' => $this->getAccessTo(),
            'appLauncherData' => $this->getAppLauncherData(),
        );

        return $datos;
    }

    /**
     * @return array
     */
    public function setArrayDatos($datos)
    {
        if (isset($datos['uid'])) {
            $this->setUid($datos['uid']);
        }
        if (isset($datos['cn'])) {
            $this->setCn($datos['cn']);
        }
        if (isset($datos['sn'])) {
            $this->setSn($datos['sn']);
        }
        if (isset($datos['description'])) {
            $this->setDescription($datos['description']);
        }
        if (isset($datos['seeAlso'])) {
            $this->setSeeAlso($datos['seeAlso']);
        }
        if (isset($datos['telephoneNumber'])) {
            $this->setTelephoneNumber($datos['telephoneNumber']);
        }
        if (isset($datos['userPassword'])) {
            $this->setUserPassword($datos['userPassword']);
        }
        if (isset($datos['password_plano'])) {
            $this->setPasswordPlano($datos['password_plano']);
        }
        if (isset($datos['password_actual_plano'])) {
            $this->setPasswordActualPlano($datos['password_actual_plano']);
        }
        if (isset($datos['destinationIndicator'])) {
            $this->setDestinationIndicator($datos['destinationIndicator']);
        }
        if (isset($datos['facsimileTelephoneNumber'])) {
            $this->setFacsimileTelephoneNumber($datos['facsimileTelephoneNumber']);
        }
        if (isset($datos['internationaliSDNNumber'])) {
            $this->setInternationaliSDNNumber($datos['internationaliSDNNumber']);
        }
        if (isset($datos['l'])) {
            $this->setL($datos['l']);
        }
        if (isset($datos['ou'])) {
            $this->setOu($datos['ou']);
        }
        if (isset($datos['physicalDeliveryOfficeName'])) {
            $this->setPhysicalDeliveryOfficeName($datos['physicalDeliveryOfficeName']);
        }
        if (isset($datos['postOfficeBox'])) {
            $this->setPostOfficeBox($datos['postOfficeBox']);
        }
        if (isset($datos['postalAddress'])) {
            $this->setPostalAddress($datos['postalAddress']);
        }
        if (isset($datos['postalCode'])) {
            $this->setPostalCode($datos['postalCode']);
        }
        if (isset($datos['preferredDeliveryMethod'])) {
            $this->setPreferredDeliveryMethod($datos['preferredDeliveryMethod']);
        }
        if (isset($datos['registeredAddress'])) {
            $this->setRegisteredAddress($datos['registeredAddress']);
        }
        if (isset($datos['st'])) {
            $this->setSt($datos['st']);
        }
        if (isset($datos['street'])) {
            $this->setStreet($datos['street']);
        }
        if (isset($datos['teletexTerminalIdentifier'])) {
            $this->setTeletexTerminalIdentifier($datos['teletexTerminalIdentifier']);
        }
        if (isset($datos['telexNumber'])) {
            $this->setTelexNumber($datos['telexNumber']);
        }
        if (isset($datos['title'])) {
            $this->setTitle($datos['title']);
        }
        if (isset($datos['x121Address'])) {
            $this->setX121Address($datos['x121Address']);
        }
        if (isset($datos['audio'])) {
            $this->setAudio($datos['audio']);
        }
        if (isset($datos['businessCategory'])) {
            $this->setBusinessCategory($datos['businessCategory']);
        }
        if (isset($datos['carLicense'])) {
            $this->setCarLicense($datos['carLicense']);
        }
        if (isset($datos['departmentNumber'])) {
            $this->setDepartmentNumber($datos['departmentNumber']);
        }
        if (isset($datos['displayName'])) {
            $this->setDisplayName($datos['displayName']);
        }
        if (isset($datos['employeeNumber'])) {
            $this->setEmployeeNumber($datos['employeeNumber']);
        }
        if (isset($datos['employeeType'])) {
            $this->setEmployeeType($datos['employeeType']);
        }
        if (isset($datos['givenName'])) {
            $this->setGivenName($datos['givenName']);
        }
        if (isset($datos['homePhone'])) {
            $this->setHomePhone($datos['homePhone']);
        }
        if (isset($datos['homePostalAddress'])) {
            $this->setHomePostalAddress($datos['homePostalAddress']);
        }
        if (isset($datos['initials'])) {
            $this->setInitials($datos['initials']);
        }
        if (isset($datos['jpegPhoto'])) {
            $this->setJpegPhoto($datos['jpegPhoto']);
        }
        if (isset($datos['jpegPhotoUrl'])) {
            $this->setJpegPhotoUrl($datos['jpegPhotoUrl']);
        }
        if (isset($datos['labeledURI'])) {
            $this->setLabeledURI($datos['labeledURI']);
        }
        if (isset($datos['mail'])) {
            $this->setMail($datos['mail']);
        }
        if (isset($datos['manager'])) {
            $this->setManager($datos['manager']);
        }
        if (isset($datos['mobile'])) {
            $this->setMobile($datos['mobile']);
        }
        if (isset($datos['o'])) {
            $this->setO($datos['o']);
        }
        if (isset($datos['pager'])) {
            $this->setPager($datos['pager']);
        }
        if (isset($datos['photo'])) {
            $this->setPhoto($datos['photo']);
        }
        if (isset($datos['preferredLanguage'])) {
            $this->setPreferredLanguage($datos['preferredLanguage']);
        }
        if (isset($datos['roomNumber'])) {
            $this->setRoomNumber($datos['roomNumber']);
        }
        if (isset($datos['secretary'])) {
            $this->setSecretary($datos['secretary']);
        }
        if (isset($datos['userCertificate'])) {
            $this->setUserCertificate($datos['userCertificate']);
        }
        if (isset($datos['userPKCS12'])) {
            $this->setUserPKCS12($datos['userPKCS12']);
        }
        if (isset($datos['userSMIMECertificate'])) {
            $this->setUserSMIMECertificate($datos['userSMIMECertificate']);
        }
        if (isset($datos['x500uniqueIdentifier'])) {
            $this->setX500uniqueIdentifier($datos['x500uniqueIdentifier']);
        }
        if (isset($datos['eduPersonAffiliation'])) {
            $this->setEduPersonAffiliation($datos['eduPersonAffiliation']);
        }
        if (isset($datos['eduPersonEntitlement'])) {
            $this->setEduPersonEntitlement($datos['eduPersonEntitlement']);
        }
        if (isset($datos['eduPersonNickname'])) {
            $this->setEduPersonNickname($datos['eduPersonNickname']);
        }
        if (isset($datos['eduPersonOrgDN'])) {
            $this->setEduPersonOrgDN($datos['eduPersonOrgDN']);
        }
        if (isset($datos['eduPersonOrgUnitDN'])) {
            $this->setEduPersonOrgUnitDN($datos['eduPersonOrgUnitDN']);
        }
        if (isset($datos['eduPersonPrimaryAffiliation'])) {
            $this->setEduPersonPrimaryAffiliation($datos['eduPersonPrimaryAffiliation']);
        }
        if (isset($datos['eduPersonPrimaryOrgUnitDN'])) {
            $this->setEduPersonPrimaryOrgUnitDN($datos['eduPersonPrimaryOrgUnitDN']);
        }
        if (isset($datos['eduPersonPrincipalName'])) {
            $this->setEduPersonPrincipalName($datos['eduPersonPrincipalName']);
        }
        if (isset($datos['eduPersonPrincipalNamePrior'])) {
            $this->setEduPersonPrincipalNamePrior($datos['eduPersonPrincipalNamePrior']);
        }
        if (isset($datos['eduPersonScopedAffiliation'])) {
            $this->setEduPersonScopedAffiliation($datos['eduPersonScopedAffiliation']);
        }
        if (isset($datos['eduPersonTargetedID'])) {
            $this->setEduPersonTargetedID($datos['eduPersonTargetedID']);
        }
        if (isset($datos['eduPersonAssurance'])) {
            $this->setEduPersonAssurance($datos['eduPersonAssurance']);
        }
        if (isset($datos['eduPersonUniqueId'])) {
            $this->setEduPersonUniqueId($datos['eduPersonUniqueId']);
        }
        if (isset($datos['eduPersonOrcid'])) {
            $this->setEduPersonOrcid($datos['eduPersonOrcid']);
        }
        if (isset($datos['bloqueado'])) {
            $this->setBloqueado($datos['bloqueado']);
        }
        if (isset($datos['idPersona'])) {
            $this->setIdPersona($datos['idPersona']);
        }
        if (isset($datos['login'])) {
            $this->setLogin($datos['login']);
        }
        if (isset($datos['loginMethod'])) {
            $this->setLoginMethod($datos['loginMethod']);
        }
        if (isset($datos['gender'])) {
            $this->setGender($datos['gender']);
        }
        if (isset($datos['birthDate'])) {
            $this->setBirthDate($datos['birthDate']);
        }
        if (isset($datos['zoneInfo'])) {
            $this->setZoneInfo($datos['zoneInfo']);
        }
        if (isset($datos['mailPassRecovery'])) {
            $this->setMailPassRecovery($datos['mailPassRecovery']);
        }
        if (isset($datos['mailVerified'])) {
            $this->setMailVerified($datos['mailVerified']);
        }
        if (isset($datos['mailPassRecoveryVerified'])) {
            $this->setMailPassRecoveryVerified($datos['mailPassRecoveryVerified']);
        }
        if (isset($datos['mobileVerified'])) {
            $this->setMobileVerified($datos['mobileVerified']);
        }
        if (isset($datos['uniqueIdentifier'])) {
            $this->setUniqueIdentifier($datos['uniqueIdentifier']);
        }
    }

    /**
     * @return array
     */
    public function getArrayDatosAtributos()
    {
        $datos = $this->getArrayDatos();
        // Atributos del usuario
        /* @var \SIU\AraiUsuarios\Entities\Atributo $atributo */
        foreach ($this->getAtributos() as $atributo) {
            $datos[$atributo->getAtributo()] = $atributo->getValor();
        }

        return $datos;
    }

    /****************************************************************************************************************
    ********************************** METODOS PRIVADOS *************************************************************
    ****************************************************************************************************************/

    /**
     * @param $appUniqueId
     * @param $cuenta
     *
     * @return string
     */
    private function getIdentificadorCuenta($appUniqueId, $cuenta)
    {
        return $appUniqueId.self::SEPARADORAPLICACIONCUENTA.$cuenta;
    }

    /**
     * @param $atributo
     *
     * @return string
     */
    private function getIdentificadorAtributo($atributo)
    {
        return strtolower($atributo);
    }

    /**
     * @param $password
     * @param string $algoritmo Si no esta definido asume que es plano o que ya esta encriptado
     *
     * @return string
     */
    private function generarUserPassword($password, $algoritmo = null)
    {
        if (isset($algoritmo) && !empty($algoritmo)) {
            $algoritmo = '{'.strtoupper($algoritmo).'}';
        }

        return $algoritmo.$password;
    }
}

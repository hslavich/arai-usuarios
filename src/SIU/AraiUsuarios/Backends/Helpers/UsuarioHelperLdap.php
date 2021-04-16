<?php
/**
 * Created by IntelliJ IDEA.
 * User: fbohn
 * Date: 11/11/15
 * Time: 17:52.
 */

namespace SIU\AraiUsuarios\Backends\Helpers;

use SIU\AraiUsuarios\Entities\Usuario;
use SIU\AraiUsuarios\Entities\Cuenta;
use SIU\AraiUsuarios\Entities\Aplicacion;
use SIU\AraiUsuarios\Entities\Atributo;
use SIU\AraiUsuarios\Filters\UsuarioFiltro;
use SIU\AraiUsuarios\Filters\Filtro;
use SIU\AraiUsuarios\Filters\CuentaFiltro;

class UsuarioHelperLdap extends HelperLdap
{
    /**
     * Retorna el filtro para una consulta LDAP.
     *
     * @param Filtro $filtro
     * @param string $separador
     * @param bool   $includeInicio
     * @param string $separadorInicial
     *
     * @return string
     *
     * @throws AraiUsuariosError
     */
    public function getFilterLdap($filtro = null, $separador = '&', $includeInicio = true, $separadorInicial = '(&')
    {
        if (isset($filtro)) {
            if ($filtro instanceof CuentaFiltro) {
                $campoAppUniqueId = $filtro->getCampo('app_unique_id');
                $filtro->eliminarCampo('app_unique_id');
                if (isset($campoAppUniqueId) && !empty($campoAppUniqueId)) {
                    $filtro->agregarCampo('appUniqueId', Filtro::ES_IGUAL_A, array($campoAppUniqueId[Filtro::CLAVE_VALORES][0]));
                }
                $cuentasVincualdas = $filtro->getCuentasVincualdas();
                if ($cuentasVincualdas) {
                    $filtro->agregarCampo('appUniqueId1', Filtro::ES_IGUAL_A, array('*'), 'appUniqueId');
                }
                $cuentasDesvincualdas = $filtro->getCuentasDesvinculadas();
                if ($cuentasDesvincualdas) {
                    $filtro->agregarCampo('appUnlink1', Filtro::ES_IGUAL_A, array('*'), 'appUnlink');
                }
            }
            if ($filtro instanceof UsuarioFiltro) {
                $arrAtributosAdicionales = $this->getAtributosAdicionales();
                $camposFiltro = $filtro->getCampos();
                foreach ($camposFiltro as $campoFiltro) {
                    if (in_array($campoFiltro[Filtro::CLAVE_ALIASSQL], $arrAtributosAdicionales) && isset($campoFiltro[Filtro::CLAVE_VALORES][0])) {
                        $filtro->agregarCampo($campoFiltro[Filtro::CLAVE_ALIASSQL], Filtro::ES_IGUAL_A, array($this->generarValorAtributoUsuario($campoFiltro[Filtro::CLAVE_ALIASSQL], $campoFiltro[Filtro::CLAVE_VALORES][0])), 'atributos');
                    }
                }
            }
        }

        $filter = parent::getFilterLdap($filtro, $separador, $includeInicio, $separadorInicial);

        return $filter;
    }

    /**
     * @param array $datosUsuarios
     *
     * @return Usuario[]
     */
    public function generarUsuarios(array $datosUsuarios)
    {
        $usuarios = array();
        // recorro los datos de los usuarios
        foreach ($datosUsuarios as $datosUsuario) {
            $usuarios[] = $this->generarUsuario($datosUsuario);
        }

        return $usuarios;
    }

    /**
     * @param array $datosUsuario
     *
     * @return null|Usuario
     */
    public function generarUsuario(array $datosUsuario)
    {
        if (isset($datosUsuario) && !empty($datosUsuario)) {
            $usuario = new Usuario();
            $usuario->setUid(isset($datosUsuario['uid'][0]) ? $datosUsuario['uid'][0] : null);
            $usuario->setSn(isset($datosUsuario['sn'][0]) ? $datosUsuario['sn'][0] : null);
            $usuario->setCn(isset($datosUsuario['cn'][0]) ? $datosUsuario['cn'][0] : null);
            $usuario->setDescription(isset($datosUsuario['description'][0]) ? $datosUsuario['description'][0] : null);
            $usuario->setSeeAlso(isset($datosUsuario['seealso'][0]) ? $datosUsuario['seealso'][0] : null);
            $usuario->setTelephoneNumber(isset($datosUsuario['telephonenumber'][0]) ? $datosUsuario['telephonenumber'][0] : null);
            $usuario->setUserPassword(isset($datosUsuario['userpassword'][0]) ? $datosUsuario['userpassword'][0] : null);
            $usuario->setDestinationIndicator(isset($datosUsuario['destinationindicator'][0]) ? $datosUsuario['destinationindicator'][0] : null);
            $usuario->setFacsimileTelephoneNumber(isset($datosUsuario['facsimiletelephonenumber'][0]) ? $datosUsuario['facsimiletelephonenumber'][0] : null);
            $usuario->setInternationaliSDNNumber(isset($datosUsuario['internationalisdnnumber'][0]) ? $datosUsuario['internationalisdnnumber'][0] : null);
            $usuario->setL(isset($datosUsuario['l'][0]) ? $datosUsuario['l'][0] : null);
            $usuario->setOu(isset($datosUsuario['ou'][0]) ? $datosUsuario['ou'][0] : null);
            $usuario->setPhysicalDeliveryOfficeName(isset($datosUsuario['physicaldeliveryofficename'][0]) ? $datosUsuario['physicaldeliveryofficename'][0] : null);
            $usuario->setPostOfficeBox(isset($datosUsuario['postofficebox'][0]) ? $datosUsuario['postofficebox'][0] : null);
            $usuario->setPostalAddress(isset($datosUsuario['postaladdress'][0]) ? $datosUsuario['postaladdress'][0] : null);
            $usuario->setPostalCode(isset($datosUsuario['postalcode'][0]) ? $datosUsuario['postalcode'][0] : null);
            $usuario->setPreferredDeliveryMethod(isset($datosUsuario['preferreddeliverymethod'][0]) ? $datosUsuario['preferreddeliverymethod'][0] : null);
            $usuario->setRegisteredAddress(isset($datosUsuario['registeredaddress'][0]) ? $datosUsuario['registeredaddress'][0] : null);
            $usuario->setSt(isset($datosUsuario['st'][0]) ? $datosUsuario['st'][0] : null);
            $usuario->setStreet(isset($datosUsuario['street'][0]) ? $datosUsuario['street'][0] : null);
            $usuario->setTeletexTerminalIdentifier(isset($datosUsuario['teletexterminalidentifier'][0]) ? $datosUsuario['teletexterminalidentifier'][0] : null);
            $usuario->setTelexNumber(isset($datosUsuario['telexnumber'][0]) ? $datosUsuario['telexnumber'][0] : null);
            $usuario->setTitle(isset($datosUsuario['title'][0]) ? $datosUsuario['title'][0] : null);
            $usuario->setX121Address(isset($datosUsuario['x121address'][0]) ? $datosUsuario['x121address'][0] : null);
            $usuario->setAudio(isset($datosUsuario['audio'][0]) ? $datosUsuario['audio'][0] : null);
            $usuario->setBusinessCategory(isset($datosUsuario['businesscategory'][0]) ? $datosUsuario['businesscategory'][0] : null);
            $usuario->setCarLicense(isset($datosUsuario['carlicense'][0]) ? $datosUsuario['carlicense'][0] : null);
            $usuario->setDepartmentNumber(isset($datosUsuario['departmentnumber'][0]) ? $datosUsuario['departmentnumber'][0] : null);
            $usuario->setDisplayName(isset($datosUsuario['displayname'][0]) ? $datosUsuario['displayname'][0] : null);
            $usuario->setEmployeeNumber(isset($datosUsuario['employeenumber'][0]) ? $datosUsuario['employeenumber'][0] : null);
            $usuario->setEmployeeType(isset($datosUsuario['employeetype'][0]) ? $datosUsuario['employeetype'][0] : null);
            $usuario->setGivenName(isset($datosUsuario['givenname'][0]) ? $datosUsuario['givenname'][0] : null);
            $usuario->setHomePhone(isset($datosUsuario['homephone'][0]) ? $datosUsuario['homephone'][0] : null);
            $usuario->setHomePostalAddress(isset($datosUsuario['homepostaladdress'][0]) ? $datosUsuario['homepostaladdress'][0] : null);
            $usuario->setInitials(isset($datosUsuario['initials'][0]) ? $datosUsuario['initials'][0] : null);
            $usuario->setJpegPhoto(isset($datosUsuario['jpegphoto'][0]) ? $datosUsuario['jpegphoto'][0] : null);
            $usuario->setLabeledURI(isset($datosUsuario['labeleduri'][0]) ? $datosUsuario['labeleduri'][0] : null);
            $usuario->setMail(isset($datosUsuario['mail'][0]) ? $datosUsuario['mail'][0] : null);
            $usuario->setManager(isset($datosUsuario['manager'][0]) ? $datosUsuario['manager'][0] : null);
            $usuario->setMobile(isset($datosUsuario['mobile'][0]) ? $datosUsuario['mobile'][0] : null);
            $usuario->setO(isset($datosUsuario['o'][0]) ? $datosUsuario['o'][0] : null);
            $usuario->setPager(isset($datosUsuario['pager'][0]) ? $datosUsuario['pager'][0] : null);
            $usuario->setPhoto(isset($datosUsuario['photo'][0]) ? $datosUsuario['photo'][0] : null);
            $usuario->setPreferredLanguage(isset($datosUsuario['preferredlanguage'][0]) ? $datosUsuario['preferredlanguage'][0] : null);
            $usuario->setRoomNumber(isset($datosUsuario['roomnumber'][0]) ? $datosUsuario['roomnumber'][0] : null);
            $usuario->setSecretary(isset($datosUsuario['secretary'][0]) ? $datosUsuario['secretary'][0] : null);
            $usuario->setUserCertificate(isset($datosUsuario['usercertificate'][0]) ? $datosUsuario['usercertificate'][0] : null);
            $usuario->setUserPKCS12(isset($datosUsuario['userpkcs12'][0]) ? $datosUsuario['userpkcs12'][0] : null);
            $usuario->setUserSMIMECertificate(isset($datosUsuario['usersmimecertificate'][0]) ? $datosUsuario['usersmimecertificate'][0] : null);
            $usuario->setX500uniqueIdentifier(isset($datosUsuario['x500uniqueidentifier'][0]) ? $datosUsuario['x500uniqueidentifier'][0] : null);
            $usuario->setEduPersonAffiliation(isset($datosUsuario['edupersonaffiliation'][0]) ? $datosUsuario['edupersonaffiliation'][0] : null);
            $usuario->setEduPersonEntitlement(isset($datosUsuario['edupersonentitlement'][0]) ? $datosUsuario['edupersonentitlement'][0] : null);
            $usuario->setEduPersonNickname(isset($datosUsuario['edupersonnickname'][0]) ? $datosUsuario['edupersonnickname'][0] : null);
            $usuario->setEduPersonOrgDN(isset($datosUsuario['edupersonorgdn'][0]) ? $datosUsuario['edupersonorgdn'][0] : null);
            $usuario->setEduPersonOrgUnitDN(isset($datosUsuario['edupersonorgunitdn'][0]) ? $datosUsuario['edupersonorgunitdn'][0] : null);
            $usuario->setEduPersonPrimaryAffiliation(isset($datosUsuario['edupersonprimaryaffiliation'][0]) ? $datosUsuario['edupersonprimaryaffiliation'][0] : null);
            $usuario->setEduPersonPrimaryOrgUnitDN(isset($datosUsuario['edupersonprimaryorgunitdn'][0]) ? $datosUsuario['edupersonprimaryorgunitdn'][0] : null);
            $usuario->setEduPersonPrincipalName(isset($datosUsuario['edupersonprincipalname'][0]) ? $datosUsuario['edupersonprincipalname'][0] : null);
            $usuario->setEduPersonPrincipalNamePrior(isset($datosUsuario['edupersonprincipalnameprior'][0]) ? $datosUsuario['edupersonprincipalnameprior'][0] : null);
            $usuario->setEduPersonScopedAffiliation(isset($datosUsuario['edupersonscopedaffiliation'][0]) ? $datosUsuario['edupersonscopedaffiliation'][0] : null);
            $usuario->setEduPersonTargetedID(isset($datosUsuario['edupersontargetedid'][0]) ? $datosUsuario['edupersontargetedid'][0] : null);
            $usuario->setEduPersonAssurance(isset($datosUsuario['edupersonassurance'][0]) ? $datosUsuario['edupersonassurance'][0] : null);
            $usuario->setEduPersonUniqueId(isset($datosUsuario['edupersonuniqueid'][0]) ? $datosUsuario['edupersonuniqueid'][0] : null);
            $usuario->setEduPersonOrcid(isset($datosUsuario['edupersonorcid'][0]) ? $datosUsuario['edupersonorcid'][0] : null);
            $usuario->setBloqueado(isset($datosUsuario['bloqueado'][0]) ? $datosUsuario['bloqueado'][0] : null);
            $usuario->setIdPersona(isset($datosUsuario['idpersona'][0]) ? $datosUsuario['idpersona'][0] : null);
            $usuario->setLogin(isset($datosUsuario['login'][0]) ? $datosUsuario['login'][0] : null);
            $usuario->setLoginMethod(isset($datosUsuario['loginmethod'][0]) ? $datosUsuario['loginmethod'][0] : null);
            $usuario->setGender(isset($datosUsuario['gender'][0]) ? $datosUsuario['gender'][0] : null);
            $usuario->setBirthDate(isset($datosUsuario['birthdate'][0]) ? $datosUsuario['birthdate'][0] : null);
            $usuario->setZoneInfo(isset($datosUsuario['zoneinfo'][0]) ? $datosUsuario['zoneinfo'][0] : null);
            $usuario->setMailPassRecovery(isset($datosUsuario['mailpassrecovery'][0]) ? $datosUsuario['mailpassrecovery'][0] : null);
            $usuario->setMailPassRecoveryVerified(isset($datosUsuario['mailpassrecoveryverified'][0]) ? $datosUsuario['mailpassrecoveryverified'][0] : null);
            $usuario->setMailVerified(isset($datosUsuario['mailverified'][0]) ? $datosUsuario['mailverified'][0] : null);
            $usuario->setMobileVerified(isset($datosUsuario['mobileverified'][0]) ? $datosUsuario['mobileverified'][0] : null);
            $usuario->setUniqueIdentifier(isset($datosUsuario['uniqueidentifier'][0]) ? $datosUsuario['uniqueidentifier'][0] : null);
            $usuario->setMemberOf(isset($datosUsuario['memberof']) ? $datosUsuario['memberof'] : array());
            
            return $usuario;
        } else {
            return null;
        }
    }

    /**
     * @param Usuario[] $usuarios
     *
     * @return array
     */
    public function getIdsUsuarios(array $usuarios)
    {
        $idsUsuarios = array();
        foreach ($usuarios as $usuario) {
            $idsUsuarios[] = $usuario->getUid();
        }

        return $idsUsuarios;
    }

    /**
     * @param string[] $datos
     *
     * @return array
     */
    public function getIdsUsuariosFromArray(array $datos)
    {
        $idsUsuarios = array();
        foreach ($datos as $dato) {
            $idsUsuarios[] = $dato['uid'][0];
        }

        return $idsUsuarios;
    }

    /**
     * @param Usuario[]    $usuarios
     * @param Aplicacion[] $aplicaciones
     * @param array        $cuentas
     */
    public function asociarAplicacionesUsuarios(array $usuarios, array $aplicaciones, array $cuentas)
    {
        //Genera arreglo de aplicaciones que no requieran cuenta especifica
        $arrAppsSinCuenta = $arrAppGrupos = array();
        foreach ($aplicaciones as $aplicacion) {
            $index = $aplicacion->getAppUniqueId();
            $icono = $this->configuracion->getUrlImgAplicaciones().'/'.$index.'.png';
            if ($aplicacion->getAccesoSinCuenta()) {
                $arrAppsSinCuenta[$index] = array('url' => $aplicacion->getUrl(),
                                                'etiqueta' => $aplicacion->getEtiqueta(),
                                                'descripcion' => $aplicacion->getDescripcion(),
                                                'appUniqueId' => $index,
                                                'ocultar_menu' => $aplicacion->getOcultarMenu(),
                                                'icono_url' =>  $icono
                );
            } else {
                //Veamos si hay algun usuario que coincida por grupo
                $disponibles = $aplicacion->getListaGruposFiltro();
                foreach ($usuarios as $usuario) {
                    foreach ($usuario->getGrupos() as $grupo) {
                        if (in_array($grupo->getCn(), $disponibles)) {		//Si coincide grupo, la agrego a las que no precisan cuentas
                            $arrAppsSinCuenta[$index] = array('url' => $aplicacion->getUrl(),
                                                'etiqueta' => $aplicacion->getEtiqueta(),
                                                'descripcion' => $aplicacion->getDescripcion(),
                                                'appUniqueId' => $index,
                                                'ocultar_menu' => $aplicacion->getOcultarMenu(),
                                                'icono_url' =>  $icono
                            );
                        }
                    }
                }
            }
        }
       
        $arrCuentas = array();
        // Recorro las cuentas y le asocio las aplicaciones
        foreach ($cuentas as $clave => $cuenta) {
            if (isset($cuenta['appuniqueid'][0]) && isset($aplicaciones[$cuenta['appuniqueid'][0]]) && !empty($aplicaciones[$cuenta['appuniqueid'][0]])) {
                //Inicializa en datos de la cuenta
                $aux = $cuenta;
                $index = $cuenta['appuniqueid'][0];
                $appEnCuestion = $aplicaciones[$index];
                
                //Agrego data extra de la aplicacion a la cuenta
                $aux['appUniqueId'][] = $index;
                $aux['url'][] = $appEnCuestion->getUrl();
                $aux['etiqueta'][] = $appEnCuestion->getEtiqueta();
                $aux['descripcion'][] = $appEnCuestion->getDescripcion();
                $aux['version'][] = $appEnCuestion->getVersion();
                $aux['orden'][] = $appEnCuestion->getOrden();
                $aux['ocultar_menu'][] = $appEnCuestion->getOcultarMenu();
                $arrCuentas[] = $aux;
            }
        }

        // Ordenar las cuentas por orden ASC, etiqueta ASC y cuenta ASC
        $columnasOrden = array('orden' => SORT_ASC,
                                'etiqueta' => SORT_ASC,
                                'cuenta' => SORT_ASC,
            );
        $arrCuentas = $this->driver->ordenarPorColumnasLdap($arrCuentas, array('orden', 'etiqueta', 'cuenta'), $columnasOrden);

        //Re-asocia las cuentas a los usuarios
        foreach ($usuarios as $usuario) {
            $usuario->quitarCuentas();
            foreach ($arrCuentas as $clave => $arrCuenta) {
                if ($usuario->getUid() == $arrCuenta['uid'][0]) {
                    $cuenta = new Cuenta();
                    $cuenta->setUid($arrCuenta['uid'][0]);
                    if (isset($arrCuenta['appUniqueId'][0])) {
                        $cuenta->setAppUniqueId($arrCuenta['appUniqueId'][0]);
                    }
                    if (isset($arrCuenta['appUnlink'][0])) {
                        $cuenta->setAppUnlink($arrCuenta['appUnlink'][0]);
                    }
                    $cuenta->setCuenta($arrCuenta['cuenta'][0]);
                    $cuenta->setEtiqueta($arrCuenta['etiqueta'][0]);
                    $cuenta->setVersion($arrCuenta['version'][0]);
                    $cuenta->setDefecto($arrCuenta['defecto'][0]);
                    $usuario->agregarCuenta($cuenta);
                }
            }
        }

        $cuentasUsuarios = array();
        foreach ($arrCuentas as $arrCuenta) {
            $uidUsuario = $arrCuenta['uid'][0];

            if (isset($arrCuenta['appUniqueId'][0]) && !empty($arrCuenta['appUniqueId'][0])) {
                $enc = false;
                $pos = 0;
                if (isset($cuentasUsuarios[$uidUsuario])) {
                    $cantidad = count($cuentasUsuarios[$uidUsuario]);
                    while ($pos < $cantidad && !$enc) {
                        if ($cuentasUsuarios[$uidUsuario][$pos]['appUniqueId'] == $arrCuenta['appUniqueId'][0]) {
                            $enc = true;
                        } else {
                            ++$pos;
                        }
                    }
                }
                if (!$enc) {
                    $cuentasUsuarios[$uidUsuario][] = array(
                        'url' => $arrCuenta['url'][0],
                        'etiqueta' => $arrCuenta['etiqueta'][0],
                        'descripcion' => $arrCuenta['descripcion'][0],
                        'appUniqueId' => $arrCuenta['appUniqueId'][0],
                        'ocultar_menu' => $arrCuenta['ocultar_menu'][0],
                    );
                }
            }
        }

        $datosCuentasUsuarios = array();
        foreach ($cuentasUsuarios as $uidUsuario => $aplicacionesUsuario) {
            $datosCuentasUsuarios[$uidUsuario]['accessTo'] = array_keys($arrAppsSinCuenta);		//Inicializo con aplicaciones sin cuenta
            $aplicacionesValidas = array();
            foreach ($aplicacionesUsuario as $claveCuenta => $arrCuenta) {
                $index = $arrCuenta['appUniqueId'];
                if (!in_array($index, $datosCuentasUsuarios[$uidUsuario]['accessTo'])) {
                    $datosCuentasUsuarios[$uidUsuario]['accessTo'][] = $index;
                    $aplicacionesValidas[$index] = $aplicacionesUsuario[$claveCuenta];
                    $aplicacionesValidas[$index]['icono_url'] = $this->configuracion->getUrlImgAplicaciones().'/'.$arrCuenta['appUniqueId'].'.png';
                }
            }

            // Armo el campo appLauncherData
            if (!isset($datosCuentasUsuarios[$uidUsuario]['appLauncherData'])) {
                $datosCuentasUsuarios[$uidUsuario]['appLauncherData'] = array();
            }
            $datosCuentasUsuarios[$uidUsuario]['appLauncherData']['aplicaciones'] = array_merge($arrAppsSinCuenta, $aplicacionesValidas);
        }

        foreach ($usuarios as $usuario) {
            $index = $usuario->getUid();
            if (!isset($datosCuentasUsuarios[$index]['appLauncherData'])) {
                $datosCuentasUsuarios[$index]['appLauncherData'] = array();
                $datosCuentasUsuarios[$index]['appLauncherData']['aplicaciones'] = $arrAppsSinCuenta;
                $datosCuentasUsuarios[$index]['accessTo'] = array_keys($arrAppsSinCuenta);
            }
            $datosCuentasUsuarios[$index]['appLauncherData']['usuario_id'] = $usuario->getLoginIdentifier();
            $datosCuentasUsuarios[$index]['appLauncherData']['usuario_nombre'] = $usuario->getCn();
            $datosCuentasUsuarios[$index]['appLauncherData']['usuario_foto'] = $usuario->getJpegPhotoUrl();
            $datosCuentasUsuarios[$index]['appLauncherData']['perfil_url'] = $this->configuracion->getUserUrlPerfil();
            $usuario->setAccessTo(isset($datosCuentasUsuarios[$index]['accessTo']) ? $datosCuentasUsuarios[$index]['accessTo'] : '');
            $usuario->setAppLauncherData($datosCuentasUsuarios[$index]['appLauncherData']);
        }
    }

    /**
     * @param Usuario[] $usuarios
     * @param array     $atributosValidos
     * @param array     $atributos
     */
    public function asociarAtributosUsuarios(array $usuarios, array $atributosValidos, array $atributos)
    {
        foreach ($usuarios as $usuario) {
            $usuario->quitarAtributos();
            foreach ($atributos as $arr_atributo) {
                if ($usuario->getUid() == $arr_atributo['uid'][0] && isset($arr_atributo['atributos'])) {
                    foreach ($arr_atributo['atributos'] as $datoAtributo) {
                        $claveValorAtributo = $this->parsearValorAtributoUsuario($datoAtributo);
                        if (isset($claveValorAtributo['clave']) && isset($claveValorAtributo['valor'])) {
                            foreach ($atributosValidos as $atributoValido) {
                                if ($claveValorAtributo['clave'] == $atributoValido) {
                                    $atributo = new Atributo();
                                    $atributo->setUid($arr_atributo['uid'][0]);
                                    $atributo->setAtributo($atributoValido);
                                    $atributo->setValor($claveValorAtributo['valor']);
                                    $usuario->agregarAtributo($atributo);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param array        $datosCuentas
     * @param Aplicacion[] $aplicaciones
     *
     * @return \SIU\AraiUsuarios\Entities\Cuenta[]
     */
    public function generarCuentas(array $datosCuentas, array $aplicaciones)
    {
        $cuentas = array();
        // Recorro las cuentas y le asocio las aplicaciones
        foreach ($datosCuentas as $clave => $datosCuenta) {
            if (isset($datosCuentas[$clave]['appuniqueid'][0]) && isset($aplicaciones[$datosCuentas[$clave]['appuniqueid'][0]]) && !empty($aplicaciones[$datosCuentas[$clave]['appuniqueid'][0]])) {
                $datosCuentas[$clave]['appUniqueId'][] = $datosCuentas[$clave]['appuniqueid'][0];
                $datosCuentas[$clave]['url'][] = $aplicaciones[$datosCuentas[$clave]['appUniqueId'][0]]->getUrl();
                $datosCuentas[$clave]['etiqueta'][] = $aplicaciones[$datosCuentas[$clave]['appUniqueId'][0]]->getEtiqueta();
                $datosCuentas[$clave]['descripcion'][] = $aplicaciones[$datosCuentas[$clave]['appUniqueId'][0]]->getDescripcion();
                $datosCuentas[$clave]['version'][] = $aplicaciones[$datosCuentas[$clave]['appUniqueId'][0]]->getVersion();
                $datosCuentas[$clave]['orden'][] = $aplicaciones[$datosCuentas[$clave]['appUniqueId'][0]]->getOrden();
                $datosCuentas[$clave]['defecto'][] = $datosCuenta['defecto'][0];
                $cuentas[] = $this->generarCuenta($datosCuentas[$clave]);
            } elseif (isset($datosCuentas[$clave]['appunlink'][0]) && !empty($datosCuentas[$clave]['appunlink'][0])) {
                $datosCuentas[$clave]['appUnlink'][] = $datosCuentas[$clave]['appunlink'][0];
                $datosCuentas[$clave]['url'][] = null;
                $datosCuentas[$clave]['etiqueta'][] = null;
                $datosCuentas[$clave]['descripcion'][] = null;
                $datosCuentas[$clave]['version'][] = null;
                $datosCuentas[$clave]['orden'][] = null;
                $datosCuentas[$clave]['defecto'][] = $datosCuenta['defecto'][0];
                $cuentas[] = $this->generarCuenta($datosCuentas[$clave]);
            } else {
                unset($datosCuentas[$clave]);
            }
        }

        return $cuentas;
    }

    /**
     * @param array $datosCuenta
     *
     * @return null|Cuenta
     */
    public function generarCuenta(array $datosCuenta)
    {
        if (isset($datosCuenta) && !empty($datosCuenta)) {
            $cuenta = new Cuenta();
            if (isset($datosCuenta['appUniqueId'][0])) {
                $cuenta->setAppUniqueId($datosCuenta['appUniqueId'][0]);
            }
            if (isset($datosCuenta['appUnlink'][0])) {
                $cuenta->setAppUnlink($datosCuenta['appUnlink'][0]);
            }
            $cuenta->setUid($datosCuenta['uid'][0]);
            $cuenta->setCuenta($datosCuenta['cuenta'][0]);
            $cuenta->setDefecto($datosCuenta['defecto'][0]);
            $cuenta->setEtiqueta($datosCuenta['etiqueta'][0]);
            $cuenta->setVersion($datosCuenta['version'][0]);

            return $cuenta;
        } else {
            return null;
        }
    }

    /**
     * @param array $uidsUsuarios
     *
     * @return string
     */
    public function getFiltroOrCuentasUsuarios(array $uidsUsuarios, Filtro $filtro = null)
    {
        $filter = '(&(|';
        if (is_null($filtro)) {
            $filtro = new Filtro();
        }

        $objectClassUserAccounts = $this->getArrayObjectClassUserAccount();
        for ($i = 0; $i < count($objectClassUserAccounts); ++$i) {
            $filtro->agregarCampo('objectClass'.($i + 1), Filtro::ES_IGUAL_A, array($objectClassUserAccounts[$i]), 'objectClass');
        }

        foreach ($uidsUsuarios as $uidUsuario) {
            //$filtro->agregarCampo($this->getUniqueAttributeUser(), Filtro::ES_IGUAL_A, array($uidUsuario));
            $filtro->agregarCampo('uid', Filtro::ES_IGUAL_A, array($uidUsuario));
            $filter .= $this->getFilterLdap($filtro, '&', false);
        }
        $filter .= '))';

        return $filter;
    }

    /**
     * @param array $uidsUsuarios
     *
     * @return string
     */
    public function getFiltroOrUsuarios(array $uidsUsuarios, Filtro $filtro = null)
    {
        $filter = '(&(|';
        if (is_null($filtro)) {
            $filtro = new UsuarioFiltro();
        }
        $objectClassUser = $this->getArrayObjectClassUser();
        for ($i = 0; $i < count($objectClassUser); ++$i) {
            $filtro->agregarCampo('objectClass'.($i + 1), Filtro::ES_IGUAL_A, array($objectClassUser[$i]), 'objectClass');
        }
        foreach ($uidsUsuarios as $uidUsuario) {
            $filtro->agregarCampo($this->getUniqueAttributeUser(), Filtro::ES_IGUAL_A, array($uidUsuario));
            $filter .= $this->getFilterLdap($filtro, '&', false);
        }
        $filter .= '))';

        return $filter;
    }

    /**
     * Aplica los filtros de objectClass para "usuario"
     *
     * @param Filtro $filtro
     * @return Filtro
     */
    public function getFilterByObjectClassUser(Filtro $filtro = null)
    {
        if (is_null($filtro)) {
            $filtro = new UsuarioFiltro();
        }

        foreach ($this->getArrayObjectClassUser() as $key=>$class) {
            $filtro->agregarCampo('objectClass'.$key, Filtro::ES_VALOR, [$class], 'objectClass');
        }

        return $filtro;
    }

    /**
     * Aplica los filtros de objectClass para "usuarioCuentas"
     *
     * @param Filtro $filtro
     * @return Filtro
     */
    public function getFilterByObjectClassUserAccount(Filtro $filtro = null)
    {
        if (is_null($filtro)) {
            $filtro = new UsuarioFiltro();
        }

        foreach ($this->getArrayObjectClassUserAccount() as $key=>$class) {
            $filtro->agregarCampo('objectClass'.$key, Filtro::ES_VALOR, [$class], 'objectClass');
        }

        return $filtro;
    }



    /**
     * @return string
     */
    public function getSeparadorLdap()
    {
        if (isset($this->definitions['separadorLdap'])) {
            return $this->definitions['separadorLdap'];
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getAttributeClave()
    {
        if (isset($this->definitions['attributeClave'])) {
            return $this->definitions['attributeClave'];
        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getAttributeValor()
    {
        if (isset($this->definitions['attributeValor'])) {
            return $this->definitions['attributeValor'];
        } else {
            return '';
        }
    }

    /**
     * @return mixed
     *
     * @throws \SIU\AraiUsuarios\Drivers\ErrorLdap
     */
    public function getOuUsers()
    {
        return $this->driver->getParametro('ou_users');
    }

    /**
     * @return mixed
     *
     * @throws \SIU\AraiUsuarios\Drivers\ErrorLdap
     */
    public function getOuUsersAccounts()
    {
        return $this->driver->getParametro('ou_users_accounts');
    }

    /**
     * @return mixed
     *
     * @throws \SIU\AraiUsuarios\Drivers\ErrorLdap
     */
    public function getOuGroups()
    {
        return $this->driver->getParametro('ou_groups');
    }

    /**
     * @return mixed
     *
     * @throws \SIU\AraiUsuarios\Drivers\ErrorLdap
     */
    public function getUniqueAttributeUsuarios()
    {
        return $this->driver->getParametro('ou_users_attr');
    }

    /**
     * @return mixed
     *
     * @throws \SIU\AraiUsuarios\Drivers\ErrorLdap
     */
    public function getUniqueAttributeUsuariosCuentas()
    {
        return $this->driver->getParametro('ou_users_accounts_attr');
    }

    /**
     * @return mixed
     *
     * @throws \SIU\AraiUsuarios\Drivers\ErrorLdap
     */
    public function getUniqueAttributeGroups()
    {
        return $this->driver->getParametro('ou_groups_attr');
    }

    /**
     * @return mixed
     *
     * @throws \SIU\AraiUsuarios\Drivers\ErrorLdap
     */
    public function getBaseDN($prefixDN = '')
    {
        $baseDN = $this->driver->getBaseDN();

        if ($prefixDN && $prefixDN != '') {
            $baseDN = $prefixDN.','.$baseDN;
        }

        return $this->driver->escapeDN($baseDN);
    }

    /**
     * @return mixed
     *
     * @throws \SIU\AraiUsuarios\Drivers\ErrorLdap
     */
    public function getBaseDNUsers($prefixDN = '')
    {
        $dn = $this->getUniqueAttributeUsuarios().'='.$this->getOuUsers().','.$this->getBaseDN($prefixDN);

        return $this->driver->escapeDN($dn);
    }

    public function getBaseDNGroups()
    {
        return $this->driver->escapeDN($this->getUniqueAttributeGroups().'='.$this->getOuGroups().','.$this->driver->getBaseDN());
    }

    /**
     * @return mixed
     *
     * @throws \SIU\AraiUsuarios\Drivers\ErrorLdap
     */
    public function getBaseDNUsersAccounts($prefixDN = '')
    {
        $dn = $this->getUniqueAttributeUsuariosCuentas().'='.$this->getOuUsersAccounts().','.$this->getBaseDN($prefixDN);

        return $this->driver->escapeDN($dn);
    }

    /**
     * @param string $uid identificador del usuario
     * @param string $prefixBaseDN opcional, prefijo sobre el DN base. Un subnodo ldap
     *
     * @return mixed
     */
    public function getDNUser($uid, $prefixBaseDN = null)
    {
        if (!$prefixBaseDN) {
            $prefixBaseDN = $this->getPrefixBaseDNUserFrom($uid);
        }

        $dn = $this->getUniqueAttributeUser().'='.$uid.','.$this->getBaseDNUsers($prefixBaseDN);

        return $this->driver->escapeDN($dn);
    }

    public function getDNGroup($groupCN)
    {
        $cn = '';
        if (is_array($groupCN)) {
            array_walk($groupCN, function (&$v, $k) {
                $v = 'cn='.$v;
            });
            $cn = implode(',', $groupCN);
        } else {
            $cn = 'cn='.$groupCN;
        }

        return $this->driver->escapeDN($cn.','.$this->getBaseDNGroups());
    }

    /**
     * @param $aid
     *
     * @return mixed
     */
    public function getDNUserAccount($aid, $prefixBaseDN = null)
    {
        if (!$prefixBaseDN) {
            $prefixBaseDN = $this->getPrefixBaseDNUsersAccountsFrom($aid);
        }

        $dn = $this->getUniqueAttributeUserAccount().'='.$aid.','.$this->getBaseDNUsersAccounts($prefixBaseDN);

        return $this->driver->escapeDN($dn);
    }

    public function getUniqueAttributeUserAccount()
    {
        if (isset($this->definitions['uniqueAttributeUserAccount'])) {
            return $this->definitions['uniqueAttributeUserAccount'];
        } else {
            return null;
        }
    }

    public function getUniqueAttributeUser()
    {
        if (isset($this->definitions['uniqueAttributeUser'])) {
            return $this->definitions['uniqueAttributeUser'];
        } else {
            return null;
        }
    }

    /**
     * @param $cuenta
     * @param $uid
     * @param null $appUniqueId
     * @param null $appUnlink
     *
     * @return string
     */
    public function getAidUserAccount($cuenta, $uid, $appUniqueId = null, $appUnlink = null)
    {
        return $cuenta.$this->getSeparadorLdap().$uid.$this->getSeparadorLdap().$appUniqueId.$this->getSeparadorLdap().$appUnlink;
    }

    public function getUidFromAid($aid)
    {
        $aux = explode($this->getSeparadorLdap(), $aid);

        return $aux[1];
    }

    public function getAidFromDN($dn)
    {
        $aux = explode(',', $dn);

        return $aux[0];
    }

    /**
     * @return array
     */
    public function getArrayObjectClassUser()
    {
        if (isset($this->definitions['objectClassUser'])) {
            return $this->definitions['objectClassUser'];
        } else {
            return array();
        }
    }

    /**
     * @return array
     */
    public function getObjectClassGroup()
    {
        if (isset($this->definitions['objectClassGroup'])) {
            return $this->definitions['objectClassGroup'];
        } else {
            return array();
        }
    }

    /**
     * Retorna el identificador (uid) del usuario administrador por defecto.
     *
     * @return string el uid de usuario administrador
     */
    public function getDefaultAdminUser()
    {
        //TODO: esto esta harcodeado por ahora. Deberá ser configurable.
        return 'admin';
    }

    /**
     * Retorna el DN completo al usuario administrador por defecto.
     *
     * Nota: esta siendo utilizado como miembro por defecto al crear grupos.
     *
     * @return array un DN hacia el usuario administrador por defecto
     */
    public function getDnAdminMemberGroup()
    {
        $adminUser = $this->getDefaultAdminUser();

        return $this->driver->escapeDN(
            sprintf(
                'cn=%s,%s',
                $adminUser,
                $this->driver->getBaseDN()
            )
        );
    }

    /**
     * @return array
     */
    public function getArrayObjectClassUserAccount()
    {
        if (isset($this->definitions['objectClassUserAccount'])) {
            return $this->definitions['objectClassUserAccount'];
        } else {
            return array();
        }
    }

    /**
     * @return mixed
     */
    public function getAtributosAdicionales()
    {
        return $this->configuracion->getAtributosAdicionales();
    }

    /**
     * @return array
     */
    public function getSAMLUserAttributes()
    {
        if (isset($this->definitions['SAMLUserAttributes'])) {
            return array_merge($this->definitions['SAMLUserAttributes'], $this->configuracion->getAtributosAdicionalesSAML());
        } else {
            return array();
        }
    }

    /**
     * @param $clave
     * @param $valor
     *
     * @return string
     */
    public function generarValorAtributoUsuario($clave, $valor)
    {
        return $this->getAttributeClave().$clave.$this->getSeparadorLdap().$this->getAttributeValor().$valor;
    }

    /**
     * @param $atributo
     *
     * @return array
     */
    public function parsearValorAtributoUsuario($atributo)
    {
        $arrAtributo = explode($this->getSeparadorLdap().$this->getAttributeValor(), $atributo);
        $claveValor = array(
            'clave' => null,
            'valor' => null,
        );
        if (count($arrAtributo) > 1) {
            $longitudClave = strlen($this->getAttributeClave());
            $claveValor['clave'] = substr($arrAtributo[0], $longitudClave);

            unset($arrAtributo[0]);
            $claveValor['valor'] = implode('', $arrAtributo);
        }

        return $claveValor;
    }

    /**
     * Recupera el usuario y extrae el "prefijo" o nodo LDAP del DN que lo identifica
     *
     * El usuario se identifica con un DN absoluto, parte siempre de:
     *
     *  - UID + [prefijoDN] + baseDN
     *
     * Esto quiere decir que, prefijoDN puede ser nulo, y que UID es único en todo el baseDN.
     *
     * @param string $uid
     * @return string
     */
    public function getPrefixBaseDNUserFrom($uid)
    {
        $prefixDN = '';

        $filtro = $this->getFilterByObjectClassUser();

        $filtro->agregarCampo('uid', UsuarioFiltro::ES_IGUAL_A, array($uid));

        $query = array();
        $query['base'] = $this->getBaseDN();
        $query['scope'] = 'sub';    //TODO: sub=multilevel, one=single level
        $query['deref'] = LDAP_DEREF_NEVER;
        $query['filter'] = $this->getFilterLdap($filtro);
        $query['attrs'] = array('dn');
        $query['baseok'] = true;

        $datos = $this->driver->query($query);

        // parece que pueden haber casos (sin nodos) donde nos arroje resultado cero
        // registros... con algún tema? o la forma de buscar en LDAP multilevel?
        // no parece que sea problema ya que estamos buscando el prefix o nodo
        if (count($datos) > 0) {
            // dc=siu,dc=cin
            $baseDN = ','.$this->getBaseDN();
            $aux = str_ireplace($baseDN, '', $datos[0]['dn'][0]);

            // uid=sergio.vier,ou=usuarios
            $userIdentifier = $this->getUniqueAttributeUser().'='.$uid.','.$this->getUniqueAttributeUsuarios().'='.$this->getOuUsers();
            $prefixDN = str_ireplace($userIdentifier, '', $aux);
        }

        return trim($prefixDN, ',');
    }

    /**
     *
     * @param string $aid
     * @return string
     */
    public function getPrefixBaseDNUsersAccountsFrom($aid)
    {
        $uid = $this->getUidFromAid($aid);

        return $this->getPrefixBaseDNUserFrom($uid);
    }
}

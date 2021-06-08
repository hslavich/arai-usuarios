<?php

/**
 * Created by IntelliJ IDEA.
 * User: fbohn
 * Date: 07/11/15
 * Time: 12:48.
 */

namespace SIU\AraiUsuarios\Core;

use Ramsey\Uuid\Uuid;
use SIU\AraiUsuarios\Backends\AplicacionesBackend;
use SIU\AraiUsuarios\Backends\AplicacionesBackendDb;
use SIU\AraiUsuarios\Backends\LoggersBackendDb;
use SIU\AraiUsuarios\Backends\UsuariosBackend;
use SIU\AraiUsuarios\Backends\UsuariosBackendLdap;
use SIU\AraiUsuarios\Backends\UsuariosPolicyBackendDb;
use SIU\AraiUsuarios\Config\Configuracion;
use SIU\AraiUsuarios\Entities\Atributo;
use SIU\AraiUsuarios\Entities\Cuenta;
use SIU\AraiUsuarios\Entities\Grupo;
use SIU\AraiUsuarios\Entities\Usuario;
use SIU\AraiUsuarios\Entities\CambioPassword;
use SIU\AraiUsuarios\Error;
use SIU\AraiUsuarios\Filters\CuentaFiltro;
use SIU\AraiUsuarios\Filters\Filtro;
use SIU\AraiUsuarios\Filters\UsuarioFiltro;
use SIU\AraiUsuarios\Filters\LoggerFiltro;
use SIU\AraiUsuarios\Util\Varios;

class UsuariosManager
{
    /* @var UsuariosBackendLdap */
    protected $backendUsuarios;

    /* @var AplicacionesBackendDb */
    protected $backendAplicaciones;

    /* @var LoggersBackendDb */
    protected $backendLoggers;

    /* @var UsuariosPolicyBackendDb */
    protected $backendPolicies;

    /* @var Configuracion */
    protected $configuracion;

    /* @var AraiVarios */
    protected $araiVarios;

    const UNIQUEIDENTIFIER = 'uniqueIdentifier';
    const APPUNIQUEID = 'appUniqueId';

    /**
     * @param UsuariosBackend     $backendUsuarios
     * @param AplicacionesBackend $backendAplicaciones
     * @param LoggersBackendDb    $backendLoggers
     * @param Configuracion       $configuracion
     * @param Varios              $araiVarios
     */
    public function __construct(UsuariosBackend $backendUsuarios, AplicacionesBackend $backendAplicaciones, LoggersBackendDb $backendLoggers, Configuracion $configuracion, Varios $araiVarios, UsuariosPolicyBackendDb $backendPoliticas)
    {
        $this->backendUsuarios = $backendUsuarios;
        $this->backendAplicaciones = $backendAplicaciones;
        $this->backendLoggers = $backendLoggers;
        $this->configuracion = $configuracion;
        $this->araiVarios = $araiVarios;
        $this->backendPolicies = $backendPoliticas;
    }

    /**
     * @param UsuarioFiltro|null $filtro
     * @param bool $hidratarDependencias Permite controlar si se hidratan las dependencias externas
     * @return \SIU\AraiUsuarios\Entities\Usuario[]
     *
     * @throws Error
     */
    public function getUsuarios(UsuarioFiltro $filtro = null, $hidratarDependencias = true)
    {
        try {
            /* @var Usuario[] $usuarios */
            $usuarios = $this->backendUsuarios->getUsuarios($filtro);

            if ($hidratarDependencias) {
                $this->hidratarGruposUsuarios($usuarios, $this->getGrupos()?? array());
                $this->backendUsuarios->hidratarAplicacionesAsociadasUsuarios($usuarios, $this->getArregloAplicaciones());
                $this->backendUsuarios->hidratarAtributosUsuarios($usuarios, $this->getAtributosAdicionalesUsuario());
                foreach ($usuarios as $usuario) {
                    $this->configurarFotoDelUsuario($usuario);
                }
            }

            return $usuarios;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param UsuarioFiltro|null $filtro
     *
     * @return int Cantidad de usuarios
     */
    public function getCantidadUsuarios(UsuarioFiltro $filtro = null)
    {
        return $this->backendUsuarios->getCantidadUsuarios($filtro);
    }

    /**
     * @param $uid string
     * @param UsuarioFiltro $filtro
     *
     * @return Usuario
     *
     * @throws Error
     */
    public function getUsuario($uid, UsuarioFiltro $filtro = null)
    {
        try {
            /* @var Usuario $usuario */
            $usuario = $this->backendUsuarios->getUsuario($uid, $filtro);
            if (isset($usuario)) {
                $this->configurarFotoDelUsuario($usuario);
                $this->hidratarGruposUsuarios(array($usuario), $this->getGrupos()?? array());
                $this->backendUsuarios->hidratarAplicacionesAsociadasUsuarios(array($usuario), $this->getArregloAplicaciones());
                $this->backendUsuarios->hidratarAtributosUsuarios(array($usuario), $this->getAtributosAdicionalesUsuario());
            }

            return $usuario;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $email
     * @param UsuarioFiltro $filtro
     *
     * @return Usuario
     *
     * @throws Error
     */
    public function getUsuarioByEmail($email, UsuarioFiltro $filtro = null)
    {
        try {
            /* @var Usuario $usuario */
            $usuario = $this->backendUsuarios->getUsuarioByEmail($email, $filtro);
            if (isset($usuario)) {
                $this->configurarFotoDelUsuario($usuario);
                $this->backendUsuarios->hidratarAplicacionesAsociadasUsuarios(array($usuario), $this->getArregloAplicaciones());
                $this->backendUsuarios->hidratarAtributosUsuarios(array($usuario), $this->getAtributosAdicionalesUsuario());
            }

            return $usuario;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $uniqueIdentifier
     * @param UsuarioFiltro $filtro
     *
     * @return Usuario
     *
     * @throws Error
     */
    public function getUsuarioByUniqueIdentifier($uniqueIdentifier, UsuarioFiltro $filtro = null)
    {
        try {
            /* @var Usuario $usuario */
            $usuario = $this->backendUsuarios->getUsuarioByUniqueIdentifier($uniqueIdentifier, $filtro);
            if (isset($usuario)) {
                $this->configurarFotoDelUsuario($usuario);
                $usuario = $this->hidratarCuentas($usuario, false);
            }

            return $usuario;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $login
     * @param UsuarioFiltro $filtro
     *
     * @return Usuario
     *
     * @throws Error
     */
    public function getUsuarioByLogin($login, UsuarioFiltro $filtro = null)
    {
        try {
            /* @var Usuario $usuario */
            $usuario = $this->backendUsuarios->getUsuarioByLogin($login, $filtro);
            if (isset($usuario)) {
                $this->configurarFotoDelUsuario($usuario);
                $usuario = $this->hidratarCuentas($usuario, false);
            }

            return $usuario;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Esto recupera todas las cuentas por aplicación del usuario, incluyendo las de apps inactivas
     *
     * @param $uid string
     * @param UsuarioFiltro $filtro
     *
     * @return Usuario
     *
     * @throws Error
     */
    public function getUsuarioConCuentasEnAppInactivas($uid, UsuarioFiltro $filtro = null)
    {
        try {
            /* @var Usuario $usuario */
            $usuario = $this->backendUsuarios->getUsuario($uid, $filtro);
            if (isset($usuario)) {
                $this->configurarFotoDelUsuario($usuario);
                $usuario = $this->hidratarCuentas($usuario, true);
            }

            return $usuario;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    public function hidratarCuentas(Usuario $usuario, $incluirAppsInactivas = false)
    {
        try {
            $aplicaciones = $this->getArregloAplicaciones();
            if ($incluirAppsInactivas) {
                $aplicaciones = $this->getArregloAplicacionesConInactivas();
            }
			$this->hidratarGruposUsuarios(array($usuario), $this->getGrupos()?? array());
            $this->backendUsuarios->hidratarAplicacionesAsociadasUsuarios(array($usuario), $aplicaciones);
            $this->backendUsuarios->hidratarAtributosUsuarios(array($usuario), $this->getAtributosAdicionalesUsuario());

            return $usuario;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $uid
     *
     * @return bool
     * @throws Error
     */
    public function existeUsuario($uid)
    {
        $usuario = $this->getUsuario($uid);

        return isset($usuario);
    }

    /**
     * @param string $uniqueIdentifier
     *
     * @return bool
     * @throws Error
     */
    public function existeUsuarioByUniqueIdentifier($uniqueIdentifier)
    {
        $usuario = $this->getUsuarioByUniqueIdentifier($uniqueIdentifier);

        return isset($usuario);
    }

    /**
     * @param string $login
     *
     * @return bool
     * @throws Error
     */
    public function existeUsuarioByLogin($login)
    {
        $usuario = $this->getUsuarioByLogin($login);

        return isset($usuario);
    }

    /**
     * Evalua si $uid es un usario administrador.
     *
     * @param string $uid el uid de usuario
     *
     * @return bool
     */
    public function esUsuarioAdministrador($uid)
    {
        return $this->backendUsuarios->esUsuarioAdministrador($uid);
    }

    public function getPrefixBaseDNUserFrom($uid)
    {
        return $this->backendUsuarios->getPrefixBaseDNUserFrom($uid);
    }

    /**
     * @return string
     */
    public function getUrlFotoUsuarioGenerico()
    {
        $nombre_archivo = self::getHashFotoUsuarioGenerico();

        return $this->configuracion->getUrlRecursoBase().$this->configuracion->getPathRecursoUsuarios().'/'.$nombre_archivo;
    }

    /**
     * @param null $salt
     *
     * @return string
     */
    public static function getHashFotoUsuarioGenerico()
    {
        return 'usuario_generico.png';
    }

    /**
     * @param $uid
     *
     * @return array
     */
    public function getDetallesFoto($uid)
    {
        $salt = $this->configuracion->getSalt();
        $datos = array();
        $nombre_archivo = md5($salt.$uid.$salt).'.png';
        $datos['jpegPhoto'] = $this->configuracion->getPathRecursoUsuarios().'/'.$nombre_archivo;

        $datos['path'] = $this->configuracion->getPathImgUsuarios().'/'.$nombre_archivo;
        $datos['jpegPhotoUrl'] = $this->configuracion->getUrlRecursoBase().$datos['jpegPhoto'];

        return $datos;
    }

	public function checkFotoSize($bytes)
	{
		$size = $this->configuracion->getParametroGeneral('images.max_size_usuario');
		if (strlen($bytes) > $size) {
			throw new Error('El tamaño de la imagen es superior a lo permitido', 400);
		}
	}

    /**
     *
     * @param Usuario $usuario
     * @return Usuario
     */
    public function configurarFotoDelUsuario(Usuario $usuario)
    {
        $fotoUrl = $usuario->getJpegPhoto();
        if (isset($fotoUrl) && !empty($fotoUrl)) {
            $jpegPhotoUrl = $this->configuracion->getUrlRecursoBase().$fotoUrl;
        } else {
            $jpegPhotoUrl = $this->getUrlFotoUsuarioGenerico();
        }
        $usuario->setJpegPhotoUrl($jpegPhotoUrl);

        return $usuario;
    }

    /**
     * Agrega un usuario.
     *
     * @param Usuario $usuario
     * @param string  $prefixBaseDN
     *
     * @return string uid del usuario agregado
     *
     * @throws Error
     */
    public function agregarUsuario(Usuario $usuario, $prefixBaseDN = null)
    {
        try {
            if (! Uuid::isValid($usuario->getUid())) {
                throw new Error('El atributo uid no es válido', 406);
            }
            if (! $usuario->getUniqueIdentifier()) {
                throw new Error('El atributo uniqueIdentifier no es válido', 406);
            }

            // -- Cambio UNQ: --
            $usuario->setUid($usuario->getUniqueIdentifier());
            // -----------------

            if ($this->existeUsuario($usuario->getUid())) {
                throw new Error("El usuario uid={$usuario->getUid()} ya existe en el sistema.", 406);
            }

            if ($this->existeUsuarioByLogin($usuario->getLogin())) {
                throw new Error("El usuario login={$usuario->getLogin()} ya existe en el sistema.", 406);
            }

            if ($this->existeUsuarioByUniqueIdentifier($usuario->getUniqueIdentifier())) {
                throw new Error("El usuario uniqueIdentifier={$usuario->getUniqueIdentifier()} ya existe en el sistema.", 406);
            }

            $permiteMailsExistentes = $this->configuracion->permiteAgregarUsuariosConMailsExistentes();
            if (! $permiteMailsExistentes) {
                if ($this->backendUsuarios->existeUsuarioConMail($usuario->getMail())) {
                    throw new Error("Ya existe un usuario con mail {$usuario->getMail()}.", 406);
                }
            }

            $commonName = $usuario->getCn();
            if (!isset($commonName) || empty($commonName)) {
                $usuario->setCn($usuario->getGivenName().' '.$usuario->getSn());
            }

            $algoritmo = $this->configuracion->getPasswordAlgoritmo();

            $bloqueado = $usuario->getBloqueado();
            if (!isset($bloqueado)) {
                $usuario->setBloqueado('0');
            }

            $passwordPlano = $usuario->getPasswordPlano();
            if (isset($passwordPlano)) {
                if ($this->passwordValido($passwordPlano)) {
                    if ($algoritmo == 'plano') {
                        $usuario->setUserPassword($passwordPlano);
                    } else {
                        $usuario->setUserPassword($this->araiVarios->passwordHash($passwordPlano, $algoritmo), $algoritmo);
                    }
                } else {
                    throw new Error('Debe indicarse un password válido.');
                }
            }
            $insertado = $this->backendUsuarios->agregarUsuario($usuario, $prefixBaseDN);
            $this->actualizarListaPasswordsUsados($usuario);
            $this->actualizarVtoPassword($usuario);

            return $insertado;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Bloquea una cuenta de usuario especificada
     * @param $uid
     * @throws Error
     */
    public function bloquearUsuario($uid)
    {
        try {
            $usuario = $this->getUsuario($uid);
            if (!isset($usuario)) {
                throw new Error("El usuario '$uid' no existe");
            }
            $usuario->setBloqueado('1');
            $this->backendUsuarios->actualizarUsuario($usuario);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $uid
     * @param $groupCN
     *
     * @return bool
     */
    public function usuarioPerteneceAGrupo($uid, $groupCN)
    {
        return $this->backendUsuarios->usuarioPerteneceAGrupo($uid, $groupCN);
    }

    /**
     * @return array|mixed
     */
    public function getGrupos(UsuarioFiltro $filtro = null)
    {
        return $this->backendUsuarios->getGrupos($filtro);
    }

    public function getCantidadGrupos(UsuarioFiltro $filtro = null)
    {
        return $this->backendUsuarios->getCantidadGrupos($filtro);
    }

    /**
     * @param string        $cn
     * @param UsuarioFiltro $filtro
     *
     * @return Grupo
     *
     * @throws Error
     */
    public function getGrupo($cn, UsuarioFiltro $filtro = null)
    {
        try {
            return $this->backendUsuarios->getGrupo($cn, $filtro);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $cn
     *
     * @return bool
     */
    public function existeGrupo($cn)
    {
        $grupo = $this->getGrupo($cn);
        if (isset($grupo)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $cn
     *
     * @return mixed
     *
     * @throws Error
     */
    public function eliminarGrupo($cn)
    {
        try {
            return $this->backendUsuarios->eliminarGrupo($cn);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Agrega un grupo.
     *
     * @param Grupo $grupo
     *
     * @return string cn del grupo agregado
     *
     * @throws Error
     */
    public function agregarGrupo(Grupo $grupo)
    {
        try {
            if ($this->existeGrupo($grupo->getCn())) {
                throw new Error("El grupo {$grupo->getCn()} ya existe en el sistema.");
            }

            return $this->backendUsuarios->agregarGrupo($grupo);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Grupo         $grupo
     * @param UsuarioFiltro $usuarioFiltro
     *
     * @return
     *
     * @throws Error
     */
    public function actualizarGrupo(Grupo $grupo, UsuarioFiltro $usuarioFiltro = null)
    {
        try {
            if ($this->existeGrupo($grupo->getCn())) {
                return $this->backendUsuarios->actualizarGrupo($grupo, $usuarioFiltro);
            } else {
                throw new Error('No existe el grupo que desea actualizar.');
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Grupo $grupo
     * @param array $miembros uid de miembros a agregar
     *
     * @return bool
     *
     * @throws Error
     */
    public function agregarMiembrosGrupo(Grupo $grupo, $miembros)
    {
        return $this->backendUsuarios->agregarMiembrosGrupo($grupo, $miembros);
    }

    /**
     * @param Grupo $grupo
     * @param array $miembros uid de miembros a eliminar del grupo
     *
     * @return bool
     *
     * @throws Error
     */
    public function eliminarMiembrosGrupo(Grupo $grupo, $miembros)
    {
        return $this->backendUsuarios->eliminarMiembrosGrupo($grupo, $miembros);
    }

    public function agregarUsuarioGrupo($uid, $groupCN)
    {
        $this->backendUsuarios->agregarUsuarioGrupo($uid, $groupCN);
    }

    /**
     * Retorna el listado completo (nombre/apellido) de los
     * usuarios que son miembros de un grupo dado.
     *
     * @param string $grupo nombre o cn del grupo
     *
     * @return array
     */
    public function getUsuariosMiembrosGrupo($grupo)
    {
        $usuarios = array();

        $datos = $this->backendUsuarios->getMiembrosGrupo($grupo);

        if (isset($datos[0]['member'])) {
            $datos = $datos[0]['member'];

            $idUsuarios = array();
            foreach ($datos as $dato) {
                // extraigo el uid unicamente
                preg_match("/^uid=([^,]*\.*)/", $dato, $matches);
                if (count($matches) == 2) {
                    $idUsuarios[] = $matches[1];
                }
            }

            // para cada uid, buscar nombre/apellido
            $usuarios = $this->hidratarMiembrosGrupo($idUsuarios);
        }

        return $usuarios;
    }

    public function hidratarMiembrosGrupo($idUsuarios)
    {
        return $this->backendUsuarios->hidratarMiembrosGrupo($idUsuarios);
    }

    /**
     * @param Usuario $usuario
     *
     * @return mixed
     *
     * @throws Error
     */
    public function eliminarUsuario(Usuario $usuario)
    {
        return $this->eliminarUsuarioByUid($usuario->getUid());
    }

    public function eliminarUsuarioByUid($uid)
    {
        try {
            $this->backendLoggers->eliminarLogsSesionesAccesosUsuario($uid);
            $this->backendLoggers->eliminarPasswordsUsadosUsuario($uid);

            // eliminar las cuentas del usuario
            /* @var CuentaFiltro $filtro */
            $filtro = new CuentaFiltro();
            $filtro->agregarCampo('uid', Filtro::ES_IGUAL_A, array($uid));
            /* @var Cuenta[] $cuentas */
            $cuentas = $this->getCuentasAplicaciones($filtro);
            /* @var Cuenta $cuenta */
            foreach ($cuentas as $cuenta) {
                $this->backendUsuarios->eliminarCuentaUsuario($cuenta);
            }

            return $this->backendUsuarios->eliminarUsuario($uid);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario       $usuario
     * @param UsuarioFiltro $usuarioFiltro
     * @param array         $datosParaEliminar opcional, podemos detallar los campos que se eliminaron.
     *
     *
     * @return
     *
     * @throws Error
     */
    public function actualizarUsuario(Usuario $usuario, UsuarioFiltro $usuarioFiltro = null, $datosParaEliminar = null)
    {
        try {
            if ($this->existeUsuario($usuario->getUid())) {
                $actualizarPasswordUsados = false;
                $passwordPlano = $usuario->getPasswordPlano();
                if (isset($passwordPlano)) {
                    $passwordActualPlano = $usuario->getPasswordActualPlano();
                    if (isset($passwordActualPlano)) {
                        $this->passwordActualValido($usuario->getUid(), $passwordActualPlano);
                    }

                    //Antes de encriptar el password verifico que no se este usando un password anterior
                    $this->passwordNoUtilizado($usuario->getUid(), $passwordPlano);

                    // Fuerzo a que se utilize el algoritmo por defecto (esto migra las claves)
                    $algoritmo = $this->configuracion->getPasswordAlgoritmo();

                    $this->passwordValido($passwordPlano);

                    if ($algoritmo == 'plano') {
                        $usuario->setUserPassword($passwordPlano);
                    } else {
                        $usuario->setUserPassword($this->araiVarios->passwordHash($passwordPlano, $algoritmo), $algoritmo);
                    }
                    $actualizarPasswordUsados = true;
                }
                $actualizacion = $this->backendUsuarios->actualizarUsuario($usuario, $usuarioFiltro, $datosParaEliminar);
                if ($actualizarPasswordUsados) {
                    $this->actualizarListaPasswordsUsados($usuario);
                }

                return $actualizacion;
            } else {
                throw new Error('No existe el usuario que desea actualizar.');
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Agrega o cambia la foto del usuario
     * Recibe el usuario y el file content de la imagen
     * Retorna la url de la foto del usuario y null en caso de error.
     *
     * @param Usuario $usuario
     * @param $fileContent
     *
     * @throws Error
     */
    public function actualizarFotoUsuario(Usuario $usuario, $fileContent)
    {
        try {
            if (isset($fileContent) && trim($fileContent) == '') {
                throw new Error('La imagen esta vacia');
            }
            if ($this->existeUsuario($usuario->getUid())) {
                // Obtengo los datos de la foto
                $informacionFoto = $this->getDetallesFoto($usuario->getUid());

                // guardo la imagen en el idp
                if (@file_put_contents($informacionFoto['path'], $fileContent)) {
                    $usuario->setJpegPhoto($informacionFoto['jpegPhoto']);
                    $this->backendUsuarios->actualizarUsuario($usuario);
                    return $informacionFoto['jpegPhotoUrl'];
                } else {
                    file_put_contents('/tmp/test.log', var_export($informacionFoto, true));
                    throw new Error('No tiene permisos para modificar el archivo '.$informacionFoto['path'], 500);
                }
            }
            // retorna null en caso de no poder crear/agregar la foto del usuario
            return null;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Mueve un usuario, cambia el uid y las cuentas relacionadas
     *
     * @param Usuario $usuario
     * @param $uidNuevo
     * @throws Error
     */
    public function moverUsuario(Usuario $usuario, $uidNuevo)
    {
        try {
            $this->backendUsuarios->moverCuentasUsuario($usuario, $uidNuevo);

            $this->backendUsuarios->moverUsuario($usuario, $uidNuevo);

            $this->moverUsuarioPoliticasAcceso($usuario, $uidNuevo);

            $this->moverUsuarioLogsAccesos($usuario, $uidNuevo);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    public function moverUsuarioPoliticasAcceso(Usuario $usuario, $uidNuevo)
    {
        $tablas = [
            'politicas_password',
            'politicas_acceso',
            'passwords_cambios',
            'passwords_usados_usuarios'
        ];

        foreach ($tablas as $tabla) {
            $this->backendPolicies->moverUsuario($tabla, $usuario->getUid(), $uidNuevo);
        }
    }

    public function moverUsuarioLogsAccesos(Usuario $usuario, $uidNuevo)
    {
        $tablas = [
            'logs_usuarios_sesiones',
            'logs_usuarios_intentos_acceso',
        ];

        foreach ($tablas as $tabla) {
            $this->backendPolicies->moverUsuario($tabla, $usuario->getUid(), $uidNuevo);
        }
    }

    /**
     * @param $apellidos
     * @param $nombres
     *
     * @return null|string
     * @throws Error
     */
    public function generateUniqueIdentifier($apellidos, $nombres)
    {
        $search = array("'", '-', '+', '.', ',', '(', ')');
        $replace = '';
        $nombres = str_replace($search, $replace, $this->araiVarios->quitarCaracteresEspeciales(strtolower($nombres)));
        $apellidos = str_replace($search, $replace, $this->araiVarios->quitarCaracteresEspeciales(strtolower($apellidos)));

        $formato = $this->configuracion->getFormatoUid();

        $formatosDisponibles = [
            'simple' => \SIU\AraiUidGenerator\InicialNombreApellido::class,
            'nombre.apellido' => \SIU\AraiUidGenerator\NombrePuntoApellido::class,
        ];

        if (!isset($formatosDisponibles[$formato])) {
            throw new Error("El formato de UID '$formato' es inválido");
        }

        $claseUidGenerador = $formatosDisponibles[$formato];

        $uidGenerator = new $claseUidGenerador($this, $apellidos, $nombres);

        return $uidGenerator->generateUniqueIdentifier();
    }

    public function generateUid()
    {
        return Uuid::uuid4()->__toString();
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomPasswordPlano($length = 15)
    {
        $enc = false;
        $maxInterntos = 200;
        $i = 0;
        $passwordPlano = 123456;
        while ($i < $maxInterntos && !$enc) {
            try {
                $passwordPlano = $this->araiVarios->getRamdomPasswordPlano($length);
                if ($this->passwordValido($passwordPlano)) {
                    $enc = true;
                }
            } catch (Error $e) {
            }
            ++$i;
        }

        return $passwordPlano;
    }

    /**
     * @param string $loginIdentifier
     * @param string $passwordPlano
     *
     * @return Usuario
     *
     * @throws Error
     */
    public function autenticar($loginIdentifier, $passwordPlano)
    {
        $filtro = new UsuarioFiltro();

        /* @var Usuario $usuario */
        $usuario = $this->getUsuarioByLogin($loginIdentifier, $filtro);

        if (!isset($usuario)) {
            throw new Error("El usuario 'login=$loginIdentifier' no existe");
        }

        if ($usuario->getBloqueado()) {
            throw new Error("El usuario 'login=$loginIdentifier' esta bloqueado");
        }

        $arrPasswordAlgoritmo = $usuario->separarPasswordAlgoritmoEncriptado();
        if (!$this->araiVarios->passwordCheck($arrPasswordAlgoritmo['password'], $arrPasswordAlgoritmo['algoritmo'], $passwordPlano)) {
            throw new Error("El usuario 'login=$loginIdentifier' ingreso un password incorrecto.");
        }

        return $usuario;
    }

    /**
     * @param $uid
     * @param $password_actual
     *
     * @return bool
     *
     * @throws Error
     */
    public function passwordActualValido($uid, $password_actual)
    {
        /* @var Usuario $usuario */
        $usuario = $this->getUsuario($uid);
        $arrPasswordAlgoritmo = $usuario->separarPasswordAlgoritmoEncriptado();
        if (!$this->araiVarios->passwordCheck($arrPasswordAlgoritmo['password'], $arrPasswordAlgoritmo['algoritmo'], $password_actual)) {
            throw new Error('La Contraseña Actual no coincide con la almacenada en el sistema.', 405);
        }

        return true;
    }

    /**
     * @param $password
     *
     * @return bool
     *
     * @throws Error
     */
    public function passwordValido($password)
    {
        $passwordLargo = $this->configuracion->getPasswordLargo();
        if (strlen($password) < $passwordLargo || !preg_match($this->getExpRegPwd($passwordLargo), $password)) {
            throw new Error($this->configuracion->getPasswordInformacion(), 405);
        }

        return true;
    }

    /**
     * @param $uid
     * @param $passwordPlano
     * @param null $noRepetidas
     *
     * @return bool
     *
     * @throws Error
     */
    public function passwordNoUtilizado($uid, $passwordPlano, $noRepetidas = null)
    {
        /* @var Usuario $usuario */
        $usuario = $this->getUsuario($uid);

        /* @var UsuarioFiltro $filtro */
        $filtro = new UsuarioFiltro();
        $filtro->agregarCampo('uid', UsuarioFiltro::ES_IGUAL_A, array($usuario->getUid()));
        $filtro->agregarCampoOrdenable('fecha');
        $filtro->setOrder('-fecha');
        if (isset($noRepetidas)) {
            $filtro->setlimit($noRepetidas);
        }

        $passwords = $this->getListaPasswordUsados($filtro);

        if (!empty($passwords)) {
            /* @var Usuario $usuarioAux */
            $usuarioAux = new Usuario();
            foreach ($passwords as $password) {
                $usuarioAux->setUserPassword($password['userpassword']);
                $arrPasswordAlgoritmo = $usuarioAux->separarPasswordAlgoritmoEncriptado();
                if ($this->araiVarios->passwordCheck($arrPasswordAlgoritmo['password'], $arrPasswordAlgoritmo['algoritmo'], $passwordPlano)) {
                    throw new Error('El password fue utilizado anteriormente, por favor seleccione uno nuevo');
                }
            }
        }

        return true;
    }

    public function verificarPeriodoMinimoCambio($uid, $periodo)
    {
        /* @var Usuario $usuario */
        $usuario = $this->getUsuario($uid);

        /* @var LoggerFiltro $filtro */
        $filtro = new LoggerFiltro();
        $filtro->agregarCampo('uid', LoggerFiltro::ES_IGUAL_A, array($usuario->getUid()));
        $filtro->agregarCampoOrdenable('fecha');
        $filtro->setOrder('-fecha');
        if (isset($periodo)) {
            $filtro->setPeriodo($periodo);
        }

        $passwords = $this->getListaPasswordUsados($filtro);

        return empty($passwords);
    }

    /**
     * @ignore
     *
     * @param $largoMinimo
     *
     * @return string
     */
    public function getExpRegPwd($largoMinimo)
    {
        return '/^(?!.*(.)\1{1})((?=.*[^\w\d\s])(?=.*[a-zA-Z])|(?=.*[0-9])(?=.*[a-zA-Z])).{'.$largoMinimo.',}$/';
    }

    public function getCuentasUsuario(Usuario $usuario)
    {
        try {
            return $usuario->getCuentas();
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario $usuario
     * @param Cuenta  $cuenta
     *
     * @return
     *
     * @throws Error
     */
    public function agregarCuentaUsuario(Usuario $usuario, Cuenta $cuenta)
    {
        try {
            $appUniqueId = $cuenta->getAppUniqueId();
            if (isset($appUniqueId)) {
                /* @var Cuenta $cuentaDefecto */
                $cuentaDefecto = $usuario->getCuentaDefectoAplicacion($appUniqueId);
                if (!$cuentaDefecto) {
                    $cuenta->setDefecto('1');
                } else {
                    $cuenta->setDefecto('0');
                }
            } else {
                $cuenta->setDefecto('0');
            }

            // buscamos el prefijo del DN (no importa donde esté alojado el usuario, ahi va las cuentas)
            $prefixBaseDN = $this->getPrefixBaseDNUserFrom($usuario->getUid());
            $resultado = $this->backendUsuarios->agregarCuentaUsuario($cuenta, $prefixBaseDN);
            $this->backendUsuarios->hidratarAplicacionesAsociadasUsuarios(array($usuario), $this->getArregloAplicaciones());

            return $resultado;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario           $usuario
     * @param Cuenta            $cuenta
     * @param CuentaFiltro|null $filtro
     *
     * @return mixed
     *
     * @throws Error
     */
    public function actualizarCuentaUsuario(Usuario $usuario, Cuenta $cuenta, CuentaFiltro $filtro = null)
    {
        try {
            /* @var Cuenta $cuentaAux */
            $cuentaAux = new Cuenta();
            $cuentaAux->setAppUniqueId($cuenta->getAppUniqueId());
            $cuentaAux->setCuenta($cuenta->getCuenta());
            $cuentaAux->setUid($cuenta->getUid());
            $cuentaAux->setEtiqueta($cuenta->getEtiqueta());
            $cuentaAux->setAppUnlink($cuenta->getAppUnlink());
            $cuentaAux->setVersion($cuenta->getVersion());
            $resultado = $this->backendUsuarios->actualizarCuentaUsuario($cuentaAux, $filtro);
            $this->backendUsuarios->hidratarAplicacionesAsociadasUsuarios(array($usuario), $this->getArregloAplicaciones());

            return $resultado;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario $usuario
     * @param Cuenta  $cuenta
     *
     * @return mixed
     *
     * @throws Error
     */
    public function actualizarCuentaDefectoUsuario(Usuario $usuario, Cuenta $cuenta)
    {
        try {
            $this->actualizarNoDefectoOtrasCuentas($cuenta);
            /* @var CuentaFiltro $filtro */
            $filtro = new CuentaFiltro();
            $filtro->agregarCampo('cuenta', Filtro::ES_IGUAL_A, array($cuenta->getCuenta()));
            $filtro->agregarCampo('appUniqueId', Filtro::ES_IGUAL_A, array($cuenta->getAppUniqueId()));
            $filtro->setCuentasVincualdas(true);

            /* @var Cuenta $cuentaAux */
            $cuentaAux = new Cuenta();
            $cuentaAux->setUid($cuenta->getUid());
            $cuentaAux->setAppUniqueId($cuenta->getAppUniqueId());
            $cuentaAux->setDefecto('1');
            $resultado = $this->backendUsuarios->actualizarCuentaUsuario($cuentaAux, $filtro);
            $this->backendUsuarios->hidratarAplicacionesAsociadasUsuarios(array($usuario), $this->getArregloAplicaciones());

            return $resultado;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario $usuario
     * @param $appUniqueId
     * @param $cuentaNombre
     *
     * @return bool
     *
     * @throws Error
     */
    public function eliminarCuentaUsuario(Usuario $usuario, $appUniqueId, $cuentaNombre)
    {
        try {
            $cuenta = $usuario->getCuenta($appUniqueId, $cuentaNombre);
            $this->actualizarDefectoOtraCuenta($usuario, $cuenta);
            $this->backendUsuarios->eliminarCuentaUsuario($cuenta);
            $this->backendUsuarios->hidratarAplicacionesAsociadasUsuarios(array($usuario), $this->getArregloAplicaciones());

            return true;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario $usuario
     * @param $appUnlink
     * @param $cuentaNombre
     *
     * @return bool
     *
     * @throws Error
     */
    public function eliminarCuentaDesvinculadaUsuario(Usuario $usuario, $appUnlink, $cuentaNombre)
    {
        try {
            /* @var Cuenta $cuenta */
            $cuenta = new Cuenta();
            $cuenta->setUid($usuario->getUid());
            $cuenta->setCuenta($cuentaNombre);
            $cuenta->setAppUnlink($appUnlink);

            $this->backendUsuarios->eliminarCuentaUsuario($cuenta);
            $this->backendUsuarios->hidratarAplicacionesAsociadasUsuarios(array($usuario), $this->getArregloAplicaciones());

            return true;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param CuentaFiltro|null $filtro
     *
     * @return Cuentas[]
     *
     * @throws Error
     */
    public function getCuentasAplicaciones(CuentaFiltro $filtro = null)
    {
        try {
            return $this->backendUsuarios->getCuentasAplicaciones($filtro, $this->getArregloAplicaciones());
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param CuentaFiltro|null $filtro
     *
     * @return Cuentas[]
     *
     * @throws Error
     */
    public function getCuentasAplicacionesConInactivas(CuentaFiltro $filtro = null)
    {
        try {
            return $this->backendUsuarios->getCuentasAplicaciones($filtro, $this->getArregloAplicacionesConInactivas());
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param CuentaFiltro|null $filtro
     *
     * @return Cuenta
     *
     * @throws Error
     */
    public function getCuentaAplicacion(CuentaFiltro $filtro = null)
    {
        /* @var Cuenta[] $cuentas */
        $cuentas = $this->getCuentasAplicaciones($filtro);
        if (!empty($cuentas)) {
            return current($cuentas);
        } else {
            return null;
        }
    }

    /**
     * @param CuentaFiltro|null $filtro
     *
     * @return Cuenta
     *
     * @throws Error
     */
    public function getCuentaAplicacionConInactivas(CuentaFiltro $filtro = null)
    {
        /* @var Cuenta[] $cuentas */
        $cuentas = $this->getCuentasAplicacionesConInactivas($filtro);
        if (!empty($cuentas)) {
            return current($cuentas);
        } else {
            return null;
        }
    }

    /**
     * @return mixed
     *
     * @throws Error
     */
    public function getAtributosAdicionalesUsuario()
    {
        try {
            $atributosAdicionales = $this->configuracion->getAtributosAdicionales();

            return $atributosAdicionales;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario  $usuario
     * @param Atributo $atributo
     *
     * @return mixed
     *
     * @throws Error
     */
    public function agregarAtributoUsuario(Usuario $usuario, Atributo $atributo)
    {
        try {
            $resultado = $this->backendUsuarios->agregarAtributoUsuario($atributo);
            $this->backendUsuarios->hidratarAtributosUsuarios(array($usuario), $this->getAtributosAdicionalesUsuario());

            return $resultado;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario  $usuario
     * @param Atributo $atributo
     *
     * @return mixed
     *
     * @throws Error
     */
    public function actualizarAtributoUsuario(Usuario $usuario, Atributo $atributo)
    {
        try {
            $resultado = $this->backendUsuarios->actualizarAtributoUsuario($atributo);
            $this->backendUsuarios->hidratarAtributosUsuarios(array($usuario), $this->getAtributosAdicionalesUsuario());

            return $resultado;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario $usuario
     * @param $claveAtributo
     *
     * @return bool
     *
     * @throws Error
     */
    public function eliminarAtributoUsuario(Usuario $usuario, $claveAtributo)
    {
        try {
            $atributo = $usuario->getAtributo($claveAtributo);
            if (isset($atributo)) {
                $this->backendUsuarios->eliminarAtributoUsuario($atributo);
                $this->backendUsuarios->hidratarAtributosUsuarios(array($usuario), $this->getAtributosAdicionalesUsuario());
            }

            return true;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Registra el inicio de sesión de un usuario en el IDP
     *
     * @nota Es factible que exista más de un inicio de sesión para diferentes $uid
     *       y un mismo identificador $session, en diferentes momentos. Esto es así
     *       ya que este $session es almacenado como cookie en el navegador y
     *       reutilizado para identificar el siguiente inicio de sesión cuando se
     *       hace en el mismo navegador.
     *
     * @param string $uid      identificador del usuario que inicia sesion
     * @param string $session  ID de sesion PHP
     * @param string $token    token de sesion generado para el usuario
     * @param string $ip       direccion IP desde donde accede el usuario
     * @param string $tipo     Tipo de conector (saml, oidc)
     * @param string $fuente   Identificador de la App a la que se accede
     * @param string $etiqueta Nombre de la app a la que se accede
     *
     * @throws Error
     */
    public function registrarLogSesionInicio($uid, $session, $token, $ip, $tipo, $fuente, $etiqueta)
    {
        try {
            $this->backendLoggers->registrarLogSesionInicio($uid, $session, $token, $ip, $tipo, $fuente, $etiqueta);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Registra en una sesión existente, el acceso a una URL de un SP
     *
     * @param string $uid identificador del usuario
     * @param string $session ID de sesión PHP
     * @param string $url url del SP que se accede
     * @throws Error
     */
    public function registrarLogSesionAcceso($uid, $session, $url)
    {
        try {
            $this->backendLoggers->registrarLogSesionAcceso($uid, $session, $url);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Cierra una sesion actualmente abierta
     *
     * @nota Si bien es posible que $session se reutilizado, en todo momento PHP lo
     *       limita a una sóla sesión. Como tal, al momento de SLO utilizamos $session
     *       para efectivamente cerrar toda sesión para dicho ID.
     *
     * @param string $session ID de sesión PHP
     *
     * @throws Error
     */
    public function registrarLogSesionFin($session)
    {
        try {
            $this->backendLoggers->registrarLogSesionFin($session);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Registra un intento de acceso fallido de inicio de sesión
     *
     * @param $uid
     * @param $remoteIp
     * @param $observaciones
     * @throws Error
     */
    public function registrarLogIntentoSesion($uid, $remoteIp, $observaciones)
    {
        try {
            $this->backendLoggers->registrarLogIntentoSesion($uid, $remoteIp, $observaciones);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param UsuarioFiltro|null $filtro
     *
     * @return int
     *
     * @throws Error
     */
    public function getCantidadLogsSesionesAccesosUsuarios(UsuarioFiltro $filtro = null)
    {
        try {
            return $this->backendLoggers->getCantidadLogsSesionesAccesosUsuarios($filtro);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     *
     * @return json array
     *
     * @throws Error
     */
    public function getLogsAccesoHeatmap()
    {
        try {
            return $this->backendLoggers->getLogsAccesoHeatmap();
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param UsuarioFiltro|null $filtro
     *
     * @return mixed
     *
     * @throws Error
     */
    public function getLogsSesionesAccesosUsuarios(UsuarioFiltro $filtro = null)
    {
        try {
            return $this->backendLoggers->getLogsSesionesAccesosUsuarios($filtro);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $uid
     *
     * @return mixed
     *
     * @throws Error
     */
    public function getLogsSesionesAccesos($uid)
    {
        try {
            /* @var Usuario $usuario */
            $usuario = $this->getUsuario($uid);
            if (isset($usuario)) {
                return $this->backendLoggers->getLogsSesionesAccesos($usuario);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param UsuarioFiltro|null $filtro
     *
     * @return int
     *
     * @throws Error
     */
    public function getCantidadCambiosPassword(UsuarioFiltro $filtro = null)
    {
        try {
            return $this->backendPolicies->getCantidadCambiosPassword($filtro);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param UsuarioFiltro|null $filtro
     *
     * @return mixed
     *
     * @throws Error
     */
    public function getCambiosPassword(UsuarioFiltro $filtro = null)
    {
        try {
            return $this->backendPolicies->getCambiosPassword($filtro);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }


    /**
     * @param UsuarioFiltro $filtro
     *
     * @return mixed
     *
     * @throws Error
     */
    public function getListaPasswordUsados(UsuarioFiltro $filtro)
    {
        try {
            return $this->backendLoggers->getListaPasswordUsados($filtro);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Usuario $usuario
     *
     * @return bool
     *
     * @throws Error
     */
    public function actualizarListaPasswordsUsados(Usuario $usuario)
    {
        try {
            if ($this->existeUsuario($usuario->getUid())) {
                /* @var Usuario $usuarioBase */
                $usuarioBase = $this->getUsuario($usuario->getUid());
                $this->backendLoggers->agregarPasswordUsadoUsuario($usuarioBase);
            }

            return true;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Verifica si un usuario puede acceder a una app. Se usa desde el bloque
     * de login.
     *
     * @param string|Usuario $uid si es un string asume que es el identificador y
     * hace la busqueda. Sino asume que es un usuario y lo utiliza como tal.
     * Este comportamiento es xq esto se utiliza desde el authproc y no tiene sentido
     * en ese camino ejecutar la consulta de usuario 2 veces
     * @param string $appUniqueId identificador de la aplicacion
     * @return array [bool chequeo exitoso, string mensaje de error]
     *
     * @throws
     */
    public function verificarAcceso($usuario, $appUniqueId)
    {
        if (is_string($usuario)) {
            $usuario = $this->getUsuarioByUniqueIdentifier($usuario);
        }

        if (! $usuario instanceof Usuario) {
            throw new \Exception("Se esperaba una instancia de Usuario");
        }

        $app = $this->backendAplicaciones->getAplicacion($appUniqueId);
        if ($app === null) {
            $mensaje = "La aplicación '$appUniqueId' a la que intenta acceder no está registrada. Comuniquese con el administrador";
            return [ false, $mensaje ];
        }

        if ($app->esInactiva()) {
            $mensaje = 'La aplicación a la que intenta acceder está inactiva. Comuniquese con el administrador';
            return [ false, $mensaje ];
        }

        if (! $usuario->tieneCuentasEnAplicacion($appUniqueId)) {
            $mensaje = 'El usuario no tiene cuenta en esta aplicación. Comuniquese con el administrador';
            return [ false, $mensaje ];
        }

        if (! $app->getFiltrarPorGrupos()) {
            return [ true, ''];
        } else { // la app filtra por grupos
            if ($this->esUsuarioAdministrador($usuario->getUid())) {
                return [ true, ''];
            }

            // Posible optimización con memberof en ldap
            foreach ($app->getListaGruposFiltro() as $grupo) {
                if ($this->usuarioPerteneceAGrupo($usuario->getUid(), $grupo)) {
                    return [ true, ''];
                }
            }
        }

        return [ false, 'error no especificado' ];
    }

    /**
     * @param Usuario $usuario
     * @param null    $appUniqueId
     *
     * @return array
     */
    public function getAtributosSAMLUsuario(Usuario $usuario, $appUniqueId = null)
    {
        $datosAtributosUsuario = $usuario->getArrayDatosAtributos();
        $datosAtributosUsuario['userAccounts'] = '';
        $datosAtributosUsuario['defaultUserAccount'] = '';

        // Si se para un appUniqueId se genera el defaultUserAccount, userAccounts y se procesa el appLauncherData para quitar la aplicacion actual
        if (isset($appUniqueId)) {
            /* @var Aplicacion $aplicacion */
            $aplicacion = $this->backendAplicaciones->getAplicacion($appUniqueId);

            /* @var Cuenta[] $cuentas */
            $cuentas = $usuario->getCuentasAplicacion($aplicacion->getAppUniqueId());
            $datosAtributosUsuario['userAccounts'] = array();
            foreach ($cuentas as $cuenta) {
                $datosAtributosUsuario['userAccounts'][] = $cuenta->getCuenta();
            }

            /* @var Cuenta $cuenta */
            $cuenta = $usuario->getCuentaDefectoAplicacion($aplicacion->getAppUniqueId());
            if (isset($cuenta)) {
                $datosAtributosUsuario['defaultUserAccount'] = $cuenta->getCuenta();
            } elseif (! empty($datosAtributosUsuario['userAccounts'])) {        //Si no hay default, toma la primer cuenta disponible
                $datosAtributosUsuario['defaultUserAccount'] = current($datosAtributosUsuario['userAccounts']);
            } else {
                $datosAtributosUsuario['defaultUserAccount'] = '';
            }

            // Quito la aplicacion actual de los datos del appLauncher
            if (isset($datosAtributosUsuario['appLauncherData'])) {
                $aplicaciones = array();
                foreach ($datosAtributosUsuario['appLauncherData']['aplicaciones'] as $app) {
                    if ($app['appUniqueId'] != $appUniqueId && $app['ocultar_menu'] == false) {
                        $aplicaciones[] = $app;
                    }
                }
                $datosAtributosUsuario['appLauncherData']['aplicaciones'] = $aplicaciones;
            }
        }
        $datosAtributosUsuario['groups'] = [];
        $grupos = $this->backendUsuarios->getGruposUsuario($usuario->getUid());
        foreach ($grupos as $grupo) {
            $datosAtributosUsuario['groups'][] = $grupo['cn'][0];
        }

        //Caso particular, este dato ya viaja en formato JSON
        $datosAtributosUsuario['appLauncherData'] = json_encode($datosAtributosUsuario['appLauncherData'], JSON_THROW_ON_ERROR);

        // Extract attributes. We allow the resultset to consist of multiple rows. Attributes
        //which are present in more than one row will become multivalued. NULL values and
        //duplicate values will be skipped. All values will be converted to strings.

        $atributos = array();

        $photo = $this->base64Imagen($this->configuracion->getPathRecursoBase() . $datosAtributosUsuario['jpegPhoto']);

        $datosAtributosUsuario['jpegPhoto'] = $photo;
        $datosAtributosUsuario['jpegPhotoUrl'] = $datosAtributosUsuario['jpegPhotoUrl'];
        $arrAtributosSAMLUsuario = $this->backendUsuarios->getListaAtributosSAMLUsuario();
        foreach ($datosAtributosUsuario as $name => $value) {
            if (in_array($name, $arrAtributosSAMLUsuario)) {
                if (is_array($value)) {
                    foreach ($value as $componente) {
                        $atributos = $this->agregarAtributosSAML($atributos, $name, $componente);
                    }
                } else {
                    $atributos = $this->agregarAtributosSAML($atributos, $name, $value);
                }
            }
        }


        return $atributos;
    }

    private function base64Imagen($path)
    {
        if (! file_exists($path)) {
            return null;
        }

        return base64_encode(file_get_contents($path));
    }

    /**
     * Devuelve un arreglo con los pedidos de cambios de password existentes
     * @param string $uid
     * @return array
     * @throws Error
     */
    public function getArregloPedidosCambioPasswordVigentes($uid)
    {
        try {
            if ($this->existeUsuario($uid)) {
                $usuarioBase = $this->getUsuario($uid);               //Meto el pedido de cambio vigente adentro del usuario o lo dejo aca?
            }
            $filtro = new UsuarioFiltro();
            $filtro->agregarCampo('estado', Filtro::ES_MENOR_QUE, array(CambioPassword::ESTADO_USADO));
            $filtro->agregarCampo('uid', Filtro::ES_IGUAL_A, array($usuarioBase->getUid()));

            return  $this->backendPolicies->getCambiosPassword($filtro);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Anula los pedidos de cambio de password que el usuario tenga pendientes
     * @param string $uid
     * @throws Error
     */
    public function anularPedidosCambioPasswordPrevios($uid)
    {
        try {
            if ($this->existeUsuario($uid)) {
                $cambio = new CambioPassword();
                $cambio->setUid($uid);
                $cambio->setEstado(CambioPassword::ESTADO_INVALIDO);
                $this->actualizarPedidosCambioPassword($cambio);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Marca un pedido de cambio de password como usado
     * @param string $uid
     * @throws Error
     */
    public function usarPedidoCambioPassword($uid)
    {
        try {
            if ($this->existeUsuario($uid)) {
                $fecha = (new \DateTime('now'))->format('Y-m-d H:i:s');

                $cambio = new CambioPassword();
                $cambio->setUid($uid);
                $cambio->setEstado(CambioPassword::ESTADO_USADO);
                $cambio->setFechaCambio($fecha);
                $this->actualizarPedidosCambioPassword($cambio);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Actualiza los pedidos de cambio de password pendientes segun lo que indica el parametro
     * @param SIU\AraiUsuarios\Entities\CambioPassword $cambio
     * @throws Error
     */
    public function actualizarPedidosCambioPassword($cambio)
    {
        try {
            if ($this->existeUsuario($cambio->getUid())) {
                $filtro = new LoggerFiltro();
                $filtro->agregarCampo('estado', Filtro::ES_MENOR_QUE, array(CambioPassword::ESTADO_USADO));
                $filtro->agregarCampo('uid', Filtro::ES_IGUAL_A, array($cambio->getUid()));

                $this->backendPolicies->actualizarCambioPassword($cambio, $filtro);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Agrega un nuevo pedido de cambio de password segun lo que indica el parametro
     * @param SIU\AraiUsuarios\Entities\CambioPassword $cambio
     * @throws Error
     */
    public function agregarPedidoCambioPassword(CambioPassword $cambio)
    {
        try {
            if ($this->existeUsuario($cambio->getUid())) {
                $this->backendPolicies->agregarCambioPassword($cambio);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    public function getBackendPoliticas()
    {
        return $this->backendPolicies;
    }

    public function setVencimientoCuenta($uid, $fecha)
    {
        try {
            if ($this->existeUsuario($uid)) {
                $this->backendPolicies->setVencimientoCuenta($uid, $fecha);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    public function getVencimientoCuenta($uid)
    {
        try {
            if ($this->existeUsuario($uid)) {
                return  $this->backendPolicies->getVencimientoCuenta($uid);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    public function esCuentaExpirada($uid, $timestamp)
    {
        $result = false;
        try {
            if ($this->existeUsuario($uid)) {
                $vto = $this->backendPolicies->getVencimientoCuenta($uid);
                if (! is_null($vto)) {
                    $dtVto = \DateTime::createFromFormat('Y-m-d H:i', $vto);
                    $result =  ($dtVto !== false) && ($dtVto <= $timestamp);
                }
            }
            return $result;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    public function usarExpiracionCuenta($uid)
    {
        try {
            if ($this->existeUsuario($uid)) {
                return  $this->backendPolicies->setExpiracionUsada($uid);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    public function getUsuariosExpiracion($datetime)
    {
        try {
            $filtro = new UsuarioFiltro();
            $filtro->agregarCampo('vencimiento_cuenta_desde', Filtro::ES_MENOR_IGUAL_QUE, array($datetime->format('Y-m-d H:i')));
            $filtro->agregarCampo('usado', Filtro::ES_IGUAL_A, array(0));

            $rs = $this->getBackendPoliticas()->getVencimientosCuentas($filtro);
            return $rs;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Determina si el password del usuario esta vencido en el timestamp indicado
     * @param string$uid
     * @param string $timestamp
     * @return boolean
     * @throws Error
     */
    public function esPasswordVencido($uid, $timestamp)
    {
        $result = false;
        try {
            if ($this->existeUsuario($uid)) {
                $filtro = new UsuarioFiltro();
                $filtro->agregarCampo('uid', Filtro::ES_IGUAL_A, array($uid));
                $filtro->agregarCampo('usado', Filtro::ES_IGUAL_A, array(0));

                $vto = $this->backendPolicies->getVencimientoPassword($filtro);
                if (! is_null($vto)) {
                    $dtVto = \DateTime::createFromFormat('Y-m-d H:i:s', $vto);
                    $result =  ($dtVto !== false) && ($dtVto <= $timestamp);
                }
            }
            return $result;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Obtiene la fecha de vencimiento del password actual del usuario
     * @param string $uid
     * @return string
     * @throws Error
     */
    public function getVencimientoPassword($uid)
    {
        try {
            if ($this->existeUsuario($uid)) {
                $filtro = new UsuarioFiltro();
                $filtro->agregarCampo('uid', Filtro::ES_IGUAL_A, array($uid));
                $filtro->agregarCampo('usado', Filtro::ES_IGUAL_A, array(0));
                return  $this->backendPolicies->getVencimientoPassword($filtro);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Fija la fecha de vencimiento del password actual (e inutiliza pedidos anteriores)
     * @param string $uid
     * @param string $fecha
     * @throws Error
     */
    public function setVencimientoPassword($uid, $fecha)
    {
        //Agrega un registro a la fecha indicada para el vto del password
        try {
            if ($this->existeUsuario($uid)) {
                $this->usarVencimientoPassword($uid);
                $this->backendPolicies->setFechaPasswordVencimiento($uid, $fecha);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Marca una fecha de vencimiento de password como inutilizada
     * @param string $uid
     * @throws Error
     */
    public function usarVencimientoPassword($uid)
    {
        try {
            if ($this->existeUsuario($uid)) {
                $this->backendPolicies->setVencimientoPasswordUsado($uid);
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Agrega/actualiza la fecha de vencimiento de password de acuerdo al parametro
     * @param Usuario $usuario
     * @throws Error
     */
    public function actualizarVtoPassword(Usuario $usuario)
    {
        try {
            $dias = $this->configuracion->getParametroSeguridad('password_duracion_dias');
            if (! is_null($dias) && $dias != 0) {                                              //Inicializacion y/o renovacion de periodo
                $start = new \Datetime();
                $agregado = new \DateInterval("P{$dias}D");
                $fecha_vto = $start->add($agregado)->format('Y-m-d');
                $this->setVencimientoPassword($usuario->getUid(), $fecha_vto);
            } else {
                $this->usarVencimientoPassword($usuario->getUid());     //Caso de vencimiento unico.
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Retorna la lista de usuarios que no tienen fecha de vencimiento de pwd activa
     * @param int $rango cantidad de dias a evaluar
     * @return array
     * @throws Error
     */
    public function getUidUsuariosSinClaveVencimiento($rango)
    {
        try {
            $con_vencimiento_vigente = $this->backendPolicies->getUsuariosVencimientosVigentes($rango);
            $vigentes = aplanar_matriz($con_vencimiento_vigente, 'uid');

            $filtro = new UsuarioFiltro();
            $filtro->agregarCampo('bloqueado', UsuarioFiltro::ES_IGUAL_A, array(0));
            $usuarios = $this->getUsuarios($filtro);
            $resultado = array();
            foreach ($usuarios as $usr) {
                if (! in_array($usr->getUniqueIdentifier(), $vigentes)) {
                    $resultado[] = $usr->getUid();
                }
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
        return $resultado;
    }

    /**
     * Retorna la fecha del ultimo cambio de password del usuario
     * @param string $uid
     * @return string
     * @throws Error
     */
    public function getFechaUltimoCambioPassword($uid)
    {
        try {
            $fecha = $this->backendPolicies->getFechaUltimoCambioPassword($uid);
            if (false === $fecha) {
                return null;
            }
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
        return $fecha;
    }

    /**
     * Genera un token de sesión tipo UUID v4
     *
     * @param Usuario $usuario
     * @return string el token de sesion
     */
    public function generarTokenDeSesion(Usuario $usuario)
    {
        $uuid = Uuid::uuid4();

        return $uuid->toString();
    }

    /**
     * Recupera el token de sesión asociado a un usuario e ID de sesión PHP
     *
     * @param  string $uid     el identificador de usuario para el cual recuperar el token
     * @param  strin  $session la sesión de usuario en la que se generó el token
     * @return string          token de sesión
     * @throws Error
     */
    public function recuperarTokenDeSesion($uid, $session)
    {
        try {
            return $this->backendLoggers->recuperarTokenDeSesion($uid, $session);
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Valida y retorna datos de sesión asociados al token
     *
     * @param string $token el token de sesión a validar
     * @param integer $duration la duración del token
     * @return mixed         retorna los datos de sesión asociados al token de sesión. False si no es válido
     * @throws Error
     */
    public function esTokenDeSesionValido($token, $duration)
    {
        try {
            $session = $this->backendLoggers->getRegistroDeSesion($token);

            // tiene fecha_fin, SLO o manual
            if (!empty($session['fecha_fin'])) {
                return false;
            }

            $fechaInicio = \DateTime::createFromFormat('Y-m-d H:i:s', $session['fecha_inicio']);
            $actual = new \DateTime('now', new \DateTimeZone($fechaInicio->getTimezone()->getName()));

            $fechaVencimiento = $fechaInicio->getTimestamp() + $duration;
            $fechaActual = $actual->getTimestamp();

            // esta vencido
            if ($fechaVencimiento < $fechaActual) {
                return false;
            }

            return $session;
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    /*********************************************************************************
    ******************** METODOS PRIVADOS ********************************************
    *********************************************************************************/

    /**
     * @param Usuario $usuario
     * @param Cuenta  $cuenta
     *
     * @return bool
     */
    private function actualizarDefectoOtraCuenta(Usuario $usuario, Cuenta $cuenta)
    {
        if ($cuenta->getDefecto()) { // seteo como defecto otra cuenta del usuario para la aplicacion
            /* @ var Cuentas[] $cuentas */
            $cuentas = $usuario->getCuentasAplicacion($cuenta->getAppUniqueId());

            // busco otra cuenta en la aplicaicon
            $enc = false;
            $i = 0;
            $cantidad = count($cuentas);
            while ($i < $cantidad && !$enc) {
                if ($cuentas[$i]->getCuenta() != $cuenta->getCuenta()) {
                    $enc = true;
                } else {
                    ++$i;
                }
            }
            if ($enc) {
                /* @var CuentaFiltro $filtro */
                $filtro = new CuentaFiltro();
                $filtro->agregarCampo('cuenta', Filtro::ES_IGUAL_A, array($cuentas[$i]->getCuenta()));
                $filtro->agregarCampo('appUniqueId', Filtro::ES_IGUAL_A, array($cuentas[$i]->getAppUniqueId()));
                $filtro->setCuentasVincualdas(true);

                /* @var Cuenta $cuentaAux */
                $cuentaAux = new Cuenta();
                $cuentaAux->setUid($cuenta->getUid());
                $cuentaAux->setAppUniqueId($cuenta->getAppUniqueId());
                $cuentaAux->setDefecto('1');

                return $this->backendUsuarios->actualizarCuentaUsuario($cuentaAux, $filtro);
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * @param Cuenta $cuenta
     *
     * @return mixed
     */
    private function actualizarNoDefectoOtrasCuentas(Cuenta $cuenta)
    {
        /* @var CuentaFiltro $filtro */
        $filtro = new CuentaFiltro();
        $filtro->agregarCampo('cuenta', Filtro::ES_DISTINTO_DE, array($cuenta->getCuenta()));
        $filtro->agregarCampo('appUniqueId', Filtro::ES_IGUAL_A, array($cuenta->getAppUniqueId()));
        $filtro->setCuentasVincualdas(true);

        /* @var Cuenta $cuentaAux */
        $cuentaAux = new Cuenta();
        $cuentaAux->setUid($cuenta->getUid());
        $cuentaAux->setAppUniqueId($cuenta->getAppUniqueId());
        $cuentaAux->setDefecto('0');

        return $this->backendUsuarios->actualizarCuentaUsuario($cuentaAux, $filtro);
    }

    /**
     * Retorna las aplicaciones existentes excluyendo inactivas
     *
     * @return array Retorna las aplicaciones, excluyendo aplicaciones inactivas
     */
    private function getArregloAplicaciones()
    {
        /* @var Aplicaciones[] $aplicaciones */
        $aplicaciones = $this->backendAplicaciones->getAplicaciones();
        $resultado = array();
        foreach ($aplicaciones as $aplicacion) {
            if ($aplicacion->getInactiva()) {
                continue;
            }
            $resultado[$aplicacion->getAppUniqueId()] = $aplicacion;
        }

        return $resultado;
    }

    /**
     * Retorna todas las aplicaciones, incluye las inactivas en el resultado
     *
     * @return array Retorna las aplicaciones incluyendo aplicaciones inactivas
     */
    private function getArregloAplicacionesConInactivas()
    {
        /* @var Aplicaciones[] $aplicaciones */
        $aplicaciones = $this->backendAplicaciones->getAplicaciones();
        $resultado = array();
        foreach ($aplicaciones as $aplicacion) {
            $resultado[$aplicacion->getAppUniqueId()] = $aplicacion;
        }

        return $resultado;
    }

    /**
     * @param $atributos
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    private function agregarAtributosSAML($atributos, $name, $value)
    {
        // value es un valor string
        if ($value === null) {
            return $atributos;
        }

        if (!in_array($name, array('appLauncherData'))) {
            $value = $this->araiVarios->utf8EncodeSeguro((string) $value);
        }

        if (!array_key_exists($name, $atributos)) {
            $atributos[$name] = array();
        }

        if (in_array($value, $atributos[$name], true)) {
            // Value already exists in attribute.
            return $atributos;
        }

        $atributos[$name][] = $value;

        return $atributos;
    }

    /**
     * Agrega las instancias de grupo correspondiente a los usuarios
     * @param Usuario[] $usuarios
     * @param array $gruposDisponibles
     */
    private function hidratarGruposUsuarios(array $usuarios, array $gruposDisponibles)
    {
        foreach ($usuarios as $indx => $usr) {
            $esMiembro = $usr->getMemberOf();
            if (! empty($esMiembro) && ! empty($gruposDisponibles)) {
                foreach ($gruposDisponibles as $grupo) {
                    if (in_array($grupo->getDn(), $esMiembro)) {
                        $usuarios[$indx]->agregarGrupo($grupo);
                    }
                }
            }
        }
    }
}

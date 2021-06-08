FROM hub.siu.edu.ar:5005/siu/expedientes/arai-usuarios/idm:v3.0.8
LABEL org.opencontainers.image.source=https://github.com/hslavich/arai-usuarios

COPY src/SIU/AraiUsuarios/Util/Varios.php /usr/local/app/core/src/SIU/AraiUsuarios/Util/Varios.php
COPY src/SIU/AraiUsuarios/Backends/Helpers/UsuarioHelperLdap.php /usr/local/app/core/src/SIU/AraiUsuarios/Backends/Helpers/UsuarioHelperLdap.php
COPY src/SIU/AraiUsuarios/Entities/Usuario.php /usr/local/app/core/src/SIU/AraiUsuarios/Entities/Usuario.php
COPY src/SIU/AraiUsuarios/Core/UsuariosManager.php /usr/local/app/core/src/SIU/AraiUsuarios/Core/UsuariosManager.php
COPY src/SIU/AraiUsuarios/Definitions/Ldap.yml /usr/local/app/core/src/SIU/AraiUsuarios/Definitions/Ldap.yml
COPY src/idm_metadatos_componentes_toba_ei_formulario_dump_31000011.sql /usr/local/app/idm/metadatos/componentes/toba_ei_formulario/dump_31000011.sql

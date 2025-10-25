# Changelog

Todas las novedades notables de este proyecto serán documentadas en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
y este proyecto se adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-10-25

### Added
- 🎉 **Versión Inicial de PasteX Pro!**
- **Core:** Arquitectura MVC ligera, router basado en FastRoute, carga de entorno, logging.
- **Pastes:** Creación, visualización y expiración de pastes públicos y no listados.
- **Usuarios:** Sistema de registro y login con hashing de contraseñas Argon2id.
- **Seguridad:** Protección CSRF, cabeceras de seguridad básicas.
- **Admin Panel:** Dashboard básico y gestión de usuarios y pastes.
- **Instalador:** Wizard de instalación web para base de datos y usuario admin.
- **API:** Endpoints básicos para crear y leer pastes con autenticación por API Key.
- **Frontend:** Tema claro/oscuro funcional, diseño responsive.
- **Herramientas:** Script de QA (`tools/qa.sh`) para linting y tests.
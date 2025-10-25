#!/bin/bash

# ==============================================================================
# PasteX Pro - Quality Assurance (QA) Script
# ==============================================================================
#
# Este script ejecuta un conjunto de herramientas de calidad de código para asegurar
# que el código base se mantenga consistente, limpio y funcional.
#
# Uso:
# 1. Asegúrate de que el script tenga permisos de ejecución: chmod +x tools/qa.sh
# 2. Ejecútalo desde el directorio raíz del proyecto: ./tools/qa.sh
#
# Requisitos:
# - Composer debe estar instalado.
# - Las dependencias de desarrollo deben estar instaladas: composer install
# ==============================================================================


# --- Configuración de Colores ---
# Permite mostrar la salida con colores para una mejor legibilidad.
C_RESET='\033[0m'
C_RED='\033[0;31m'
C_GREEN='\033[0;32m'
C_YELLOW='\033[0;33m'
C_BLUE='\033[0;34m'
C_BOLD='\033[1m'


# --- Función para Imprimir Encabezados ---
# Ayuda a separar visualmente cada paso del proceso de QA.
function print_header() {
    echo -e "\n${C_BLUE}${C_BOLD}=======================================================================${C_RESET}"
    echo -e "${C_BLUE}${C_BOLD} $1${C_RESET}"
    echo -e "${C_BLUE}${C_BOLD}=======================================================================${C_RESET}"
}


# --- Función para Ejecutar Comandos y Comprobar Errores ---
# Ejecuta un comando, muestra un mensaje de éxito o fallo y sale si hay un error.
function run_command() {
    local command_to_run="$1"
    local success_message="$2"

    echo -e "${C_YELLOW}🚀 Ejecutando: ${command_to_run}${C_RESET}"
    
    # Ejecuta el comando y captura su salida y código de retorno
    output=$(eval ${command_to_run} 2>&1)
    status=$?

    if [ $status -ne 0 ]; then
        echo -e "\n${C_RED}❌ FALLO: El comando falló con el código de salida ${status}.${C_RESET}"
        echo -e "${C_RED}------------------------- Salida del Error -------------------------${C_RESET}"
        echo "$output"
        echo -e "${C_RED}----------------------------------------------------------------------${C_RESET}"
        echo -e "${C_RED}QA detenido debido a un error.${C_RESET}"
        exit 1
    else
        echo -e "${C_GREEN}✅ ÉXITO: ${success_message}${C_RESET}"
    fi
}


# --- INICIO DEL SCRIPT ---

# 1. Arreglar Estilo de Código (Linting & Fixing)
print_header "Paso 1: Arreglando el Estilo de Código con PHP-CS-Fixer"
# El comando `vendor/bin/php-cs-fixer fix` analiza y corrige automáticamente
# los archivos en los directorios `app`, `config` y `tests`.
run_command "vendor/bin/php-cs-fixer fix" "El estilo de código ha sido verificado y corregido."


# 2. Análisis Estático de Código con PHPStan
print_header "Paso 2: Realizando Análisis Estático con PHPStan"
# PHPStan analiza el código en busca de errores sin ejecutarlo.
# `level 5` es un buen punto de partida, equilibrando rigurosidad y pragmatismo.
# Se le indica que analice los directorios `app` y `config`.
run_command "vendor/bin/phpstan analyse app config --level=5" "El análisis estático se completó sin errores."


# 3. Ejecución de Pruebas Automatizadas con PHPUnit
print_header "Paso 3: Ejecutando la Suite de Pruebas con PHPUnit"
# Este comando corre todos los tests definidos en la carpeta `tests/`.
# `--testdox` proporciona una salida más legible.
run_command "vendor/bin/phpunit --testdox" "Todas las pruebas pasaron con éxito."


# --- FIN DEL SCRIPT ---

print_header "🎉 ¡Control de Calidad (QA) completado exitosamente! 🎉"
echo -e "${C_GREEN}El código base está limpio, formateado y ha pasado todas las pruebas.${C_RESET}\n"

exit 0
<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for the quizaccess_puce plugin.
 *
 * @package    quizaccess_puce
 * @copyright  2024 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'PUCE - Reglas de acceso al cuestionario';
$string['privacy:metadata'] = 'El módulo (plugin) PUCE no almacena datos personales.';
$string['enableblocksession'] = 'Bloqueo de Sesión';
$string['configenableblocksession'] = 'Si está habilitado, los usuarios solo pueden continuar un intento de cuestionario en la misma sesión del navegador. Cualquier intento de abrir el mismo intento de cuestionario usando otra computadora, dispositivo o navegador será bloqueado. Esto puede ser útil para asegurarse de que nadie ayude a un estudiante abriendo el mismo intento de cuestionario en otra computadora.';
$string['defaultsessiononattempt'] = 'Sesión por defecto en intento';
$string['configdefaultsessiononattempt'] = 'Número máximo de sesiones permitidas en un intento de cuestionario. Si se establece en 1, los usuarios solo pueden continuar un intento de cuestionario en la misma sesión del navegador. Cualquier intento de abrir el mismo intento de cuestionario usando otra computadora, dispositivo o navegador será bloqueado. Esto puede ser útil para asegurarse de que nadie ayude a un estudiante abriendo el mismo intento de cuestionario en otra computadora.';
$string['maxnumbersessions'] = 'Sesiones permitidas en intento';
$string['onesession'] = 'Solo una sesión';
$string['maxnumbersessions_help'] = 'Los usuarios solo pueden continuar un intento de cuestionario en la misma sesión del navegador. Cualquier intento de abrir el mismo intento de cuestionario usando otra computadora, dispositivo o navegador será bloqueado. Esto puede ser útil para asegurarse de que nadie ayude a un estudiante abriendo el mismo intento de cuestionario en otra computadora.';
$string['anothersession'] = 'Intentas acceder a un cuestionario que ha sido bloqueado. Este bloqueo puede deberse a múltiples intentos de inicio de sesión desde un ordenador, dispositivo o navegador distinto al que se utilizó originalmente para empezar el cuestionario. Si cerraste accidentalmente tu sesión o el navegador, por favor, contacta con el profesor.';
$string['studentinfo'] = 'Advertencia! Está prohibido cambiar de dispositivo mientras se realiza este cuestionario. Ten en cuenta que después del comienzo del intento de cuestionario, cualquier conexión a este cuestionario usando otros ordenadores, dispositivos y navegadores será bloqueada. No cierres la ventana del navegador hasta el final del intento, de lo contrario, no podrás completar este cuestionario.';
$string['notificationsintro'] = 'Las siguientes notificaciones son enviadas por el módulo (plugin) de reglas de acceso al cuestionario.';
$string['enablenotifications'] = 'Habilitar notificaciones';
$string['configenablenotifications'] = 'Si está habilitado, se enviarán notificaciones a los estudiantes cuando se bloquee un intento de cuestionario.';
$string['notifyprofiles'] = 'Destinatarios de notificaciones';
$string['confignotifyprofiles'] = 'Envía las notificaciones a los perfiles seleccionados.';
$string['notifyprofiles_help'] = 'Envía las notificaciones a los perfiles seleccionados.';
$string['teacher'] = 'Profesor';
$string['student'] = 'Estudiante';
$string['notifymessages'] = 'Opciones de notificaciones';
$string['confignotifymessages'] = 'Selecciona los casos en los que se enviarán notificaciones.';
$string['notifymessages_help'] = 'Selecciona los casos en los que se enviarán notificaciones.';
$string['startattempt'] = 'Al iniciar o continuar con un cuestionario';
$string['blockattempt'] = 'Al bloquear el cuestionario';
$string['finishattempt'] = 'Al finalizar el cuestionario';
// Start attempt email
$string['emailstartattemptnotifysubject'] = 'Inicio de cuestionario: {$a->quizname}';
$string['emailstartattemptnotifybody'] = 'Hola {$a->username},

{$a->studentname} ha iniciado el cuestionario: \'{$a->quizname}\' ({$a->quizurl}) en el curso \'{$a->coursename}\'.';
$string['emailstartattemptnotifysmall'] = '{$a->studentname} ha iniciado el cuestionario \'{$a->quizname}\'';

// Block attempt email
$string['emailblockattemptnotifysubject'] = 'Bloqueo de cuestionario: {$a->quizname}';
$string['emailblockattemptnotifybody'] = 'Hola {$a->username},
e ha bloqueado el cuestionario ({$a->quizurl
{$a->studentname} s}) en el curso \'{$a->coursename}\' por razones de seguridad del exámen.';
$string['emailblockattemptnotifysmall'] = '{$a->studentname} se ha bloqueado el cuestionario \'{$a->quizname}\'';

// Finish attempt email
$string['emailfinishattemptnotifysubject'] = 'Finalización de cuestionario: {$a->quizname}';
$string['emailfinishattemptnotifybody'] = 'Hola {$a->username},

{$a->studentname} ha finalizado el cuestionario ({$a->quizurl}) en el curso \'{$a->coursename}\'.';
$string['emailfinishattemptnotifysmall'] = '{$a->studentname} ha finalizado el cuestionario \'{$a->quizname}\'';

$string['notifyrolesenables'] = 'Habilitar roles para notificaciones';
$string['confignotifyrolesenables'] = 'Selecciona los roles que pueden recibir notificaciones.';
$string['notifyrolesdefault'] = 'Roles por defecto para notificaciones';
$string['confignotifyrolesdefault'] = 'Selecciona los roles por defecto que pueden recibir notificaciones.';
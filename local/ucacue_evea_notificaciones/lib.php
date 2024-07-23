<?php

defined('MOODLE_INTERNAL') || die();

function local_ucacue_evea_notificaciones_before_standard_top_of_body_html() {
    global $PAGE, $USER, $SITE;

    if (isloggedin() && !isguestuser()){
        //Se obtiene el archivo?
        if($arrayData = json_decode(file_get_contents('https://www.ucacue.edu.ec/evea/dataNotificaciones.json'))){
            //Leer el archivo
            foreach ($arrayData as $objectData) {
                $estado = $objectData->estado;
                if($estado==1){
                    //Notificacion desde
                    $fechaInicio = $objectData->fechaInicio;
                    $timestampFechaInicio = strtotime($fechaInicio);
                    //Notificacion hasta
                    $fechaFin = $objectData->fechaFin;
                    $timestampFechaFin = strtotime($fechaFin);
                    //Hora del sistema | zonahoraria configurada en moodle
                    $timestampFechaActual = time();
                    //Se cumplen con la fecha?
                    if ($timestampFechaInicio < $timestampFechaActual && $timestampFechaFin > $timestampFechaActual) {
                        //Obtener los datos en variables
                        $sitesToNotify = $objectData->sitesToNotify; // Sitios donde se debe mostrar la notificación
                        $arrayUsuarios = $objectData->usuarios;
                        $arrayUbicaciones = $objectData->ubicaciones;
                        $contenidoNotificacion = $objectData->contenidoNotificacion;
                        $tipoNotificacion = $objectData->tipoNotificacion;
                        $arrayCarreras = $objectData->carreras;
                        $arrayCiclos = $objectData->ciclos;

                        // $site_url = $CFG->wwwroot;
                        $site_name = $SITE->shortname;
                        
                        // Verifica si el sitio actual está en la lista de sitios especificados
                        if (in_array($site_name, $sitesToNotify)) {
                            // Recorrer el array por ubicaciones donde mostrar la notificación                      
                            foreach ($arrayUbicaciones as $ubicacion) {
                                foreach ($arrayUsuarios as $usuario) {
                                    switch ($ubicacion) {
                                        case 'AreaPrincipal':
                                            if (strpos($PAGE->url, '/my/')) {
                                                notificar_mensaje($usuario, $contenidoNotificacion, $tipoNotificacion, $arrayCarreras, $arrayCiclos);
                                            }
                                            break;
                                        case 'PaginaPrincipal':
                                            if (strpos($PAGE->url, '/?redirect=0')) {
                                                notificar_mensaje($usuario, $contenidoNotificacion, $tipoNotificacion, $arrayCarreras, $arrayCiclos);
                                            }
                                            break;
                                        case 'Usuario':
                                            if (strpos($PAGE->url, '/user/')) {
                                                notificar_mensaje($usuario, $contenidoNotificacion, $tipoNotificacion, $arrayCarreras, $arrayCiclos);
                                            }
                                            break;
                                        case 'Curso':
                                            if (strpos($PAGE->url, '/course/')) {
                                                notificar_mensaje($usuario, $contenidoNotificacion, $tipoNotificacion, $arrayCarreras, $arrayCiclos);
                                            }
                                            break;
                                        default:
                                            break;
                                    }
                                }
                            }
                        } 
                    }   
                }
            }
        }
    }
}

function notificar_mensaje($usuarios, $contenidoNotificacion, $tipoNotificacion, $carreras, $ciclos) {
    global $DB, $USER;
    
    //Es administrador?
    $isadmin = is_siteadmin($USER);

    //Obtener el tipo de notificacion
    switch ($tipoNotificacion) {
        case 'info':
            $typeNotify = \core\output\notification::NOTIFY_INFO;
            break;
        case 'error':
            $typeNotify = \core\output\notification::NOTIFY_ERROR;
            break;
        case 'warning':
            $typeNotify = \core\output\notification::NOTIFY_WARNING;
            break;
        case 'success':
            $typeNotify = \core\output\notification::NOTIFY_SUCCESS;
            break;
        default:
            break;
    }

    //A que usuarios mostrar la notificacion
    switch ($usuarios) {
        case 'SinCursos':
            //Lista de cursos del usuario
            $courses = enrol_get_my_courses();
            $numCourses = count($courses);
            //Tiene cursos enrolados?
            if($numCourses==0 && !$isadmin){
                \core\notification::add($contenidoNotificacion, $typeNotify);
            }
            break;
        case 'Docentes':
            //El usuario tiene en el base de datos rol 'editingteacher'
            $teacherRole = $DB->get_field('role', 'id', array('shortname' => 'editingteacher'));
            $isTeacher = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $teacherRole]);
            //Es docente?
            if ($isTeacher && !$isadmin) {
                //Recorrer las carreras, desde el archivo
                if ($carreras[0] == 'todos' && $ciclos[0] == 'todos') {
                    \core\notification::add($contenidoNotificacion, $typeNotify);
                } else {
                    notificacionPorCarreras($carreras, $ciclos, $contenidoNotificacion, $typeNotify);
                }
                
            }
            break;
        case 'Estudiantes':
            //El usuario tiene en el base de datos rol 'editingteacher'
            $editingteacherRole = $DB->get_field('role', 'id', array('shortname' => 'editingteacher'));
            $isEditingTeacher = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $editingteacherRole]);
            //El usuario tiene en el base de datos rol 'teacher'
            $studentRole = $DB->get_field('role', 'id', array('shortname' => 'student'));
            $isStudent = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $studentRole]);
            if (!$isEditingTeacher && !$isadmin && $isStudent) {
                if ($carreras[0] == 'todos' && $ciclos[0] == 'todos') {
                    \core\notification::add($contenidoNotificacion, $typeNotify);
                } else {
                    notificacionPorCarreras($carreras, $ciclos, $contenidoNotificacion, $typeNotify);
                }
            }
            break;
        case 'Todos':
            //Todos los usuarios, excepto los administradores
            if (!$isadmin) {
                \core\notification::add($contenidoNotificacion, $typeNotify);
            }
            break;
        case 'Administradores':
            //Solo los administradores
            if ($isadmin) {
                \core\notification::add($contenidoNotificacion, $typeNotify);
            }
            break;
        default:
            break;
    }            
}

function notificacionPorCarreras($carreras, $ciclos, $contenidoNotificacion, $typeNotify){
    $courses = enrol_get_my_courses();
    foreach ($courses as $objectCourse) {
        $idnumber = $objectCourse->idnumber;
        $idCategories = explode('/', $idnumber, 7);
        $idCarrera = explode('-', $idCategories[4], 2);
        $idCiclo = $idCategories[5];
        if ($carreras[0] != 'todos') {
            foreach ($carreras as $carrera) {
                if ($carrera == $idCarrera[0]) {
                    foreach ($ciclos as $ciclo) {
                        if ($ciclo == 'todos') {
                            \core\notification::add($contenidoNotificacion, $typeNotify);
                            break 3;
                        } else {
                            if ($ciclo == $idCiclo) {
                                \core\notification::add($contenidoNotificacion, $typeNotify);
                                break 3;
                            }
                        }
                    }
                }
            }
        } else if ($ciclos[0] != 'todos') {
            foreach ($ciclos as $ciclo) {
                if ($ciclo == $idCiclo) {
                    \core\notification::add($contenidoNotificacion, $typeNotify);
                    break 2;
                }
            }
        }
    }
}



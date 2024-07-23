<?php

defined('MOODLE_INTERNAL') || die();

class format_columns_content implements renderable {
    protected $format;

    public function __construct($format) {
        $this->format = $format;
    }

    public function render_column($columnnumber) {
        global $OUTPUT;

        $content = '';

        // Aquí deberás agregar el código para obtener y renderizar las secciones y actividades
        // correspondientes a la columna especificada por $columnnumber.

        $content .= html_writer::tag('div', 'Sección de columna ' . $columnnumber, array('class' => 'column-content'));

        return $content;
    }

    public function render_add_section_button($columnnumber) {
        global $OUTPUT;

        // Código para renderizar el botón de "Añadir sección".
        $url = new moodle_url('/course/format/columns/addsection.php', array('column' => $columnnumber));
        $button = $OUTPUT->single_button($url, 'Añadir sección');

        return $button;
    }
}

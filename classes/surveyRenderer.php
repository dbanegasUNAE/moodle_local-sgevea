<?php

namespace local_sgevea;

defined('MOODLE_INTERNAL') || die();

class surveyRenderer
{

    private $page;

    public function __construct($page)
    {
        $this->page = $page;
    }

    public function render_surveys($surveys)
    {
        global $OUTPUT;
        $data = null;
        if ($surveys && is_array($surveys)) {
            $data = [
                'surveys' => [],
                'viewlist' => true
            ];
            foreach ($surveys as $survey) {
                $data['surveys'][] = [
                    'id' => $survey['ID'],
                    'name' => $survey['NAME'],
                    'startdate' => $survey['DATEBEG'],
                    'enddate' => $survey['DATEEXP'],
                    'required' => $survey['REQUIRED'] ? get_string('survreqyes', 'local_sgevea') : get_string('survreqno', 'local_sgevea'),
                    'status' => $survey['STATUS'] ? get_string('survstatusyes', 'local_sgevea') : get_string('survstatusno', 'local_sgevea')
                ];
            }
        } else {
            $data = [
                'viewlist' => false,
                'nosurveys' => get_string('survlistempty', 'local_sgevea')
            ];
        }
        return $OUTPUT->render_from_template('local_sgevea/surveys', $data);
    }
}

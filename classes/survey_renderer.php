<?php

namespace local_sgeveasurvey;

defined('MOODLE_INTERNAL') || die();

class survey_renderer
{

    private $page;

    public function __construct($page)
    {
        $this->page = $page;
    }

    public function render_surveys($surveys)
    {
        global $OUTPUT;

        $data = [
            'surveys' => []
        ];

        foreach ($surveys as $survey) {
            $data['surveys'][] = [
                'id' => $survey['ID'],
                'name' => $survey['NAME'],
                'startdate' => $survey['DATEBEG'],
                'enddate' => $survey['DATEEXP'],
                'required' => $survey['REQUIRED'] ? get_string('survreqyes','local_sgeveasurvey') : get_string('survreqno','local_sgeveasurvey'),
                'status' => $survey['STATUS'] ? get_string('survstatusyes','local_sgeveasurvey') : get_string('survstatusno','local_sgeveasurvey')
            ];
        }

        return $OUTPUT->render_from_template('local_sgeveasurvey/surveys', $data);
    }
    
}

<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class ScheduleModel extends Model
{

    protected $db, $authenticationModel;

    function __construct(AuthenticationModel $authenticationModel)
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->authenticationModel = $authenticationModel;
    }

    function getScheduleList($searchTerm)
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        if (empty($searchTerm))
            $builder = $this->db
                ->table('schedule')
                ->select('
                    schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                    schedule.date_start as date_start, schedule.date_end as date_end,
                    schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                    class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                    study_program.name as study_program_name, semester.id as semester_id,
                    semester.name as semester_name,
                    (
                        select group_concat(distinct lecturer_schedule.lecturer_id separator ", ")
                        from lecturer_schedule
                        where lecturer_schedule.schedule_id = schedule.id
                        group by lecturer_schedule.schedule_id
                    ) as lecturer_ids,
                    (
                        select group_concat(distinct lecturer.name separator ", ")
                        from lecturer_schedule
                        left join lecturer on lecturer.id = lecturer_schedule.lecturer_id
                        where lecturer_schedule.schedule_id = schedule.id
                        group by lecturer_schedule.schedule_id
                    ) as lecturer_names
                ')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = schedule.semester_id', 'left')
                ->orderBy('schedule.created_on', 'ASC');
        else
            $builder = $this->db
                ->table('schedule')
                ->select('
                    schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                    schedule.date_start as date_start, schedule.date_end as date_end,
                    schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                    class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                    study_program.name as study_program_name, semester.id as semester_id,
                    semester.name as semester_name,
                    (
                        select group_concat(distinct lecturer_schedule.lecturer_id separator ", ")
                        from lecturer_schedule
                        where lecturer_schedule.schedule_id = schedule.id
                        group by lecturer_schedule.schedule_id
                    ) as lecturer_ids,
                    (
                        select group_concat(distinct lecturer.name separator ", ")
                        from lecturer_schedule
                        left join lecturer on lecturer.id = lecturer_schedule.lecturer_id
                        where lecturer_schedule.schedule_id = schedule.id
                        group by lecturer_schedule.schedule_id
                    ) as lecturer_names
                ')
                ->join('class', 'class.id = schedule.class_id', 'left')
                ->join('study_program', 'study_program.id = class.study_program_id', 'left')
                ->join('semester', 'semester.id = schedule.semester_id', 'left')
                ->like('lower(trim(schedule.code))', strtolower(trim($searchTerm)))
                ->like('lower(trim(schedule.name))', strtolower(trim($searchTerm)))
                ->like('lower(trim(schedule.attendace_code))', strtolower(trim($searchTerm)))
                ->like('lower(trim(class.name))', strtolower(trim($searchTerm)))
                ->like('lower(trim(study_program.name))', strtolower(trim($searchTerm)))
                ->like('lower(trim(semester.name))', strtolower(trim($searchTerm)))
                ->like('lower(trim(lecturer_names))', strtolower(trim($searchTerm)))
                ->like('date_format(schedule.date_start, "%d/%m/%Y %h/%i %p")', strtolower(trim($searchTerm)))
                ->like('date_format(schedule.date_end, "%d/%m/%Y %h/%i %p")', strtolower(trim($searchTerm)))
                ->like('date_format(schedule.created_on, "%d/%m/%Y %h/%i %p")', strtolower(trim($searchTerm)))
                ->orderBy('schedule.created_on', 'ASC');
        if (!empty($userStudyProgramId)) $builder->where(['study_program.id' => $userStudyProgramId]);
        $data = $builder->get()->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function getFilteredScheduleList($classIds, $semesterIds, $lecturerIds, $dateStart, $dateEnd)
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        $builder = $this->db
            ->table('schedule')
            ->select('
                schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                schedule.date_start as date_start, schedule.date_end as date_end,
                schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id,
                semester.name as semester_name,
                (
                    select group_concat(distinct lecturer_schedule.lecturer_id separator ", ")
                    from lecturer_schedule
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_ids,
                (
                    select group_concat(distinct lecturer.name separator ", ")
                    from lecturer_schedule
                    left join lecturer on lecturer.id = lecturer_schedule.lecturer_id
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_names
            ')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = schedule.semester_id', 'left')
            ->orderBy('schedule.created_on', 'ASC');
        if (!empty($classIds)) foreach ($classIds as $classId) $builder->orLike('class.id', $classId);
        if (!empty($semesterIds)) foreach ($semesterIds as $semesterId) $builder->orLike('semester.id', $semesterId);
        if (!empty($lecturerIds)) foreach ($lecturerIds as $lecturerId) $builder->orWhere("
            $lecturerId in(
                select lecturer_schedule.lecturer_id
                from lecturer_schedule
                where lecturer_schedule.schedule_id = schedule.id
            )
        ");
        if (!empty($dateStart)) $builder->where("schedule.date_start between '$dateStart' and '$dateEnd'");
        if (!empty($userStudyProgramId)) $builder->where(['study_program.id' => $userStudyProgramId]);
        $data = $builder->get()->getResultArray();
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $data;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addSchedule(
        int $classId, int $semesterId, array $lecturerIds, string $name,
        string $dateStart, string $dateEnd
    )
    {
        $this->db->transBegin();
        $this->db->table('schedule')->insert([
            'schedule.class_id' => $classId,
            'schedule.semester_id' => $semesterId,
            'schedule.schedule_code' => $this->generateScheduleCode($classId, $semesterId),
            'schedule.name' => trim($name),
            'schedule.date_start' => trim($dateStart),
            'schedule.date_end' => trim($dateEnd),
            'schedule.attendance_code' => $this->generateAttendanceCode()
        ]);
        $scheduleId = $this->db->insertId();
        foreach ($lecturerIds as $lecturerId)
            $this->db->table('lecturer_schedule')->insert([
                'schedule_id' => $scheduleId,
                'lecturer_id' => $lecturerId
            ]);
        $insertedRow = $this->db
            ->table('schedule')
            ->select('
                schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                schedule.date_start as date_start, schedule.date_end as date_end,
                schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id,
                semester.name as semester_name,
                (
                    select group_concat(distinct lecturer_schedule.lecturer_id separator ", ")
                    from lecturer_schedule
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_ids,
                (
                    select group_concat(distinct lecturer.name separator ", ")
                    from lecturer_schedule
                    left join lecturer on lecturer.id = lecturer_schedule.lecturer_id
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_names
            ')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = schedule.semester_id', 'left')
            ->getWhere(['schedule.id' => $scheduleId])
            ->getRow();
        if ($this->db->transStatus() && !empty($insertedRow)) {
            $this->db->transCommit();
            return $insertedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function addBatchSchedule(
        array $classNames, array $studyProgramNames, array $semesterNames, array $lecturerNames,
        array $names, array $dateStarts, array $dateEnds, array $createdOns
    )
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        for ($i = 0; $i < count($names); $i++) {
            $studyProgramId = $this->db->table('study_program')->select('study_program.id as id')->getWhere([
                'lower(trim(study_program.name))' => strtolower(trim($studyProgramNames[$i]))
            ])->getRow()->id;
            if (empty($userStudyProgramId)) $userStudyProgramId = $studyProgramId;
            $classId = $this->db->table('class')->getWhere([
                'lower(trim(class.name))' => strtolower(trim($classNames[$i])),
                'class.study_program_id' => $studyProgramId
            ])->getRow()->id;
            $semesterId = $this->db->table('semester')->getWhere([
                'lower(trim(semester.name))' => strtolower(trim($semesterNames[$i]))
            ])->getRow()->id;
            $lecturerIds = array();
            foreach (explode(', ', $lecturerNames[$i]) as $lecturerName)
                $lecturerIds[] = $this->db->table('lecturer')->getWhere([
                    'lower(trim(lecturer.name))' => strtolower(trim($lecturerName))
                ])->getRow()->id;
            $data = [
                'schedule.schedule_code' => $this->generateScheduleCode($classId, $semesterId),
                'schedule.class_id' => $classId,
                'schedule.semester_id' => $semesterId,
                'schedule.name' => trim($names[$i]),
                'schedule.date_start' => trim($dateStarts[$i]),
                'schedule.date_end' => trim($dateEnds[$i]),
                'schedule.attendance_code' => $this->generateAttendanceCode()
            ];
            if (empty($classId) || empty($lecturerIds)) {
                $this->db->transRollback();
                return null;
            }
            if (!empty($createdOns[$i])) $data['schedule.created_on'] = trim($createdOns[$i]);
            if (!empty($data['schedule.schedule_code']) && !empty($data['schedule.class_id']) &&
                !empty('schedule.semester_id') && !empty($data['schedule.name']) &&
                !empty($data['schedule.date_start']) && !empty($data['schedule.date_end']) &&
                !empty($data['schedule.attendance_code']) && $studyProgramId == $userStudyProgramId) {
                $this->db->table('schedule')->insert($data);
                $scheduleId = $this->db->insertId();
                foreach ($lecturerIds as $lecturerId)
                    $this->db->table('lecturer_schedule')->insert([
                        'schedule_id' => $scheduleId,
                        'lecturer_id' => $lecturerId
                    ]);
            }
        }
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function editSchedule(
        int $id, int $classId, int $semesterId, array $lecturerIds,
        string $name, string $dateStart, string $dateEnd
    )
    {
        $this->db->transBegin();
        $this->db->table('schedule')->where(['schedule.id' => $id])->update([
            'schedule.class_id' => $classId,
            'schedule.semester_id' => $semesterId,
            'schedule.lecturer_id' => $lecturerId,
            'schedule.name' => trim($name),
            'schedule.date_start' => trim($dateStart),
            'schedule.date_end' => trim($dateEnd)
        ]);
        $scheduleId = $this->db->insertId();
        foreach ($lecturerIds as $lecturerId)
            $this->db->table('lecturer_schedule')->insert([
                'schedule_id' => $scheduleId,
                'lecturer_id' => $lecturerId
            ]);
        $updatedRow = $this->db
            ->table('schedule')
            ->select('
                schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                schedule.date_start as date_start, schedule.date_end as date_end,
                schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id,
                semester.name as semester_name,
                (
                    select group_concat(distinct lecturer_schedule.lecturer_id separator ", ")
                    from lecturer_schedule
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_ids,
                (
                    select group_concat(distinct lecturer.name separator ", ")
                    from lecturer_schedule
                    left join lecturer on lecturer.id = lecturer_schedule.lecturer_id
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_names
            ')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = schedule.semester_id', 'left')
            ->getWhere(['schedule.id' => $scheduleId])
            ->getRow();
        if ($this->db->transStatus() && !empty($updatedRow)) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function deleteSchedule(int $id)
    {
        $this->db->transBegin();
        $deletedRow = $this->db
            ->table('schedule')
            ->select('
                schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                schedule.date_start as date_start, schedule.date_end as date_end,
                schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id,
                semester.name as semester_name,
                (
                    select group_concat(distinct lecturer_schedule.lecturer_id separator ", ")
                    from lecturer_schedule
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_ids,
                (
                    select group_concat(distinct lecturer.name separator ", ")
                    from lecturer_schedule
                    left join lecturer on lecturer.id = lecturer_schedule.lecturer_id
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_names
            ')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = schedule.semester_id', 'left')
            ->getWhere(['schedule.id' => $id])
            ->getRow();
        $this->db->table('schedule')->where(['schedule.id' => $id])->delete();
        $this->db->table('lecturer_schedule')->where(['lecturer_schedule.schedule_id' => $id])->delete();
        if ($this->db->transStatus() && !empty($deletedRow)) {
            $this->db->transCommit();
            return $deletedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    function importSchedule(
        array $classNames, array $studyProgramNames, array $semesterNames, array $lecturerNames,
        array $names, array $dateStarts, array $dateEnds, array $createdOns
    )
    {
        $this->db->transBegin();
        $userStudyProgramId = $this->authenticationModel->getSession()->user_study_program_id;
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('schedule')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        for ($i = 0; $i < count($names); $i++) {
            $studyProgramId = $this->db->table('study_program')->select('study_program.id as id')->getWhere([
                'lower(trim(study_program.name))' => strtolower(trim($studyProgramNames[$i]))
            ])->getRow()->id;
            if (empty($userStudyProgramId)) $userStudyProgramId = $studyProgramId;
            $classId = $this->db->table('class')->getWhere([
                'lower(trim(class.name))' => strtolower(trim($classNames[$i])),
                'class.study_program_id' => $studyProgramId
            ])->getRow()->id;
            $semesterId = $this->db->table('semester')->getWhere([
                'lower(trim(semester.name))' => strtolower(trim($semesterNames[$i]))
            ])->getRow()->id;
            $lecturerIds = array();
            foreach (explode(', ', $lecturerNames[$i]) as $lecturerName)
                $lecturerIds[] = $this->db->table('lecturer')->getWhere(['lower(trim(lecturer.name))' => strtolower(trim($lecturerName))])->getRow()->id;
            $data = [
                'schedule.schedule_code' => $this->generateScheduleCode($classId, $semesterId),
                'schedule.class_id' => $classId,
                'schedule.semester_id' => $semesterId,
                'schedule.name' => trim($names[$i]),
                'schedule.date_start' => trim($dateStarts[$i]),
                'schedule.date_end' => trim($dateEnds[$i]),
                'schedule.attendance_code' => $this->generateAttendanceCode()
            ];
            if (empty($classId) || empty($lecturerIds)) {
                $this->db->transRollback();
                return null;
            }
            if (!empty($createdOns[$i])) $data['schedule.created_on'] = trim($createdOns[$i]);
            if (!empty($data['schedule.schedule_code']) && !empty($data['schedule.class_id']) &&
                !empty('schedule.semester_id') && !empty($data['schedule.name']) &&
                !empty($data['schedule.date_start']) && !empty($data['schedule.date_end']) &&
                !empty($data['schedule.attendance_code']) && $studyProgramId == $userStudyProgramId)
                $this->db->table('schedule')->insert($data);
            $scheduleId = $this->db->insertId();
            foreach ($lecturerIds as $lecturerId)
                $this->db->table('lecturer_schedule')->insert([
                    'schedule_id' => $scheduleId,
                    'lecturer_id' => $lecturerId
                ]);
        }
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function truncateSchedule()
    {
        $this->db->transBegin();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('schedule')->truncate();
        $this->db->table('lecturer_schedule')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return true;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    function renewScheduleCode(int $id)
    {
        $this->db->transBegin();
        $this->db->table('schedule')->where(['schedule.id' => $id])->update([
            'schedule.attendance_code' => $this->generateAttendanceCode()
        ]);
        $updatedRow = $this->db
            ->table('schedule')
            ->select('
                schedule.id as id, schedule.schedule_code as schedule_code, schedule.name as name,
                schedule.date_start as date_start, schedule.date_end as date_end,
                schedule.attendance_code as attendance_code, schedule.created_on as created_on,
                class.id as class_id, class.name as class_name, study_program.id as study_program_id,
                study_program.name as study_program_name, semester.id as semester_id,
                semester.name as semester_name,
                (
                    select group_concat(distinct lecturer_schedule.lecturer_id separator ", ")
                    from lecturer_schedule
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_ids,
                (
                    select group_concat(distinct lecturer.name separator ", ")
                    from lecturer_schedule
                    left join lecturer on lecturer.id = lecturer_schedule.lecturer_id
                    where lecturer_schedule.schedule_id = schedule.id
                    group by lecturer_schedule.schedule_id
                ) as lecturer_names
            ')
            ->join('class', 'class.id = schedule.class_id', 'left')
            ->join('study_program', 'study_program.id = class.study_program_id', 'left')
            ->join('semester', 'semester.id = schedule.semester_id', 'left')
            ->getWhere(['schedule.id' => $id])
            ->getRow();
        if ($this->db->transStatus() && !empty($updatedRow)) {
            $this->db->transCommit();
            return $updatedRow;
        } else {
            $this->db->transRollback();
            return null;
        }
    }

    private function generateScheduleCode(int $classId, int $semesterId)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $scheduleCode = "S$classId$semesterId";
        for ($i = 0; $i < 6; $i++) $scheduleCode .= $characters[rand(0, strlen($characters) - 1)];
        $scheduleCode = substr($scheduleCode, 0, 6);
        return strtoupper(trim($scheduleCode));
    }

    private function generateAttendanceCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $studentAttendanceCode = '';
        for ($i = 0; $i < 6; $i++) $studentAttendanceCode .= $characters[rand(0, strlen($characters) - 1)];
        return strtoupper(trim($studentAttendanceCode));
    }
}

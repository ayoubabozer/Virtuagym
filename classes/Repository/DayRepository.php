<?php
/**
 * Created by PhpStorm.
 * User: itay
 * Date: 13/05/19
 * Time: 11:13
 */
namespace classes\Repository;

use classes\Repository\UserRepository;

class DayRepository extends GeneralRepository
{

    public function __construct()
    {
        parent::__construct();
        $this->userRepository =  new UserRepository();

    }

    private $userRepository;


    public function getPlanDays($plan_id)
    {
        $query = "SELECT 
                    day.id as dayId, day.name as dayName, exercise.name as exerciseName
                FROM
                    day
                        LEFT JOIN
                    day_exercise ON day.id = day_exercise.day_id
                    LEFT JOIN exercise ON exercise_id = exercise.id
                WHERE
                    plan_id =:plan_id";
        $statement = $this->con->prepare($query);
        if($statement->execute(array(':plan_id'=>$plan_id)))
        {
            $result = [];
            while($row = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $day = $row['dayName'] ?? '';
                $result[$day][] = $row;
            }
            return $result;
        }
    }

    public function addDay($form_data)
    {

        $query = "INSERT INTO day (name, plan_id) VALUES(:name, :plan_id)";

        $statement = $this->con->prepare($query);

        $plan_id = $form_data['plan_id'] ?? 0;
        $users = $this->userRepository->getUsersByPlan($plan_id);
        foreach ($users as $user) {
            $email = $user['email'] ?? 0;
            if($email)
            {
                $this->mail->setTo($email);
                $this->mail->setSubject('Plan Modification!');
                $this->mail->setMessage('A day had been added to your plan day!. ');
                $this->mail->send();
            }
        }

        if($statement->execute($form_data))
        {
            $data = "insert";
        }
        else {
            $data = "error";
        }

        return $data;

    }
}
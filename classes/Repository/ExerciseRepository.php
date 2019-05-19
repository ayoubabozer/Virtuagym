<?php
/**
 * Created by PhpStorm.
 * User: itay
 * Date: 13/05/19
 * Time: 11:13
 */
namespace classes\Repository;

use classes\Repository\UserRepository;

class ExerciseRepository extends GeneralRepository
{

    public function __construct()
    {
        parent::__construct();
        $this->userRepository =  new UserRepository();
    }

    public function getExercises()
    {
        $query = "SELECT * FROM exercise";

        $statement = $this->con->prepare($query);
        $result = [];
        if($statement->execute())
        {
            while($row = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $result[] = $row;
            }
            return $result;
        }
    }

    public function addExercise($form_data)
    {
        $query = "INSERT INTO day_exercise (day_id, exercise_id) VALUES(:day_id, :exercise_id)";


        $statement = $this->con->prepare($query);

        if($statement->execute($form_data))
        {
            $data = "insert";
        }
        else {
            $data = "error";
        }

        $day_id = $form_data['day_id'] ?? 0;

        $users = $this->userRepository->getUsersByPlanDay($day_id);
        foreach ($users as $user) {
            $email = $user['email'] ?? 0;
            if($email)
            {
                $this->mail->setTo($email);
                $this->mail->setSubject('Plan Modification!');
                $this->mail->setMessage('An exercise had been added to your plan day!. ');
                $this->mail->send();
            }
        }

        return $data;
    }

    public function addDay($form_data)
    {
        $data = [];

        $query = "INSERT INTO day (name, plan_id) VALUES(:name, :plan_id)";

        $statement = $this->con->prepare($query);


        if($statement->execute($form_data))
        {
            $data['success'] = 1;
        }
        else {
            $data['success'] = 0;
        }

        return $data;

    }
}
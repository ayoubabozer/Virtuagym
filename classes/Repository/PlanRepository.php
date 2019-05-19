<?php
/**
 * Created by PhpStorm.
 * User: itay
 * Date: 13/05/19
 * Time: 11:13
 */
namespace classes\Repository;

use classes\Repository\UserRepository;

class PlanRepository extends GeneralRepository
{

    public function __construct()
    {
        parent::__construct();
        $this->userRepository =  new UserRepository();
    }

    public function getPlans()
    {
        $query = "SELECT 
                  *
                  FROM plan";

        $statement = $this->con->prepare($query);
        $result = [];

        if ($statement->execute()) {
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
        }

        return $result;
    }

    public function getPlan($id)
    {
        $query = "SELECT * FROM plan WHERE id=:id";
        $statement = $this->con->prepare($query);
        $data = [];
        if ($statement->execute(array(':id' => $id))) {
            $data = $statement->fetch(\PDO::FETCH_ASSOC);
        }

        return $data;
    }

    public function addPlan($form_data)
    {

        $query = "INSERT INTO plan (name) VALUES(:plan_name)";

        $statement = $this->con->prepare($query);

        if ($statement->execute($form_data)) {
            $data = "insert";
        } else {
            $data = "error";
        }
        return $data;

    }

    public function deletePlan($id)
    {
        $query = "DELETE FROM plan WHERE id=:id";
        $statement = $this->con->prepare($query);
        $data = [];
        if ($statement->execute(array(':id' => $id))) {
            $data['success'] = 1;
        } else {
            $data['success'] = 0;
        }

        return $data;
    }

    public function updatePlan($form_data)
    {
        $query = "UPDATE plan SET name=:plan_name WHERE id=:id";

        $statement = $this->con->prepare($query);

        if ($statement->execute($form_data)) {
            $data = "update";
        } else {
            $data = "error";
        }

        return $data;
    }

    public function assignPlan($form_data)
    {

        $query = "UPDATE user SET plan_id=:plan_id WHERE id = :user_id";

        $userId = $form_data['user_id'] ?? 0;

        $statement = $this->con->prepare($query);


        $user_data = $this->userRepository->getUser($userId);

        $email = $user_data['email'] ?? 0;
        if($email)
        {
            $this->mail->setTo($email);
            $this->mail->setSubject('Plan Assignment Confirmation');
            $this->mail->setMessage('Congratulations you have a new plan!. ');
            $this->mail->send();

        }

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
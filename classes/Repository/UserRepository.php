<?php
/**
 * Created by PhpStorm.
 * User: itay
 * Date: 13/05/19
 * Time: 11:13
 */
namespace classes\Repository;

class UserRepository extends GeneralRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUsers()
    {
        $query = "SELECT 
                  *
                  ,(SELECT plan.name FROM plan WHERE plan.id = user.plan_id) as plan_name
                  FROM user";

        $statement = $this->con->prepare($query);
        $result = [];
        if($statement->execute())
        {
            while($row = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $result[] = $row;
            }
        }

        return $result;

    }

    public function getUser($id)
    {
        $query = "SELECT 
                    *
                FROM
                    user
                WHERE
                    user.id = :id";
        $statement = $this->con->prepare($query);
        $result = [];
        if($statement->execute(array(':id'=>$id)))
        {
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function addUser($form_data)
    {

        $query = "INSERT INTO user (first_name, last_name, email) VALUES(:first_name, :last_name, :email)";

        $statement = $this->con->prepare($query);

        if($statement->execute($form_data))
        {
            $data = "insert";
        }
        else {
            $data = "error";
        }
        return $data;

    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM user WHERE id=:id";
        $statement = $this->con->prepare($query);
        $data = [];
        if($statement->execute(array(':id'=>$id)))
        {
            $data['success'] = 1;
        }
        else {
            $data['success'] = 0;
        }

        return $data;
    }

    public function updateUser($form_data)
    {
        $query = "UPDATE user SET first_name=:first_name, last_name=:last_name, email=:email WHERE id=:id";

        $statement = $this->con->prepare($query);

        if($statement->execute($form_data))
        {
            $data = "update";
        }
        else {
            $data = "error";
        }

        return $data;
    }

    public function getUsersByPlan($plan_id)
    {
        $query = "SELECT 
                    *
                FROM
                    user
                WHERE
                    user.plan_id = :plan_id";
        $statement = $this->con->prepare($query);
        $result = [];
        if($statement->execute(array(':plan_id'=>$plan_id)))
        {
            while($row = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $result[] = $row;
            }
        }

        return $result;
    }

    public function getUsersByPlanDay($day_id)
    {
        $query = "SELECT 
                        *
                    FROM
                        user
                            JOIN
                        plan ON user.plan_id = plan.id
                            JOIN
                        day ON day.plan_id = plan.id
                    WHERE
                        day.id = :day_id";
        $statement = $this->con->prepare($query);
        $result = [];
        if($statement->execute(array(':day_id'=>$day_id)))
        {
            while($row = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                $result[] = $row;
            }
        }

        return $result;
    }
}
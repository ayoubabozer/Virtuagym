<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]).'/Virtuagym';
include $root.'/init.php';

use classes\Repository\PlanRepository;


$request_method=$_SERVER["REQUEST_METHOD"];
$planRepository = new PlanRepository();


function echo_data($result)
{
    echo json_encode($result);
}


switch($request_method)
{
    case 'GET':
        // retrive data
        if(!empty($_GET["id"]))
        {
            $id=intval(($_GET["id"]??0));
            $result = $planRepository->getPlan($id);
            echo_data($result);
        }
        else
        {
            $result = $planRepository->getPlans();
            echo_data($result);
        }
        break;
    case 'POST':
        // insert user
        $data = array(
            'plan_name' => $_POST['plan_name'],
        );
        $result = $planRepository->addPlan($data);

        $history = array(
            ':endpoint' => basename($_SERVER['PHP_SELF']),
            ':method' => $request_method,
            ':request' => json_encode($data),
            ':response' => json_encode($result),
        );
        $planRepository->addHistory($history);
        echo ($result);
        break;
    case 'PUT':
        // update user
        parse_str(file_get_contents("php://input"),$post_vars);

        $data = array(
            'plan_name' => $post_vars['plan_name'],
            'id' => $post_vars['hidden_plan_id'],
        );

        $result = $planRepository->updatePlan($data);

        $history = array(
            ':endpoint' => basename($_SERVER['PHP_SELF']),
            ':method' => $request_method,
            ':request' => json_encode($data),
            ':response' => json_encode($result),
        );
        $planRepository->addHistory($history);

        echo ($result);
        break;
    case 'DELETE':
        // delete user
        $id=intval($_GET["id"]);
        $result = $planRepository->deletePlan($id);
        echo_data($result);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

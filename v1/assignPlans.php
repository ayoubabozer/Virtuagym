<?php


$root = realpath($_SERVER["DOCUMENT_ROOT"]).'/Virtuagym';
include $root.'/init.php';

$request_method=$_SERVER["REQUEST_METHOD"];

use classes\Repository\PlanRepository;
$planRepository = new PlanRepository();



function echo_data($result)
{
    echo json_encode($result);
}


switch($request_method)
{
    case 'GET':
        // retrive data

        break;
    case 'POST':
        // insert user
        break;
    case 'PUT':
        // update user
        parse_str(file_get_contents("php://input"),$post_vars);

        $data = array(
            'user_id' => $post_vars['user_id'],
            'plan_id' => $post_vars['plan_id'],
        );

        $result = $planRepository->assignPlan($data);

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
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

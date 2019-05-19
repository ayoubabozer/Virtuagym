<?php


$root = realpath($_SERVER["DOCUMENT_ROOT"]).'/Virtuagym';
include $root.'/init.php';

$request_method=$_SERVER["REQUEST_METHOD"];

use classes\Repository\DayRepository;
$dayRepository = new DayRepository();



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
            $result = $dayRepository->getPlanDays($id);
            echo_data($result);
        }
        else
        {

        }
        break;
    case 'POST':
        // insert exercise

        $plan_id = $_POST['hidden_day_plan_id'];

        $data = array(
            'name' => $_POST['day_name'],
            'plan_id' => $plan_id,
        );

        $result = $dayRepository->addDay($data);

        $history = array(
            ':endpoint' => basename($_SERVER['PHP_SELF']),
            ':method' => $request_method,
            ':request' => json_encode($data),
            ':response' => json_encode($result),
        );
        $dayRepository->addHistory($history);

        echo ($result);
        break;
    case 'PUT':
        // update exercise
        break;
    case 'DELETE':
        // delete exercise
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

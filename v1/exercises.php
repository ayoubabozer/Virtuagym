<?php


$root = realpath($_SERVER["DOCUMENT_ROOT"]).'/Virtuagym';
include $root.'/init.php';


$request_method=$_SERVER["REQUEST_METHOD"];

use classes\Repository\ExerciseRepository;

$exerciseReposiory = new ExerciseRepository();



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

        }
        else
        {
            $result = $exerciseReposiory->getExercises();
            echo_data($result);
        }
        break;
    case 'POST':
        // insert exercise
        $data = array(
            'day_id' => $_POST['dayId'],
            'exercise_id' => $_POST['exerciseId'],
        );
        $result = $exerciseReposiory->addExercise($data);
        $history = array(
            ':endpoint' => basename($_SERVER['PHP_SELF']),
            ':method' => $request_method,
            ':request' => json_encode($data),
            ':response' => ($result),
        );
        $exerciseReposiory->addHistory($history);

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

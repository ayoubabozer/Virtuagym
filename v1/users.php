<?php


$root = realpath($_SERVER["DOCUMENT_ROOT"]).'/Virtuagym';
include $root.'/init.php';

$request_method=$_SERVER["REQUEST_METHOD"];

use classes\Repository\UserRepository;


$userRepository = new UserRepository();




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
            $result = $userRepository->getUser($id);
            echo_data($result);
        }
        else
        {
            $result = $userRepository->getUsers();
            echo_data($result);
        }
        break;
    case 'POST':
    // insert user
        $data = array(
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
        );
        $result = $userRepository->addUser($data);

        $history = array(
            ':endpoint' => basename($_SERVER['PHP_SELF']),
            ':method' => $request_method,
            ':request' => json_encode($data),
            ':response' => json_encode($result),
        );
        $userRepository->addHistory($history);
        echo ($result);
        break;
    case 'PUT':
    // update user
        parse_str(file_get_contents("php://input"),$post_vars);

        $data = array(
            'first_name' => $post_vars['first_name'],
            'last_name' => $post_vars['last_name'],
            'email' => $post_vars['email'],
            'id' => $post_vars['hidden_id'],
        );
        $result = $userRepository->updateUser($data);


        $history = array(
            ':endpoint' => basename($_SERVER['PHP_SELF']),
            ':method' => $request_method,
            ':request' => json_encode($data),
            ':response' => json_encode($result),
        );
        $userRepository->addHistory($history);
        echo ($result);
        break;
    case 'DELETE':
    // delete user
        $id=intval($_GET["id"]);
        $result = $userRepository->deleteUser($id);

        $history = array(
            ':endpoint' => basename($_SERVER['PHP_SELF']),
            ':method' => $request_method,
            ':request' => json_encode($data),
            ':response' => json_encode($result),
        );
        $userRepository->addHistory($history);

        echo_data($result);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

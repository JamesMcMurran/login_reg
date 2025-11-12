<?php require_once '../core/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="container">
        <?php
        $user = DB::getInstance()->get('users', array('username', '=', 'adam'));

        if(!$user->count()) {
            echo 'NO User';
        } else {
            echo $user->first()->username;
        }

        // flash a message if exists //
        if(Session::exists('home')) {
            echo '<p>'.Session::flash('home').'</p>';
        }

        $user = new User();

        if($user->isLoggedIn()) {
            echo 'Logged In';
            ?>
            <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>">
                <?php echo escape($user->data()->username); ?></a>!</p>

            <ul>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="update.php">Update</a></li>
                <li><a href="changepassword.php">Change Password</a></li>
            </ul>
        <?php
        if($user->hasPermission('admin')) {
            echo '<p>You are an admin.</p>';
        }
        } else {
            echo '<p>You need to <a href="login.php">login</a> or <a href="register.php">register</a>.</p>';
        }
        ?>
    </div>
</body>
</html>

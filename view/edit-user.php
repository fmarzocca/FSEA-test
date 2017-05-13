<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>
        <?php print htmlentities($title) ?>
        </title>
        <link rel="stylesheet" href="view/view.css">
    </head>
    <body>
        <?php
        if ( $errors ) {
            print '<ul class="errors">';
            foreach ( $errors as $field => $error ) {
                print '<li>'.htmlentities($error).'</li>';
            }
            print '</ul>';
        }
        ?>

        <div id="addform">
        <h1>Edit user</h1>
        <form method="POST" action="">
            <label for="first">First Name:</label><br/>
            <input type="text" name="first" value="<?php print htmlentities($contact->first) ?>"/>
            <br/>
            
            <label for="last">Last Name:</label><br/>
            <input type="text" name="last" value="<?php print htmlentities($contact->last) ?>"/>
            <br/>
            <label for="email">Email:</label><br/>
            <input type="text" name="email" value="<?php print htmlentities($contact->email) ?>" />
            <br/>
            <label for="password">Password:</label><br/>
            <input type="password" name="password" value="<?php print htmlentities($contact->password) ?>"/>
            <br/>
            <input type="hidden" name="form-submitted" value="1" />
            <input type="submit" value="Submit" />
        </form>
        </div>
    </body>
</html>

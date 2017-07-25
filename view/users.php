<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Fast Software Engineer Assignment</title>
        <link rel="stylesheet" href="view/view.css">
    </head>
    <body>
    	<h1>Fast Software Engineer Assignment</h1>
    	<div id="userstable">
        <div class="adduser"><a href="index.php?op=new">Add new user</a></div>
        <br />
        <table class="users" border="0" cellpadding="0" cellspacing="0" >
            <thead>
                <tr>
                    <th><a href="?orderby=first">First Name</a></th>
                    <th><a href="?orderby=last">Last Name</a></th>
                    <th><a href="?orderby=email">Email</a></th>
                    <th><a href="?orderby=password">Password</a></th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php $rowclass="e"; ?>
            <?php foreach ($users as $user) : ?>
                <tr class="<?php echo($rowclass); ?>">
                    <td><?php print htmlentities($user->first); ?></a></td>
                    <td><?php print htmlentities($user->last); ?></td>
                    <td><?php print htmlentities($user->email); ?></td>
                    <td>************</td>
                    <td><a href="index.php?op=edit&id=<?php print $user->id; ?>">edit</a></td>
                    <td><a href="index.php?op=delete&id=<?php print $user->id; ?>">delete</a></td>
                </tr>
                <?php $rowclass=="e" ? $rowclass="o" : $rowclass="e"; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </body>
</html>

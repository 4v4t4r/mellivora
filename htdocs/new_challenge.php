<?php

define('IN_FILE', true);
require('../include/general.inc.php');

enforce_authentication(CONFIG_UC_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['action'] == 'new') {

       $id = db_insert(
          'challenges',
          array(
             'added'=>time(),
             'added_by'=>$_SESSION['id'],
             'title'=>$_POST['title'],
             'description'=>$_POST['description'],
             'flag'=>$_POST['flag'],
             'points'=>$_POST['points'],
             'category'=>$_POST['category'],
             'num_attempts_allowed'=>$_POST['num_attempts_allowed'],
             'available_from'=>strtotime($_POST['available_from']),
             'available_until'=>strtotime($_POST['available_until'])
          )
       );

       if ($id) {
          header('location: edit_challenge.php?id='.$id);
          exit();
       } else {
          message_error('Could not insert new category: '.$db->errorCode());
       }
    }
}

head('Site management');
menu_management();
section_subhead('New challenge');

echo '
<form class="form-horizontal" method="post">

    <div class="control-group">
        <label class="control-label" for="title">Title</label>
        <div class="controls">
            <input type="text" id="title" name="title" class="input-block-level" placeholder="Title">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="description">Description</label>
        <div class="controls">
            <textarea id="description" name="description" class="input-block-level" rows="10"></textarea>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="flag">Flag</label>
        <div class="controls">
            <input type="text" id="flag" name="flag" class="input-block-level" placeholder="Flag">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="points">Points</label>
        <div class="controls">
            <input type="text" id="points" name="points" class="input-block-level" placeholder="Points">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="num_attempts_allowed">Max number of flag guesses</label>
        <div class="controls">
            <input type="text" id="num_attempts_allowed" name="num_attempts_allowed" class="input-block-level" value="5">
        </div>
    </div>';


    echo '
    <div class="control-group">
        <label class="control-label" for="category">Category</label>
        <div class="controls">

        <select id="category" name="category">';
    $stmt = $db->query('SELECT * FROM categories ORDER BY title');
    while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="',htmlspecialchars($category['id']),'"',(isset($_GET['category']) && $category['id'] == $_GET['category'] ? ' selected="selected"' : ''),'>', htmlspecialchars($category['title']), '</option>';
    }
    echo '
        </select>

        </div>
    </div>
    ';


    echo '<div class="control-group">
        <label class="control-label" for="available_from">Available from</label>
        <div class="controls">
            <input type="text" id="available_from" name="available_from" class="input-block-level" value="',get_date_time(),'">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="available_until">Available until</label>
        <div class="controls">
            <input type="text" id="available_until" name="available_until" class="input-block-level" value="',get_date_time(),'">
        </div>
    </div>';

    echo '
        <div class="control-group">
            <label class="control-label" for="files">Files</label>
            <div class="controls">
                <input type="text" id="files" class="input-block-level" value="Create and edit challenge to add files." disabled />
         </div>
     </div>

    <input type="hidden" name="action" value="new" />

    <div class="control-group">
        <label class="control-label" for="save"></label>
        <div class="controls">
            <button type="submit" id="save" class="btn btn-primary">Create challenge</button>
        </div>
    </div>

</form>
';

foot();
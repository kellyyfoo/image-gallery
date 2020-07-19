<?php include("includes/init.php");
$image_id = $_GET['image_id'];
$show_image_delete_confirmation = FALSE;
$show_empty_tag_error = FALSE;
$show_repeat_tag_error = FALSE;

function display_image($image_record)
{
?>
  <!-- all images: Source: (original work) Kelly Foo -->
  <img class="full_image" src="uploads/images/<?php echo htmlspecialchars($image_record["id"]) . "." . htmlspecialchars($image_record["file_ext"]); ?>" alt="full_image">
<?php
}

function display_tag($tag_record)
{
?>
  <button class="tag_button" name="tag_id" title="Delete tag?" value="<?php echo htmlspecialchars($tag_record["tag_id"]); ?>"> x <?php echo "&nbsp;&nbsp;" . htmlspecialchars($tag_record["name"]); ?></button>
<?php
}

function get_tag_id($tag_record)
{
  return htmlspecialchars($tag_record["id"]);
}

function get_image_ext($image_record)
{
  return htmlspecialchars($image_record["file_ext"]);
}

if (isset($_POST["tag_id"])) {
  $tag_id = $_POST["tag_id"];
  $sql = "DELETE FROM image_tags WHERE (image_id = :image_id AND tag_id = :tag_id);";
  $params = array(
    ':image_id' => $image_id,
    ':tag_id' => $tag_id
  );
  exec_sql_query($db, $sql, $params);
}

if (isset($_POST["delete_image"])) {
  $sql = "SELECT * FROM images WHERE (id = :image_id);";
  $params = array(
    ':image_id' => $image_id
  );
  $result = exec_sql_query($db, $sql, $params);
  $records = $result->fetchAll();
  foreach ($records as $record) {
    $image_ext = get_image_ext($record);
  }

  $sql = "DELETE FROM images WHERE (id = :image_id);";
  $params = array(
    ':image_id' => $image_id
  );
  exec_sql_query($db, $sql, $params);

  unlink('uploads/images/' . $image_id . "." . $image_ext);

  $sql = "DELETE FROM image_tags WHERE (image_id = :image_id);";
  $params = array(
    ':image_id' => $image_id
  );
  exec_sql_query($db, $sql, $params);

  $show_image_delete_confirmation = TRUE;
}

if (isset($_POST["submit_tag"])) {
  $new_tag = trim(filter_input(INPUT_POST, 'new_tag', FILTER_SANITIZE_STRING));
  if (empty($new_tag)) {
    $show_empty_tag_error = TRUE;
  } else {
    $sql = "SELECT * FROM tags WHERE (name = :new_tag)";
    $params = array(
      ':new_tag' => $new_tag
    );
    $result = exec_sql_query($db, $sql, $params);
    $records = $result->fetchAll();
    if (empty($records)) {
      $sql = "INSERT INTO tags (name) VALUES (:new_tag)";
      $params = array(
        ':new_tag' => $new_tag
      );
      exec_sql_query($db, $sql, $params);

      $tag_id = $db->lastInsertId("id");
    } else {
      $sql = "SELECT * FROM tags WHERE (name = :new_tag)";
      $params = array(
        ':new_tag' => $new_tag
      );
      $new_tags_result = exec_sql_query($db, $sql, $params);
      $new_tag_records = $new_tags_result->fetchAll();
      foreach ($new_tag_records as $new_tag_record) {
        $tag_id = get_tag_id($new_tag_record);
      }
    }
    $sql = "SELECT * FROM image_tags WHERE (image_id = :image_id AND tag_id = :tag_id)";
    $params = array(
      ':image_id' => $image_id,
      ':tag_id' => $tag_id
    );
    $tag_image_result = exec_sql_query($db, $sql, $params);
    $tag_image_records = $tag_image_result->fetchAll();
    if (empty($tag_image_records)) {
      $sql = "INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :tag_id)";
      $params = array(
        ':image_id' => $image_id,
        ':tag_id' => $tag_id
      );
      exec_sql_query($db, $sql, $params);
    } else {
      $show_repeat_tag_error = TRUE;
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<?php include("includes/header.php"); ?>

<form class="back_button_form" action="index.php">
  <button class="back_button"> ← Back to gallery </button>
</form>

<?php if ($show_image_delete_confirmation == FALSE) { ?>

  <div class="full_image_title"> VIEW IMAGE DETAILS </div>

  <div class="contain">
    <?php
    $sql = "SELECT * FROM images WHERE id = :image_id;";
    $params = array(
      ':image_id' => $image_id
    );
    $image_result = exec_sql_query($db, $sql, $params);
    $image_records = $image_result->fetchAll();
    foreach ($image_records as $image_record) {
      display_image($image_record);
    }
    ?>

    <form id="delete_image" method="post" action="image.php?image_id=<?php echo htmlspecialchars($image_id); ?>">
      <button name="delete_image" class="trash" title="Delete image?">
        <!-- Source: (original work) Kelly Foo -->
        <img class="trash_icon" src="images/trash.png" alt="trash">
      </button>
    </form>
  </div>

  <div class="tag_title"> TAGS: </div>

  <div class="tags">
    <form id="delete_tag" method="post" action="image.php?image_id=<?php echo htmlspecialchars($image_id); ?>">
      <?php
      $sql = "SELECT * FROM tags INNER JOIN image_tags ON tags.id = image_tags.tag_id WHERE image_tags.image_id = :image_id";
      $params = array(
        ':image_id' => $image_id
      );
      $tags_result = exec_sql_query($db, $sql, $params);
      $tag_records = $tags_result->fetchAll();
      if (count($tag_records) == 0) { ?>
        <p class="no_tags"> No tags for this image </p>
      <?php } else {
        foreach ($tag_records as $tag_record) {
          display_tag($tag_record);
        }
      }
      ?>
    </form>
  </div>

  <p class="full_image_title"> ADD A NEW TAG TO IMAGE </p>
  <div class=image_form>
    <form id="add_tag" method="post" action="image.php?image_id=<?php echo htmlspecialchars($image_id); ?>">
      <div class="group_label_input">
        <label class="form_title"> TAG </label>
        <input id="new_tag" type="text" name="new_tag" class="input_term" />
      </div>

      <div class="group_label_input">
        <span></span>
        <input name="submit_tag" type="submit" value="+ ADD" />
      </div>
    </form>
  </div>
  <?php if ($show_empty_tag_error) {
  ?> <p class="upload_error"> Please enter a valid tag </p>
  <?php }
  if ($show_repeat_tag_error) {
  ?> <p class="upload_error"> This image already has that tag. </p>
  <?php }
} else { ?>
  <p class="upload_error"> The image has been deleted. </p>
<?php } ?>


</html>

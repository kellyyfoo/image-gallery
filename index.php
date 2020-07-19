<?php include("includes/init.php");

const MAX_FILE_SIZE = 10000000;
$display_upload_error = FALSE;
$display_upload_size_error = FALSE;
$show_all = TRUE;

function print_tags($tag_record)
{
?>
  <label class="container"><?php echo htmlspecialchars($tag_record["name"]); ?>
    <input type="radio" name="tag_id" value="<?php echo htmlspecialchars($tag_record["id"]); ?>">
    <span class="checkmark"></span>
  </label>
<?php
}

function get_tag_name($tag_record)
{
  return htmlspecialchars($tag_record["name"]);
}

function display_image($image_record)
{
?>
  <div class="img">
    <!-- all images: Source: (original work) Kelly Foo -->
    <a href="image.php?<?php echo http_build_query(array('image_id' => htmlspecialchars($image_record["id"]))); ?>"> </a>
    <button class="photo_button" name="image_id" value="<?php echo htmlspecialchars($image_record["id"]); ?>">
      <img class="gallery_image" src="uploads/images/<?php echo htmlspecialchars($image_record["id"]) . "." . htmlspecialchars($image_record["file_ext"]); ?>" alt="gallery_image">
    </button>
  </div>
<?php
}

if (isset($_GET["tag_id"])) {
  $show_all = FALSE;
  $tag_id = $_GET['tag_id'];
}

if (isset($_GET["remove_filter_submit"])) {
  $show_all = TRUE;
}

if (isset($_POST["submit_upload"])) {
  if ($_FILES['photo_file']['error'] == UPLOAD_ERR_OK) {
    $upload_info = $_FILES["photo_file"];
    $upload_name = basename($upload_info["name"]) . PHP_EOL;
    $path_parts = pathinfo($upload_name);
    $upload_ext = trim(strtolower($path_parts['extension']));

    $sql = "INSERT INTO images (file_name, file_ext) VALUES (:upload_name, :upload_ext)";
    $params = array(
      ':upload_name' => $upload_name,
      ':upload_ext' => $upload_ext
    );
    exec_sql_query($db, $sql, $params);

    $id = $db->lastInsertId("id");
    $new_path = "uploads/images/" . $id . "." . $upload_ext;
    move_uploaded_file($_FILES["photo_file"]["tmp_name"], $new_path);
  }
  if ($_FILES['photo_file']['error'] == 1) {
    $display_upload_size_error = TRUE;
  }
  if ($_FILES['photo_file']['error'] == 4) {
    $display_upload_error = TRUE;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include("includes/header.php"); ?>

<div class="full">
  <div class="table">
    <!-- FILTER BAR -->
    <div class="left">
      <?php if ($show_all == FALSE) {
        $sql = "SELECT * FROM tags WHERE id = :tag_id";
        $params = array(
          ':tag_id' => $tag_id
        );
        $tag_filter_result = exec_sql_query($db, $sql, $params);
        $tag_filter_records = $tag_filter_result->fetchAll();
        foreach ($tag_filter_records as $tag_filter_record) {
          $name = get_tag_name($tag_filter_record);
        } ?>
        <p class="filter_name"> Filtering by: <?php echo htmlspecialchars($name); ?> </p>
        <hr>
      <?php } ?>
      <div class="filter_title"> FILTER BY: </div>

      <form id="filter" method="get" action="index.php">
        <div>
          <?php
          $sql = "SELECT * FROM tags;";
          $params = [];
          $tag_result = exec_sql_query($db, $sql, $params);
          $tag_records = $tag_result->fetchAll();
          foreach ($tag_records as $tag_record) {
            print_tags($tag_record);
          }
          ?>
          <input type="submit" class="filter_button" value="FILTER">
        </div>
      </form>

      <?php if ($show_all == FALSE) { ?>
        <form id="clear_filter" method="get" action="index.php">
          <div class="filter_button_div">
            <button class="remove_filter_button" name="remove_filter_submit">
              X &nbsp; REMOVE FILTER
            </button>
          </div>
        </form>
      <?php } ?>
    </div>

    <!-- GALLERY BAR -->
    <div class="right">
      <div class="gallery">
        <form id="gallery_image" method="get" action="image.php">
          <?php if ($show_all) {
            $sql = "SELECT * FROM images;";
            $images_result = exec_sql_query($db, $sql, $params);
            $image_records = $images_result->fetchAll();
            foreach ($image_records as $image_record) {
              display_image($image_record);
            }
          } else {
            $sql = "SELECT * FROM image_tags LEFT OUTER JOIN images ON image_tags.image_id = images.id WHERE image_tags.tag_id = :tag_id";
            $params = array(
              ':tag_id' => $tag_id
            );
            $filtered_result = exec_sql_query($db, $sql, $params);
            $filtered_records = $filtered_result->fetchAll();
            foreach ($filtered_records as $filtered_record) {
              display_image($filtered_record);
            }
          }
          ?>
        </form>
      </div>


      <h2> UPLOAD A NEW IMAGE </h2>
      <div class=image_form>
        <form class="upload_image_form" id="uploadFile" method="post" action="index.php" enctype="multipart/form-data">

          <div class="group_label_input">
            <label class="form_title" for="photo_file"> IMAGE </label>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
            <input id="photo_file" type="file" name="photo_file" accept=".png, image/png, .jpg, image/jpeg, .gif, image/gif" />
          </div>

          <div class="group_label_input">
            <span></span>
            <input name="submit_upload" type="submit" value="+ UPLOAD" />
          </div>
        </form>
      </div>
      <?php if ($display_upload_size_error) {
      ?> <p class="upload_error"> Selected file is too large </p>
      <?php }
      if ($display_upload_error) { ?>
        <p class="upload_error"> No file was selected. </p>
      <?php } ?>
    </div>
  </div>
</div>


</html>

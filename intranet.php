<?php require_once('config/config.php'); ?>
<?php require_once(INCLUDES_DIR . '/header.php'); ?>
<?php __autoload('email'); ?>
<script type="text/javascript" src="js/ckeditor/ckeditor_basic.js"></script>
<script src="js/ckeditor/sample.js" type="text/javascript"></script>
<link href="js/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<body>
<div class="main">

  <?php require_once(INCLUDES_DIR . "/top_menu.php"); ?>

  <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
        <?php
          if(isset($_POST['submitIntranet']) && !empty($_POST['submitIntranet']))
          Validate::intranetEmailValidation($_POST);
        ?>
          <h2>Intranet Email</h2>
          <p>You can use the form below to communicate with other departments.</p>
        </div>
        <div class="article">
          <h2>Send  mail</h2>
          <form action="intranet.php" method="post" id="sendemail" name="sendemail">
          <ol><li>
            <label for="name">Name (required)</label>
            <input id="name" name="name" class="text" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['name'])) : '' ?>" />
          </li><li>
            <label for="email">Email Address (required)</label>
            <input id="email" name="email" class="text" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['email'])) : '' ?>" />
          </li><li>
            <label for="department">Department</label>
            <?php $stmt = DB::getInstance()->query("select * from department where department.id_lang = 1 order by id_department asc"); ?>
            <select name="department" id="department">
            <option selected="selected">Please select one</option>
            <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
            <option value="<?php echo $row['id_department']; ?>" id="department"><?php echo $row['department_name']; ?></option>
            <?php endwhile; ?>
            <?php DB::Close(); ?>
            </select>
            <br /><i>(Choosing a department will automatically send email to all doctors registered at that department)</i>
          </li><li>
            <label for="subject">Subject</label>
            <input id="subject" name="subject" class="text" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['subject'])) : '' ?>" />
          </li><li>
            <label for="editor1">Your Message</label>
            <textarea id="editor1" rows="8" cols="50" class="ckeditor" name="editor1"><?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['editor1'])) : '' ?></textarea>
          </li><li>
            <input type="hidden" name="submitIntranet" id="submitIntranet" value="intranet email" />           
            <a href="javascript:document.sendemail.submit()" >
            <img src="images/submit.gif" class="send" border="0" alt="Submit this form" name="submitIntranet" />
            </a>
            
            <div class="clr"></div>
          </li></ol>
          </form>
        </div>
      </div>
      <div class="sidebar">
        <div class="searchform">
          <?php echo (isset($_SESSION['id_employee']) && !empty($_SESSION['id_employee'])) ? "<img src='images/userpic.gif' align='top' />&nbsp;Welcome ".$_SESSION['firstname']." ".$_SESSION['lastname']."!&nbsp;&nbsp;<a href='index.php?ToDo=logout'>(Logout)</a>" : ""; ?>
        </div>
         <?php require_once(INCLUDES_DIR . '/mainmenu.php'); ?>
      </div>
      <div class="clr"></div>
    </div>
  </div>

  <div class="fbg">
 
      <div class="clr"></div>
    </div>
  </div>
    <?php require_once(INCLUDES_DIR . '/footer.php'); ?>
  </div>
</div>
</body>
</html>
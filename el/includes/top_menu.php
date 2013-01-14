<div class="header">
    <div class="header_resize">
      <div class="menu_nav">
        <ul>
          <li <?php echo (substr($_SERVER['PHP_SELF'], - strlen("index.php")) == "index.php") ? "class='active'" : "" ?>><a href="index.php"><span><span>Αρχική</span></span></a></li>
          <li <?php echo (substr($_SERVER['PHP_SELF'], - strlen("statistics.php")) == "statistics.php") ? "class='active'" : "" ?>><a href="statistics.php"><span><span>Στατιστικά</span></span></a></li>
          <li <?php echo (substr($_SERVER['PHP_SELF'], - strlen("appointments.php")) == "appointments.php") ? "class='active'" : "" ?>><a href="appointments.php" target="_blank"><span><span>Ραντεβού</span></span></a></li>
          <li <?php echo (substr($_SERVER['PHP_SELF'], - strlen("contact.php")) == "contact.php") ? "class='active'" : "" ?>><a href="contact.php"><span><span>Επικοινωνία</span></span></a></li>
        </ul>
      </div>
	  <div class="clr"></div>
      <div class="logo">
        <h1><a href="index.php">HMS<br />
      <small>Σύστημα Διαχείρισης Νοσοκομειακής Μονάδας</small></a></h1></div>
      <div class="clr"></div>
      <div class="clr"></div>
      <div class="languages">
      <a title="Αγγλικά" href="../index.php">
      <img width="16" height="11" alt="en" border="0" src="images/1.jpg" />
      </a>
      <a title="Ελληνικά" href="index.php">
      <img width="16" height="11" alt="el" border="0" src="images/2.jpg" />
      </a>
      </div>
    </div>
  </div>
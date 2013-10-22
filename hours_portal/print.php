<?php
ob_start();
session_start();

include('includes/head.inc.php');
?>

<ul class="breadcrumb expand">
  <li><a href="http://www.library.ubc.ca" title="UBC Library">Libray Home</a> <span class="divider">/</span></li>
  <li><a href="http://hours.library.ubc.ca" title="UBC Library Hours and Locations">Hours &amp; Locations</a> <span class="divider">/</span></li>
  <li>Print Signage &amp; Bookmarks</li>
</ul>

<div class="grid">

  <header id="hours-header">
    <h1><a href="" title="Hours and Locations"><img src="img/hours-locations-header.gif" title="Hours and Locations" /></a></h1>
  </header>

  <h3>Print Signage and Bookmarks</h3>
  
  <p>Please note that the signage and bookmark links reflect what is <em>currently</em> in our database. When new hours are entered and approved, an update notice will be sent to staff to print new materials.</p>
  
  <div class="message note">
    
    <p><strong>For best print results, please adjust your print settings to: </strong></p>
    <ul>
      <li>Turn off headers and footers
        <ol>In Firefox:
          <li>From the File menu, select Page Setup</li>
          <li>Select the tab Margins &amp; Header/Footer</li>
          <li>Select "blank" in each of the 6 drop-down options</li>
        </ol>
      </li>
      <li>Set print margins to 0.5&quot;</li>
      <li>Use Landscape orientation</li> 
    </ul>
    
    <p><strong>For 2-sided printing of bookmarks, please also:</strong></p>
    <ul>
      <li>Use Firefox or Internet Explorer</li>
      <li>Select 2-sided printing with the &quot;flip&quot; or &quot;binding&quot; on the short edge</li>
    </ul>
    
  </div><!-- closes message -->
  
  <br />
  
  <div class="row-fluid">
    
    <div class="span5">
      
      <h4>Signage</h4>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="9" />
        <input type="hidden" name="category" value="1" />
        <input type="hidden" name="version" value="full" />
        <input type="submit" value="Fall/Winter Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="12,1" />
        <input type="hidden" name="category" value="5" />
        <input type="hidden" name="version" value="full" />
        <input type="submit" value="December Holiday Hours" class="button" />
      </form>
  
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="3,4" />
        <input type="hidden" name="category" value="5" />
        <input type="hidden" name="version" value="full" />
        <input type="submit" value="Easter Holiday Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="4,5" />
        <input type="hidden" name="category" value="2" />
        <input type="hidden" name="version" value="full" />
        <input type="submit" value="Spring Intersession Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="5,6" />
        <input type="hidden" name="category" value="3,4" />
        <input type="hidden" name="version" value="full" />
        <input type="submit" value="Summer Session Term 1 Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="7,8" />
        <input type="hidden" name="category" value="3,4" />
        <input type="hidden" name="version" value="full" />
        <input type="submit" value="Summer Session Term 2 Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="8" />
        <input type="hidden" name="category" value="2" />
        <input type="hidden" name="version" value="full" />
        <input type="submit" value="Summer Intersession Hours" class="button" />
      </form>
      
    </div><!-- closes first column --->
    
    <div class="span7">
      
      <h4>Bookmarks</h4>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="9" />
        <input type="hidden" name="category" value="1" />
        <input type="hidden" name="version" value="bookmark" />
        <input type="submit" value="Fall/Winter Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="12,1" />
        <input type="hidden" name="category" value="5" />
        <input type="hidden" name="version" value="bookmark" />
        <input type="submit" value="December Holiday Hours" class="button" />
      </form>
  
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="3,4" />
        <input type="hidden" name="category" value="5" />
        <input type="hidden" name="version" value="bookmark" />
        <input type="submit" value="Easter Holiday Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="4,5" />
        <input type="hidden" name="category" value="2" />
        <input type="hidden" name="version" value="bookmark" />
        <input type="submit" value="Spring Intersession Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="5,6" />
        <input type="hidden" name="category" value="3,4" />
        <input type="hidden" name="version" value="bookmark" />
        <input type="submit" value="Summer Session Term 1 Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="7,8" />
        <input type="hidden" name="category" value="3,4" />
        <input type="hidden" name="version" value="bookmark" />
        <input type="submit" value="Summer Session Term 2 Hours" class="button" />
      </form>
      
      <form action="print-hours.php" method="post">
        <input type="hidden" name="month" value="8" />
        <input type="hidden" name="category" value="2" />
        <input type="hidden" name="version" value="bookmark" />
        <input type="submit" value="Summer Intersession Hours" class="button" />
      </form>
      
    </div><!-- closes second column -->
    
  </div>
  
</div><!-- closes grid -->

<?php
include('includes/footer.inc.php');

ob_end_flush();
?>
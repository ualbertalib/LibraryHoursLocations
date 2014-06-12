<div class="print index twelve columns">
    <?php
        $print_url = PUBLIC_URL . "/print-hours.php";
    ?>     
    <h2>Print Signage and Bookmarks</h2> 
     
    <p>Please note that the signage and bookmark links reflect what is <em>currently</em> in our database. When new hours are entered and approved, an update notice will be sent to staff to print new materials.</p> 
     
    <div class="message note row"> 
      <strong>For best print results, please adjust your print settings to: </strong> 
      <ul class="bottom-margin">
        <li>Turn off headers and footers 
          In Firefox:
          <ol> 
            <li>From the File menu, select Page Setup</li> 
            <li>Select the tab Margins &amp; Header/Footer</li> 
            <li>Select "blank" in each of the 6 drop-down options</li> 
          </ol> 
        </li> 
        <li>Set print margins to 0.5&quot;</li> 
        <li>Use Landscape orientation</li> 
      </ul> 
      <strong>For 2-sided printing of bookmarks, please also:</strong> 
      <ul>
        <li>Use Firefox or Internet Explorer</li> 
        <li>Select 2-sided printing with the &quot;flip&quot; or &quot;binding&quot; on the short edge</li> 
      </ul> 
       
    </div><!-- closes message --> 
     
    <div class="fit five columns"> 
       
      <h3>Signage</h3> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="9" /> 
        <input type="hidden" name="category" value="1" /> 
        <input type="hidden" name="version" value="full" /> 
        <input type="submit" value="Fall" class="button" /> 
      </form> 
      
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="1" /> 
        <input type="hidden" name="category" value="1" /> 
        <input type="hidden" name="version" value="full" /> 
        <input type="submit" value="Winter Hours" class="button" /> 
      </form> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="12,1" /> 
        <input type="hidden" name="category" value="5" /> 
        <input type="hidden" name="version" value="full" /> 
        <input type="submit" value="December Holiday Hours" class="button" /> 
      </form> 
   
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="3,4" /> 
        <input type="hidden" name="category" value="5" /> 
        <input type="hidden" name="version" value="full" /> 
        <input type="submit" value="Easter Holiday Hours" class="button" /> 
      </form> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="4,5" /> 
        <input type="hidden" name="category" value="2" /> 
        <input type="hidden" name="version" value="full" /> 
        <input type="submit" value="Spring Intersession Hours" class="button" /> 
      </form> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="5,6" /> 
        <input type="hidden" name="category" value="3,4" /> 
        <input type="hidden" name="version" value="full" /> 
        <input type="submit" value="Summer Session Term 1 Hours" class="button" /> 
      </form> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="7,8" /> 
        <input type="hidden" name="category" value="3,4" /> 
        <input type="hidden" name="version" value="full" /> 
        <input type="submit" value="Summer Session Term 2 Hours" class="button" /> 
      </form> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="8" /> 
        <input type="hidden" name="category" value="2" /> 
        <input type="hidden" name="version" value="full" /> 
        <input type="submit" value="Summer Intersession Hours" class="button" /> 
      </form> 
       
    </div><!-- closes first column ---> 
     
    <div class="fit six columns row"> 
       
      <h3>Bookmarks</h3> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="9" /> 
        <input type="hidden" name="category" value="1" /> 
        <input type="hidden" name="version" value="bookmark" /> 
        <input type="submit" value="Fall Hours" class="button" /> 
      </form> 
      
       <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="1" /> 
        <input type="hidden" name="category" value="1" /> 
        <input type="hidden" name="version" value="bookmark" /> 
        <input type="submit" value="Winter Hours" class="button" /> 
      </form> 
      
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="12,1" /> 
        <input type="hidden" name="category" value="5" /> 
        <input type="hidden" name="version" value="bookmark" /> 
        <input type="submit" value="December Holiday Hours" class="button" /> 
      </form> 
   
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="3,4" /> 
        <input type="hidden" name="category" value="5" /> 
        <input type="hidden" name="version" value="bookmark" /> 
        <input type="submit" value="Easter Holiday Hours" class="button" /> 
      </form> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="4,5" /> 
        <input type="hidden" name="category" value="2" /> 
        <input type="hidden" name="version" value="bookmark" /> 
        <input type="submit" value="Spring Intersession Hours" class="button" /> 
      </form> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="5,6" /> 
        <input type="hidden" name="category" value="3,4" /> 
        <input type="hidden" name="version" value="bookmark" /> 
        <input type="submit" value="Summer Session Term 1 Hours" class="button" /> 
      </form> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="7,8" /> 
        <input type="hidden" name="category" value="3,4" /> 
        <input type="hidden" name="version" value="bookmark" /> 
        <input type="submit" value="Summer Session Term 2 Hours" class="button" /> 
      </form> 
       
      <form action="<?php echo $print_url; ?>" method="post"> 
        <input type="hidden" name="month" value="8" /> 
        <input type="hidden" name="category" value="2" /> 
        <input type="hidden" name="version" value="bookmark" /> 
        <input type="submit" value="Summer Intersession Hours" class="button" /> 
      </form> 
       
    </div><!-- closes second column --> 
     
  </div><!-- closes twelve --> 

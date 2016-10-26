
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img style="float:left;" src="milkwhite.png" width="50"><a class="navbar-brand" href="index.php">ThistleCAT <?php echo $libraryname; ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        
            
          <ul class="nav navbar-nav navbar-left">
          
   
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Select Layer... <span class="caret"></span></a>
               <ul class="dropdown-menu">
                <li><a href="barcodes_issues.php">Total Checkouts</a></li>
                <li><a href="barcodes_lastborrowed.php">Last Checkout Date</a></li>
                <li><a href="barcodes_lang.php">Language</strong></a></li>
                <li role="separator" class="divider"></li>
               <!-- <li class="dropdown-header">Nav header</li> -->
                <li><a href="barcodes.php">Clear layers</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>